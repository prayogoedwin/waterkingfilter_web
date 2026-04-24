<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class DeleteAccountRequestController extends Controller
{
    public function showForm()
    {
        return view('auth.delete_account_request');
    }

    public function submit(Request $request)
    {
        $captchaEnabled = env('RECAPTCHA_V2') == 1 && filled(env('RECAPTCHA_SECRET_KEY'));

        $validated = $request->validate([
            'account_type' => 'required|in:member,partner',
            'email' => 'required|email',
            'password' => 'required|string',
            'confirm_delete' => 'required|accepted',
            'recaptcha_token' => $captchaEnabled ? 'required' : 'nullable',
        ]);

        if ($captchaEnabled) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $validated['recaptcha_token'],
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false) || (($result['score'] ?? 0) < 0.5)) {
                return back()
                    ->withInput($request->except('password'))
                    ->withErrors(['email' => 'Verifikasi keamanan gagal.']);
            }
        }

        $user = $validated['account_type'] === 'member'
            ? Member::where('email', $validated['email'])->first()
            : Partner::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'Data akun tidak valid.']);
        }

        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        $user->delete();

        return redirect()
            ->route('account.delete.form')
            ->with('success', 'Permohonan hapus akun berhasil diproses. Akun Anda telah dinonaktifkan.');
    }
}
