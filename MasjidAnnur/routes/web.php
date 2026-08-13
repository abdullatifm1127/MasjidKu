<?php

use Illuminate\Support\Facades\Route;

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

// Halaman Daftar Akun
Route::get('/register', function () {
    return view('auth.registerAkun');
})->name('register');

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
