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
        // Hapus pengetatan ->where('status', 'approved') agar masjid yang baru memperpanjang/pending tetap bisa diakses publik
        $mosque = Mosque::where('slug', $slug)->firstOrFail();

        // Pastikan paketnya bukan free
        $hasDonationFeature = $mosque->package_type !== 'free';
        
        if (!$hasDonationFeature) {
            return redirect()->route('masjid.detail', $slug); 
        }

        $zakatFitrahDefault = $mosque->zakat_fitrah_default ?? 45000;
        
        // Pastikan Model Donasi Galeri aman dari error jika tabelnya kosong
        $items = class_exists('\App\Models\DonasiGaleri') 
            ? \App\Models\DonasiGaleri::where('mosque_id', $mosque->id)->latest()->get() 
            : collect();

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