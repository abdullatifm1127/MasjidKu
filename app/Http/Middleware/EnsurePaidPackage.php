<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePaidPackage
{
    public function handle(Request $request, Closure $next)
    {
        $mosque = $request->user()?->mosque;

        if (! $mosque || ! $mosque->isPaid()) {
            return redirect()
                ->route('masjid.perpanjangan.create')
                ->with('error', 'Fitur ini khusus paket berbayar. Silakan berlangganan atau perpanjang paket.');
        }

        return $next($request);
    }
}