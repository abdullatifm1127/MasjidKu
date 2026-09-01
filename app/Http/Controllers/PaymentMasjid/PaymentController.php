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

        // Jika sudah lunas/approved, langsung ke dashboard
        if ($mosque->status === 'approved') {
            return redirect()->route('dashboard'); // <-- Ubah dari 'admin.dashboard' menjadi 'dashboard'
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
}