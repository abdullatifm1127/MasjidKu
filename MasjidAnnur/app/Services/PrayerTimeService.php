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
     * Pemetaan provinsi -> zona waktu PHP/IANA.
     * Key sudah dinormalisasi lowercase + tanpa spasi/strip lewat normalizeProvince().
     *
     * WIB = Asia/Jakarta (UTC+7)
     * WITA = Asia/Makassar (UTC+8)
     * WIT = Asia/Jayapura (UTC+9)
     */
    protected array $provinceTimezones = [
        // ===== WIB =====
        'aceh'                        => 'Asia/Jakarta',
        'sumaterautara'                => 'Asia/Jakarta',
        'sumateraselatan'               => 'Asia/Jakarta',
        'sumaterabarat'                 => 'Asia/Jakarta',
        'bengkulu'                      => 'Asia/Jakarta',
        'riau'                          => 'Asia/Jakarta',
        'kepulauanriau'                 => 'Asia/Jakarta',
        'jambi'                         => 'Asia/Jakarta',
        'lampung'                       => 'Asia/Jakarta',
        'bangkabelitung'                => 'Asia/Jakarta',
        'kepulauanbangkabelitung'       => 'Asia/Jakarta',
        'banten'                        => 'Asia/Jakarta',
        'dkijakarta'                    => 'Asia/Jakarta',
        'jakarta'                       => 'Asia/Jakarta',
        'jawabarat'                     => 'Asia/Jakarta',
        'jawatengah'                    => 'Asia/Jakarta',
        'diyogyakarta'                  => 'Asia/Jakarta',
        'yogyakarta'                    => 'Asia/Jakarta',
        'jawatimur'                     => 'Asia/Jakarta',
        'kalimantanbarat'               => 'Asia/Jakarta',
        'kalimantantengah'              => 'Asia/Jakarta',

        // ===== WITA =====
        'bali'                          => 'Asia/Makassar',
        'nusatenggarabarat'             => 'Asia/Makassar',
        'nusatenggaratimur'             => 'Asia/Makassar',
        'kalimantanselatan'             => 'Asia/Makassar',
        'kalimantantimur'               => 'Asia/Makassar',
        'kalimantanutara'               => 'Asia/Makassar',
        'sulawesiutara'                 => 'Asia/Makassar',
        'sulawesitengah'                => 'Asia/Makassar',
        'sulawesiselatan'               => 'Asia/Makassar',
        'sulawesitenggara'              => 'Asia/Makassar',
        'sulawesibarat'                 => 'Asia/Makassar',
        'gorontalo'                     => 'Asia/Makassar',

        // ===== WIT =====
        'maluku'                        => 'Asia/Jayapura',
        'malukuutara'                   => 'Asia/Jayapura',
        'papua'                         => 'Asia/Jayapura',
        'papuabarat'                    => 'Asia/Jayapura',
        'papuabaratdaya'                => 'Asia/Jayapura',
        'papuatengah'                   => 'Asia/Jayapura',
        'papuapegunungan'               => 'Asia/Jayapura',
        'papuaselatan'                  => 'Asia/Jayapura',
    ];

    /**
     * Ambil jadwal shalat HARI INI untuk satu masjid, berdasarkan $mosque->city
     * dan zona waktu yang mengikuti $mosque->province.
     * Sudah termasuk flag 'active' untuk kartu yang sedang berjalan.
     *
     * @return array<int, array{name:string, time:string, active:bool}>
     */
    public function forMosque(Mosque $mosque): array
    {
        $timezone = $this->resolveTimezone($mosque->province);
        $cityId   = $this->resolveCityId($mosque->city);

        if (!$cityId) {
            return $this->withActiveFlag($this->dummy(), $timezone);
        }

        $jadwal = $this->fetchJadwal($cityId, $timezone);

        if (!$jadwal) {
            return $this->withActiveFlag($this->dummy(), $timezone);
        }

        $prayers = [];
        foreach ($this->map as $label => $apiKey) {
            $prayers[] = [
                'name' => $label,
                'time' => $jadwal[$apiKey] ?? '--:--',
            ];
        }

        return $this->withActiveFlag($prayers, $timezone);
    }

    /**
     * Tentukan timezone IANA berdasarkan nama provinsi masjid.
     * Default ke Asia/Jakarta (WIB) kalau provinsi tidak dikenali/kosong.
     */
    protected function resolveTimezone(?string $province): string
    {
        if (empty($province)) {
            return 'Asia/Jakarta';
        }

        $key = $this->normalizeProvince($province);

        return $this->provinceTimezones[$key] ?? 'Asia/Jakarta';
    }

    /**
     * Normalisasi nama provinsi supaya cocok dengan key di $provinceTimezones,
     * terlepas dari variasi penulisan (spasi, huruf besar/kecil, tanda hubung).
     */
    protected function normalizeProvince(string $province): string
    {
        $value = Str::lower($province);
        $value = str_replace(['provinsi', 'prov.', 'prov '], '', $value);
        $value = preg_replace('/[^a-z]/', '', $value); // buang spasi, titik, strip, dll

        return $value;
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
     * Tanggal "hari ini" dihitung berdasarkan timezone masjid itu sendiri,
     * supaya masjid di WIT tidak salah ambil tanggal saat mendekati tengah malam.
     * Di-cache 1 hari (reset otomatis tiap hari berganti, per kota+timezone).
     */
    protected function fetchJadwal(string $cityId, string $timezone): ?array
    {
        $today = Carbon::now($timezone);
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
     * Tandai satu item sebagai 'active' = waktu shalat terakhir yang sudah lewat hari ini,
     * dihitung memakai timezone masjid tersebut (bukan timezone server).
     * Kalau belum masuk Subuh, dianggap masih waktu Isya (item terakhir).
     */
    protected function withActiveFlag(array $prayers, string $timezone): array
    {
        $now = Carbon::now($timezone);
        $activeIndex = null;

        foreach ($prayers as $i => $p) {
            if ($p['time'] === '--:--') {
                continue;
            }
            $prayerTime = Carbon::createFromFormat('H:i', $p['time'], $timezone)
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