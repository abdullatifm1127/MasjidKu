<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // <--- 1. TAMBAHKAN INI DI BAGIAN ATAS

class AuthController extends Controller
{
    // Proses Register Akun
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_active_at' => Carbon::now(), // <--- 2. Tambahkan ini saat register jika langsung login
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            // <--- 3. UPDATE 'last_active_at' DI SINI SAAT LOGIN BERHASIL --->
            \App\Models\User::where('id', $user->id)->update([
                'last_active_at' => Carbon::now(),
            ]);
            // -------------------------------------------------------------

            $mosque = Mosque::where('user_id', $user->id)->first();

            // Belum mendaftarkan masjid
            if (!$mosque) {
                return redirect()->route('daftar.masjid');
            }

            // Menunggu verifikasi admin
            if ($mosque->status === 'pending') {
                return redirect()->route('waiting');
            }

            // Sudah disetujui admin
            if ($mosque->status === 'approved') {
                return redirect()->route('dashboard');
            }

            // Ditolak admin
            if ($mosque->status === 'rejected') {
                return redirect()->route('home')
                    ->with('error', 'Pendaftaran masjid ditolak admin.');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}