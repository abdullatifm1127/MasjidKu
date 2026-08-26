<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\LandingPage;
use App\Services\PrayerTimeService;
use Illuminate\Http\Request;

class PublicMosqueController extends Controller
{
    /**
     * Tampilkan halaman utama publik satu masjid berdasarkan slug.
     * Contoh URL: /masjid/masjid-annur-malang
     */
    public function show(string $slug, PrayerTimeService $prayerTimeService)
    {
        $mosque = Mosque::where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $landingPage = LandingPage::where('mosque_id', $mosque->id)->first();

        // Kalau masjid belum publish landing page-nya, jangan tampilkan ke publik
        if (!$landingPage || !$landingPage->is_published) {
            abort(404);
        }

        $prayers = $prayerTimeService->forMosque($mosque);

        return view('auth.adminmasjid.halamanUtamaUser', [
            'mosque'      => $mosque,
            'landingPage' => $landingPage,
            'prayers'     => $prayers,
        ]);
    }
}