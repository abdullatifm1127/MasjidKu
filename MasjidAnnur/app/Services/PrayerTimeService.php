<?php

namespace App\Services;

use App\Models\Mosque;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrayerTimeService
{
    /**
     * Nama waktu shalat yang ditampilkan, dipetakan ke key response MyQuran API.
     */
    protected array $map = [
        'Subuh'   => 'subuh',
        'Dzuhur'  => 'dzuhur',
        'Ashar'   => 'ashar',
        'Maghrib' => 'maghrib',
        'Isya'    => 'isya',
    ];

    /**
     * Ambil jadwal shalat HARI INI untuk satu masjid, berdasarkan $mosque->city.
     * Sudah termasuk flag 'active' untuk kartu yang sedang berjalan.
     *
     * @return array<int, array{name:string, time:string, active:bool}>
     */
    public function forMosque(Mosque $mosque): array
    {
        $cityId = $this->resolveCityId($mosque->city);

        if (!$cityId) {
            return $this->withActiveFlag($this->dummy());
        }

        $jadwal = $this->fetchJadwal($cityId);

        if (!$jadwal) {
            return $this->withActiveFlag($this->dummy());
        }

        $prayers = [];
        foreach ($this->map as $label => $apiKey) {
            $prayers[] = [
                'name' => $label,
                'time' => $jadwal[$apiKey] ?? '--:--',
            ];
        }

        return $this->withActiveFlag($prayers);
    }

    /**
     * Cari id kota di MyQuran API berdasarkan nama kota (mis. "Malang").
     * Di-cache lama (30 hari) karena id kota tidak berubah-ubah.
     */
    protected function resolveCityId(?string $cityName): ?string
    {
        if (empty($cityName)) {
            return null;
        }

        $cacheKey = 'myquran_city_id_' . Str::slug($cityName, '_');

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($cityName) {
            try {
                $response = Http::timeout(6)
                    ->get('https://api.myquran.com/v2/sholat/kota/cari/' . urlencode($cityName));

                if ($response->successful() && $response->json('status') === true) {
                    $results = $response->json('data', []);
                    return $results[0]['id'] ?? null;
                }
            } catch (\Throwable $e) {
                Log::warning('PrayerTimeService: gagal cari kota', [
                    'city' => $cityName,
                    'error' => $e->getMessage(),
                ]);
            }

            return null;
        });
    }

    /**
     * Ambil jadwal shalat hari ini untuk id kota tertentu.
     * Di-cache 1 hari (reset otomatis tiap hari berganti).
     */
    protected function fetchJadwal(string $cityId): ?array
    {
        $today = Carbon::now();
        $cacheKey = sprintf('myquran_jadwal_%s_%s', $cityId, $today->format('Y-m-d'));

        return Cache::remember($cacheKey, now()->endOfDay(), function () use ($cityId, $today) {
            try {
                $url = sprintf(
                    'https://api.myquran.com/v2/sholat/jadwal/%s/%s/%s/%s',
                    $cityId,
                    $today->format('Y'),
                    $today->format('m'),
                    $today->format('d')
                );

                $response = Http::timeout(6)->get($url);

                if ($response->successful() && $response->json('status') === true) {
                    return $response->json('data.jadwal');
                }
            } catch (\Throwable $e) {
                Log::warning('PrayerTimeService: gagal ambil jadwal', [
                    'city_id' => $cityId,
                    'error' => $e->getMessage(),
                ]);
            }

            return null;
        });
    }

    /**
     * Tandai satu item sebagai 'active' = waktu shalat terakhir yang sudah lewat hari ini.
     * Kalau belum masuk Subuh, dianggap masih waktu Isya (item terakhir).
     */
    protected function withActiveFlag(array $prayers): array
    {
        $now = Carbon::now();
        $activeIndex = null;

        foreach ($prayers as $i => $p) {
            if ($p['time'] === '--:--') {
                continue;
            }
            $prayerTime = Carbon::createFromFormat('H:i', $p['time'])
                ->setDate($now->year, $now->month, $now->day);

            if ($now->gte($prayerTime)) {
                $activeIndex = $i;
            }
        }

        $activeIndex = $activeIndex ?? (count($prayers) - 1);

        foreach ($prayers as $i => &$p) {
            $p['active'] = ($i === $activeIndex);
        }

        return $prayers;
    }

    protected function dummy(): array
    {
        return [
            ['name' => 'Subuh',   'time' => '04:32'],
            ['name' => 'Dzuhur',  'time' => '12:05'],
            ['name' => 'Ashar',   'time' => '15:21'],
            ['name' => 'Maghrib', 'time' => '18:02'],
            ['name' => 'Isya',    'time' => '19:14'],
        ];
    }
}