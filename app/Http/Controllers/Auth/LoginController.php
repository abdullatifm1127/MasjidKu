<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login super admin
    public function showLoginForm()
    {
        return view('auth.superadmin.halamanloginSuperAdmin'); // Sesuaikan letak file blade Anda
    }

    // Memproses data saat tombol Masuk ditekan
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pengecekan: Jika akun adalah super admin / email khusus
            if ($user->email === 'admin@masjidku.id' || $user->role === 'super_admin') {
                return redirect()->intended('/superadmin/dashboard');
            }

            // Jika user biasa, diarahkan ke dashboard admin masjid
            return redirect()->intended('/dashboard');
        }

        // Jika gagal login, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}