<?php

namespace App\Http\Controllers\PaymentMasjid;

use Illuminate\Http\Request;
use App\Models\Mosque;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Menampilkan halaman form pembayaran (menampilkan tombol bayar Midtrans Snap)
    public function index()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid');
        }

        $pendingRenewal = session('pending_renewal');

        if (!$pendingRenewal) {
            $paymentStatus = strtolower($mosque->payment_status ?? '');
            if ($paymentStatus === 'free' || $paymentStatus === 'gratis') {
                return redirect()->route('dashboard');
            }

            if ($mosque->status === 'approved' && $mosque->payment_status === 'approved') {
                return redirect()->route('dashboard');
            }
            
            $packageType = $mosque->package_type ?? '100000_1';
        } else {
            $packageType = $pendingRenewal['package'];
        }

        // Tentukan teks dan nominal untuk ditampilkan di layar (1 Bulan = Rp 100.000)
        if ($packageType === '1000000_12') {
            $packageName = 'Langganan 1 Tahun';
            $amountFormatted = 'Rp 1.000.000';
        } else {
            $packageName = 'Langganan 1 Bulan';
            $amountFormatted = 'Rp 100.000';
        }

        return view('paymentmasjid.payment', compact('mosque', 'packageName', 'amountFormatted'));
    }

    public function createTransaction(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return response()->json(['error' => 'Data masjid tidak ditemukan.'], 404);
        }

        // Ambil data pilihan paket dari Session perpanjangan
        $pendingRenewal = session('pending_renewal');

        if ($pendingRenewal) {
            $orderId = $pendingRenewal['order_id'];
            $packageType = $pendingRenewal['package']; 
        } else {
            $orderId = 'MOSQUE-' . $mosque->id . '-' . time();
            $packageType = $mosque->package_type ?? '100000_1';
        }

        // AMBIL NOMINAL HARGA SECARA OTOMATIS (1 Bulan = 100000, 1 Tahun = 1000000)
        if (str_contains($packageType, '_')) {
            list($grossAmount, $durationMonths) = explode('_', $packageType);
            $grossAmount = (int) $grossAmount;
        } else {
            $grossAmount = ($packageType === '1000000_12') ? 1000000 : 100000;
        }

        // Simpan order_id dan package_type terbaru ke database
        $mosque->update([
            'order_id'       => $orderId,
            'package_type'   => $packageType,
            'payment_status' => 'pending'
        ]);

        // Validasi email agar tidak error di Midtrans
        $customerEmail = filter_var($mosque->email, FILTER_VALIDATE_EMAIL) 
            ? $mosque->email 
            : (Auth::user()->email ?? 'admin@simmasjid.test');

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount, 
            ],
            'customer_details' => [
                'first_name' => $mosque->mosque_name,
                'email'      => $customerEmail, // Menggunakan email yang sudah dijamin valid
                'phone'      => $mosque->phone ?? '08123456789',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Menangani Notifikasi / Webhook dari Server Midtrans (Otomatis Ubah Status Lunas)
    public function handleNotification(Request $request)
    {
        $payload = $request->all();
        $serverKey = config('services.midtrans.server_key');
        
        $hashedKey = hash("sha512", $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);

        if ($hashedKey !== $payload['signature_key']) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        $mosque = Mosque::where('order_id', $orderId)->first();
        if (!$mosque) {
            return response()->json(['message' => 'Mosque not found'], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $mosque->payment_status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $mosque->payment_status = 'approved';
                $mosque->status = 'approved';
                $mosque->has_online_donation = true;
            }
        } else if ($transactionStatus == 'settlement') {
            $mosque->payment_status = 'approved';
            $mosque->status = 'approved';
            $mosque->has_online_donation = true;
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $mosque->payment_status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $mosque->payment_status = 'pending';
        }

        $mosque->save();

        // Hapus sesi perpanjangan setelah notifikasi diproses
        session()->forget('pending_renewal');

        return response()->json(['message' => 'Notification successfully handled']);
    }

     public function cancelRegistration()
{
    // Cek dulu apakah ini pembatalan PERPANJANGAN (mosque sudah pernah approved)
    // sebelum session-nya dihapus, karena setelah forget() kita kehilangan info ini.
    $isRenewalCancel = session()->has('pending_renewal');

    // Bersihkan sesi perpanjangan
    session()->forget('pending_renewal');

    $mosque = Mosque::where('user_id', Auth::id())->first();

    if ($mosque) {
        if ($isRenewalCancel) {
            // Kasus PERPANJANGAN dibatalkan: akun sudah aktif sebelumnya,
            // jangan sentuh payment_status/package_type sama sekali.
            // Langganan lama tetap berjalan sampai masa aktifnya habis.
            return redirect()->route('dashboard')
                ->with('info', 'Perpanjangan langganan dibatalkan. Langganan Anda saat ini tetap berjalan.');
        }

        // Kasus REGISTRASI AWAL dibatalkan: masjid belum pernah approved/bayar.
        // Downgrade otomatis ke paket Free supaya tidak nyangkut di halaman pembayaran.
        $mosque->update([
            'package_type'   => 'free',
            'payment_status' => 'free',
        ]);

        return redirect()->route('dashboard')
            ->with('info', 'Pembayaran dibatalkan. Akun Anda menggunakan paket Free.');
    }

    return redirect()->route('dashboard')->with('info', 'Pembayaran dibatalkan.');
}
}