<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('member.login')->withErrors('Login failed. Please try again.');
        }

        // Cari atau buat member berdasarkan email
        $member = Member::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'status' => 1,
                'provider_id' => $socialUser->getId(),
                'password' => bcrypt(uniqid()), // Password acak
            ]
        );

        // Login member
        Auth::guard('member')->login($member, true);

        return redirect()->route('member.dashboard');
    }
}