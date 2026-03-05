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
use Illuminate\Support\Str;

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

            $memberVouchers = MemberVoucher::with([
                'voucher.jenis',
                'voucher.waktuVoucher',
            ])
                ->where('member_id', $memberId)
                ->get();

            $paginated = new LengthAwarePaginator(
                $memberVouchers->forPage($page, $perPage)->values(),
                $memberVouchers->count(),
                $perPage,
                $page,
                ['path' => request()->url()]
            );

            $paginated->getCollection()->transform(function ($memberVoucher) {
                $voucher = $memberVoucher->voucher;
                $jenis = $voucher->jenis->jenis ?? null;

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
                    'jenis' => $jenis,
                    'value' => $voucher->value,
                    'prefix' => $prefix,
                    'display_value' => $displayValue,
                    'start_date' => $voucher->tanggal_mulai?->format('Y-m-d'),
                    'end_date' => $voucher->tanggal_selesai?->format('Y-m-d'),
                    'waktu' => $voucher->waktuVoucher?->waktu,
                    'waktu_description' => $voucher->waktu_description,
                    'can_use_today' => $voucher->canBeUsedToday(),
                    'is_active' => $voucher->isActive(),
                    'barcode' => $memberVoucher->barcode,
                    'claim_count' => $memberVoucher->claim_count,
                    'last_claim_date' => $memberVoucher->last_claim_date,
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

            $historyOrder = Invoice::with('items', 'items.product')
                ->where('member_id', $request->user()->id)
                ->get();

            $historyOrder->each(function ($invoice) {
                $invoice->items->each(function ($item) {

                    if ($item->product && $item->product->image) {
                        if (!Str::startsWith($item->product->image, ['http://', 'https://'])) {
                            $item->product->image_url = asset('storage/' . $item->product->image);
                        } else {
                            $item->product->image_url = $item->product->image;
                        }
                    }
                });
            });

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
    public function detailVoucher(Request $request, $id)
    {
        try {
            $memberId = $request->user()->id;

            $vouchers = VoucherPartnerDetail::with([
                'voucher.jenis',
                'voucher.waktuVoucher'
            ])
                ->where('partner_id', $id)
                ->get()
                ->map(function ($voucherPartner) use ($memberId) {
                    $voucher = $voucherPartner->voucher;
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

                    // Cek apakah member memiliki voucher ini
                    $memberVoucher = MemberVoucher::where('member_id', $memberId)
                        ->where('voucher_id', $voucher->id)
                        ->first();

                    $isMemberOwned = $memberVoucher !== null;

                    return [
                        'id' => $voucherPartner->id,
                        'partner_id' => $voucherPartner->partner_id,
                        'voucher_id' => $voucher->id,
                        'voucher_name' => $voucher->name,
                        'jenis' => $jenis,
                        'value' => $voucher->value,
                        'prefix' => $prefix,
                        'display_value' => $displayValue,
                        'waktu' => $voucher->waktuVoucher?->waktu,
                        'waktu_description' => $voucher->waktu_description,
                        'start_date' => $voucher->tanggal_mulai?->format('Y-m-d'),
                        'end_date' => $voucher->tanggal_selesai?->format('Y-m-d'),
                        'can_use_today' => $voucher->canBeUsedToday(),
                        'is_active' => $voucher->isActive(),
                        'is_member_owned' => $isMemberOwned,
                        'barcode' => $isMemberOwned ? $memberVoucher->barcode : null,
                        'created_at' => $voucherPartner->created_at,
                        'updated_at' => $voucherPartner->updated_at,
                    ];
                });

            return $this->ok($vouchers);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function detailPartner(Request $request)
    {
        try {
            $countVoucher = VoucherPartnerDetail::where('partner_id', $request->user()->id)->count();
            $claim = VoucherClaimHistory::where('partner_id', $request->user()->id)->count();
            $sisa = $countVoucher - $claim;
            return $this->ok([
                'jumlah_voucher' => $countVoucher,
                'jumlah_claim' => $claim,
                'sisa' => $sisa
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
