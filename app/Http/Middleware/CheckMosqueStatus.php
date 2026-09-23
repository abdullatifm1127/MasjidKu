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
                // 3. Cek jika akun berstatus pending/nonaktif dari admin
                if ($mosque->status !== 'approved' && $mosque->package_type !== 'free') {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Akun masjid Anda masih berstatus Pending atau Nonaktif. Silakan tunggu persetujuan Superadmin.');
                }

                // 4. CEK PEMBAYARAN: Jika paket bukan 'free', pastikan payment_status sudah 'approved' (lunas)
                if ($mosque->package_type !== 'free' && $mosque->payment_status !== 'approved') {
                    // Tambahkan pengecualian untuk rute perpanjangan di sini
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