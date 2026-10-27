<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sesuaikan pengecekan role sesuai database Anda (misal kolom 'role' atau 'is_superadmin')
        if (auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->email === 'admin@masjidku.id')) {
            return $next($request);
        }

        return redirect()->route('superadmin.login')->with('error', 'Akses ditolak. Silakan login sebagai Super Admin.');
    }
}