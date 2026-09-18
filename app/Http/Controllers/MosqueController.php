<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            // Jika pembayaran masjid masih pending, arahkan ke halaman pembayaran
            if ($existingMosque->payment_status === 'pending') {
                return redirect()->route('masjid.payment');
            }
            
            // Jika sudah approved atau pembayarannya sudah beres, arahkan ke dashboard
            if ($existingMosque->status === 'approved' || $existingMosque->payment_status === 'approved') {
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
            return redirect()->route('dashboard')
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

            // 1. UPDATE VALIDASI: Sesuaikan dengan value baru dari form (free, 100000_1, 1000000_12)
            'package_type' => 'required|in:free,100000_1,1000000_12',

            'description' => 'nullable|string',
            'agree' => 'required|accepted',
        ]);

        $status = 'approved'; 
        // 2. Jika paket bukan 'free' (artinya 100000_1 atau 1000000_12), status pembayaran jadi 'unpaid'
        $paymentStatus = ($validated['package_type'] === 'free') ? 'approved' : 'unpaid';

        $mosque = Mosque::create([
            'user_id' => Auth::id(),
            'status' => $status,
            'slug' => Str::slug($validated['mosque_name'] . '-' . $validated['city']),

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

            // 3. Fitur donasi aktif jika memilih paket berbayar
            'has_online_donation' => ($validated['package_type'] !== 'free'),
            'has_prayer_schedule' => $request->has('has_prayer_schedule'),

            'package_type' => $validated['package_type'],
            'payment_status' => $paymentStatus,

            'description' => $validated['description'] ?? null,
        ]);

        // Jika memilih Free, baru arahkan ke dashboard
        if ($validated['package_type'] === 'free') {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Pendaftaran akun Free berhasil! Selamat datang di Dashboard.');
        }

        // Jika memilih Paket 1 Bulan atau 1 Tahun, arahkan langsung ke halaman pembayaran Midtrans
        return redirect()
            ->route('masjid.payment')
            ->with('success', 'Pendaftaran berhasil! Silakan selesaikan pembayaran langganan Anda.');
    }
    

    public function dashboard()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid');
        }

        if ($mosque->payment_status === 'unpaid' && empty($mosque->payment_proof)) {
            return redirect()->route('masjid.payment');
        }

        // Catatan: pengecekan status 'pending' yang dulu mengarah ke halaman
        // waiting sudah dihapus. Middleware CheckMosqueStatus sudah menangani
        // pemblokiran akses dashboard untuk masjid yang statusnya belum
        // approved dan paketnya bukan free.

        $totalAcara = class_exists('\App\Models\Acara') ? \App\Models\Acara::where('mosque_id', $mosque->id)->count() : 0;
        $totalPengumuman = class_exists('\App\Models\Pengumuman') ? \App\Models\Pengumuman::where('mosque_id', $mosque->id)->count() : 0;
        
        $totalJamaah = 0; 
        $totalDonasiBulanIni = 0;

        return view('auth.adminmasjid.berandaAdmin', compact(
            'mosque', 
            'totalAcara', 
            'totalPengumuman', 
            'totalJamaah', 
            'totalDonasiBulanIni'
        ));
    }

    public function editProfil()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
        }

        return view('auth.adminmasjid.profilMasjid', compact('mosque'));
    }

    public function updateProfil(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

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

            'vision'            => 'nullable|string',
            'photo'             => 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'photo_secondary'   => 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048',

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

        $updateData = [
            'mosque_name'       => $validated['mosque_name'],
            'arabic_name'       => $validated['arabic_name'] ?? null,
            'slug'              => Str::slug($validated['mosque_name'] . '-' . $validated['city']),
            'tagline'           => $validated['tagline'] ?? null,
            'founded'           => $validated['founded'] ?? null,
            'capacity'          => $validated['capacity'] ?? null,
            'description'       => $validated['description'] ?? null,
            'about_vision'      => $validated['vision'] ?? null,

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
        ];

        if ($request->hasFile('photo')) {
            $updateData['about_photo'] = $request->file('photo')->store('mosque/about', 'public');
        }

        if ($request->hasFile('photo_secondary')) {
            $updateData['about_photo_secondary'] = $request->file('photo_secondary')->store('mosque/about', 'public');
        }

        $mosque->update($updateData);

        if ($mosque->user_id) {
            $user = \App\Models\User::find($mosque->user_id);
            if ($user) {
                $user->email = $validated['email'];
                $user->save();
            }
        }

        return redirect()->route('admin.profil-masjid')
            ->with('success', 'Profil masjid dan email akun berhasil diperbarui.');
    }

    public function verifikasi()
    {
        $pendaftaran = Mosque::with(['subscriptions' => function ($query) {
            $query->latest();
        }])->latest()->get();

        $totalPending = Mosque::where('status', 'pending')
            ->orWhere('payment_status', 'pending')
            ->count();

        $totalPendingSubscription = Subscription::where('status', 'pending')->count();
        $totalPending = $totalPending + $totalPendingSubscription;

        $totalApproved = Mosque::where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('payment_status')
                  ->orWhere('payment_status', '!=', 'pending');
            })->count();

        $totalRejected = Mosque::where('status', 'rejected')->count();
        $totalSemua    = $pendaftaran->count();

        return view('auth.superadmin.verifSuperAdmin', compact(
            'pendaftaran', 'totalPending', 'totalApproved', 'totalRejected', 'totalSemua'
        ));
    }

    public function approveVerifikasi($id)
    {
        $mosque = Mosque::findOrFail($id);

        if ($mosque->status === 'pending' || $mosque->payment_status === 'pending') {
            $mosque->status = 'approved';
            
            if ($mosque->package_type === 'free' || $mosque->payment_proof) {
                $mosque->payment_status = 'approved';
                $mosque->has_online_donation = true;
            }
            
            $mosque->save();
        }

        $pendingSubscription = $mosque->subscriptions()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($pendingSubscription) {
            $pendingSubscription->update(['status' => 'approved']);
            $mosque->update([
                'payment_status'      => 'approved',
                'has_online_donation' => true,
            ]);
        }

        return redirect()->route('superadmin.verifikasi')->with('success', 'Berhasil disetujui.');
    }

    public function rejectVerifikasi($id)
    {
        $mosque = Mosque::findOrFail($id);

        if ($mosque->status === 'pending') {
            $mosque->update(['status' => 'rejected']);
        }

        $pendingSubscription = $mosque->subscriptions()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($pendingSubscription) {
            $pendingSubscription->update(['status' => 'rejected']);
            $mosque->update([
                'payment_status'      => 'rejected',
                'has_online_donation' => false,
                'package_type'        => 'free',
            ]);
        }

        return redirect()->route('superadmin.verifikasi')->with('error', 'Ditolak.');
    }

    public function destroyVerifikasi($id)
    {
        $mosque = Mosque::findOrFail($id);
        $mosqueName = $mosque->mosque_name;

        if ($mosque->payment_proof && Storage::disk('public')->exists($mosque->payment_proof)) {
            Storage::disk('public')->delete($mosque->payment_proof);
        }

        foreach ($mosque->subscriptions as $sub) {
            if ($sub->payment_proof && Storage::disk('public')->exists($sub->payment_proof)) {
                Storage::disk('public')->delete($sub->payment_proof);
            }
        }

        $mosque->subscriptions()->delete();
        $mosque->delete();

        return redirect()->route('superadmin.verifikasi')
            ->with('success', "Data masjid \"{$mosqueName}\" berhasil dihapus.");
    }

    public function manajemenMasjid()
    {
        $masjids = Mosque::latest()->get();

        $totalSemua = $masjids->count();
        $totalAktif = Mosque::where('status', 'Aktif')->count();      
        $totalPending = Mosque::where('status', 'Pending')->count();   
        $totalNonaktif = Mosque::where('status', 'Nonaktif')->count(); 

        return view('auth.superadmin.manajemenMasjidSuperAdmin', compact(
            'masjids',
            'totalSemua',
            'totalAktif',
            'totalPending',
            'totalNonaktif'
        ));
    }

    public function storeRenewal(Request $request)
    {
        $request->validate([
            'package' => 'required',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,avif|max:2048',
        ]);

        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->back()->with('error', 'Data masjid tidak ditemukan.');
        }

        list($amount, $durationMonths) = explode('_', $request->package);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        Subscription::create([
            'mosque_id' => $mosque->id,
            'amount' => $amount,
            'payment_proof' => $path,
            'status' => 'pending',
        ]);

        $mosque->update([
            'status'             => 'approved',          
            'payment_status'     => 'pending',           
            'package_type'       => 'paid',
            'has_online_donation'=> true,         
            'payment_proof'      => $path 
        ]);

        return redirect()->route('dashboard')->with('status', 'Bukti pembayaran berhasil dikirim. Modul donasi akan aktif setelah diverifikasi Superadmin.');
    }

    public function createRenewal()
    {
        return view('paymentmasjid.perpanjangan');
    }

    public function cancelRegistration()
{
    // Cari masjid berdasarkan user yang login saja agar pasti ketemu
    $mosque = Mosque::where('user_id', Auth::id())->first();

    if ($mosque) {
        if ($mosque->payment_proof && Storage::disk('public')->exists($mosque->payment_proof)) {
            Storage::disk('public')->delete($mosque->payment_proof);
        }

        $mosque->delete();
    }

    return redirect()->route('daftar.masjid')
                     ->with('success', 'Pendaftaran berhasil dibatalkan. Silakan isi kembali formulir pendaftaran masjid.');
}

    public function unsubscribe(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->back()->with('error', 'Data masjid tidak ditemukan.');
        }

        $mosque->update([
            'package_type'   => 'free',
            'payment_status' => 'approved', 
            'status'         => 'approved'  
        ]);

        \App\Models\Subscription::where('mosque_id', $mosque->id)->update([
            'status' => 'approved' 
        ]);

        return redirect()->route('dashboard')->with('status', 'Anda telah berhenti berlangganan ke paket Free.');
    }
}