<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Models\Invoice;
use App\Models\MemberVoucher;
use App\Models\Partner;
use App\Models\Voucher;
use App\Models\VoucherClaimHistory;
use App\Models\VoucherPartnerDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function partner(Request $request)
    {
        try {
            $partners = Partner::paginate($request->per_page ?? 10);

            $partners->getCollection()->transform(function ($item) {
                $item->image = $item->image
                    ? config('app.url') . Storage::url($item->image)
                    : null;

                return $item;
            });

            return $this->ok($partners);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function voucher(Request $request)
    {
        try {
            $memberId = $request->user()->id;
            $perPage = min($request->per_page ?? 10, 50);
            $page = $request->page ?? 1;

            $khusus = MemberVoucher::with('voucher.tipe', 'voucher.jenis')
                ->where('member_id', $memberId)
                ->get()
                ->pluck('voucher');

            $terbuka = Voucher::with('tipe', 'jenis')
                ->whereHas('tipe', fn($q) => $q->where('tipe', 'terbuka'))
                ->get();

            $collection = $khusus
                ->merge($terbuka)
                ->unique('id')
                ->values();

            $paginated = new LengthAwarePaginator(
                $collection->forPage($page, $perPage)->values(),
                $collection->count(),
                $perPage,
                $page,
                ['path' => request()->url()]
            );

            $paginated->getCollection()->transform(function ($voucher) {

                $jenis = $voucher->jenis->jenis ?? null;
                $tipe = $voucher->tipe->tipe ?? null;

                $prefix = match ($jenis) {
                    'potongan_persentase' => '%',
                    'potongan_nominal', 'cashback' => 'Rp',
                    default => ''
                };

                $displayValue = match ($jenis) {
                    'potongan_persentase' => $voucher->value . '%',
                    'potongan_nominal', 'cashback' =>
                    'Rp ' . number_format($voucher->value, 0, ',', '.'),
                    'gratis' => 'Gratis',
                    default => $voucher->value
                };

                return [
                    'id' => $voucher->id,
                    'name' => $voucher->name,
                    'tipe' => $tipe,
                    'jenis' => $jenis,
                    'value' => $voucher->value,
                    'prefix' => $prefix,
                    'display_value' => $displayValue,
                    'start_date' => $voucher->tanggal_mulai,
                    'end_date' => $voucher->tanggal_selesai,
                ];
            });

            return $this->ok($paginated);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function historyOrder(Request $request)
    {
        try {
            $historyOrder = Invoice::with('items', 'items.product')->where('member_id', $request->user()->id)->get();
            return $this->ok($historyOrder);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function historyVoucher(Request $request)
    {
        try {
            $voucherHistory = VoucherClaimHistory::with('voucher', 'invoice')->where('member_id', $request->user()->id)->get();
            return $this->ok($voucherHistory);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function detailVoucher($id)
    {
        try {
            $voucher = VoucherPartnerDetail::with('voucher.jenis')
                ->where('partner_id', $id)
                ->get()
                ->map(function ($voucherPartner) {
                    $voucher = $voucherPartner->voucher;
                    $jenis = $voucher->jenis->jenis ?? null;

                    $prefix = match ($jenis) {
                        'potongan_persentase' => '%',
                        'potongan_nominal' => 'Rp',
                        'gratis' => '',
                        'cashback' => 'Rp',
                        default => ''
                    };

                    $displayValue = match ($jenis) {
                        'potongan_persentase' => $voucher->value . '%',
                        'potongan_nominal' => 'Rp ' . number_format($voucher->value, 0, ',', '.'),
                        'cashback' => 'Rp ' . number_format($voucher->value, 0, ',', '.'),
                        'gratis' => 'Gratis',
                        default => $voucher->value
                    };

                    return [
                        'id' => $voucherPartner->id,
                        'partner_id' => $voucherPartner->partner_id,
                        'voucher_id' => $voucher->id,
                        'voucher_name' => $voucher->name,
                        'jenis' => $jenis,
                        'value' => $voucher->value,
                        'prefix' => $prefix,
                        'display_value' => $displayValue,
                        'created_at' => $voucherPartner->created_at,
                        'updated_at' => $voucherPartner->updated_at,
                    ];
                });

            return $this->ok($voucher);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function detailPartner(Request $request)
    {
        try {
            $partner = Partner::with('vouchers')->where('id', $request->user()->partner_id)->first();
            return $this->ok($partner);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
