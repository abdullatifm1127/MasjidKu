<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\DonasiGaleri;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonasiAdminController extends Controller
{
    /**
     * Kategori donasi, sama persis dengan yang dipakai DonasiController (publik).
     * Kalau nanti dipindah ke database, cukup ganti isi method ini di kedua tempat,
     * atau taruh di satu Trait/Helper bersama.
     */
    protected function categories(): array
    {
        return [
            'zakat'       => ['title' => 'Zakat'],
            'infaq'       => ['title' => 'Infaq'],
            'sedekah'     => ['title' => 'Sedekah'],
            'pembangunan' => ['title' => 'Pembangunan Masjid'],
            'yatim'       => ['title' => 'Santunan Yatim & Dhuafa'],
            'bencana'     => ['title' => 'Bantuan Bencana'],
            'wakaf'       => ['title' => 'Wakaf'],
            'qurban'      => ['title' => 'Qurban'],
            'lainnya'     => ['title' => 'Donasi Bebas'],
        ];
    }

    protected function mosque(): Mosque
    {
        return Mosque::where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * GET /admin/donasi
     */
    public function index()
    {
        $mosque = $this->mosque();

        // CEK APAKAH PAKET MASJID MASIH FREE
        if (($mosque->package_type ?? 'free') === 'free') {
            // Jika free, langsung arahkan ke halaman pembayaran yang ada di folder paymentmasjid
            return view('paymentmasjid.payment', [
                'mosque' => $mosque
            ]);
        }

        // Jika sudah berbayar, tampilkan halaman kelola donasi admin seperti biasa
        $items = DonasiGaleri::where('mosque_id', $mosque->id)
            ->latest('tanggal')
            ->get();

        return view('auth.adminmasjid.donasiAdmin', [
            'mosque'     => $mosque,
            'categories' => $this->categories(),
            'items'      => $items,
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