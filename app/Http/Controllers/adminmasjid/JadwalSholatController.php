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

    // Tambahkan method update jika nanti ada fitur simpan/ubah jadwal shalat
    public function update(Request $request)
    {
        // Logika update jadwal shalat di sini...
    }
}