<?php

use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', function () {
    return view('halamanUtama');
})->name('home');

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register Akun (nama, email, password)
Route::get('/register', function () {
    return view('auth.registerAkun');
})->name('register');

// Daftarkan Masjid (form lengkap masjid)
Route::get('/daftar-masjid', function () {
    return view('auth.registerMasjid');
})->name('daftar.masjid');

// Forgot Password
Route::get('/forgot-password', function () {
    return view('auth.login');
})->name('password.request');
