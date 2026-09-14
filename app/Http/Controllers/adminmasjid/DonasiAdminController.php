<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\DonasiGaleri;
use App\Models\DonationCategory;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Jika sudah berbayar, tampilkan halaman kelola donasi admin seperti biasa
        $items = DonasiGaleri::where('mosque_id', $mosque->id)
            ->latest('tanggal')
            ->get();

        return view('auth.adminmasjid.donasiAdmin', [
            'mosque'             => $mosque,
            'donationCategories' => $donationCategories,
            'items'              => $items,
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
        ]);

        $mosque->update([
            'zakat_fitrah_default' => $data['zakat_fitrah_default'],
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
            'tanggal'          => 'required|date',
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
}