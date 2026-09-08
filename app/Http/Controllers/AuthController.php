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
            'last_active_at' => Carbon::now(),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home');
    }
// Proses Login Reguler (Admin Masjid / User)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            \App\Models\User::where('id', $user->id)->update([
                'last_active_at' => Carbon::now(),
            ]);

            $mosque = Mosque::where('user_id', $user->id)->first();

            // Jika belum daftar masjid, arahkan ke halaman daftar masjid
            if (!$mosque) {
                return redirect()->route('daftar.masjid');
            }

            // Jika statusnya masih pending, arahkan ke halaman waiting
            if ($mosque->status === 'pending' || $mosque->status === 'Pending') {
                return redirect()->route('waiting');
            }

            // Jika sudah Aktif / approved, langsung lempar ke dashboard admin.
            // (Nanti middleware 'check.status' yang akan otomatis mengamankan jika berubah jadi nonaktif/pending lagi)
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // --- TAMBAHKAN METHOD INI UNTUK LOGIN SUPER ADMIN ---
    public function superAdminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Update waktu aktif terakhir
            User::where('id', $user->id)->update([
    'last_active_at' => Carbon::now(),
]);

            // Validasi apakah user benar-benar superadmin (sesuaikan pengecekan role di database Anda)
            // Contoh jika menggunakan kolom 'role' atau 'is_superadmin':
            if (isset($user->role) && $user->role === 'superadmin') {
                return redirect()->route('superadmin.dashboard');
            }

            // Jika bukan superadmin, keluarkan dan berikan pesan error
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akses ditolak. Akun Anda bukan Super Admin.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi Super Admin salah.',
        ])->onlyInput('email');
    }
    // ----------------------------------------------------

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}