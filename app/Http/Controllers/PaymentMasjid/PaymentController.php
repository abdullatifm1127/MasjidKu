<?php

namespace App\Http\Controllers\PaymentMasjid;

use Illuminate\Http\Request;
use App\Models\Mosque;
use App\Models\Subscription;
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

        // Bersihkan sesi perpanjangan yang transaksinya sudah lunas
        // (webhook Midtrans tidak punya session user, jadi dibersihkan di sini)
        if ($pendingRenewal) {
            $sudahLunas = Subscription::where('order_id', $pendingRenewal['order_id'])
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->exists();

            if ($sudahLunas) {
                session()->forget('pending_renewal');
                return redirect()->route('dashboard')
                    ->with('success', 'Pembayaran langganan berhasil.');
            }
        }

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
                'email'      => $customerEmail,
                'phone'      => $mosque->phone ?? '08123456789',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // RIWAYAT: catat transaksi (status awal pending) setelah Snap token berhasil dibuat
            Subscription::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'mosque_id'          => $mosque->id,
                    'package_type'       => $packageType,
                    'amount'             => $grossAmount,
                    'status'             => 'pending',
                    'transaction_status' => 'pending',
                ]
            );

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

        $hashedKey = hash("sha512", ($payload['order_id'] ?? '') . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . $serverKey);

        if (!hash_equals($hashedKey, (string) ($payload['signature_key'] ?? ''))) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        // Cari lewat riwayat dulu, fallback ke kolom order_id di mosques (transaksi lama)
        $subscription = Subscription::where('order_id', $orderId)->first();
        $mosque = $subscription
            ? Mosque::find($subscription->mosque_id)
            : Mosque::where('order_id', $orderId)->first();

        if (!$mosque) {
            return response()->json(['message' => 'Mosque not found'], 404);
        }

        $isPaid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && $fraudStatus === 'accept');

        // Status sederhana untuk kolom `status`
        if ($isPaid) {
            $statusSederhana = 'paid';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $statusSederhana = 'rejected';
        } else {
            $statusSederhana = 'pending';
        }

        $packageType = $subscription->package_type ?? $mosque->package_type ?? '100000_1';

        // Masa aktif: dihitung sekali saat pertama kali lunas (aman jika Midtrans mengirim settlement berulang).
        // Kalau masih ada langganan aktif, masa aktif baru disambung dari tanggal berakhir yang lama.
        $expiredDate = $subscription->expired_date ?? null;
        if ($isPaid && !$expiredDate) {
            $months = (int) (explode('_', $packageType)[1] ?? 1) ?: 1;

            $lastActive = Subscription::where('mosque_id', $mosque->id)
                ->where('order_id', '!=', $orderId)
                ->whereIn('status', ['paid', 'approved'])
                ->max('expired_date');

            $base = ($lastActive && \Carbon\Carbon::parse($lastActive)->isFuture())
                ? \Carbon\Carbon::parse($lastActive)
                : now();

            $expiredDate = $base->copy()->addMonths($months);
        }

        // RIWAYAT: simpan hasil notifikasi ke tabel subscriptions
        Subscription::updateOrCreate(
            ['order_id' => $orderId],
            [
                'mosque_id'          => $mosque->id,
                'package_type'       => $packageType,
                'amount'             => (int) round((float) ($payload['gross_amount'] ?? 0)),
                'status'             => $statusSederhana,
                'transaction_status' => $transactionStatus,
                'payment_type'       => $payload['payment_type'] ?? null,
                'transaction_id'     => $payload['transaction_id'] ?? null,
                'fraud_status'       => $fraudStatus,
                'paid_at'            => $isPaid ? ($subscription->paid_at ?? now()) : ($subscription->paid_at ?? null),
                'expired_date'       => $expiredDate,
                'raw_notification'   => $payload,
            ]
        );

        // Status masjid hanya diubah jika notifikasi ini untuk transaksi TERBARU.
        // Mencegah notifikasi "expire" dari order lama menimpa status order baru yang sudah lunas.
        if ($mosque->order_id === $orderId) {
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
        }

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
                return redirect()->route('dashboard')
                    ->with('info', 'Perpanjangan langganan dibatalkan. Langganan Anda saat ini tetap berjalan.');
            }

            // Kasus REGISTRASI AWAL dibatalkan: downgrade otomatis ke paket Free.
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