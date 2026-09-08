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

        // Jika judul berubah, update juga key/slug-nya secara otomatis
        if ($kategori->title !== $data['title']) {
            $baseKey = Str::slug($data['title']);
            $key = $baseKey;
            $i = 1;
            while (DonationCategory::where('mosque_id', $kategori->mosque_id)
                ->where('key', $key)
                ->where('id', '!=', $kategori->id)
                ->exists()) {
                $key = $baseKey . '-' . (++$i);
            }
            $data['key'] = $key;
        }

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