<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\LandingPage;
use App\Models\DonasiGaleri;
use App\Models\DonationCategory;
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

        $hasDonationFeature = in_array($mosque->package_type ?? 'free', ['premium', 'pro', 'paid']);

        // === TAMBAHKAN PENGAMBILAN DATA DONASI INI ===
        $categories = DonationCategory::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $items = DonasiGaleri::where('mosque_id', $mosque->id)
            ->latest()
            ->get();
        // ============================================

        return view('auth.adminmasjid.halamanUtamaUser', [
            'mosque'             => $mosque,
            'landingPage'        => $landingPage,
            'prayers'            => $prayers,
            'acaras'             => $acaras,
            'hasDonationFeature' => $hasDonationFeature,
            'categories'         => $categories, // <-- Kirim ke view
            'items'              => $items,      // <-- Kirim ke view
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

        // Ambil kategori donasi yang aktif
        $categories = DonationCategory::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // UBAH KE JSON agar bisa dibaca langsung oleh script window.donasiCategories di Blade
        $categoriesJson = $categories->toJson();

        return view('donasi.donasi', [
            'mosque'             => $mosque,
            'zakatFitrahDefault' => $zakatFitrahDefault,
            'items'              => $items,
            'categories'         => $categories,
            'categoriesJson'     => $categoriesJson, // <-- TAMBAHKAN INI
        ]);
    }
}