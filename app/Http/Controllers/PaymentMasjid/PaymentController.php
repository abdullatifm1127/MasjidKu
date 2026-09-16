<?php

namespace App\Http\Controllers\PaymentMasjid;

use Illuminate\Http\Request;
use App\Models\Mosque;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    // Menampilkan halaman form pembayaran
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
        if ($mosque->status === 'approved' && $mosque->payment_status === 'paid') {
            return redirect()->route('dashboard');
        }

        // Jika sudah kirim bukti tapi status masih pending, arahkan ke dashboard saja
        if ($mosque->payment_status === 'pending' && $mosque->payment_proof) {
            return redirect()->route('dashboard');
        }

        return view('paymentmasjid.payment', compact('mosque'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi file (gunakan huruf kecil pada ekstensi)
            $request->validate([
                'payment_proof' => 'required|mimes:jpeg,png,jpg,avif|max:2048',
            ]);

            $mosque = Mosque::where('user_id', Auth::id())->first();

            if (!$mosque) {
                return redirect()->route('daftar.masjid');
            }

            // Simpan file bukti transfer ke storage/app/public/payment-proofs
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Update status masjid menjadi approved & payment_status jadi pending (menunggu cek admin di latar belakang)
            $mosque->update([
                'payment_proof' => $path,
                'payment_status' => 'pending', 
                'status'         => 'approved', // <--- Pastikan status tetap approved agar bisa akses dashboard
            ]);

            // UBAH REDIRECT KE DASHBOARD, BUKAN KE WAITING
            return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi Super Admin.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan pendaftaran & menghapus data masjid agar user bisa daftar ulang.
     */
    public function cancelRegistration()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if ($mosque) {
            // Hapus file bukti pembayaran jika sempat ter-upload
            if ($mosque->payment_proof && Storage::disk('public')->exists($mosque->payment_proof)) {
                Storage::disk('public')->delete($mosque->payment_proof);
            }

            // Hapus record masjid agar user bisa mendaftar dari awal
            $mosque->delete();
        }

        return redirect()->route('daftar.masjid')->with('info', 'Pendaftaran dibatalkan. Silakan isi kembali form pendaftaran.');
    }
}