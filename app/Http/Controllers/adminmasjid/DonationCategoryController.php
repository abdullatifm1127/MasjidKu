<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\DonationCategory;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DonationCategoryController extends Controller
{
    public function store(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'calc_type' => ['required', 'in:zakat,nominal'],
            'icon_key' => ['required', 'in:' . implode(',', array_keys(DonationCategory::iconOptions()))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseKey = Str::slug($data['title']);
        $key = $baseKey;
        $i = 1;
        while (DonationCategory::where('mosque_id', $mosque->id)->where('key', $key)->exists()) {
            $key = $baseKey . '-' . (++$i);
        }

        DonationCategory::create([
            'mosque_id' => $mosque->id,
            'key' => $key,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'calc_type' => $data['calc_type'],
            'icon_key' => $data['icon_key'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Jenis donasi berhasil ditambahkan.');
    }

    public function update(Request $request, DonationCategory $kategori)
    {
        $this->authorizeMosque($kategori);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'calc_type' => ['required', 'in:zakat,nominal'],
            'icon_key' => ['required', 'in:' . implode(',', array_keys(DonationCategory::iconOptions()))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // PENTING: `key` SENGAJA TIDAK diregenerasi di sini lagi.
        //
        // Sebelumnya, kalau admin mengubah judul kategori, `key` ikut berubah
        // (mis. "Infaq Jumat" -> infaq-jumat berubah jadi "Infaq Jumat Berkah" -> infaq-jumat-berkah).
        // Karena foto di galeri (DonasiGaleri::kategori) menyimpan `key` lama sebagai
        // referensi string, perubahan itu MEMUTUS tautan galeri -> kategori secara diam-diam:
        // foto lama yang sudah diunggah tiba-tiba tampil sebagai kategori "Umum".
        //
        // `key` sekarang bersifat stabil (dibuat sekali saat kategori pertama kali dibuat).
        // Admin tetap bebas mengubah judul tampilan (`title`) kapan pun tanpa efek samping ini.
        $kategori->update($data);

        return back()->with('success', 'Jenis donasi berhasil diperbarui.');
    }

    public function toggle(DonationCategory $kategori)
    {
        $this->authorizeMosque($kategori);

        $kategori->update(['is_active' => ! $kategori->is_active]);

        return back()->with('success', $kategori->is_active ? 'Jenis donasi diaktifkan.' : 'Jenis donasi dinonaktifkan.');
    }

    public function destroy(DonationCategory $kategori)
    {
        $this->authorizeMosque($kategori);

        $kategori->delete();

        return back()->with('success', 'Jenis donasi dihapus.');
    }

    private function authorizeMosque(DonationCategory $kategori): void
    {
        $mosque = Mosque::where('user_id', Auth::id())->firstOrFail();
        abort_unless($kategori->mosque_id === $mosque->id, 403);
    }
}