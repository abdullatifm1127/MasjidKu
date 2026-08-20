<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Mosque;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\SuperAdmin\PenggunaController;
use App\Http\Controllers\SuperAdmin\BerandaSuperAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\PengaturanController;

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

// Halaman Publik Masjid

Route::middleware(['auth'])->get('/masjidUser', function () {
    $mosque = Mosque::where('user_id', Auth::id())->first();  
    if (!$mosque) {
        return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
    }
    return view('auth.adminmasjid.halamanUtamaUser', compact('mosque'));
})->name('masjid.publik');
/*
|--------------------------------------------------------------------------
| Authentication
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
| Halaman yang membutuhkan Login (Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/daftar-masjid', [MosqueController::class, 'create'])
        ->name('daftar.masjid');

    Route::post('/daftar-masjid', [MosqueController::class, 'store'])
        ->name('daftar.masjid.store');

    Route::get('/dashboard', [MosqueController::class, 'dashboard'])
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Halaman Waiting
|--------------------------------------------------------------------------
*/
Route::get('/waiting', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('mosque.waiting');
})->name('waiting');

/*
|--------------------------------------------------------------------------
| Forgot Password
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', function () {
    return view('auth.login');
})->name('password.request');

/*
|--------------------------------------------------------------------------
| Admin Masjid (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [MosqueController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/landing-page', function () {
        return view('admin.landingPage');
    })->name('admin.landing-page');

    Route::get('/admin/profil-masjid', [MosqueController::class, 'editProfil'])
        ->name('admin.profil-masjid');

    Route::put('/admin/profil-masjid', [MosqueController::class, 'updateProfil'])
        ->name('admin.profil-masjid.update');
});

// Dashboard Admin Masjid (new)
Route::get('/admin2/dashboard', function () {
    return view('auth.adminmasjid.berandaAdmin');
})->name('admin2.dashboard');

Route::get('/admin2/landing-page', function () {
    return view('admin.landingPage');
})->name('admin2.landing-page');

Route::get('/admin2/profil-masjid', function () {
    return view('auth.adminmasjid.profilMasjid');
})->name('admin2.profil-masjid');

Route::get('/admin/landing-page', function () {
    return view('admin.landingPage');
})->name('admin.landing-page');

Route::put('/admin/landing-page', function (\Illuminate\Http\Request $request) {
    return redirect()->route('admin.landing-page')
        ->with('success', 'Landing page berhasil disimpan.');
})->name('admin.landing-page.update');

Route::get('/admin/profil-masjid', [MosqueController::class, 'editProfil'])
    ->name('admin.profil-masjid');

Route::put('/admin/profil-masjid', [MosqueController::class, 'updateProfil'])
    ->name('admin.profil-masjid.update');

/*
|--------------------------------------------------------------------------
| Super Admin (Harus Login & Punya Akses)
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