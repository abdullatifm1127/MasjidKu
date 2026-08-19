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
Route::get('/masjidUser', function () {
    $mosque = Mosque::first(); 
    return view('auth.halamanUtamaUser', compact('mosque'));
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
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [MosqueController::class, 'dashboard'])
        ->name('admin.dashboard');
});

// Dashboard Admin Masjid (new)
Route::get('/admin2/dashboard', function () {
    return view('auth.adminmasjid.berandaAdmin');
})->name('admin2.dashboard');

Route::get('/admin2/landing-page', function () {
    return view('admin.landingPage');
})->name('admin2.landing-page');

Route::get('/admin2/profil-masjid', [MosqueController::class, 'editProfil'])
    ->name('admin2.profil-masjid');

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
| Super Admin
|--------------------------------------------------------------------------
*/
Route::get('/superadmin/dashboard', [BerandaSuperAdminController::class, 'dashboard'])
    ->name('superadmin.dashboard');

Route::get('/superadmin/verifikasi', [MosqueController::class, 'verifikasi'])
    ->name('superadmin.verifikasi');

Route::put('/superadmin/verifikasi/{id}/approve', function ($id) {
    $mosque = Mosque::findOrFail($id);
    $mosque->update(['status' => 'approved']);
    return redirect()->route('superadmin.verifikasi')
        ->with('success', 'Pendaftaran berhasil disetujui.');
})->name('superadmin.verifikasi.approve');

Route::put('/superadmin/verifikasi/{id}/reject', function ($id) {
    $mosque = Mosque::findOrFail($id);
    $mosque->update(['status' => 'rejected']);
    return redirect()->route('superadmin.verifikasi')
        ->with('error', 'Pendaftaran telah ditolak.');
})->name('superadmin.verifikasi.reject');

Route::get('/superadmin/manajemen-masjid', [MosqueController::class, 'manajemenMasjid'])
    ->name('superadmin.manajemen-masjid');

Route::post('/superadmin/manajemen-masjid', function (\Illuminate\Http\Request $request) {
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

    return redirect()->route('superadmin.manajemen-masjid')
        ->with('success', 'Masjid berhasil ditambahkan.');
})->name('superadmin.manajemen-masjid.store');

// Manajemen Pengguna Super Admin (Menggunakan Controller Bersih Tanpa Duplikasi)
Route::get('/superadmin/pengguna', [PenggunaController::class, 'index'])
    ->name('superadmin.pengguna');

Route::post('/superadmin/pengguna', [PenggunaController::class, 'store'])
    ->name('superadmin.pengguna.store');

Route::put('/superadmin/pengguna/{id}', [PenggunaController::class, 'update'])
    ->name('superadmin.pengguna.update');

// Pengaturan Super Admin
// Rute untuk menampilkan halaman
Route::get('/superadmin/pengaturan', [PengaturanController::class, 'index'])
    ->name('superadmin.pengaturan');
// Rute untuk memproses update
Route::put('/superadmin/pengaturan', [PengaturanController::class, 'update'])
    ->name('superadmin.pengaturan.update');

//halaman superadmin
// Menampilkan form login
Route::get('/superadmin/login', [LoginController::class, 'showLoginForm'])->name('login');
// Memproses data login dari form
Route::post('/superadmin/login', [LoginController::class, 'login']);
// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//seting
Route::put('/superadmin/pengaturan/update', [PengaturanController::class, 'update'])->name('superadmin.pengaturan.update');
Route::delete('/superadmin/pengaturan/reset', [App\Http\Controllers\SuperAdmin\PengaturanController::class, 'reset'])
    ->name('superadmin.pengaturan.reset');