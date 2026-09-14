<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Mosque;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\SuperAdmin\PenggunaController;
use App\Http\Controllers\SuperAdmin\BerandaSuperAdminController;
use App\Http\Controllers\SuperAdmin\PengaturanController;
use App\Http\Controllers\adminmasjid\LandingPageController;
use App\Http\Controllers\adminmasjid\ProgramController;
use App\Http\Controllers\adminmasjid\AcaraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicMosqueController;
use App\Http\Controllers\adminmasjid\JadwalSholatController;
use App\Http\Controllers\PaymentMasjid\PaymentController;
use App\Http\Controllers\SuperAdmin\MosqueManagementController;
use App\Http\Controllers\Donasi\DonasiController;
use App\Http\Controllers\adminmasjid\DonasiAdminController;
use App\Http\Controllers\adminmasjid\JamaahController;
use App\Http\Controllers\adminmasjid\DonationCategoryController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $userMosque = null;
    if (Auth::check()) {
        $userMosque = Mosque::where('user_id', Auth::id())->first();
    }
    return view('auth.halamanUtama', compact('userMosque'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Halaman Pembayaran & Perpanjangan Langganan Masjid
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/masjid/pembayaran', [PaymentController::class, 'index'])->name('masjid.payment');
    Route::post('/masjid/pembayaran/upload', [PaymentController::class, 'store'])->name('masjid.payment.upload');

    Route::delete('/masjid/batalkan', [MosqueController::class, 'cancelRegistration'])->name('masjid.cancel');

    // Perpanjangan Langganan
    Route::get('/masjid/perpanjangan', [MosqueController::class, 'createRenewal'])->name('masjid.perpanjangan.create');
    Route::post('/masjid/perpanjangan', [MosqueController::class, 'storeRenewal'])->name('masjid.perpanjangan.store');

    Route::delete('/masjid/unsubscribe', [MosqueController::class, 'unsubscribe'])->name('masjid.unsubscribe');
});

/*
|--------------------------------------------------------------------------
| Halaman Publik Masjid, Jadwal Sholat & Donasi Publik
|--------------------------------------------------------------------------
*/

Route::get('/masjid/{slug}', [PublicMosqueController::class, 'show'])->name('masjid.publik');

// Halaman Publik Donasi (Berdasarkan Slug Masjid)
Route::get('/masjid/{slug}/donasi', [PublicMosqueController::class, 'showDonasi'])->name('masjid.donasi.publik');

// Rute Publik Store Donasi (Ditambahkan)
Route::post('/masjid/{slug}/donasi', [DonasiController::class, 'store'])
    ->name('masjid.donasi.store');

Route::middleware(['auth'])->get('/masjidUser', function () {
    $mosque = \App\Models\Mosque::where('user_id', Auth::id())->first();
    if (!$mosque) {
        return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
    }
    return redirect()->route('masjid.publik', $mosque->slug);
})->name('masjid.user.redirect');

/*
|--------------------------------------------------------------------------
| Authentication (General User)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', function () {
    return view('auth.registerAkun');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Super Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/superadmin/login', function () {
    return view('auth.superadmin.halamanloginsuperadmin');
})->name('superadmin.login');

Route::post('/superadmin/login', [LoginController::class, 'login'])->name('superadmin.login.process');

/*
|--------------------------------------------------------------------------
| Halaman Waiting & Forgot Password
|--------------------------------------------------------------------------
*/
Route::get('/waiting', [MosqueController::class, 'waiting'])->name('waiting');

Route::get('/forgot-password', function () {
    return view('auth.login');
})->name('password.request');

/*
|--------------------------------------------------------------------------
| Admin Masjid (Harus Login & Status Aktif)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/daftar-masjid', [MosqueController::class, 'create'])->name('daftar.masjid');
    Route::post('/daftar-masjid', [MosqueController::class, 'store'])->name('daftar.masjid.store');
});

Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/dashboard', [MosqueController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/dashboard', [MosqueController::class, 'dashboard'])->name('admin.dashboard');

    // TAMBAHKAN RUTE FITUR TERKUNCI DI SINI
    Route::get('/admin/fitur-terkunci', function () {
        return view('auth.adminmasjid.fiturTerkunci');
    })->name('admin.fitur.terkunci');

    Route::get('/admin/landing-page', [LandingPageController::class, 'index'])->name('admin.landing-page');
    Route::put('/admin/landing-page', [LandingPageController::class, 'update'])->name('admin.landing-page.update');

    Route::get('/admin/profil-masjid', [MosqueController::class, 'editProfil'])->name('admin.profil-masjid');
    Route::put('/admin/profil-masjid', [MosqueController::class, 'updateProfil'])->name('admin.profil-masjid.update');

    Route::get('/admin/beranda', function () {
        return view('auth.adminmasjid.berandaAdmin');
    })->name('admin.beranda');

    Route::get('/admin/jadwal-sholat', [JadwalSholatController::class, 'index'])->name('admin.jadwal-sholat');
    Route::put('/admin/jadwal-sholat', [JadwalSholatController::class, 'update'])->name('admin.jadwal-sholat.update');

    // Program Unggulan
    Route::get('/admin/program', [ProgramController::class, 'index'])->name('admin.program');
    Route::put('/admin/program', [ProgramController::class, 'update'])->name('admin.program.update');

    // Acara / Kegiatan
    Route::get('/admin/acara', [AcaraController::class, 'index'])->name('admin.acara');
    Route::post('/admin/acara', [AcaraController::class, 'store'])->name('admin.acara.store');
    Route::put('/admin/acara/{acara}', [AcaraController::class, 'update'])->name('admin.acara.update');
    Route::delete('/admin/acara/{acara}', [AcaraController::class, 'destroy'])->name('admin.acara.destroy');

    // DONASI ADMIN (Otomatis beralih ke halaman pembayaran jika paket masih free)
    Route::get('/admin/donasi', [DonasiAdminController::class, 'index'])->name('admin.donasi');
    Route::put('/admin/donasi/pengaturan', [DonasiAdminController::class, 'updatePengaturan'])->name('admin.donasi.pengaturan');
    Route::post('/admin/donasi/galeri', [DonasiAdminController::class, 'storeGaleri'])->name('admin.donasi.galeri.store');
    Route::delete('/admin/donasi/galeri/{id}', [DonasiAdminController::class, 'destroyGaleri'])->name('admin.donasi.galeri.destroy');

    // Rute Kategori Donasi Admin (Ditambahkan)
    Route::post('/admin/donasi/kategori', [DonationCategoryController::class, 'store'])
        ->name('admin.donasi.kategori.store');

    Route::put('/admin/donasi/kategori/{kategori}', [DonationCategoryController::class, 'update'])
        ->name('admin.donasi.kategori.update');

    Route::patch('/admin/donasi/kategori/{kategori}/toggle', [DonationCategoryController::class, 'toggle'])
        ->name('admin.donasi.kategori.toggle');

    Route::delete('/admin/donasi/kategori/{kategori}', [DonationCategoryController::class, 'destroy'])
        ->name('admin.donasi.kategori.destroy');

    // ===== Data Jamaah (Dimasukkan ke dalam Group Middleware Admin) =====
    Route::get('/admin/jamaah', [JamaahController::class, 'index'])
        ->name('admin.jamaah');

    Route::post('/admin/jamaah', [JamaahController::class, 'store'])
        ->name('admin.jamaah.store');

    Route::get('/admin/jamaah/{jamaah}/edit', [JamaahController::class, 'edit'])
        ->name('admin.jamaah.edit');

    Route::put('/admin/jamaah/{jamaah}', [JamaahController::class, 'update'])
        ->name('admin.jamaah.update');

    Route::delete('/admin/jamaah/{jamaah}', [JamaahController::class, 'destroy'])
        ->name('admin.jamaah.destroy');
});

/*
|--------------------------------------------------------------------------
| Super Admin Panel (Harus Login & Punya Akses)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [BerandaSuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/verifikasi', [MosqueController::class, 'verifikasi'])->name('verifikasi');

    Route::put('/verifikasi/{id}/approve', [MosqueController::class, 'approveVerifikasi'])
        ->name('verifikasi.approve');

    Route::put('/verifikasi/{id}/reject', [MosqueController::class, 'rejectVerifikasi'])
        ->name('verifikasi.reject');

    // === TAMBAHAN: Hapus data masjid dari daftar verifikasi ===
   Route::delete('/verifikasi/{id}', [MosqueController::class, 'destroyVerifikasi'])
    ->name('verifikasi.destroy');

    Route::get('/manajemen-masjid', [MosqueController::class, 'manajemenMasjid'])->name('manajemen-masjid');
    Route::patch('/manajemen-masjid/{id}/update-status', [MosqueManagementController::class, 'updateStatus'])->name('manajemen-masjid.updateStatus');
    Route::get('/riwayat-pembayaran', [MosqueManagementController::class, 'paymentHistory'])->name('riwayat-pembayaran');

    Route::post('/manajemen-masjid', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'mosque_name'   => 'required|string|max:255',
            'arabic_name'   => 'nullable|string|max:255',
            'city'          => 'required|string|max:255',
            'province'      => 'required|string|max:255',
            'imam_name'     => 'required|string|max:255',
            'chairman_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:30',
            'status'        => 'required|in:approved,pending',
        ]);

        Mosque::create(array_merge($validated, ['user_id' => 1]));
        return redirect()->route('superadmin.manajemen-masjid')->with('success', 'Masjid berhasil ditambahkan.');
    })->name('manajemen-masjid.store');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::delete('/pengaturan/reset', [PengaturanController::class, 'reset'])->name('pengaturan.reset');
});