<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingController extends Controller
{
    public function index()
    {
        // Ambil data pengaturan pembayaran dari database, jika kosong seed default data termasuk paket
        $settings = DB::table('payment_settings')->get();
        if ($settings->isEmpty()) {
            DB::table('payment_settings')->insert([
                [
                    'method_name' => 'Midtrans (Payment Gateway)', 
                    'code' => 'midtrans', 
                    'is_active' => true, 
                    'config_data' => json_encode(['server_key' => '', 'client_key' => ''])
                ],
                [
                    'method_name' => 'Transfer Manual (Kirim Foto Bukti)', 
                    'code' => 'manual', 
                    'is_active' => true, 
                    'config_data' => json_encode(['bank_name' => 'BSI', 'account_number' => '1234567890', 'account_holder' => 'Masjid Annur'])
                ],
                [
                    'method_name' => 'Paket 1 Bulan', 
                    'code' => 'package_monthly', 
                    'is_active' => true, 
                    'config_data' => json_encode([
                        'price' => 100000, 
                        'features' => ["Akses penuh fitur standar", "Masa aktif 30 hari", "Pengelolaan sehari-hari"]
                    ])
                ],
                [
                    'method_name' => 'Paket 1 Tahun', 
                    'code' => 'package_yearly', 
                    'is_active' => true, 
                    'config_data' => json_encode([
                        'price' => 1000000, 
                        'features' => ["Masa aktif penuh 12 bulan", "Harga per bulan paling hemat", "Bebas repot tiap bulan"]
                    ])
                ]
            ]);
            $settings = DB::table('payment_settings')->get();
        }

        return view('auth.superadmin.pembayaran.index', compact('settings'));
    }

    public function update(Request $request, $id)
    {
        $setting = DB::table('payment_settings')->where('id', $id)->first();
        $isActive = $request->has('is_active') ? true : false;
        
        $configData = [];

        if ($setting->code === 'midtrans') {
            $configData = [
                'server_key' => $request->input('server_key'),
                'client_key' => $request->input('client_key'),
            ];
        } elseif ($setting->code === 'manual') {
            $configData = [
                'bank_name' => $request->input('bank_name'),
                'account_number' => $request->input('account_number'),
                'account_holder' => $request->input('account_holder'),
            ];
        } else {
            // Untuk Paket Berlangganan (Monthly/Yearly)
            // Memisahkan fitur berdasarkan baris baru (enter)
            $features = array_filter(explode("\n", str_replace("\r", "", $request->input('features', ''))));
            
            $configData = [
                'price' => (int) $request->input('price', 0),
                'features' => array_values($features),
            ];
        }

        $updateData = [
            'is_active' => $isActive,
            'config_data' => json_encode($configData),
            'updated_at' => now(),
        ];

        // Jika data paket, izinkan juga update nama metode/paket
        if (in_array($setting->code, ['package_monthly', 'package_yearly'])) {
            $updateData['method_name'] = $request->input('method_name');
        }

        DB::table('payment_settings')->where('id', $id)->update($updateData);

        return redirect()->route('superadmin.pembayaran.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}