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

        // Kirim model Acara asli (bukan array hasil map) supaya blade bisa
        // akses $a->photo, $a->title, $a->event_date, dst secara langsung.
        $acaras = $mosque->acaras()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        return view('auth.adminmasjid.halamanUtamaUser', [
            'mosque'      => $mosque,
            'landingPage' => $landingPage,
            'prayers'     => $prayers,
            'acaras'      => $acaras,
        ]);
    }
}