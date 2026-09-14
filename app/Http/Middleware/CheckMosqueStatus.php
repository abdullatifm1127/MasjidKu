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

            // 3. Izinkan masuk jika statusnya 'approved' ATAU paketnya 'free'
            if ($mosque && $mosque->status !== 'approved' && $mosque->package_type !== 'free') {
                auth()->logout();
                return redirect()->route('login')
                    ->with('error', 'Akun masjid Anda masih berstatus Pending atau Nonaktif. Silakan tunggu persetujuan Superadmin.');
            }
        }

        return $next($request);
    }
}