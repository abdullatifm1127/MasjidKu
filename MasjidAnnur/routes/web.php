<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Mosque;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MosqueController;


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

    return view('halamanUtama', compact('userMosque'));
})->name('home');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Halaman Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Proses Login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

// Halaman Daftar Akun
Route::get('/register', function () {
    return view('auth.registerAkun');
})->name('register');

// Proses Daftar Akun
Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Halaman yang membutuhkan Login (Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Form Daftarkan Masjid
    Route::get('/daftar-masjid', [MosqueController::class, 'create'])
        ->name('daftar.masjid');

    // Proses simpan masjid
    Route::post('/daftar-masjid', [MosqueController::class, 'store'])
        ->name('daftar.masjid.store');

    // Dashboard
    Route::get('/dashboard', [MosqueController::class, 'dashboard'])
        ->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Halaman Waiting (Dipindah keluar dari middleware auth)
|--------------------------------------------------------------------------
*/
Route::get('/waiting', function () {
    // Jika tidak ada user yang login sama sekali, baru arahkan ke login
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('waiting');
})->name('waiting');


/*
|--------------------------------------------------------------------------
| Forgot Password
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', function () {
    return view('auth.login'); // Ubah jika nanti sudah membuat view khusus forgot password
})->name('password.request');


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('auth.berandaAdmin');
})->name('admin.dashboard');

// Landing Page Editor
Route::get('/admin/landing-page', function () {
    return view('admin.landingPage');
})->name('admin.landing-page');