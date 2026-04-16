<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Models\HistoryKeuanganPartner;
use App\Models\MemberVoucher;
use App\Models\Partner;
use App\Models\VoucherClaimHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherScanController extends Controller
{
    /**
     * Scan barcode voucher dan validasi
     */
    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'total_transaction' => 'required|integer|min:0',
        ]);

        try {
            // Partner yang login
            $partner = $request->user();

            // Decode barcode
            $memberVoucher = MemberVoucher::findByBarcode($request->barcode);

            if (!$memberVoucher) {
                return $this->error('Barcode tidak valid', 400);
            }

            // Load relasi
            $memberVoucher->load(['voucher.jenis', 'voucher.waktuVoucher', 'voucher.partnerDetails', 'member']);
            $voucher = $memberVoucher->voucher;

            // VALIDASI 1: Voucher berlaku di partner ini?
            if (!$voucher->isValidForPartner($partner->id)) {
                return $this->error('Voucher tidak berlaku di merchant ini', 400);
            }

            // VALIDASI 2: Expired Voucher (start_date & end_date)
            if (!$voucher->isActive()) {
                $message = 'Voucher tidak aktif. ';

                if ($voucher->tanggal_mulai && now()->isBefore($voucher->tanggal_mulai)) {
                    $message .= 'Voucher mulai berlaku tanggal ' . $voucher->tanggal_mulai->format('d F Y');
                } elseif ($voucher->tanggal_selesai && now()->isAfter($voucher->tanggal_selesai)) {
                    $message .= 'Voucher sudah expired tanggal ' . $voucher->tanggal_selesai->format('d F Y');
                }

                return $this->error($message, 400);
            }

            // VALIDASI 3: Waktu Voucher (tanggal_fix, periode_tanggal, dll)
            if (!$voucher->canBeUsedToday()) {
                return $this->error('Voucher tidak dapat digunakan hari ini. ' . $voucher->waktu_description, 400);
            }

            // VALIDASI 4: Cek apakah sudah di-claim hari ini (untuk voucher yang bisa di-claim sekali per hari)
            // OPSIONAL - sesuaikan business logic
            $claimToday = VoucherClaimHistory::where('member_id', $memberVoucher->member_id)
                ->where('partner_id', $partner->id)
                ->where('voucher_id', $voucher->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($claimToday) {
                return $this->error('Voucher sudah digunakan hari ini', 400);
            }

            // Semua validasi lolos, proses claim
            return DB::transaction(function () use ($memberVoucher, $voucher, $partner, $request) {

                // Hitung nominal claim
                $totalTransaction = $request->total_transaction;
                $nominalClaim = $voucher->calculateNominalClaim($totalTransaction);

                // Hitung persentase jika ada
                $persentaseClaim = null;
                if ($voucher->jenis->jenis === 'potongan_persentase') {
                    $persentaseClaim = $voucher->value;
                }

                $settlementMethod = $partner->settlement_method ?? Partner::SETTLEMENT_POSTPAID;

                if ($settlementMethod === Partner::SETTLEMENT_PREPAID) {
                    $availableBalance = $partner->fresh()->saldo_wallet;

                    if ($availableBalance < $nominalClaim) {
                        return $this->error(
                            'Saldo modal partner tidak mencukupi untuk claim voucher ini.',
                            400
                        );
                    }
                }

                // Insert ke history claim
                $historyClaim = VoucherClaimHistory::create([
                    'member_id' => $memberVoucher->member_id,
                    'voucher_id' => $voucher->id,
                    'partner_id' => $partner->id,
                    'persentase_claim' => $persentaseClaim,
                    'nominal_claim' => $nominalClaim,
                ]);

                if ($settlementMethod === Partner::SETTLEMENT_PREPAID) {
                    HistoryKeuanganPartner::create([
                        'partner_id' => $partner->id,
                        'nominal' => $nominalClaim,
                        'tipe' => HistoryKeuanganPartner::TIPE_CLAIM_DEBIT,
                        'status' => HistoryKeuanganPartner::STATUS_TERBAYAR,
                        'keterangan' => 'Debit otomatis dari scan voucher #' . $historyClaim->id,
                    ]);
                }

                return $this->ok([
                    'message' => 'Voucher berhasil di-claim!',
                    'claim' => [
                        'id' => $historyClaim->id,
                        'member' => $memberVoucher->member->name,
                        'voucher' => $voucher->name,
                        'jenis' => $voucher->jenis->jenis,
                        'persentase_claim' => $persentaseClaim,
                        'nominal_claim' => $nominalClaim,
                        'total_transaction' => $totalTransaction,
                        'settlement_method' => $settlementMethod,
                        'saldo_partner_setelah_claim' => $partner->fresh()->saldo_wallet,
                        'claimed_at' => $historyClaim->created_at->format('d F Y H:i'),
                    ]
                ]);
            });
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Preview voucher sebelum scan
     */
    public function previewBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        try {
            $partner = $request->user();
            $memberVoucher = MemberVoucher::findByBarcode($request->barcode);

            if (!$memberVoucher) {
                return $this->error('Barcode tidak valid', 400);
            }

            $memberVoucher->load(['voucher.jenis', 'voucher.waktuVoucher', 'member']);
            $voucher = $memberVoucher->voucher;
            $jenis = $voucher->jenis->jenis ?? null;

            // Hitung prefix dan display_value
            $prefix = match ($jenis) {
                'potongan_persentase' => '%',
                'potongan_nominal', 'cashback' => 'Rp',
                default => ''
            };

            $displayValue = match ($jenis) {
                'potongan_persentase' => $voucher->value . '%',
                'potongan_nominal', 'cashback' => 'Rp ' . number_format($voucher->value, 0, ',', '.'),
                'gratis' => 'Gratis',
                default => $voucher->value
            };

            return $this->ok([
                'member' => [
                    'id' => $memberVoucher->member->id,
                    'name' => $memberVoucher->member->name,
                ],
                'voucher' => [
                    'id' => $voucher->id,
                    'voucher_name' => $voucher->name,
                    'jenis' => $jenis,
                    'value' => $voucher->value,
                    'prefix' => $prefix,
                    'display_value' => $displayValue,
                    'waktu' => $voucher->waktuVoucher?->waktu,
                    'waktu_description' => $voucher->waktu_description,
                    'start_date' => $voucher->start_date?->format('d F Y'),
                    'end_date' => $voucher->end_date?->format('d F Y'),
                ],
                'claim_count' => $memberVoucher->claim_count,
                'last_claim_date' => $memberVoucher->last_claim_date,
                'can_use_today' => $voucher->canBeUsedToday(),
                'is_active' => $voucher->isActive(),
                'valid_for_partner' => $voucher->isValidForPartner($partner->id),
            ]);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function historyVoucher(Request $request)
    {
        try {
            $partnerId = $request->user()->id;

            $history = VoucherClaimHistory::with(['voucher.jenis'])
                ->where('partner_id', $partnerId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($claim) {
                    $voucher = $claim->voucher;
                    $jenis = $voucher->jenis->jenis ?? null;

                    $prefix = match ($jenis) {
                        'potongan_persentase' => '%',
                        'potongan_nominal', 'cashback' => 'Rp',
                        default => ''
                    };

                    $displayValue = match ($jenis) {
                        'potongan_persentase' => $voucher->value . '%',
                        'potongan_nominal', 'cashback' => 'Rp ' . number_format($voucher->value, 0, ',', '.'),
                        'gratis' => 'Gratis',
                        default => $voucher->value
                    };

                    return [
                        'id' => $claim->id,
                        'voucher_id' => $voucher->id,
                        'voucher_name' => $voucher->name,
                        'jenis' => $jenis,
                        'value' => $voucher->value,
                        'prefix' => $prefix,
                        'display_value' => $displayValue,
                        'persentase_claim' => $claim->persentase_claim,
                        'nominal_claim' => $claim->nominal_claim,
                        'formatted_nominal' => 'Rp ' . number_format($claim->nominal_claim, 0, ',', '.'),
                        'claimed_at' => $claim->created_at->format('d F Y H:i'),
                    ];
                });

            return $this->ok($history);
        } catch (Exception $e) {
            Log::error('Error History Voucher Partner: ' . $e->getMessage());
            return $this->error($e->getMessage());
        }
    }

    public function claimedVoucherHistory(Request $request)
    {
        try {
            $partnerId = $request->user()->id;

            $history = VoucherClaimHistory::with(['voucher.jenis', 'member'])
                ->where('partner_id', $partnerId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($claim) {
                    $voucher = $claim->voucher;
                    $jenis = $voucher->jenis->jenis ?? null;
                    $tipeVoucher = $jenis === 'potongan_persentase' ? 'persen' : 'nominal';

                    return [
                        'nama_voucher' => $voucher->name,
                        'tipe_voucher' => $tipeVoucher,
                        'tanggal_claim_voucher' => $claim->created_at->format('d F Y H:i'),
                        'nominal_voucher_full' => (int) $voucher->value,
                        'nominal_voucher_singkat' => $this->formatVoucherValueShort($voucher->value, $jenis),
                        'siapa_yang_claim' => $claim->member->name ?? '-',
                    ];
                })
                ->values();

            return $this->ok($history, 'Berhasil memuat history voucher partner');
        } catch (Exception $e) {
            Log::error('Error Claimed Voucher History Partner: ' . $e->getMessage());
            return $this->error($e->getMessage());
        }
    }

    private function formatVoucherValueShort(int|float|string $value, ?string $jenis): string
    {
        $numericValue = (int) $value;

        if ($jenis === 'potongan_persentase') {
            return $numericValue . '%';
        }

        if ($numericValue >= 1000000) {
            $short = $numericValue / 1000000;
            return rtrim(rtrim(number_format($short, 1, '.', ''), '0'), '.') . 'M';
        }

        if ($numericValue >= 1000) {
            $short = $numericValue / 1000;
            return rtrim(rtrim(number_format($short, 1, '.', ''), '0'), '.') . 'K';
        }

        return (string) $numericValue;
    }
}
