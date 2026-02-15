<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class MemberRegisterController extends Controller {
  public function showRegisterForm() {
    return view('auth.member_register'); // View register khusus member
  }

  public function register(Request $request) {

    if(env('RECAPTCHA_V2') == 1){
        $credentials = $request->validate([
          'name' => 'required',
          'email' => 'required|email|unique:members',
          'password' => 'required|min:8',
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
          'name' => 'required',
          'email' => 'required|email|unique:members',
          'password' => 'required|min:8',
        ]);

      }

    Member::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    return redirect('/member/login')->with('success', 'Registrasi berhasil!');
  }
}
