<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class PackagePricing
{
    /** Harga & durasi paket. Harga dibaca dari payment_settings (diatur Super Admin). */
    public static function resolve(string $packageType): array
    {
        // Angka di belakang "_" pada kode paket = lama langganan (bulan)
        $months  = (int) (explode('_', $packageType)[1] ?? 1) ?: 1;
        $code    = $months >= 12 ? 'package_yearly' : 'package_monthly';
        $default = $months >= 12 ? 1000000 : 100000;

        $row    = DB::table('payment_settings')->where('code', $code)->first();
        $config = $row ? (json_decode($row->config_data, true) ?: []) : [];

        return [
            'code'   => $code,
            'months' => $months,
            'amount' => (int) ($config['price'] ?? $default),
            'name'   => $row->method_name ?? ($months >= 12 ? 'Langganan 1 Tahun' : 'Langganan 1 Bulan'),
        ];
    }

    public static function amount(string $packageType): int
    {
        return self::resolve($packageType)['amount'];
    }

    /** Contoh: rupiah(1) => "Rp 100.000", rupiah(12) => "Rp 1.000.000" (sesuai pengaturan Super Admin) */
    public static function rupiah(int $months): string
    {
        return 'Rp ' . number_format(self::resolve('0_' . $months)['amount'], 0, ',', '.');
    }
}