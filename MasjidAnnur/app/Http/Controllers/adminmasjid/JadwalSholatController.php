<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mosque;

class JadwalSholatController extends Controller
{
    public function index()
    {
        // Ambil data masjid milik admin yang sedang login
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
        }

        return view('auth.adminmasjid.jadwalSholat', compact('mosque'));
    }

    // Jadwal shalat diambil dari API publik berdasarkan koordinat/kota masjid.
    // Method update ini disiapkan untuk menyimpan preferensi kota/koordinat jika admin ingin override.
    public function update(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('admin.jadwal-sholat')->with('error', 'Data masjid belum tersedia.');
        }

        $validated = $request->validate([
            'city'     => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
        ]);

        // Simpan override kota untuk kalkulasi jadwal shalat
        $mosque->update(array_filter($validated));

        return redirect()->route('admin.jadwal-sholat')->with('success', 'Pengaturan lokasi jadwal shalat berhasil disimpan.');
    }
}