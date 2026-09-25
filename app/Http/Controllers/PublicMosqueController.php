<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\LandingPage;
use App\Models\DonasiGaleri;
use App\Models\DonationCategory;
use App\Models\Donasi;
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

        // Perhitungan Dana Terkumpul Otomatis (Hanya status 'diterima')
        $donasiTerkumpul = Donasi::where('mosque_id', $mosque->id)
            ->where('status', 'diterima')
            ->sum('nominal');

        $donasiTarget = $mosque->donation_target ?? 500000000; 

        $categories = DonationCategory::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $items = DonasiGaleri::where('mosque_id', $mosque->id)
            ->latest()
            ->get();

        return view('auth.adminmasjid.halamanUtamaUser', [
            'mosque'             => $mosque,
            'landingPage'        => $landingPage,
            'prayers'            => $prayers,
            'acaras'             => $acaras,
            'hasDonationFeature' => $hasDonationFeature,
            'categories'         => $categories,
            'items'              => $items,
            'donasiTerkumpul'    => $donasiTerkumpul,
            'donasiTarget'       => $donasiTarget,
        ]);
    }

    public function showDonasi(string $slug)
    {
        $mosque = Mosque::where('slug', $slug)->firstOrFail();

        $hasDonationFeature = $mosque->package_type !== 'free';

        if (!$hasDonationFeature) {
            return redirect()->route('masjid.detail', $slug);
        }

        $zakatFitrahDefault = $mosque->zakat_fitrah_default ?? 45000;

        $items = class_exists('\App\Models\DonasiGaleri')
            ? \App\Models\DonasiGaleri::where('mosque_id', $mosque->id)->latest()->get()
            : collect();

        $categories = DonationCategory::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categoriesJson = $categories->toJson();

        // Riwayat Donasi Pribadi (Hanya menampilkan milik akun yang sedang login, status 'diterima' atau 'pending')
        $recentDonations = collect(); 
        
        if (auth()->check()) {
            $recentDonations = Donasi::where('mosque_id', $mosque->id)
                ->where('user_id', auth()->id()) 
                ->whereIn('status', ['diterima', 'pending'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($donasi) use ($categories) {
                    $donasi->category_title = optional($categories->firstWhere('key', $donasi->jenis))->title
                        ?? ucfirst($donasi->jenis);
                    return $donasi;
                });
        }

        return view('donasi.donasi', [
            'mosque'             => $mosque,
            'zakatFitrahDefault' => $zakatFitrahDefault,
            'items'              => $items,
            'categories'         => $categories,
            'categoriesJson'     => $categoriesJson,
            'recentDonations'    => $recentDonations,
        ]);
    }
}