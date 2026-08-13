<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MosqueController;
=======
>>>>>>> 7fcff53fb5b947e1a2e47bffc9f273956b98d171

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('halamanUtama');
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

<<<<<<< HEAD
// Proses Login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

=======
>>>>>>> 7fcff53fb5b947e1a2e47bffc9f273956b98d171
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
| Forgot Password
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', function () {
    return view('auth.login'); // Ubah jika nanti sudah membuat view khusus forgot password
})->name('password.request');
=======
// Halaman Daftarkan Masjid
Route::get('/daftar-masjid', function () {
    return view('auth.registerMasjid');
})->name('daftar.masjid');

// Forgot Password
Route::get('/forgot-password', function () {
    return view('auth.login');
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
>>>>>>> 7fcff53fb5b947e1a2e47bffc9f273956b98d171
