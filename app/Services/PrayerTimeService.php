<?php

namespace App\Services;

use App\Models\Mosque;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrayerTimeService
{
    public const TZ_WIB  = 'Asia/Jakarta';
    public const TZ_WITA = 'Asia/Makassar';
    public const TZ_WIT  = 'Asia/Jayapura';

    protected const API_BASE     = 'https://api.myquran.com/v2/sholat';
    protected const HTTP_TIMEOUT = 6;      // detik
    protected const FAILURE_TTL  = 300;    // detik; hasil gagal di-cache singkat agar halaman tidak menunggu timeout berulang
    protected const DHUHA_OFFSET = 25;     // menit setelah terbit, dipakai bila API tidak menyediakan waktu Dhuha

    /**
     * Waktu shalat yang ditampilkan di kartu "hari ini", dipetakan ke key API.
     */
    protected array $map = [
        'Subuh'   => 'subuh',
        'Dzuhur'  => 'dzuhur',
        'Ashar'   => 'ashar',
        'Maghrib' => 'maghrib',
        'Isya'    => 'isya',
    ];

    /**
     * Provinsi per zona waktu (nilai sudah dinormalisasi: huruf kecil, tanpa spasi/tanda baca).
     * Provinsi dicek lebih dulu karena lebih akurat daripada daftar kota.
     */
    protected array $provinceTimezones = [
        self::TZ_WITA => [
            'bali', 'nusatenggarabarat', 'nusatenggaratimur',
            'kalimantanselatan', 'kalimantantimur', 'kalimantanutara',
            'sulawesiutara', 'sulawesitengah', 'sulawesiselatan', 'sulawesitenggara', 'sulawesibarat',
            'gorontalo',
        ],
        self::TZ_WIT => [
            'maluku', 'malukuutara',
            'papua', 'papuabarat', 'papuabaratdaya', 'papuaselatan', 'papuatengah', 'papuapegunungan',
        ],
    ];

    /**
     * Fallback bila kolom province kosong atau tidak dikenali: daftar kota/kabupaten WITA (UTC+8).
     */
    protected array $witaCities = [
        'denpasar', 'badung', 'gianyar', 'tabanan', 'klungkung', 'bangli', 'karangasem', 'buleleng', 'jembrana',
        'mataram', 'lombokbarat', 'lomboktengah', 'lomboktimur', 'lombokutara', 'sumbawa', 'sumbawabarat', 'dompu', 'bima', 'kotabima',
        'kupang', 'kotakupang', 'timortengahselatan', 'timortengahutara', 'belu', 'malaka', 'alor', 'floristimur',
        'sikka', 'ende', 'nagekeo', 'ngada', 'manggarai', 'manggaraibarat', 'manggaraitimur',
        'sumbabarat', 'sumbatengah', 'sumbatimur', 'sumbabaratdaya', 'rotendao', 'saburaijua', 'lembata',
        'banjarmasin', 'banjarbaru', 'banjar', 'baritokuala', 'tapin', 'hulusungaiselatan', 'hulusungaitengah',
        'hulusungaiutara', 'tabalong', 'tanahlaut', 'tanahbumbu', 'kotabaru', 'balangan',
        'samarinda', 'balikpapan', 'bontang', 'kutaikartanegara', 'kutaitimur', 'kutaibarat', 'paser', 'penajampaserutara', 'berau', 'mahakamulu',
        'tarakan', 'bulungan', 'malinau', 'nunukan', 'tanatidung',
        'manado', 'bitung', 'tomohon', 'kotamobagu', 'minahasa', 'minahasautara', 'minahasaselatan',
        'minahasatenggara', 'bolaangmongondow', 'sangihe', 'talaud', 'sitaro',
        'palu', 'poso', 'donggala', 'banggai', 'banggaikepulauan', 'banggailaut', 'buol', 'tolitoli',
        'parigimoutong', 'sigi', 'morowali', 'morowaliutara', 'tojounauna',
        'makassar', 'palopo', 'parepare', 'gowa', 'takalar', 'jeneponto', 'bantaeng', 'bulukumba', 'selayar',
        'sinjai', 'maros', 'pangkajene', 'barru', 'soppeng', 'wajo', 'sidenrengrappang', 'pinrang', 'enrekang',
        'luwu', 'luwuutara', 'luwutimur', 'tanatoraja', 'torajautara',
        'kendari', 'baubau', 'konawe', 'konaweselatan', 'konaweutara', 'konawekepulauan', 'kolaka',
        'kolakautara', 'kolakatimur', 'muna', 'munabarat', 'buton', 'butonutara', 'butonselatan',
        'butontengah', 'wakatobi', 'bombana',
        'mamuju', 'majene', 'polewalimandar', 'mamasa', 'pasangkayu', 'mamujutengah',
        'gorontalo', 'kotagorontalo', 'boalemo', 'bonebolango', 'gorontalotutara', 'pohuwato',
    ];

    /**
     * Fallback: daftar kota/kabupaten WIT (UTC+9).
     */
    protected array $witCities = [
        'ambon', 'malukutengah', 'buru', 'buruselatan', 'serambagianbarat', 'serambagiantimur',
        'kepulauanaru', 'malukutenggara', 'malukutenggarabarat', 'kepulauantanimbar', 'tual',
        'ternate', 'tidorekepulauan', 'halmaherabarat', 'halmaheratengah', 'halmaheratimur',
        'halmaherautara', 'halmaheraselatan', 'kepulauansula', 'pulaumorotai', 'pulautaliabu',
        'jayapura', 'kotajayapura', 'merauke', 'biaknumfor', 'nabire', 'jayawijaya', 'yahukimo',
        'pegununganbintang', 'bovendigoel', 'mappi', 'asmat', 'yapen', 'sarmi', 'keerom', 'waropen',
        'supiori', 'mamberamoraya', 'mamberamotengah', 'yalimo', 'puncakjaya', 'puncak', 'dogiyai',
        'intanjaya', 'deiyai', 'nduga', 'lannyjaya', 'tolikara', 'paniai', 'mimika',
        'manokwari', 'sorong', 'kotasorong', 'sorongselatan', 'sorongbaratdaya', 'maybrat', 'tambrauw',
        'rajaampat', 'fakfak', 'kaimana', 'telukbintuni', 'telukwondama', 'pegununganarfak',
    ];

    /* ------------------------------------------------------------------
     |  API publik
     * ------------------------------------------------------------------ */

    /**
     * Jadwal shalat HARI INI untuk satu masjid.
     * Tiap item: name, time, active, is_fallback (true bila memakai data perkiraan, bukan data API).
     */
    public function forMosque(Mosque $mosque): array
    {
        $timezone = $this->timezoneFor($mosque);
        $cityId   = $this->resolveCityId($mosque->city);
        $jadwal   = $cityId ? $this->fetchDaily($cityId, $timezone) : null;

        if (!$jadwal) {
            return $this->withActiveFlag($this->fallback(), $timezone, true);
        }

        $prayers = [];
        foreach ($this->map as $label => $apiKey) {
            $prayers[] = [
                'name' => $label,
                'time' => $this->validTime($jadwal[$apiKey] ?? null) ?? '--:--',
            ];
        }

        return $this->withActiveFlag($prayers, $timezone, false);
    }

    /**
     * Jadwal SATU BULAN penuh (sudah dinormalisasi & di-cache).
     * Tiap baris memuat key asli API (imsak, subuh, terbit, dhuha, dzuhur, ashar, maghrib, isya, tanggal)
     * ditambah: date (Y-m-d), day (1-31), weekday (0 = Ahad ... 6 = Sabtu).
     * Mengembalikan array kosong bila data tidak tersedia.
     */
    public function monthlyForMosque(Mosque $mosque, $year, $month): array
    {
        $cityId = $this->resolveCityId($mosque->city);
        if (!$cityId) {
            return [];
        }

        $year  = (int) $year;
        $month = (int) $month;
        $key   = sprintf('myquran:month:%s:%04d-%02d', $cityId, $year, $month);

        return $this->remember($key, now()->addDays(7), function () use ($cityId, $year, $month) {
            $url      = sprintf('%s/jadwal/%s/%04d/%02d', self::API_BASE, $cityId, $year, $month);
            $response = Http::timeout(self::HTTP_TIMEOUT)->get($url);

            if (!$response->successful() || $response->json('status') !== true) {
                return null;
            }

            $rows = $response->json('data.jadwal', []);

            return empty($rows) ? null : $this->normalizeMonth($rows, $year, $month);
        }) ?? [];
    }

    /**
     * Validasi & normalisasi filter bulan/tahun dari query string.
     * Nilai tidak valid diganti bulan/tahun sekarang (menurut zona waktu masjid).
     *
     * @return array{0: string, 1: int} [bulan dua digit, tahun]
     */
    public function resolvePeriod($month, $year, string $timezone): array
    {
        $now = Carbon::now($timezone);

        $m = filter_var($month, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 12]]);
        $y = filter_var($year, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => $now->year - 1, 'max_range' => $now->year + 2],
        ]);

        return [
            sprintf('%02d', $m !== false ? $m : $now->month),
            $y !== false ? $y : $now->year,
        ];
    }

    public function timezoneFor(Mosque $mosque): string
    {
        $province = $this->normalizeKey((string) ($mosque->province ?? ''));

        if ($province !== '') {
            foreach ($this->provinceTimezones as $tz => $provinces) {
                if (in_array($province, $provinces, true)) {
                    return $tz;
                }
            }
        }

        $city = $this->normalizeKey($this->cleanCityName($mosque->city));

        if (in_array($city, $this->witaCities, true)) {
            return self::TZ_WITA;
        }
        if (in_array($city, $this->witCities, true)) {
            return self::TZ_WIT;
        }

        return self::TZ_WIB;
    }

    public function timezoneLabel(string $timezone): string
    {
        if ($timezone === self::TZ_WITA) {
            return 'WITA';
        }

        return $timezone === self::TZ_WIT ? 'WIT' : 'WIB';
    }

    /* ------------------------------------------------------------------
     |  Internal
     * ------------------------------------------------------------------ */

    /**
     * Hapus awalan "Kota"/"Kabupaten"/"Kab." hanya di DEPAN nama,
     * sehingga nama seperti "Kotabaru" tidak rusak menjadi "baru".
     */
    protected function cleanCityName(?string $city): string
    {
        return trim((string) preg_replace('/^\s*(kota|kabupaten|kab\.?)\s+/i', '', (string) $city));
    }

    protected function normalizeKey(string $value): string
    {
        return (string) preg_replace('/[^a-z]/', '', str_replace('provinsi', '', Str::lower($value)));
    }

    protected function resolveCityId(?string $city): ?string
    {
        $city  = trim((string) $city);
        $clean = $this->cleanCityName($city);

        if ($clean === '') {
            return null;
        }

        $key = 'myquran:city:' . Str::slug($city, '_');

        return $this->remember($key, now()->addDays(30), function () use ($city, $clean) {
            $response = Http::timeout(self::HTTP_TIMEOUT)
                ->get(self::API_BASE . '/kota/cari/' . rawurlencode($clean));

            if (!$response->successful() || $response->json('status') !== true) {
                return null;
            }

            return $this->pickCity($response->json('data', []), $city, $clean);
        });
    }

    /**
     * Pilih hasil pencarian kota yang paling cocok.
     * Bila input diawali "Kota"/"Kab", hasil dengan awalan yang sama diprioritaskan
     * (membedakan mis. "Kota Bima" dan "Kabupaten Bima").
     */
    protected function pickCity(array $results, string $original, string $clean): ?string
    {
        if (empty($results)) {
            return null;
        }

        $needle = Str::lower($clean);

        $matches = array_values(array_filter($results, function ($r) use ($needle) {
            $lokasi = Str::lower((string) ($r['lokasi'] ?? ''));

            return $lokasi !== '' && str_contains($lokasi, $needle);
        }));

        if (empty($matches)) {
            $matches = array_values($results); // tidak ada yang cocok persis: pakai hasil teratas API
        }

        $type = preg_match('/^\s*kota\b/i', $original) ? 'kota'
            : (preg_match('/^\s*kab/i', $original) ? 'kab' : null);

        if ($type) {
            foreach ($matches as $r) {
                if (str_starts_with(Str::lower((string) ($r['lokasi'] ?? '')), $type)) {
                    return isset($r['id']) ? (string) $r['id'] : null;
                }
            }
        }

        return isset($matches[0]['id']) ? (string) $matches[0]['id'] : null;
    }

    protected function fetchDaily(string $cityId, string $timezone): ?array
    {
        $today = Carbon::now($timezone);
        $key   = sprintf('myquran:day:%s:%s', $cityId, $today->format('Y-m-d'));

        return $this->remember($key, $today->copy()->endOfDay(), function () use ($cityId, $today) {
            $url = sprintf(
                '%s/jadwal/%s/%s/%s/%s',
                self::API_BASE,
                $cityId,
                $today->format('Y'),
                $today->format('m'),
                $today->format('d')
            );

            $response = Http::timeout(self::HTTP_TIMEOUT)->get($url);

            if ($response->successful() && $response->json('status') === true) {
                return $response->json('data.jadwal');
            }

            return null;
        });
    }

    /**
     * Cache dengan negative caching: hasil gagal (null) disimpan singkat,
     * sehingga saat API mati halaman tidak menunggu timeout di setiap request.
     */
    protected function remember(string $key, $ttl, callable $callback)
    {
        $hit = Cache::get($key);

        if (is_array($hit) && array_key_exists('data', $hit)) {
            return $hit['data'];
        }

        try {
            $data = $callback();
        } catch (\Throwable $e) {
            Log::warning('PrayerTimeService: permintaan API gagal', ['key' => $key, 'error' => $e->getMessage()]);
            $data = null;
        }

        Cache::put($key, ['data' => $data], $data === null ? self::FAILURE_TTL : $ttl);

        return $data;
    }

    protected function normalizeMonth(array $rows, int $year, int $month): array
    {
        $base = Carbon::create($year, $month, 1);
        $out  = [];

        foreach (array_values($rows) as $i => $row) {
            if (!is_array($row)) {
                continue;
            }

            $date = (isset($row['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $row['date']))
                ? Carbon::parse($row['date'])
                : $base->copy()->addDays($i);

            $row['date']    = $date->toDateString();
            $row['day']     = $date->day;
            $row['weekday'] = $date->dayOfWeek;
            $row['dhuha']   = $this->validTime($row['dhuha'] ?? null)
                ?? $this->addMinutes($row['terbit'] ?? null, self::DHUHA_OFFSET)
                ?? '--:--';

            $out[] = $row;
        }

        return $out;
    }

    protected function withActiveFlag(array $prayers, string $timezone, bool $isFallback): array
    {
        $now         = Carbon::now($timezone);
        $activeIndex = null;

        foreach ($prayers as $i => $p) {
            if ($this->validTime($p['time']) === null) {
                continue;
            }

            $at = Carbon::createFromFormat('Y-m-d H:i', $now->toDateString() . ' ' . $p['time'], $timezone);

            if ($now->gte($at)) {
                $activeIndex = $i;
            }
        }

        // Sebelum Subuh, waktu yang masih berlaku adalah Isya hari sebelumnya.
        $activeIndex ??= count($prayers) - 1;

        foreach ($prayers as $i => &$p) {
            $p['active']      = ($i === $activeIndex);
            $p['is_fallback'] = $isFallback;
        }
        unset($p);

        return $prayers;
    }

    protected function validTime($time): ?string
    {
        return (is_string($time) && preg_match('/^\d{1,2}:\d{2}$/', $time)) ? $time : null;
    }

    protected function addMinutes(?string $time, int $minutes): ?string
    {
        if ($this->validTime($time) === null) {
            return null;
        }

        try {
            return Carbon::createFromFormat('H:i', $time)->addMinutes($minutes)->format('H:i');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Data perkiraan bila API tidak dapat dijangkau. Selalu ditandai is_fallback = true
     * agar tampilan dapat memberi tahu pengguna bahwa ini bukan jadwal resmi.
     */
    protected function fallback(): array
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