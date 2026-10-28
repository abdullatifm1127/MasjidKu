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

    public static function months(?string $code): int
    {
        if (!$code || $code === 'free') {
            return 0;
        }
        return (int) (explode('_', $code)[1] ?? 0);
    }

    private static function setting(int $months): ?array
    {
        $settingCode = self::MAP[$months] ?? null;
        if (!$settingCode) {
            return null;
        }

        $row = DB::table('payment_settings')->where('code', $settingCode)->first();
        if (!$row) {
            return null;
        }

        $cfg = json_decode($row->config_data, true) ?: [];

        return [
            'name'  => $row->method_name ?? null,
            'price' => (int) ($cfg['price'] ?? 0),
        ];
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
        $set = self::setting(1);
        return ($set['price'] ?? 0) . '_1';
    }
}