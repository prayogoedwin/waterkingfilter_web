<?php

namespace App\Http\Controllers\API;

use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberVoucher;
use App\Models\VoucherClaimHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function user(Request $request)
    {
        try {
            $countVoucher = MemberVoucher::with('voucher')->where('member_id', $request->user()->id);
            $historyOrder = Invoice::with('items', 'items.product')->where('member_id', $request->user()->id);
            $voucherHistory = VoucherClaimHistory::with('voucher', 'invoice')->where('member_id', $request->user()->id);
            return $this->ok([
                'profile' => $request->user(),
                'count_voucher' => $countVoucher->count(),
                'list_voucher' => $countVoucher->get(),
                'history_order' => $historyOrder->get(),
                'voucher_claim_history' => $voucherHistory->get(),
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('members')->whereNull('deleted_at'),
            ],
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'phoneNumber' => 'required'
        ]);

        try {
            $member = Member::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $member->createToken('member-token')->plainTextToken;

            return $this->ok([
                'access_token' => $token,
                'member' => $member
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        try {
            $member = Member::where('email', $request->email)->first();

            if (! $member || ! Hash::check($request->password, $member->password)) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $token = $member->createToken('member-token')->plainTextToken;

            return $this->ok([
                'access_token' => $token,
                'member' => $member
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->ok('Logout berhasil');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
