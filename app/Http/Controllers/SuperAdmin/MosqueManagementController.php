<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mosque;
use App\Models\Subscription;

class MosqueManagementController extends Controller
{
    /**
     * Menampilkan halaman manajemen masjid.
     */
    public function index()
    {
        // PERBAIKAN: Gunakan ->get() agar with('subscriptions') berjalan dengan benar
        $mosques = Mosque::with('subscriptions')->get(); 

        return view('auth.superadmin.manajemenMasjid', compact('mosques'));
    }

    /**
     * Memperbarui status masjid melalui AJAX / Dropdown.
     */
    public function updateStatus(Request $request, $id)
    {
        // Cari data masjid berdasarkan ID atau 404 jika tidak ditemukan
        $mosque = Mosque::findOrFail($id);
        
        // Validasi input status yang diperbolehkan
        $request->validate([
            'status' => 'required|in:aktif,pending,nonaktif,approved,rejected'
        ]);

        $newStatus = $request->status;

        // Update status masjid
        $mosque->status = $newStatus;
        
        // Jika status diubah menjadi aktif/approved, update juga status pembayarannya
        if ($newStatus === 'aktif' || $newStatus === 'approved') {
            $mosque->payment_status = 'paid';
        }
        $mosque->save();

        // === TAMBAHAN INI ===
        // Sinkronkan status pada tabel subscriptions miliknya yang masih pending
        if ($newStatus === 'aktif' || $newStatus === 'approved') {
            $mosque->subscriptions()->where('status', 'pending')->update([
                'status' => 'approved' // atau 'paid' / 'disetujui' sesuai nilai di database Anda
            ]);
        }
        // ====================

        // Kembalikan respons dalam bentuk JSON agar dibaca oleh JavaScript
        return response()->json([
            'success' => true,
            'message' => 'Status masjid dan riwayat transaksi berhasil diperbarui!',
            'new_status' => $mosque->status
        ]);
    }

    public function paymentHistory()
    {
        // Mengambil seluruh data riwayat langganan diurutkan dari yang terbaru, beserta data masjidnya
        $subscriptions = Subscription::with('mosque')->latest()->paginate(10);

        return view('paymentmasjid.riwayat_pembayaran', compact('subscriptions'));
    }
}