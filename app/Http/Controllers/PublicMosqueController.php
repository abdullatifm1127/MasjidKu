<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\LandingPage;
use App\Models\DonasiGaleri;
use App\Services\PrayerTimeService;
use Illuminate\Http\Request;

class PublicMosqueController extends Controller
{
    public function show(string $slug, PrayerTimeService $prayerTimeService)
    {
        $mosque = Mosque::where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $landingPage = LandingPage::where('mosque_id', $mosque->id)->first();

        if (!$landingPage || !$landingPage->is_published) {
            abort(404);
        }

        $prayers = $prayerTimeService->forMosque($mosque);

        $acaras = $mosque->acaras()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // 1. SESUAIKAN KOLOM MENJADI package_type (berdasarkan database Anda)
        $hasDonationFeature = in_array($mosque->package_type ?? 'free', ['premium', 'pro', 'paid']);

        return view('auth.adminmasjid.halamanUtamaUser', [
            'mosque'             => $mosque,
            'landingPage'        => $landingPage,
            'prayers'            => $prayers,
            'acaras'             => $acaras,
            'hasDonationFeature' => $hasDonationFeature,
        ]);
    }

    public function showDonasi(string $slug)
    {
        $mosque = Mosque::where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        // 2. SESUAIKAN KOLOM MENJADI package_type
        $hasDonationFeature = in_array($mosque->package_type ?? 'free', ['premium', 'pro', 'paid']);
        
        if (!$hasDonationFeature) {
            // 3. SESUAIKAN NAMA RUTE REDIRECT KE RUTE YANG BENAR DI WEB.PHP ANDA
            // Contoh jika nama rutenya 'masjid.detail' atau sesuaikan dengan rute beranda publik masjid Anda:
            return redirect()->route('masjid.detail', $slug); 
        }

        $zakatFitrahDefault = $mosque->zakat_fitrah_default ?? 45000;
        $items = DonasiGaleri::where('mosque_id', $mosque->id)->latest('tanggal')->get();

        $categories = [
            'zakat' => ['title' => 'Zakat Fitrah & Mal'],
            'infaq' => ['title' => 'Infaq & Shodaqoh Umum'],
            'pembangunan' => ['title' => 'Pembangunan & Renovasi Masjid'],
            'kegiatan' => ['title' => 'Kegiatan & Santunan Sosial']
        ];

        return view('donasi.donasi', [
            'mosque'             => $mosque,
            'zakatFitrahDefault' => $zakatFitrahDefault,
            'items'              => $items,
            'categories'         => $categories,
        ]);
    }
}