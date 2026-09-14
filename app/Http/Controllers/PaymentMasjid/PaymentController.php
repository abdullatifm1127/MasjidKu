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

        // Jika belum daftar masjid, lempar ke form pendaftaran
        if (!$mosque) {
            return redirect()->route('daftar.masjid');
        }

        // 1. TAMBAHAN CEK PAKET FREE: 
        // Jika status pembayaran atau tipe/paketnya adalah free/gratis, langsung arahkan ke dashboard utama
        $paymentStatus = strtolower($mosque->payment_status ?? '');
        if ($paymentStatus === 'free' || $paymentStatus === 'gratis') {
            return redirect()->route('dashboard');
        }

        // 2. Jika sudah disetujui (approved) dan lunas, baru boleh masuk dashboard utama
        if ($mosque->status === 'approved' && $mosque->payment_status === 'paid') {
            return redirect()->route('dashboard');
        }

        // 3. Jika status pembayaran sudah 'pending' (sudah upload bukti tapi belum diverifikasi admin),
        // arahkan ke halaman waiting agar mereka tahu datanya sedang dicek.
        if ($mosque->payment_status === 'pending' && $mosque->payment_proof) {
            return redirect()->route('waiting');
        }

        // 4. Selain kondisi di atas (artinya baru daftar / belum upload bukti / batal bayar),
        // tampilkan kembali halaman pembayaran beserta datanya.
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

            // Update status masjid menjadi pending & simpan path foto
            $mosque->update([
                'payment_proof' => $path,
                'payment_status' => 'pending', 
            ]);

            return redirect()->route('waiting')->with('success', 'Bukti pembayaran berhasil dikirim!');

        } catch (\Exception $e) {
            // Jika terjadi error sistem di luar validasi (misal folder storage belum di-link)
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