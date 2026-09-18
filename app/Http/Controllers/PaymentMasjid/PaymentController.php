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

        $paymentStatus = strtolower($mosque->payment_status ?? '');
        if ($paymentStatus === 'free' || $paymentStatus === 'gratis') {
            return redirect()->route('dashboard');
        }

        // Jika sudah lunas / approved
        if ($mosque->status === 'approved' && $mosque->payment_status === 'approved') {
            return redirect()->route('dashboard');
        }

        return view('paymentmasjid.payment', compact('mosque'));
    }

    public function createTransaction(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return response()->json(['error' => 'Data masjid tidak ditemukan.'], 404);
        }

        $orderId = 'MOSQUE-' . $mosque->id . '-' . time();
        
        // Tentukan nominal berdasarkan package_type yang dipilih saat registrasi
        $grossAmount = 100000; // Default 1 bulan
        if ($mosque->package_type === '1000000_12') {
            $grossAmount = 1000000; // 1 Tahun
        }

        // Simpan order_id ke database
        $mosque->update([
            'order_id' => $orderId,
            'payment_status' => 'pending'
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $mosque->mosque_name,
                'email' => $mosque->email,
                'phone' => $mosque->phone,
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

        return response()->json(['message' => 'Notification successfully handled']);
    }

    /**
     * Membatalkan pendaftaran & menghapus data masjid agar user bisa daftar ulang.
     */
    public function cancelRegistration()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if ($mosque) {
            if ($mosque->payment_proof && Storage::disk('public')->exists($mosque->payment_proof)) {
                Storage::disk('public')->delete($mosque->payment_proof);
            }

            $mosque->delete();
        }

        return redirect()->route('daftar.masjid')->with('info', 'Pendaftaran dibatalkan. Silakan isi kembali form pendaftaran.');
    }
}