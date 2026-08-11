<?php

use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Forgot Password
Route::get('/forgot-password', function () {
    return view('auth.login');
})->name('password.request');

