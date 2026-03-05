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
use Illuminate\Validation\Rules\Password;

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

            if (!$member || !Hash::check($request->password, $member->password)) {
                return $this->notFound('The provided credentials are incorrect.');
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

    /**
     * Get profil member yang sedang login
     */
    public function profile(Request $request)
    {
        try {
            $member = $request->user();

            return $this->ok([
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone ?? null,
                'created_at' => $member->created_at->format('d F Y'),
            ]);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'whatsapp.max' => 'Nomor telepon maksimal 20 karakter',
        ]);

        try {
            $member = $request->user();

            $member->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return $this->ok($member);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            $member = $request->user();

            // Validasi password lama
            if (!Hash::check($request->current_password, $member->password)) {
                return $this->error('Password lama tidak sesuai', 400);
            }

            // Validasi password baru tidak sama dengan password lama
            if (Hash::check($request->new_password, $member->password)) {
                return $this->error('Password baru tidak boleh sama dengan password lama', 400);
            }

            // Update password
            $member->update([
                'password' => Hash::make($request->new_password),
            ]);

            return $this->ok([
                'message' => 'Password berhasil diperbarui',
            ]);
        } catch (Exception $e) {
            return $this->error('Terjadi kesalahan: ' . $e->getMessage(), 500);
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
