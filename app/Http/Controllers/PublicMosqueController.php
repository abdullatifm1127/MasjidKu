<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\LandingPage;
use App\Models\DonasiGaleri;
use App\Models\DonationCategory;
use App\Models\Donasi;
use App\Models\Pengumuman;
use App\Services\PrayerTimeService;
use Illuminate\Http\Request;

class PublicMosqueController extends Controller
{
    /** Tema yang tersedia di halaman publik masjid. */
    protected const THEMES = ['default', 'emerald', 'terracotta', 'corporate'];

    /**
     * Fitur donasi aktif jika paket bukan free, pembayaran sudah approved,
     * dan masa langganan belum berakhir (bila tanggal berakhirnya tercatat).
     */
    protected function hasDonationFeature(Mosque $mosque): bool
    {
        $berbayar = ($mosque->package_type ?? 'free') !== 'free'
            && strtolower(trim($mosque->payment_status ?? '')) === 'approved';

        if (!$berbayar) {
            return false;
        }

        $akhir = data_get($mosque, 'subscription_ends_at');
        if ($akhir && \Carbon\Carbon::parse($akhir)->isPast()) {
            return false;
        }

        return true;
    }

    /** Tema aktif masjid (mendukung preview via parameter query). */
    protected function themeFor(Mosque $mosque, ?string $preview = null): string
{
    $theme = strtolower(trim((string) ($preview ?: ($mosque->theme ?? 'default'))));

    return in_array($theme, self::THEMES, true) ? $theme : 'default';
}

    public function show(string $slug, PrayerTimeService $prayerTimeService, Request $request)
    {
        $mosque = Mosque::where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $landingPage = LandingPage::where('mosque_id', $mosque->id)->first();

        if (!$landingPage || !$landingPage->is_published) {
            abort(404);
        }

        $prayers = $prayerTimeService->forMosque($mosque);

        $timezone = $prayerTimeService->timezoneFor($mosque);
        [$bulan, $tahun] = $prayerTimeService->resolvePeriod(
            $request->input('bulan'),
            $request->input('tahun'),
            $timezone
        );
        $monthlySchedule = $prayerTimeService->monthlyForMosque($mosque, $tahun, $bulan);

        $acaras = $mosque->acaras()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        $pengumumans = Pengumuman::where('mosque_id', $mosque->id)
            ->active()
            ->orderByDesc('is_pinned')
            ->latest()
            ->take(7)
            ->get();

        $hasDonationFeature = $this->hasDonationFeature($mosque);

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

        // Membaca tema aktif dari database atau parameter preview_theme di URL
        $preview = $request->query('preview_theme');
        $theme = $this->themeFor($mosque, is_string($preview) ? $preview : null);

        return view('auth.adminmasjid.halamanUtamaUser', [
            'mosque'              => $mosque,
            'landingPage'         => $landingPage,
            'prayers'             => $prayers,
            'monthlySchedule'     => $monthlySchedule,
            'bulan'               => $bulan,
            'tahun'               => $tahun,
            'timezone'            => $timezone,
            'timezoneLabel'       => $prayerTimeService->timezoneLabel($timezone),
            'today'               => \Carbon\Carbon::now($timezone)->toDateString(),
            'acaras'              => $acaras,
            'pengumumans'         => $pengumumans,
            'hasDonationFeature'  => $hasDonationFeature,
            'categories'          => $categories,
            'items'               => $items,
            'donasiTerkumpul'     => $donasiTerkumpul,
            'donasiTarget'        => $donasiTarget,
            'theme'               => $theme, // Dikirim ke Blade untuk atribut data-theme
        ]);
    }

    public function showDonasi(string $slug, Request $request)
    {
        $mosque = Mosque::where('slug', $slug)->firstOrFail();

        if (!$this->hasDonationFeature($mosque)) {
            return redirect()->route('masjid.publik', $slug);
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

        $preview = $request->query('preview_theme');
        $theme = $this->themeFor($mosque, is_string($preview) ? $preview : null);

        return view('donasi.donasi', [
            'mosque'             => $mosque,
            'zakatFitrahDefault' => $zakatFitrahDefault,
            'items'              => $items,
            'categories'         => $categories,
            'categoriesJson'     => $categoriesJson,
            'recentDonations'    => $recentDonations,
            'theme'              => $theme,
        ]);
    }
}