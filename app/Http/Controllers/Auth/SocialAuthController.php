<?php
// app/Http/Controllers/Auth/SocialAuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $social = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan ' . ucfirst($provider) . ' gagal. Silakan coba lagi.']);
        }

        if (!$social->getEmail()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun ' . ucfirst($provider) . ' Anda tidak membagikan email. Daftar manual dengan email.']);
        }

        // 1) Sudah pernah login dengan provider ini
        $user = User::where('provider', $provider)
            ->where('provider_id', $social->getId())
            ->first();

        // 2) Email sudah terdaftar -> hubungkan akunnya
        if (!$user) {
            $user = User::where('email', $social->getEmail())->first();

            if ($user) {
                $user->update([
                    'provider'    => $provider,
                    'provider_id' => $social->getId(),
                    'avatar'      => $social->getAvatar(),
                ]);
            } else {
                // 3) Pengguna baru
                $user = User::create([
                    'name'              => $social->getName() ?: Str::before($social->getEmail(), '@'),
                    'email'             => $social->getEmail(),
                    'email_verified_at' => now(),
                    'password'          => Hash::make(Str::random(32)),
                    'provider'          => $provider,
                    'provider_id'       => $social->getId(),
                    'avatar'            => $social->getAvatar(),
                ]);
            }
        }

        Auth::login($user, true);

        return redirect()->intended('/');
    }
}