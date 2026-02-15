<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MemberResetPasswordController extends Controller
{
    // Tampilkan form reset password
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.member_reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Proses reset password
    public function reset(Request $request)
    {
       

         if(env('RECAPTCHA_V2') == 1){
        $credentials = $request->validate([
          'name' => 'required',
          'email' => 'required|email|unique:members',
          'password' => 'required|min:8|confirmed',
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

         $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

      }

        $status = Password::broker('members')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($member, $password) {
                $member->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('member.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}