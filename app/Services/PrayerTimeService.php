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
     * Daftar kota/kabupaten yang masuk WITA (UTC+8).
     */
    protected array $witaCities = [
        // Bali
        'denpasar', 'badung', 'gianyar', 'tabanan', 'klungkung', 'bangli', 'karangasem', 'buleleng', 'jembrana',
        // NTB
        'mataram', 'lombokbarat', 'lomboktengah', 'lomboktimur', 'lombokutara', 'sumbawa', 'sumbawabarat', 'dompu', 'bima', 'kotabima',
        // NTT
        'kupang', 'kotakupang', 'timortengahselatan', 'timortengahutara', 'belu', 'malaka', 'alor', 'floristimur',
        'sikka', 'ende', 'nagekeo', 'ngada', 'manggarai', 'manggaraibarat', 'manggaraitimur',
        'sumbabarat', 'sumbatengah', 'sumbatimur', 'sumbabaratdaya', 'rotendao', 'saburaijua', 'lembata',
        // Kalimantan Selatan
        'banjarmasin', 'banjarbaru', 'banjar', 'baritokuala', 'tapin', 'hulusungaiselatan', 'hulusungaitengah',
        'hulusungaiutara', 'tabalong', 'tanahlaut', 'tanahbumbu', 'kotabaru', 'balangan',
        // Kalimantan Timur
        'samarinda', 'balikpapan', 'bontang', 'kutaikartanegara', 'kutaitimur', 'kutaibarat', 'paser', 'penajampaserutara', 'berau', 'mahakamulu',
        // Kalimantan Utara
        'tarakan', 'bulungan', 'malinau', 'nunukan', 'tanatidung',
        // Sulawesi Utara
        'manado', 'bitung', 'tomohon', 'kotamobagu', 'minahasa', 'minahasautara', 'minahasaselatan',
        'minahasatenggara', 'bolaangmongondow', 'sangihe', 'talaud', 'sitaro',
        // Sulawesi Tengah
        'palu', 'poso', 'donggala', 'banggai', 'banggaikepulauan', 'banggailaut', 'buol', 'tolitoli',
        'parigimoutong', 'sigi', 'morowali', 'morowaliutara', 'tojounauna',
        // Sulawesi Selatan
        'makassar', 'palopo', 'parepare', 'gowa', 'takalar', 'jeneponto', 'bantaeng', 'bulukumba', 'selayar',
        'sinjai', 'maros', 'pangkajene', 'barru', 'soppeng', 'wajo', 'sidenrengrappang', 'pinrang', 'enrekang',
        'luwu', 'luwuutara', 'luwutimur', 'tanatoraja', 'torajautara',
        // Sulawesi Tenggara
        'kendari', 'baubau', 'konawe', 'konaweselatan', 'konaweutara', 'konawekepulauan', 'kolaka',
        'kolakautara', 'kolakatimur', 'muna', 'munabarat', 'buton', 'butonutara', 'butonselatan',
        'butontengah', 'wakatobi', 'bombana',
        // Sulawesi Barat
        'mamuju', 'majene', 'polewalimandar', 'mamasa', 'pasangkayu', 'mamujutengah',
        // Gorontalo
        'gorontalo', 'kotagorontalo', 'boalemo', 'bonebolango', 'gorontalotutara', 'pohuwato',
    ];

    /**
     * Daftar kota/kabupaten yang masuk WIT (UTC+9).
     */
    protected array $witCities = [
        // Maluku
        'ambon', 'malukutengah', 'buru', 'buruselatan', 'serambagianbarat', 'serambagiantimur',
        'kepulauanaru', 'malukutenggara', 'malukutenggarabarat', 'kepulauantanimbar', 'tual',
        // Maluku Utara
        'ternate', 'tidorekepulauan', 'halmaherabarat', 'halmaheratengah', 'halmaheratimur',
        'halmaherautara', 'halmaheraselatan', 'kepulauansula', 'pulaumorotai', 'pulautaliabu',
        // Papua
        'jayapura', 'kotajayapura', 'merauke', 'biaknumfor', 'nabire', 'jayawijaya', 'yahukimo',
        'pegununganbintang', 'bovendigoel', 'mappi', 'asmat', 'yapen', 'sarmi', 'keerom', 'waropen',
        'supiori', 'mamberamoraya', 'mamberamotengah', 'yalimo', 'puncakjaya', 'puncak', 'dogiyai',
        'intanjaya', 'deiyai', 'nduga', 'lannyjaya', 'tolikara', 'paniai', 'mimika',
        'manokwari', 'sorong', 'kotasorong', 'sorongselatan', 'sorongbaratdaya', 'maybrat', 'tambrauw',
        'rajaampat', 'fakfak', 'kaimana', 'telukbintuni', 'telukwondama', 'pegununganarfak',
    ];

    /**
     * Ambil jadwal shalat HARI INI untuk satu masjid, berdasarkan $mosque->city
     */
    public function forMosque(Mosque $mosque): array
    {
        $timezone = $this->resolveTimezone($mosque->city);
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
     * Tentukan timezone IANA berdasarkan nama kota/kabupaten masjid.
     */
    protected function resolveTimezone(?string $city): string
    {
        if (empty($city)) {
            return 'Asia/Jakarta';
        }

        $key = $this->normalizeCity($city);

        if (in_array($key, $this->witaCities, true)) {
            return 'Asia/Makassar';
        }

        if (in_array($key, $this->witCities, true)) {
            return 'Asia/Jayapura';
        }

        return 'Asia/Jakarta';
    }

    /**
     * Normalisasi nama kota supaya bersih dari kata kabupaten/kota.
     */
    protected function normalizeCity(string $city): string
    {
        $value = Str::lower($city);
        $value = str_replace(['kota ', 'kabupaten ', 'kab.', 'kab '], '', $value);
        $value = preg_replace('/[^a-z]/', '', $value);

        return $value;
    }

    /**
     * Cari ID kota di MyQuran API dengan pembersihan keyword yang lebih optimal.
     */
    protected function resolveCityId(?string $cityName): ?string
    {
        if (empty($cityName)) {
            return null;
        }

        $cleanName = trim(str_ireplace(['Kota', 'Kabupaten', 'Kab.'], '', $cityName));
        $cacheKey = 'myquran_city_id_' . Str::slug($cleanName, '_');

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($cleanName) {
            try {
                $response = Http::timeout(6)
                    ->get('https://api.myquran.com/v2/sholat/kota/cari/' . urlencode($cleanName));

                if ($response->successful() && $response->json('status') === true) {
                    $results = $response->json('data', []);
                    
                    if (!empty($results)) {
                        // Cocokkan yang paling mirip jika ada banyak hasil
                        foreach ($results as $res) {
                            $lokasi = strtolower($res['lokasi'] ?? '');
                            if (str_contains($lokasi, strtolower($cleanName)) || str_contains(strtolower($cleanName), $lokasi)) {
                                return $res['id'] ?? null;
                            }
                        }
                        // Fallback ambil hasil pertama jika tidak ada yang exact match
                        return $results[0]['id'] ?? null;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('PrayerTimeService: gagal cari kota', [
                    'city' => $cleanName,
                    'error' => $e->getMessage(),
                ]);
            }

            return null;
        });
    }

    /**
     * Ambil jadwal shalat hari ini untuk ID kota tertentu.
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