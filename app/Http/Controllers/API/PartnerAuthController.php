<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Models\Partner;
use App\Models\VoucherClaimHistory;
use App\Models\VoucherPartnerDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PartnerAuthController extends Controller
{
    public function me(Request $request)
    {
        try {
            $countVoucher = VoucherPartnerDetail::where('partner_id', $request->user()->id)->count();
            $claim = VoucherClaimHistory::where('partner_id', $request->user()->id)->count();
            $sisa = $countVoucher - $claim;
            $profile = $request->user();
            $data = [
                'id' => $profile->id,
                'name' => $profile->name,
                'email' => $profile->email,
                'photo' => $profile->image
                    ? asset('storage/' . $profile->image)
                    : null,
            ];
            return $this->ok([
                'profile' => $data,
                'jumlah_voucher' => $countVoucher,
                'jumlah_claim' => $claim,
                'sisa' => $sisa
            ]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        try {
            $partner = Partner::where('email', $request->email)->first();

            if (!$partner || !Hash::check($request->password, $partner->password)) {
                return $this->notFound('The provided credentials are incorrect.');
            }

            $token = $partner->createToken('partner-token')->plainTextToken;
            $data = [
                'id' => $partner->id,
                'name' => $partner->name,
                'email' => $partner->email,
                'photo' => $partner->image
                    ? asset('storage/' . $partner->image)
                    : null,
            ];

            return $this->ok([
                'access_token' => $token,
                'partner' => $data
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ]);

        try {
            $partner = Auth::guard('partner-api')->user();

            if (!$partner) {
                return $this->error('Unauthorized');
            }

            // cek password lama
            if (!Hash::check($request->password_lama, $partner->password)) {
                return $this->error('Password lama salah');
            }

            // update password
            $partner->password = Hash::make($request->password_baru);
            $partner->save();

            return $this->ok([
                'message' => 'Password berhasil diubah'
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
