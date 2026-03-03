<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Models\HistoryKeuanganPartner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    /**
     * Get saldo wallet partner
     */
    public function wallet(Request $request)
    {
        try {
            $partner = $request->user();

            return $this->ok([
                'saldo' => $partner->saldo_wallet,
                'detail' => [
                    'total_pendapatan' => $partner->total_pendapatan,
                    'total_topup' => $partner->total_topup,
                    'total_withdrawal' => $partner->total_withdrawal,
                ],
            ]);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Withdrawal saldo
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'nominal' => 'required|integer|min:10000', // Minimal withdrawal 10rb
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $partner = $request->user();
            $nominal = $request->nominal;

            // Validasi: Cek saldo cukup
            if ($partner->saldo_wallet < $nominal) {
                return $this->error('Saldo tidak mencukupi. Saldo Anda saat ini: Rp ' . number_format($partner->saldo_wallet, 0, ',', '.'), 400);
            }

            // Validasi: Minimal withdrawal (opsional, sesuaikan kebutuhan)
            if ($nominal < 10000) {
                return $this->error('Minimal withdrawal adalah Rp 10.000', 400);
            }

            return DB::transaction(function () use ($partner, $nominal, $request) {

                // Insert ke history keuangan
                $historyKeuangan = HistoryKeuanganPartner::create([
                    'partner_id' => $partner->id,
                    'nominal' => $nominal,
                    'status' => 'withdrawal',
                    'keterangan' => $request->keterangan ?? 'Penarikan saldo wallet',
                ]);

                // Hitung saldo terbaru
                $saldoBaru = $partner->fresh()->saldo_wallet;

                return $this->ok([
                    'message' => 'Withdrawal berhasil! Dana akan diproses dalam 1-3 hari kerja.',
                    'withdrawal' => [
                        'id' => $historyKeuangan->id,
                        'nominal' => $nominal,
                        'saldo_sebelum' => $partner->saldo_wallet + $nominal, // Saldo sebelum withdrawal
                        'saldo_setelah' => $saldoBaru,
                        'tanggal' => $historyKeuangan->created_at->format('d F Y H:i'),
                    ]
                ]);
            });
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * History keuangan partner (withdrawal & topup)
     */
    public function historyKeuangan(Request $request)
    {
        try {
            $partner = $request->user();
            $perPage = min($request->per_page ?? 15, 50);

            $history = HistoryKeuanganPartner::where('partner_id', $partner->id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $history->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'nominal' => $item->nominal,
                    'status' => $item->status,
                    'status_label' => $item->status === 'withdrawal' ? 'Penarikan' : 'Top Up',
                    'keterangan' => $item->keterangan,
                    'tanggal' => $item->created_at->format('d F Y H:i'),
                    'formatted_nominal' => 'Rp ' . number_format($item->nominal, 0, ',', '.'),
                ];
            });

            return $this->ok($history);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * History claim voucher (pendapatan)
     */
    public function historyPendapatan(Request $request)
    {
        try {
            $partner = $request->user();
            $perPage = min($request->per_page ?? 15, 50);

            $history = $partner->historyClaimVoucher()
                ->with(['voucher', 'member'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $history->getCollection()->transform(function ($claim) {
                return [
                    'id' => $claim->id,
                    'voucher' => $claim->voucher->name,
                    'member' => $claim->member->name,
                    'nominal_claim' => $claim->nominal_claim,
                    'persentase_claim' => $claim->persentase_claim,
                    'tanggal' => $claim->created_at->format('d F Y H:i'),
                    'formatted_nominal' => 'Rp ' . number_format($claim->nominal_claim, 0, ',', '.'),
                ];
            });

            return $this->ok($history);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }
}
