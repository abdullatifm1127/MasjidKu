<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\Subscription;
use App\Support\PackagePricing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MosqueController extends Controller
{
    /* =====================================================================
     |  HELPER LANGGANAN
     | ===================================================================== */

    /**
     * Hitung tanggal berakhir langganan dari kode paket (mis. "150000_1" -> +1 bulan).
     * Kalau masjid masih punya langganan aktif, masa aktif disambung dari tanggal berakhir lama.
     */
    private function hitungExpired(Mosque $mosque, ?string $packageType, bool $sambung = true): ?Carbon
    {
        $months = PackagePricing::months($packageType);
        if ($months <= 0) {
            return null;
        }

        $base = now();
        if ($sambung && $mosque->subscription_expires_at) {
            $lama = Carbon::parse($mosque->subscription_expires_at);
            if ($lama->isFuture()) {
                $base = $lama;
            }
        }

        return $base->copy()->addMonths($months);
    }

    /**
     * Ambil transaksi yang masih menunggu keputusan Super Admin.
     * Transfer manual (manual_pending) didahulukan, lalu yang terbaru.
     */
    private function pendingSubscription(Mosque $mosque): ?Subscription
    {
        return $mosque->subscriptions()
            ->where('status', 'pending')
            ->orderByRaw("CASE WHEN transaction_status = 'manual_pending' THEN 0 ELSE 1 END")
            ->latest()
            ->first();
    }

    /**
     * Setujui satu transaksi: status jadi approved/manual_approved, isi paid_at dan masa aktif.
     * Mengembalikan tanggal berakhir yang dipakai (untuk disimpan ke masjid).
     *
     * @param string|null $paket Kode paket yang dipakai menghitung masa aktif
     *                           (default: paket pada transaksi, lalu paket masjid).
     */
    private function approveSubscription(Mosque $mosque, Subscription $sub, ?string $paket = null): ?Carbon
    {
        $paket   = $paket ?: ($sub->package_type ?: $mosque->package_type);
        $expired = $sub->expired_date ?? $this->hitungExpired($mosque, $paket);

        $sub->update([
            'status'             => 'approved',
            'transaction_status' => 'manual_approved',
            'paid_at'            => $sub->paid_at ?? now(),
            'expired_date'       => $expired,
        ]);

        return $expired;
    }

    /** Tolak satu transaksi. Foto bukti sengaja tidak dihapus agar tetap terlihat di riwayat. */
    private function rejectSubscription(Subscription $sub): void
    {
        $sub->update([
            'status'             => 'rejected',
            'transaction_status' => 'manual_rejected',
        ]);
    }

    /* =====================================================================
     |  PENDAFTARAN MASJID
     | ===================================================================== */

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
            'tagline'     => 'nullable|string|max:255',
            'founded'     => 'required|integer|min:1000|max:' . date('Y'),
            'capacity'    => 'required|string|max:100',

            'address'     => 'required|string',
            'kelurahan'   => 'required|string|max:255',
            'kecamatan'   => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'city'        => 'required|string|max:255',
            'province'    => 'required|string|max:255',

            'phone'   => 'required|string|max:30',
            'email'   => 'required|email|max:255',
            'website' => 'nullable|string|max:255',

            'organization_name' => 'nullable|string|max:255',

            'imam_name'  => 'required|string|max:255',
            'imam_phone' => 'nullable|string|max:30',

            'chairman_name'  => 'required|string|max:255',
            'chairman_phone' => 'nullable|string|max:30',

            'secretary_name' => 'nullable|string|max:255',
            'treasurer_name' => 'nullable|string|max:255',

            'facilities'   => 'nullable|array',
            'facilities.*' => 'string',

            'programs'   => 'nullable|array',
            'programs.*' => 'string',

            'has_online_donation' => 'nullable',
            'has_prayer_schedule' => 'nullable',

            // Format kode paket: "free" atau "harga_bulan" (mis. 150000_1 / 1500000_12).
            // Tidak dikunci ke angka tertentu supaya ikut harga yang diatur Super Admin.
            'package_type' => ['required', 'regex:/^(free|\d+_(1|12))$/'],
            'description'  => 'nullable|string',
            'agree'        => 'required|accepted',
        ]);

        $isFree        = $validated['package_type'] === 'free';
        $paymentStatus = $isFree ? 'approved' : 'unpaid';

        Mosque::create([
            'user_id' => Auth::id(),
            'status'  => 'approved',
            'slug'    => Str::slug($validated['mosque_name'] . '-' . $validated['city']),

            'mosque_name' => $validated['mosque_name'],
            'arabic_name' => $validated['arabic_name'] ?? null,
            'tagline'     => $validated['tagline'] ?? null,
            'founded'     => $validated['founded'] ?? null,
            'capacity'    => $validated['capacity'] ?? null,

            'address'     => $validated['address'],
            'kelurahan'   => $validated['kelurahan'],
            'kecamatan'   => $validated['kecamatan'],
            'postal_code' => $validated['postal_code'] ?? null,
            'city'        => $validated['city'],
            'province'    => $validated['province'],

            'phone'   => $validated['phone'],
            'email'   => $validated['email'],
            'website' => $validated['website'] ?? null,

            'organization_name' => $validated['organization_name'] ?? null,

            'imam_name'  => $validated['imam_name'] ?? null,
            'imam_phone' => $validated['imam_phone'] ?? null,

            'chairman_name'  => $validated['chairman_name'] ?? null,
            'chairman_phone' => $validated['chairman_phone'] ?? null,

            'secretary_name' => $validated['secretary_name'] ?? null,
            'treasurer_name' => $validated['treasurer_name'] ?? null,

            'facilities' => $validated['facilities'] ?? [],
            'programs'   => $validated['programs'] ?? [],

            // Donasi online hanya aktif untuk paket free; paket berbayar aktif setelah pembayaran disetujui
            'has_online_donation' => $isFree,
            'has_prayer_schedule' => $request->has('has_prayer_schedule'),

            'package_type'   => $validated['package_type'],
            'payment_status' => $paymentStatus,

            'description' => $validated['description'] ?? null,
        ]);

        // Paket Free: langsung ke dashboard
        if ($isFree) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Pendaftaran akun Free berhasil! Selamat datang di Dashboard.');
        }

        // Paket berbayar: arahkan ke halaman pembayaran
        return redirect()
            ->route('masjid.payment')
            ->with('success', 'Pendaftaran berhasil! Silakan selesaikan pembayaran langganan Anda.');
    }

    /* =====================================================================
     |  DASHBOARD & PROFIL (ADMIN MASJID)
     | ===================================================================== */

    public function dashboard()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid');
        }

        if ($mosque->payment_status === 'unpaid' && empty($mosque->payment_proof)) {
            return redirect()->route('masjid.payment');
        }

        // Catatan: pemblokiran akses dashboard untuk masjid yang belum approved
        // dan paketnya bukan free ditangani middleware CheckMosqueStatus.

        $totalAcara      = class_exists('\App\Models\Acara') ? \App\Models\Acara::where('mosque_id', $mosque->id)->count() : 0;
        $totalPengumuman = class_exists('\App\Models\Pengumuman') ? \App\Models\Pengumuman::where('mosque_id', $mosque->id)->count() : 0;

        $totalJamaah         = 0;
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
            'mosque_name' => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'tagline'     => 'nullable|string|max:255',
            'founded'     => 'nullable|integer|min:1000|max:' . date('Y'),
            'capacity'    => 'nullable|string|max:100',
            'description' => 'nullable|string',

            'vision'          => 'nullable|string',
            'photo'           => 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'photo_secondary' => 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048',

            'organization_name' => 'nullable|string|max:255',
            'imam_name'         => 'required|string|max:255',
            'imam_phone'        => 'nullable|string|max:30',
            'chairman_name'     => 'required|string|max:255',
            'chairman_phone'    => 'nullable|string|max:30',
            'secretary_name'    => 'nullable|string|max:255',
            'treasurer_name'    => 'nullable|string|max:255',

            'address'     => 'required|string',
            'kelurahan'   => 'required|string|max:255',
            'kecamatan'   => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'city'        => 'required|string|max:255',
            'province'    => 'required|string|max:255',

            'phone'   => 'required|string|max:30',
            'email'   => 'required|email|max:255',
            'website' => 'nullable|string|max:255',

            'facilities'   => 'nullable|array',
            'facilities.*' => 'string',
            'programs'     => 'nullable|array',
            'programs.*'   => 'string',
        ]);

        $updateData = [
            'mosque_name'  => $validated['mosque_name'],
            'arabic_name'  => $validated['arabic_name'] ?? null,
            'slug'         => Str::slug($validated['mosque_name'] . '-' . $validated['city']),
            'tagline'      => $validated['tagline'] ?? null,
            'founded'      => $validated['founded'] ?? null,
            'capacity'     => $validated['capacity'] ?? null,
            'description'  => $validated['description'] ?? null,
            'about_vision' => $validated['vision'] ?? null,

            'organization_name' => $validated['organization_name'] ?? null,
            'imam_name'         => $validated['imam_name'],
            'imam_phone'        => $validated['imam_phone'] ?? null,
            'chairman_name'     => $validated['chairman_name'],
            'chairman_phone'    => $validated['chairman_phone'] ?? null,
            'secretary_name'    => $validated['secretary_name'] ?? null,
            'treasurer_name'    => $validated['treasurer_name'] ?? null,

            'address'     => $validated['address'],
            'kelurahan'   => $validated['kelurahan'],
            'kecamatan'   => $validated['kecamatan'],
            'postal_code' => $validated['postal_code'] ?? null,
            'city'        => $validated['city'],
            'province'    => $validated['province'],

            'phone'   => $validated['phone'],
            'email'   => $validated['email'],
            'website' => $validated['website'] ?? null,

            'facilities' => $validated['facilities'] ?? [],
            'programs'   => $validated['programs'] ?? [],

            'has_online_donation' => $request->has('has_online_donation'),
            'has_prayer_schedule' => $request->has('has_prayer_schedule'),
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

    /* =====================================================================
     |  SUPER ADMIN: VERIFIKASI
     | ===================================================================== */

    public function verifikasi()
    {
        $pendaftaran = Mosque::with(['subscriptions' => function ($query) {
            $query->latest();
        }])->latest()->get();

        $totalPending = Mosque::where('status', 'pending')
            ->orWhere('payment_status', 'pending')
            ->count();

        $totalPending += Subscription::where('status', 'pending')->count();

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
                $mosque->payment_status      = 'approved';
                $mosque->has_online_donation = true;
            }

            $mosque->save();
        }

        $sub = $this->pendingSubscription($mosque);

        if ($sub) {
            // Masa aktif dihitung dari paket transaksi ini (disambung bila masih ada langganan aktif)
            $expired = $this->approveSubscription($mosque, $sub);

            // forceFill agar subscription_expires_at tersimpan walau belum ada di $fillable model
            $mosque->forceFill([
                'payment_status'          => 'approved',
                'has_online_donation'     => true,
                'subscription_expires_at' => $expired,
            ])->save();
        }

        return redirect()->route('superadmin.verifikasi')->with('success', 'Berhasil disetujui.');
    }

    public function rejectVerifikasi($id)
    {
        $mosque = Mosque::findOrFail($id);

        $masihAktif = $mosque->subscription_expires_at
            && Carbon::parse($mosque->subscription_expires_at)->isFuture();

        $sub = $this->pendingSubscription($mosque);

        if ($sub) {
            $this->rejectSubscription($sub);
        }

        if ($masihAktif) {
            // PERPANJANGAN ditolak: langganan lama masih berjalan, jangan ganggu akses masjid.
            // Paket dikembalikan ke paket dari transaksi lunas terakhir, karena saat bukti diunggah
            // package_type masjid sudah ikut diganti ke paket perpanjangan.
            $terakhirLunas = $mosque->subscriptions()
                ->whereIn('status', ['paid', 'approved'])
                ->latest()
                ->first();

            $mosque->forceFill([
                'status'         => 'approved',
                'payment_status' => 'approved',
                'package_type'   => $terakhirLunas->package_type ?? $mosque->package_type,
            ])->save();

            return redirect()->route('superadmin.verifikasi')
                ->with('error', 'Perpanjangan ditolak. Langganan masjid yang sedang berjalan tidak berubah.');
        }

        // PENDAFTARAN AWAL ditolak: hapus bukti lama di tabel masjid (jika masih ada)
        // dan reset agar pembayaran bisa diulang. Bukti di riwayat transaksi dibiarkan.
        if ($mosque->payment_proof && Storage::disk('public')->exists($mosque->payment_proof)) {
            Storage::disk('public')->delete($mosque->payment_proof);
        }

        $mosque->update([
            'status'         => 'pending',
            'payment_status' => 'unpaid', // membuka kembali halaman pembayaran
            'payment_proof'  => null,
        ]);

        return redirect()->route('superadmin.verifikasi')
            ->with('error', 'Verifikasi ditolak. Masjid diminta melakukan pembayaran ulang.');
    }

    public function destroyVerifikasi($id)
    {
        $mosque     = Mosque::findOrFail($id);
        $mosqueName = $mosque->mosque_name;

        if ($mosque->payment_proof && Storage::disk('public')->exists($mosque->payment_proof)) {
            Storage::disk('public')->delete($mosque->payment_proof);
        }

        foreach ($mosque->subscriptions as $sub) {
            // Bukti transfer kini disimpan di kolom payment_proof dan/atau raw_notification.proof_of_payment
            $files = array_filter([
                $sub->payment_proof,
                data_get($sub->raw_notification, 'proof_of_payment'),
            ]);

            foreach (array_unique($files) as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        $mosque->subscriptions()->delete();
        $mosque->delete();

        return redirect()->route('superadmin.verifikasi')
            ->with('success', "Data masjid \"{$mosqueName}\" berhasil dihapus.");
    }

    public function manajemenMasjid()
    {
        $masjids    = Mosque::latest()->get();
        $totalSemua = $masjids->count();

        // Status di database berupa 'approved' / 'pending' / 'rejected' (huruf kecil)
        $totalAktif    = Mosque::whereIn(DB::raw('LOWER(status)'), ['approved', 'aktif'])->count();
        $totalPending  = Mosque::whereIn(DB::raw('LOWER(status)'), ['pending'])->count();
        $totalNonaktif = Mosque::whereIn(DB::raw('LOWER(status)'), ['rejected', 'nonaktif'])->count();

        return view('auth.superadmin.manajemenMasjidSuperAdmin', compact(
            'masjids',
            'totalSemua',
            'totalAktif',
            'totalPending',
            'totalNonaktif'
        ));
    }

    /**
     * Mengubah paket dan status donasi masjid secara manual oleh Super Admin.
     */
    public function updateSubscription(Request $request, $id)
    {
        $mosque = Mosque::findOrFail($id);

        $request->validate([
            // Kode paket dinamis ("harga_bulan") agar ikut harga yang diatur Super Admin
            'package_type'   => ['required', 'regex:/^(free|\d+_(1|12))$/'],
            'payment_status' => 'required|in:approved,unpaid,pending,failed',
        ]);

        $packageType   = $request->package_type;
        $paymentStatus = $request->payment_status;

        $isFree = $packageType === 'free';
        $aktif  = !$isFree && $paymentStatus === 'approved';

        $data = [
            'package_type'        => $packageType,
            'payment_status'      => $paymentStatus,
            // Donasi online aktif jika bukan paket free dan pembayaran approved
            'has_online_donation' => $aktif,
        ];

        $sub = $this->pendingSubscription($mosque);

        if ($isFree) {
            // Paket Free tidak punya masa aktif
            $data['subscription_expires_at'] = null;

        } elseif ($aktif && $sub) {
            // Ada transaksi yang menunggu (mis. transfer manual): setujui juga transaksinya
            // supaya paid_at, expired_date, dan status riwayat ikut terisi.
            $data['subscription_expires_at'] = $this->approveSubscription($mosque, $sub, $packageType);

        } elseif ($aktif) {
            // Tidak ada transaksi menunggu. Kalau paket sama dan masih aktif, tanggal lama
            // dipertahankan supaya menekan Simpan berulang tidak memperpanjang terus.
            $sudahSama = $mosque->package_type === $packageType
                && $mosque->payment_status === 'approved'
                && $mosque->subscription_expires_at
                && Carbon::parse($mosque->subscription_expires_at)->isFuture();

            if (!$sudahSama) {
                $data['subscription_expires_at'] = $this->hitungExpired($mosque, $packageType, false);
            }

        } elseif ($paymentStatus === 'failed' && $sub) {
            // Ditandai gagal: transaksi yang menunggu ikut ditolak
            $this->rejectSubscription($sub);
        }

        $mosque->forceFill($data)->save();

        return redirect()->route('superadmin.verifikasi')
            ->with('success', 'Status paket dan fitur donasi masjid berhasil diperbarui secara manual.');
    }

    /* =====================================================================
     |  PERPANJANGAN & BERHENTI BERLANGGANAN (ADMIN MASJID)
     | ===================================================================== */

    public function createRenewal()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        // Ambil data paket bulanan dan tahunan dari tabel payment_settings
        $packages = DB::table('payment_settings')
            ->whereIn('code', ['package_monthly', 'package_yearly'])
            ->get()
            ->keyBy('code');

        return view('paymentmasjid.perpanjangan', compact('mosque', 'packages'));
    }

    public function storeRenewal(Request $request)
    {
        $request->validate([
            'package' => 'required',
        ]);

        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->back()->with('error', 'Data masjid tidak ditemukan.');
        }

        // Paket Free (0_0): langsung diterapkan tanpa pembayaran
        if ($request->package === '0_0') {
            $mosque->forceFill([
                'package_type'            => 'free',
                'payment_status'          => 'approved',
                'has_online_donation'     => false,
                'order_id'                => null,
                'subscription_expires_at' => null,
            ])->save();

            return redirect()->route('dashboard')
                ->with('status', 'Paket Free berhasil dipilih. Fitur donasi online dinonaktifkan.');
        }

        // Paket berbayar (format "harga_bulan", mis. 150000_1 atau 1500000_12):
        // simpan ke session sementara agar data masjid aman jika dibatalkan.
        $orderId = 'RENEW-' . time() . '-' . rand(100, 999);

        session([
            'pending_renewal' => [
                'package'   => $request->package,
                'order_id'  => $orderId,
                'mosque_id' => $mosque->id,
            ],
        ]);

        return redirect()->route('masjid.payment');
    }

    public function cancelRegistration()
    {
        // Dibatalkan dari proses PERPANJANGAN langganan
        if (session()->has('pending_renewal')) {
            session()->forget('pending_renewal');

            return redirect()->route('dashboard')
                ->with('info', 'Perpanjangan langganan dibatalkan.');
        }

        // Dibatalkan dari proses pendaftaran awal
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

        // Kembali ke Free: donasi online dimatikan dan tanggal berakhir dikosongkan.
        // Riwayat transaksi (subscriptions) sengaja TIDAK diubah.
        $mosque->forceFill([
            'package_type'            => 'free',
            'payment_status'          => 'approved',
            'status'                  => 'approved',
            'has_online_donation'     => false,
            'subscription_expires_at' => null,
        ])->save();

        return redirect()->route('dashboard')
            ->with('status', 'Anda telah berhenti berlangganan ke paket Free.');
    }
}