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
    /**
     * Fitur donasi aktif jika paket bukan free DAN pembayaran sudah approved.
     * Dipakai di show() dan showDonasi() supaya aturannya sama di kedua halaman.
     * (package_type tersimpan sebagai 'free', '100000_1', atau '1000000_12',
     *  bukan 'premium' / 'pro' / 'paid'.)
     */
    protected function hasDonationFeature(Mosque $mosque): bool
    {
        return ($mosque->package_type ?? 'free') !== 'free'
            && strtolower(trim($mosque->payment_status ?? '')) === 'approved';
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

        // Jadwal bulanan: filter divalidasi, data di-cache, dan aman bila API gagal
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

        $hasDonationFeature = $this->hasDonationFeature($mosque);

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
            'hasDonationFeature'  => $hasDonationFeature,
            'categories'          => $categories,
            'items'               => $items,
            'donasiTerkumpul'     => $donasiTerkumpul,
            'donasiTarget'        => $donasiTarget,
        ]);
    }

    public function showDonasi(string $slug)
    {
        $mosque = Mosque::where('slug', $slug)->firstOrFail();

        if (!$this->hasDonationFeature($mosque)) {
            // Route lama 'masjid.detail' tidak ada di web.php; yang benar 'masjid.publik'
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

        // Riwayat Donasi Pribadi (hanya milik akun yang sedang login, status 'diterima' atau 'pending')
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