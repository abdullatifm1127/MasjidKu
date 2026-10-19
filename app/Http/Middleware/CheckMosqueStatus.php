<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Mosque;

class CheckMosqueStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            // 1. Jika Super Admin, abaikan pengecekan status masjid
            if ($user->role === 'super_admin') {
                return $next($request);
            }

            // 2. Cari data masjid berdasarkan user_id
            $mosque = Mosque::where('user_id', $user->id)->first();

            if ($mosque) {
                // 3. Izinkan akses ke dashboard jika statusnya pending (sedang menunggu verifikasi) 
                // atau approved, asalkan tidak berstatus 'rejected' atau 'nonaktif'
                if ($mosque->status === 'rejected' || $mosque->status === 'Nonaktif') {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Akun masjid Anda ditolak atau dinonaktifkan oleh Superadmin.');
                }

                // 4. PENGATURAN PEMBAYARAN:
                // Jika paket bukan 'free' dan pembayaran belum approved, 
                // TETAPI user sudah mengunggah bukti bayar (status pending), izinkan mereka akses dashboard.
                // Jika benar-benar belum bayar sama sekali (unpaid & belum ada bukti), baru arahkan ke halaman pembayaran.
                if ($mosque->package_type !== 'free' && $mosque->payment_status === 'unpaid' && empty($mosque->payment_proof)) {
                    if (!$request->is('masjid/pembayaran*') && !$request->is('masjid/batalkan*') && !$request->is('masjid/perpanjangan*')) {
                        return redirect()->route('masjid.payment')
                            ->with('error', 'Silakan selesaikan pembayaran langganan Anda terlebih dahulu.');
                    }
                }
            }
        }

        return $next($request);
    }
}