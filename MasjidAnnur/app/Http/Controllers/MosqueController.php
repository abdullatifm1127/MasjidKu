<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MosqueController extends Controller
{
    /**
     * Menampilkan form pendaftaran masjid.
     */
    public function create()
    {
        // Cek apakah user sudah pernah mendaftarkan masjid sebelumnya
        $existingMosque = Mosque::where('user_id', Auth::id())->first();

        if ($existingMosque) {
            // Jika statusnya masih pending, arahkan ke halaman waiting
            if ($existingMosque->status === 'pending') {
                return redirect()->route('waiting');
            }
            // Jika sudah approved, arahkan ke dashboard
            if ($existingMosque->status === 'approved') {
                return redirect()->route('dashboard');
            }
        }

        return view('auth.adminmasjid.registerMasjid');
    }

    /**
     * Menyimpan data masjid.
     */
    public function store(Request $request)
    {
        // Cek pengaman ganda agar 1 akun tidak bisa daftar dua kali
        $existingMosque = Mosque::where('user_id', Auth::id())->first();
        if ($existingMosque) {
            return redirect()->route('waiting')
                ->with('error', 'Anda sudah mendaftarkan masjid sebelumnya.');
        }

        $validated = $request->validate([
            'mosque_name' => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'founded' => 'required|integer|min:1000|max:' . date('Y'),
            'capacity' => 'required|string|max:100',

            'address' => 'required|string',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',

            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'website' => 'nullable|string|max:255',

            'organization_name' => 'nullable|string|max:255',

            'imam_name' => 'required|string|max:255',
            'imam_phone' => 'nullable|string|max:30',

            'chairman_name' => 'required|string|max:255',
            'chairman_phone' => 'nullable|string|max:30',

            'secretary_name' => 'nullable|string|max:255',
            'treasurer_name' => 'nullable|string|max:255',

            'facilities' => 'nullable|array',
            'facilities.*' => 'string',

            'programs' => 'nullable|array',
            'programs.*' => 'string',

            'has_online_donation' => 'nullable',
            'has_prayer_schedule' => 'nullable',

            'description' => 'nullable|string',
            'agree' => 'required|accepted',
        ]);

        $mosque = Mosque::create([
            'user_id' => Auth::id(),
            'status' => 'pending', // Memastikan status awal ter-set pending

            'mosque_name' => $validated['mosque_name'],
            'arabic_name' => $validated['arabic_name'] ?? null,
            'tagline' => $validated['tagline'] ?? null,
            'founded' => $validated['founded'] ?? null,
            'capacity' => $validated['capacity'] ?? null,

            'address' => $validated['address'],
            'kelurahan' => $validated['kelurahan'],
            'kecamatan' => $validated['kecamatan'],
            'postal_code' => $validated['postal_code'] ?? null,
            'city' => $validated['city'],
            'province' => $validated['province'],

            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'website' => $validated['website'] ?? null,

            'organization_name' => $validated['organization_name'] ?? null,

            'imam_name' => $validated['imam_name'] ?? null,
            'imam_phone' => $validated['imam_phone'] ?? null,

            'chairman_name' => $validated['chairman_name'] ?? null,
            'chairman_phone' => $validated['chairman_phone'] ?? null,

            'secretary_name' => $validated['secretary_name'] ?? null,
            'treasurer_name' => $validated['treasurer_name'] ?? null,

            'facilities' => $validated['facilities'] ?? [],
            'programs' => $validated['programs'] ?? [],

            'has_online_donation' => $request->has('has_online_donation'),
            'has_prayer_schedule' => $request->has('has_prayer_schedule'),

            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('waiting')
            ->with('success', 'Masjid berhasil didaftarkan dan menunggu verifikasi admin.');
    }

    /**
     * Dashboard masjid.
     */
    public function dashboard()
    {
        // Mengambil data masjid milik user yang sedang login
        $mosque = Mosque::where('user_id', Auth::id())->first();

        // Jika belum mendaftarkan masjid, arahkan ke form pendaftaran
        if (!$mosque) {
            return redirect()->route('daftar.masjid');
        }

        // Jika statusnya masih pending, arahkan ke halaman waiting
        if ($mosque->status === 'pending') {
            return redirect()->route('waiting');
        }

        // Jika sudah approved, tampilkan halaman beranda admin
        return view('auth.adminmasjid.berandaAdmin', compact('mosque'));
    }

    /**
     * Halaman edit Profil Masjid (admin).
     */
    public function editProfil()
{
    // Mengambil data masjid berdasarkan user yang sedang login
    $mosque = Mosque::where('user_id', Auth::id())->first();

    if (!$mosque) {
        return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
    }

    return view('auth.adminmasjid.profilMasjid', compact('mosque'));
}

    /**
     * Simpan perubahan Profil Masjid (admin).
     */
    public function updateProfil(Request $request)
    {
        $mosque = Mosque::first();

        if (!$mosque) {
            return redirect()->route('admin.profil-masjid')
                ->with('error', 'Data masjid belum tersedia.');
        }

        $validated = $request->validate([
            'mosque_name'       => 'required|string|max:255',
            'arabic_name'       => 'nullable|string|max:255',
            'tagline'           => 'nullable|string|max:255',
            'founded'           => 'nullable|integer|min:1000|max:' . date('Y'),
            'capacity'          => 'nullable|string|max:100',
            'description'       => 'nullable|string',

            'organization_name' => 'nullable|string|max:255',
            'imam_name'         => 'required|string|max:255',
            'imam_phone'        => 'nullable|string|max:30',
            'chairman_name'     => 'required|string|max:255',
            'chairman_phone'    => 'nullable|string|max:30',
            'secretary_name'    => 'nullable|string|max:255',
            'treasurer_name'    => 'nullable|string|max:255',

            'address'           => 'required|string',
            'kelurahan'         => 'required|string|max:255',
            'kecamatan'         => 'required|string|max:255',
            'postal_code'       => 'nullable|string|max:20',
            'city'              => 'required|string|max:255',
            'province'          => 'required|string|max:255',

            'phone'             => 'required|string|max:30',
            'email'             => 'required|email|max:255',
            'website'           => 'nullable|string|max:255',

            'facilities'        => 'nullable|array',
            'facilities.*'      => 'string',
            'programs'          => 'nullable|array',
            'programs.*'        => 'string',
        ]);

        $mosque->update([
            'mosque_name'       => $validated['mosque_name'],
            'arabic_name'       => $validated['arabic_name'] ?? null,
            'tagline'           => $validated['tagline'] ?? null,
            'founded'           => $validated['founded'] ?? null,
            'capacity'          => $validated['capacity'] ?? null,
            'description'       => $validated['description'] ?? null,

            'organization_name' => $validated['organization_name'] ?? null,
            'imam_name'         => $validated['imam_name'],
            'imam_phone'        => $validated['imam_phone'] ?? null,
            'chairman_name'     => $validated['chairman_name'],
            'chairman_phone'    => $validated['chairman_phone'] ?? null,
            'secretary_name'    => $validated['secretary_name'] ?? null,
            'treasurer_name'    => $validated['treasurer_name'] ?? null,

            'address'           => $validated['address'],
            'kelurahan'         => $validated['kelurahan'],
            'kecamatan'         => $validated['kecamatan'],
            'postal_code'       => $validated['postal_code'] ?? null,
            'city'              => $validated['city'],
            'province'          => $validated['province'],

            'phone'             => $validated['phone'],
            'email'             => $validated['email'],
            'website'           => $validated['website'] ?? null,

            'facilities'        => $validated['facilities'] ?? [],
            'programs'          => $validated['programs'] ?? [],

            'has_online_donation'  => $request->has('has_online_donation'),
            'has_prayer_schedule'  => $request->has('has_prayer_schedule'),
        ]);

        return redirect()->route('admin.profil-masjid')
            ->with('success', 'Profil masjid berhasil disimpan.');
    }

    /**
     * Halaman Verifikasi Super Admin (Menampilkan semua pendaftaran & statistik).
     */
    public function verifikasi()
    {
        // Ambil semua data masjid dari database
        $pendaftaran = Mosque::latest()->get();

        // Hitung jumlah masing-masing status secara dinamis
        $totalPending = Mosque::where('status', 'pending')->count();
        $totalApproved = Mosque::where('status', 'approved')->count();
        $totalRejected = Mosque::where('status', 'rejected')->count();
        $totalSemua = $pendaftaran->count();

        return view('auth.superadmin.verifSuperAdmin', compact(
            'pendaftaran', 
            'totalPending', 
            'totalApproved', 
            'totalRejected', 
            'totalSemua'
        ));
    }

    public function manajemenMasjid()
{
    $masjids = Mosque::latest()->get();

    $totalSemua = $masjids->count();
    $totalAktif = Mosque::where('status', 'approved')->count();
    $totalPending = Mosque::where('status', 'pending')->count();
    $totalNonaktif = Mosque::where('status', 'rejected')->count();

    return view('auth.superadmin.manajemenMasjidSuperAdmin', compact(
        'masjids', 
        'totalSemua', 
        'totalAktif', 
        'totalPending', 
        'totalNonaktif'
    ));
}
    public function landingPage()
    {
        $user = Auth::user();
        $mosque = Mosque::where('user_id', $user->id)->first();

        return view('admin.landingPage', compact('mosque'));
    }
}