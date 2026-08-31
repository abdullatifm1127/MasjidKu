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
use App\Http\Controllers\adminmasjid\PengumumanController;
use App\Http\Controllers\adminmasjid\DonasiController;
use App\Http\Controllers\adminmasjid\JamaahController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicMosqueController;

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
| Halaman Publik Masjid & Jadwal Sholat (Menggunakan Controller)
|--------------------------------------------------------------------------
*/
Route::get('/masjid/{slug}', [PublicMosqueController::class, 'show'])->name('masjid.show');

// Alias tambahan agar jika ada bagian lain yang memanggil route('masjid.publik') tetap mengarah ke controller yang sama
Route::get('/masjid/{slug}', [PublicMosqueController::class, 'show'])->name('masjid.publik');

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

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/register', function () {
    return view('auth.registerAkun');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Super Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/superadmin/login', function () {
    return view('auth.superadmin.halamanloginsuperadmin');
})->name('superadmin.login');

Route::post('/superadmin/login', [LoginController::class, 'login'])
    ->name('superadmin.login.process');

/*
|--------------------------------------------------------------------------
| Halaman Waiting & Forgot Password
|--------------------------------------------------------------------------
*/
Route::get('/waiting', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('mosque.waiting');
})->name('waiting');

Route::get('/forgot-password', function () {
    return view('auth.login');
})->name('password.request');

/*
|--------------------------------------------------------------------------
| Admin Masjid (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/daftar-masjid', [MosqueController::class, 'create'])
        ->name('daftar.masjid');

    Route::post('/daftar-masjid', [MosqueController::class, 'store'])
        ->name('daftar.masjid.store');

    Route::get('/dashboard', [MosqueController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/admin/dashboard', [MosqueController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Rute Landing Page Admin Masjid (Menggunakan Controller)
    Route::get('/admin/landing-page', [LandingPageController::class, 'index'])
        ->name('admin.landing-page');

    Route::put('/admin/landing-page', [LandingPageController::class, 'update'])
        ->name('admin.landing-page.update');

    Route::get('/admin/profil-masjid', [MosqueController::class, 'editProfil'])
        ->name('admin.profil-masjid');

    Route::put('/admin/profil-masjid', [MosqueController::class, 'updateProfil'])
        ->name('admin.profil-masjid.update');

    Route::get('/admin/beranda', function () {
        return view('auth.adminmasjid.berandaAdmin');
    })->name('admin.beranda');

    Route::get('/admin/jadwal-sholat', function () {
        return view('auth.adminmasjid.jadwalSholat');
    })->name('admin.jadwal-sholat');

    // ===== Program Unggulan =====
    Route::get('/admin/program', [ProgramController::class, 'index'])
        ->name('admin.program');

    Route::put('/admin/program', [ProgramController::class, 'update'])
        ->name('admin.program.update');

    // ===== Acara / Kegiatan =====
    Route::get('/admin/acara', [AcaraController::class, 'index'])
        ->name('admin.acara');

    Route::post('/admin/acara', [AcaraController::class, 'store'])
        ->name('admin.acara.store');

    Route::put('/admin/acara/{acara}', [AcaraController::class, 'update'])
        ->name('admin.acara.update');

    Route::delete('/admin/acara/{acara}', [AcaraController::class, 'destroy'])
        ->name('admin.acara.destroy');

    // ===== Pengumuman =====
    Route::get('/admin/pengumuman', [PengumumanController::class, 'index'])
        ->name('admin.pengumuman');

    Route::post('/admin/pengumuman', [PengumumanController::class, 'store'])
        ->name('admin.pengumuman.store');

    Route::get('/admin/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])
        ->name('admin.pengumuman.edit');

    Route::put('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])
        ->name('admin.pengumuman.update');

    Route::delete('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])
        ->name('admin.pengumuman.destroy');

    Route::patch('/admin/pengumuman/{pengumuman}/toggle-status', [PengumumanController::class, 'toggleStatus'])
        ->name('admin.pengumuman.toggle-status');

    // ===== Donasi =====
    Route::get('/admin/donasi', [DonasiController::class, 'index'])
        ->name('admin.donasi');

    Route::post('/admin/donasi', [DonasiController::class, 'store'])
        ->name('admin.donasi.store');

    Route::get('/admin/donasi/{donasi}/edit', [DonasiController::class, 'edit'])
        ->name('admin.donasi.edit');

    Route::put('/admin/donasi/{donasi}', [DonasiController::class, 'update'])
        ->name('admin.donasi.update');

    Route::delete('/admin/donasi/{donasi}', [DonasiController::class, 'destroy'])
        ->name('admin.donasi.destroy');

    Route::patch('/admin/donasi/{donasi}/toggle-status', [DonasiController::class, 'toggleStatus'])
        ->name('admin.donasi.toggle-status');

    // ===== Data Jamaah =====
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

    Route::put('/verifikasi/{id}/approve', function ($id) {
        $mosque = Mosque::findOrFail($id);
        $mosque->update(['status' => 'approved']);
        return redirect()->route('superadmin.verifikasi')->with('success', 'Pendaftaran berhasil disetujui.');
    })->name('verifikasi.approve');

    Route::put('/verifikasi/{id}/reject', function ($id) {
        $mosque = Mosque::findOrFail($id);
        $mosque->update(['status' => 'rejected']);
        return redirect()->route('superadmin.verifikasi')->with('error', 'Pendaftaran telah ditolak.');
    })->name('verifikasi.reject');

    Route::get('/manajemen-masjid', [MosqueController::class, 'manajemenMasjid'])->name('manajemen-masjid');

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