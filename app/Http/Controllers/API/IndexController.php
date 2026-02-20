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
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function partner()
    {
        try {
            $partner = Partner::get();
            $partner->map(function ($item) {
                $item->image = env('APP_URL') . Storage::url($item->image);
            });
            return $this->ok($partner);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function voucher(Request $request)
    {
        try {
            $memberId = $request->user()->id;
            $khusus = MemberVoucher::with('voucher.tipe')
                ->where('member_id', $memberId)
                ->get();
            $terbuka = Voucher::whereHas(
                'tipe',
                fn($q) =>
                $q->where('tipe', 'terbuka')
            )->get();

            $list = $khusus->pluck('voucher')
                ->merge($terbuka)
                ->unique('id')
                ->values();
            return $this->ok($list);
        } catch (Exception $e) {
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
            $voucher = VoucherPartnerDetail::with('voucher')->where('partner_id', $id)->get();
            return $this->ok($voucher);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
