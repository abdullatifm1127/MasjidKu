<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MosqueController;
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

// Proses Login
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])
    ->name('login.process');

// Halaman Daftar Akun
Route::get('/register', function () {
    return view('auth.registerAkun');
})->name('register');

// Proses Daftar Akun
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])
    ->name('register.process');

// Logout
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Halaman yang membutuhkan Login
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
    return view('auth.login');
})->name('password.request');