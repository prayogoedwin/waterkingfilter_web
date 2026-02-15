<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MemberForgotPasswordController extends Controller
{
    // Tampilkan form lupa password
    public function showLinkRequestForm()
    {
        return view('auth.passwords.member_email');
    }

    // Kirim email reset password
    public function sendResetLinkEmail_(Request $request)
    {
        if(env('RECAPTCHA_V2') == 1){
        $credentials = $request->validate([
          'email' => 'required|email',
          'recaptcha_token' => 'required'
        ]);
        // Verifikasi token dengan Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
            return back()->withErrors(['email' => 'Verifikasi keamanan gagal.']);
        }

      }else{

        $request->validate(['email' => 'required|email']);

      }

        // Kirim reset link menggunakan broker 'members'
        $status = Password::broker('members')->sendResetLink(
            $request->only('email')
        );
        

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

   private function generatePassword($length = 5)
    {
        $chars = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($chars, 5)), 0, $length);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'recaptcha_token' => env('RECAPTCHA_V2') ? 'required' : '',
        ]);

        // Cek reCAPTCHA jika diaktifkan
        if (env('RECAPTCHA_V2')) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->recaptcha_token,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
                return back()->withErrors(['email' => 'Verifikasi keamanan gagal.']);
            }
        }

        // Cari member berdasarkan email
        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Generate dan hash password baru
        $newPassword = $this->generatePassword();
        $member->password = Hash::make($newPassword);
        $member->save();

        // Kirim email password baru
        try {
            Mail::raw("Password baru Anda adalah: {$newPassword}", function ($message) use ($member) {
                $message->to($member->email)
                        ->subject('Password Baru Anda');
            });

            return back()->with(['status' => 'Password baru telah dikirim ke email Anda.']);
        } catch (Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. ' . $e->getMessage()]);
        }
    }
}