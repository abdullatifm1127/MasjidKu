<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Models\Mosque; // 1. Tambahkan import model Mosque
use Illuminate\Support\Facades\Auth; // 2. Tambahkan import Auth

class LandingPageController extends Controller
{
    public function index()
    {
        // 3. Ambil data landing page
        $landingPage = LandingPage::first();
        
        // 4. Ambil data masjid milik user yang login untuk slug preview
        $mosque = Mosque::where('user_id', Auth::id())->first();

        // 5. Kirim KEDUANYA ke view
        return view('admin.landingPage', compact('landingPage', 'mosque'));
    }

    public function update(Request $request)
    {
        // 1. Ambil data masjid milik admin yang sedang login
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->back()->with('error', 'Data masjid tidak ditemukan.');
        }

        // 2. Cari landing page berdasarkan mosque_id masjid tersebut, jika belum ada maka buat baru
        $landingPage = LandingPage::firstOrNew(['mosque_id' => $mosque->id]);

        // 3. Masukkan data dari input request
        // Pastikan nama input di form HTML sesuai dengan nama kolom di bawah ini
        $landingPage->hero_title = $request->input('hero_title');
        $landingPage->arabic_name = $request->input('arabic_name');
        $landingPage->tagline = $request->input('tagline');
        // Tambahkan kolom lain sesuai input form Anda...

        // 4. Simpan ke database
        $landingPage->save();

        return redirect()->back()->with('success', 'Perubahan berhasil disimpan!');
    }
}