<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Models\MemberVoucher;
use App\Models\VoucherClaimHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

                // Insert ke history claim
                $historyClaim = VoucherClaimHistory::create([
                    'member_id' => $memberVoucher->member_id,
                    'voucher_id' => $voucher->id,
                    'partner_id' => $partner->id,
                    'persentase_claim' => $persentaseClaim,
                    'nominal_claim' => $nominalClaim,
                ]);

                // TODO: Update wallet partner
                // $partner->wallet += $nominalClaim;
                // $partner->save();

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

            return $this->ok([
                'member' => [
                    'id' => $memberVoucher->member->id,
                    'name' => $memberVoucher->member->name,
                ],
                'voucher' => [
                    'id' => $voucher->id,
                    'name' => $voucher->name,
                    'jenis' => $voucher->jenis->jenis,
                    'value' => $voucher->value,
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
}
