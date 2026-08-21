<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Models\LandingPage; // Pastikan model LandingPage di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
{
    $mosque = Mosque::where('user_id', Auth::id())->first();

    if (!$mosque) {
        return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
    }

    // Cari berdasarkan mosque_id, jika belum ada buat baru dengan mengisi mosque_id
    $landingPage = LandingPage::firstOrNew(['mosque_id' => $mosque->id]);
    if (!$landingPage->exists) {
        $landingPage->save();
    }

    return view('admin.landingPage', compact('mosque', 'landingPage'));
}

public function update(Request $request)
{
    $mosque = Mosque::where('user_id', Auth::id())->first();

    if (!$mosque) {
        return redirect()->route('admin.landing-page')->with('error', 'Data masjid belum tersedia.');
    }

    // Ambil data landing page milik masjid ini, atau buat baru jika benar-benar belum ada
    $landingPage = LandingPage::firstOrNew(['mosque_id' => $mosque->id]);

    $validated = $request->validate([
        'hero_title'        => 'nullable|string|max:255',
        'hero_subtitle'     => 'nullable|string|max:255',
        'hero_desc'         => 'nullable|string',
        'btn_primary'       => 'nullable|string|max:100',
        'btn_primary_url'   => 'nullable|string|max:255',
        'btn_secondary'     => 'nullable|string|max:100',
        'btn_secondary_url' => 'nullable|string|max:255',
        'hero_image'        => 'nullable|image|max:2048',
        'hero_bg_color'     => 'nullable|string|max:20',
        'hero_text_color'   => 'nullable|string|max:20',
        'contact_address'   => 'nullable|string',
        'contact_phone'     => 'nullable|string|max:30',
        'contact_email'     => 'nullable|email|max:255',
        'contact_maps'      => 'nullable|url|max:255',
        'social_ig'         => 'nullable|url|max:255',
        'social_fb'         => 'nullable|url|max:255',
        'social_yt'         => 'nullable|url|max:255',
        'social_wa'         => 'nullable|string|max:30',
        'modul'             => 'nullable|array',
        'is_published'      => 'nullable|boolean',
    ]);

    if ($request->hasFile('hero_image')) {
        $validated['hero_image'] = $request->file('hero_image')->store('mosque/hero', 'public');
    } else {
        unset($validated['hero_image']);
    }

    $validated['active_modules'] = $validated['modul'] ?? [];
    unset($validated['modul']);

    $validated['is_published'] = $request->boolean('is_published');

    // Simpan atau update data ke tabel landing_pages dengan mosque_id yang pasti terisi
    $landingPage->fill($validated);
    $landingPage->mosque_id = $mosque->id;
    $landingPage->save();

    return redirect()->route('admin.landing-page')->with('success', 'Landing page berhasil diperbarui.');
}
}