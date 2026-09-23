<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\DonasiGaleri;
use App\Models\DonationCategory;
use App\Models\Mosque;
use App\Models\MosqueBankAccount; // <-- BARU: Model rekening bank
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DonasiAdminController extends Controller
{
    protected function mosque(): Mosque
    {
        // Ambil data masjid pertama milik user yang sedang login
        $mosque = Mosque::where('user_id', Auth::id())->firstOrFail();

        // Paksa ubah status dan paket secara otomatis di memori
        // jika di database sebenarnya sudah berstatus approved/paid
        if (strtolower(trim($mosque->status)) === 'approved' || strtolower(trim($mosque->status)) === 'aktif') {
            $mosque->package_type = 'paid';
        }

        return $mosque;
    }

    /**
     * GET /admin/donasi
     */
    public function index()
    {
        $mosque = $this->mosque();

        // CEK APAKAH PAKET MASJID MASIH FREE
        if (($mosque->package_type ?? 'free') === 'free') {
            return view('paymentmasjid.payment', [
                'mosque' => $mosque
            ]);
        }

        // Ambil data kategori donasi dari database khusus untuk masjid ini
        $donationCategories = DonationCategory::where('mosque_id', $mosque->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        $items = DonasiGaleri::where('mosque_id', $mosque->id)
            ->latest('tanggal')
            ->get();

        // ===== BARU: daftar donasi masuk (butuh admin bisa melihat & memverifikasi) =====
        $categoryTitles = $donationCategories->pluck('title', 'key');

        $recentDonations = Donasi::where('mosque_id', $mosque->id)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($d) use ($categoryTitles) {
                $d->category_title = $categoryTitles[$d->jenis] ?? $d->jenis;
                return $d;
            });

        $summary = [
            'total_diterima' => (int) Donasi::where('mosque_id', $mosque->id)
                ->where('status', 'diterima')
                ->sum('nominal'),
            'total_menunggu' => (int) Donasi::where('mosque_id', $mosque->id)
                ->where('status', 'pending')
                ->sum('nominal'),
            'jumlah_donatur' => Donasi::where('mosque_id', $mosque->id)
                ->where('status', 'diterima')
                ->count(),
        ];
        // ================================================================================

        return view('auth.adminmasjid.donasiAdmin', [
            'mosque'             => $mosque,
            'donationCategories' => $donationCategories,
            'items'              => $items,
            'recentDonations'    => $recentDonations,
            'summary'            => $summary,
            'bankAccounts'       => $mosque->bankAccounts, // <-- BARU: Kirim data rekening bank ke view
        ]);
    }

    /**
     * PUT /admin/donasi/pengaturan
     */
    public function updateSettings(Request $request)
    {
        $mosque = $this->mosque();

        $data = $request->validate([
            'zakat_fitrah_default' => ['required', 'numeric', 'min:0'],
            'zakat_nisab' => ['required', 'numeric', 'min:0'],
        ]);

        $mosque->update([
            'zakat_fitrah_default' => $data['zakat_fitrah_default'],
            'zakat_nisab' => $data['zakat_nisab'],
        ]);

        return back()->with('success', 'Pengaturan zakat fitrah berhasil disimpan.');
    }

    /**
     * Alias untuk mengantisipasi pemanggilan rute updatePengaturan
     */
    public function updatePengaturan(Request $request)
    {
        return $this->updateSettings($request);
    }

    /**
     * POST /admin/donasi/galeri
     */
    public function storeGaleri(Request $request)
    {
        $mosque = $this->mosque();

        $data = $request->validate([
            'judul'            => 'required|string|max:150',
            'kategori'         => 'nullable|string|max:50',
            'deskripsi'        => 'nullable|string|max:1000',
            'tanggal'          => 'required|date|before_or_equal:today',
            'nominal_terpakai' => 'nullable|numeric|min:0',
            'foto'             => 'required|image|max:4096', // maks 4MB
        ]);

        $path = $request->file('foto')->store('donasi-galeri', 'public');

        DonasiGaleri::create([
            'mosque_id'        => $mosque->id,
            'judul'            => $data['judul'],
            'kategori'         => $data['kategori'] ?: null,
            'deskripsi'        => $data['deskripsi'] ?? null,
            'tanggal'          => $data['tanggal'],
            'nominal_terpakai' => $data['nominal_terpakai'] ?? null,
            'foto'             => $path,
        ]);

        return back()->with('success', 'Foto realisasi donasi berhasil ditambahkan.');
    }

    /**
     * DELETE /admin/donasi/galeri/{id}
     */
    public function destroyGaleri(int $id)
    {
        $mosque = $this->mosque();

        $item = DonasiGaleri::where('mosque_id', $mosque->id)->findOrFail($id);

        Storage::disk('public')->delete($item->foto);
        $item->delete();

        return back()->with('success', 'Foto dihapus.');
    }

    /**
     * PATCH /admin/donasi/{donasi}/status
     */
    public function updateStatus(Request $request, Donasi $donasi)
    {
        $mosque = $this->mosque();
        abort_unless($donasi->mosque_id === $mosque->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['diterima', 'ditolak', 'pending'])],
        ]);

        $donasi->update(['status' => $data['status']]);

        return back()->with('success', 'Status donasi ' . $donasi->no_referensi . ' diperbarui menjadi "' . $data['status'] . '".');
    }

    // ==========================================
    // BARU: MANAJEMEN REKENING BANK & QRIS
    // ==========================================

    /**
     * POST /admin/donasi/rekening
     */
    public function storeBankAccount(Request $request)
    {
        $mosque = $this->mosque();
        
        $data = $request->validate([
            'bank_name'      => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ]);

        MosqueBankAccount::create([
            'mosque_id'      => $mosque->id,
            'bank_name'      => $data['bank_name'],
            'account_number' => $data['account_number'],
            'account_holder' => $data['account_holder'],
            'sort_order'     => (MosqueBankAccount::where('mosque_id', $mosque->id)->max('sort_order') ?? -1) + 1,
            'is_active'      => true,
        ]);

        return back()->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    /**
     * PUT /admin/donasi/rekening/{id}
     */
    public function updateBankAccount(Request $request, int $id)
    {
        $mosque = $this->mosque();
        $rekening = MosqueBankAccount::where('mosque_id', $mosque->id)->findOrFail($id);
        
        $data = $request->validate([
            'bank_name'      => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ]);

        $rekening->update($data);

        return back()->with('success', 'Rekening bank berhasil diperbarui.');
    }

    /**
     * PATCH /admin/donasi/rekening/{id}/toggle
     */
    public function toggleBankAccount(int $id)
    {
        $mosque = $this->mosque();
        $rekening = MosqueBankAccount::where('mosque_id', $mosque->id)->findOrFail($id);
        
        $rekening->update(['is_active' => ! $rekening->is_active]);

        return back()->with('success', $rekening->is_active ? 'Rekening diaktifkan.' : 'Rekening dinonaktifkan.');
    }

    /**
     * DELETE /admin/donasi/rekening/{id}
     */
    public function destroyBankAccount(int $id)
    {
        $mosque = $this->mosque();
        $rekening = MosqueBankAccount::where('mosque_id', $mosque->id)->findOrFail($id);
        
        $rekening->delete();

        return back()->with('success', 'Rekening bank dihapus.');
    }

    /**
     * POST /admin/donasi/qris
     */
    public function updateQris(Request $request)
    {
        $mosque = $this->mosque();
        
        $request->validate([
            'qris_image' => ['required', 'image', 'max:2048'], // maks 2MB
        ]);

        // Hapus file QRIS lama supaya storage tidak menumpuk
        if ($mosque->qris_image) {
            Storage::disk('public')->delete($mosque->qris_image);
        }

        $path = $request->file('qris_image')->store('qris', 'public');
        $mosque->update(['qris_image' => $path]);

        return back()->with('success', 'Gambar QRIS berhasil diperbarui.');
    }

    /**
     * DELETE /admin/donasi/qris
     */
    public function destroyQris()
    {
        $mosque = $this->mosque();
        
        if ($mosque->qris_image) {
            Storage::disk('public')->delete($mosque->qris_image);
            $mosque->update(['qris_image' => null]);
        }

        return back()->with('success', 'Gambar QRIS dihapus.');
    }
}