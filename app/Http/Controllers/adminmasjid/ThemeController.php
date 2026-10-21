<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mosque;

class ThemeController extends Controller
{
    /**
     * Menampilkan halaman pilihan tema admin masjid.
     */
    public function index()
    {
        // Mengambil data masjid berdasarkan user yang sedang login
        $mosque = Mosque::where('user_id', Auth::id())->first();

        // Mengirim data ke view temaadmin.blade.php
        return view('auth.adminmasjid.temaadmin', compact('mosque'));
    }

    /**
     * Menampilkan tema web masjid (Emerald, Terracotta, Corporate)
     */
    public function showTheme($slug, $theme)
    {
        $allowedThemes = ['emerald', 'terracotta', 'corporate'];
        if (!in_array($theme, $allowedThemes)) {
            $theme = 'emerald';
        }

        // Ambil data masjid berdasarkan slug
        $mosque = Mosque::where('slug', $slug)->firstOrFail();

        // Mengarah ke folder resources/views/themes/{theme}.blade.php
        return view('themes.' . $theme, compact('mosque'));
    }

    /**
     * (Opsional) Jika ingin menyimpan preferensi tema ke database di masa mendatang,
     * Anda bisa menambahkan method update atau menyimpan ke kolom khusus pada tabel mosques.
     */
    public function update(Request $request)
    {
        $request->validate([
    'theme' => 'required|string|in:default,emerald,terracotta,corporate',
]);

        $mosque = Mosque::where('user_id', Auth::id())->first();

        if ($mosque) {
            $mosque->theme = $request->theme;
            $mosque->save();

            return response()->json([
                'success' => true,
                'message' => 'Tema berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Masjid tidak ditemukan.'
        ], 404);
    }
}