<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mosque;

class BerandaSuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalMasjid = Mosque::count();
        $masjidAktif = Mosque::where('status', 'aktif')->count();
        $masjidPending = Mosque::where('status', 'pending')->count();
        
        $totalDonasi = Mosque::sum('total_donasi'); // Sesuaikan dengan kolom donasi di database Anda

        // Ambil data masjid beserta perhitungan persentase progress-nya
        $mosques = Mosque::latest()->get()->map(function ($m) {
            $target = $m->target_donasi > 0 ? $m->target_donasi : 1;
            $terkumpul = $m->total_donasi ?? 0;
            
            // Hitung persentase dan batasi maksimal 100%
            $m->persentase = min(round(($terkumpul / $target) * 100), 100);
            return $m;
        });

        $donasiTerkini = []; // Ubah dengan query tabel donasi jika sudah ada

        return view('auth.superadmin.berandaSuperAdmin', compact(
            'totalMasjid', 
            'masjidAktif', 
            'masjidPending', 
            'totalDonasi', 
            'mosques', 
            'donasiTerkini'
        ));
    }
}