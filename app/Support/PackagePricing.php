<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class PackagePricing
{
    // jumlah bulan => kode di tabel payment_settings
    private const MAP = [
        1  => 'package_monthly',
        12 => 'package_yearly',
    ];

    /** Cache per-request supaya tidak query berulang untuk bulan yang sama. */
    private static array $cache = [];

    /** Ambil jumlah bulan dari kode paket, contoh "100000_1" => 1, "free" => 0. */
    public static function months(?string $code): int
    {
        if (!$code || $code === 'free') {
            return 0;
        }
        return (int) (explode('_', $code)[1] ?? 0);
    }

    /** Baca pengaturan paket dari tabel payment_settings (diatur Super Admin). */
    private static function setting(int $months): ?array
    {
        if (array_key_exists($months, self::$cache)) {
            return self::$cache[$months];
        }

        $settingCode = self::MAP[$months] ?? null;
        if (!$settingCode) {
            return self::$cache[$months] = null;
        }

        $row = DB::table('payment_settings')->where('code', $settingCode)->first();
        if (!$row) {
            return self::$cache[$months] = null;
        }

        $cfg = json_decode($row->config_data, true) ?: [];

        return self::$cache[$months] = [
            'name'  => $row->method_name ?? null,
            'price' => (int) ($cfg['price'] ?? 0),
        ];
    }

    /** Harga (angka) sesuai pengaturan Super Admin. Dipakai untuk tampilan. */
    public static function price(int $months): int
    {
        return self::setting($months)['price'] ?? 0;
    }

    /** Harga terformat, contoh: Rp 100.000 */
    public static function rupiah(int $months): string
    {
        return 'Rp ' . number_format(self::price($months), 0, ',', '.');
    }

    /** Kode paket untuk value radio, mengikuti harga Super Admin, contoh: "100000_1" */
    public static function code(int $months): string
    {
        return self::price($months) . '_' . $months;
    }

    /** Harga SELALU diambil dari pengaturan Super Admin, bukan dari kode yang dikirim user. */
    public static function resolve(?string $code): array
    {
        $months = self::months($code);

        if ($months === 0) {
            return ['name' => 'Free', 'amount' => 0, 'months' => 0];
        }

        $set = self::setting($months);

        if ($set) {
            return [
                'name'   => $set['name'] ?: ($months === 12 ? 'Paket 1 Tahun' : 'Paket ' . $months . ' Bulan'),
                'amount' => $set['price'],
                'months' => $months,
            ];
        }

        // Fallback terakhir (data lama): pakai angka di dalam kode, mis. "150000_1"
        return [
            'name'   => 'Paket ' . $months . ' Bulan',
            'amount' => (int) (explode('_', (string) $code)[0] ?? 0),
            'months' => $months,
        ];
    }

    public static function amount(?string $code): int
    {
        return self::resolve($code)['amount'];
    }

    /** Kode paket bulanan default, mengikuti harga Super Admin saat ini. */
    public static function defaultCode(): string
    {
        return self::code(1);
    }
}