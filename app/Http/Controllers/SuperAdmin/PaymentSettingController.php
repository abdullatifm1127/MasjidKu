<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function index()
    {
        // Seed default jika tabel masih kosong
        if (DB::table('payment_settings')->count() === 0) {
            DB::table('payment_settings')->insert([
                [
                    'method_name' => 'Midtrans (Payment Gateway)',
                    'code'        => 'midtrans',
                    'is_active'   => true,
                    'config_data' => json_encode(['server_key' => '', 'client_key' => '']),
                ],
                [
                    'method_name' => 'Transfer Manual (Kirim Foto Bukti)',
                    'code'        => 'manual',
                    'is_active'   => true,
                    'config_data' => json_encode([
                        'accounts' => [
                            ['bank_name' => 'BSI', 'account_number' => '1234567890', 'account_holder' => 'Masjid Annur'],
                        ],
                        'bank_name'      => 'BSI',
                        'account_number' => '1234567890',
                        'account_holder' => 'Masjid Annur',
                    ]),
                ],
                [
                    'method_name' => 'Paket 1 Bulan',
                    'code'        => 'package_monthly',
                    'is_active'   => true,
                    'config_data' => json_encode([
                        'price'    => 100000,
                        'features' => ['Akses penuh fitur standar', 'Masa aktif 30 hari', 'Pengelolaan sehari-hari'],
                    ]),
                ],
                [
                    'method_name' => 'Paket 1 Tahun',
                    'code'        => 'package_yearly',
                    'is_active'   => true,
                    'config_data' => json_encode([
                        'price'    => 1000000,
                        'features' => ['Masa aktif penuh 12 bulan', 'Harga per bulan paling hemat', 'Bebas repot tiap bulan'],
                    ]),
                ],
            ]);
        }

        // Pastikan baris QRIS selalu ada (tidak perlu migration terpisah)
        if (!DB::table('payment_settings')->where('code', 'qris')->exists()) {
            $row = [
                'method_name' => 'QRIS',
                'code'        => 'qris',
                'is_active'   => false,
                'config_data' => json_encode(['merchant_name' => '', 'qris_image' => '']),
            ];
            if (Schema::hasColumn('payment_settings', 'created_at')) $row['created_at'] = now();
            if (Schema::hasColumn('payment_settings', 'updated_at')) $row['updated_at'] = now();

            DB::table('payment_settings')->insert($row);
        }

        $settings = DB::table('payment_settings')->get();

        return view('auth.superadmin.pembayaran.index', compact('settings'));
    }

    public function update(Request $request, $id)
    {
        $setting = DB::table('payment_settings')->where('id', $id)->first();
        abort_if(!$setting, 404);

        $isActive = $request->has('is_active');

        // Mulai dari config lama agar data yang tidak diubah (mis. gambar QRIS) tidak hilang
        $configData = json_decode($setting->config_data, true) ?: [];

        if ($setting->code === 'midtrans') {
            $configData = [
                'server_key' => $request->input('server_key'),
                'client_key' => $request->input('client_key'),
            ];

        } elseif ($setting->code === 'manual') {
            $accounts = collect($request->input('accounts', []))
                ->map(fn ($a) => [
                    'bank_name'      => trim($a['bank_name'] ?? ''),
                    'account_number' => trim($a['account_number'] ?? ''),
                    'account_holder' => trim($a['account_holder'] ?? ''),
                ])
                ->filter(fn ($a) => $a['bank_name'] !== '' && $a['account_number'] !== '')
                ->values()
                ->all();

            $first = $accounts[0] ?? [];

            $configData = [
                'accounts'       => $accounts,
                // Field lama diisi dari rekening pertama agar kode lain yang masih membacanya tidak rusak
                'bank_name'      => $first['bank_name'] ?? '',
                'account_number' => $first['account_number'] ?? '',
                'account_holder' => $first['account_holder'] ?? '',
            ];

        } elseif ($setting->code === 'qris') {
            $request->validate([
                'merchant_name' => 'nullable|string|max:100',
                'qris_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $configData['merchant_name'] = trim((string) $request->input('merchant_name', ''));

            if ($request->hasFile('qris_image')) {
                if (!empty($configData['qris_image'])) {
                    Storage::disk('public')->delete($configData['qris_image']);
                }
                $configData['qris_image'] = $request->file('qris_image')->store('qris', 'public');
            }

            // QRIS tidak bisa diaktifkan kalau gambarnya belum ada
            if ($isActive && empty($configData['qris_image'])) {
                return redirect()->route('superadmin.pembayaran.index')
                    ->withErrors(['qris_image' => 'Unggah gambar QRIS dulu sebelum mengaktifkan metode ini.']);
            }

        } else {
            // Paket Berlangganan (Monthly/Yearly)
            $features = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->input('features', '')))));

            $configData = [
                'price'    => (int) $request->input('price', 0),
                'features' => array_values($features),
            ];
        }

        $updateData = [
            'is_active'   => $isActive,
            'config_data' => json_encode($configData),
            'updated_at'  => now(),
        ];

        if (in_array($setting->code, ['package_monthly', 'package_yearly'])) {
            $updateData['method_name'] = $request->input('method_name');
        }

        DB::table('payment_settings')->where('id', $id)->update($updateData);

        return redirect()->route('superadmin.pembayaran.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}