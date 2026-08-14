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

return view('auth.halamanUtama', compact('userMosque'));
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
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [MosqueController::class, 'dashboard'])
        ->name('admin.dashboard');
});

// Landing Page Editor
Route::get('/admin/landing-page', function () {
    return view('admin.landingPage');
})->name('admin.landing-page');

// Profil Masjid Admin
Route::get('/admin/profil-masjid', [MosqueController::class, 'editProfil'])
    ->name('admin.profil-masjid');

Route::put('/admin/profil-masjid', [MosqueController::class, 'updateProfil'])
    ->name('admin.profil-masjid.update');


/*
|--------------------------------------------------------------------------
| Super Admin
|--------------------------------------------------------------------------
*/

// Dashboard Super Admin
Route::get('/superadmin/dashboard', function () {
    return view('auth.berandaSuperAdmin');
})->name('superadmin.dashboard');

// Verifikasi Pendaftaran Super Admin
Route::get('/superadmin/verifikasi', function () {
    // Hanya ambil masjid yang statusnya pending
    $pendaftaran = Mosque::where('status', 'pending')->latest()->get();

    return view('auth.verifSuperAdmin', compact('pendaftaran'));
})->name('superadmin.verifikasi');

// Approve & Reject pendaftaran (placeholder — ganti dengan controller saat ada logika DB)
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

// Manajemen Masjid Super Admin
Route::get('/superadmin/manajemen-masjid', function () {
    return view('auth.manajemenMasjidSuperAdmin');
})->name('superadmin.manajemen-masjid');

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

// Pengguna Super Admin
Route::get('/superadmin/pengguna', function () {
    return view('auth.penggunaSuperAdmin');
})->name('superadmin.pengguna');

Route::post('/superadmin/pengguna', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name'                  => 'required|string|max:255',
        'email'                 => 'required|email|max:255|unique:users',
        'role'                  => 'required|in:tenant_admin,super_admin',
        'password'              => 'required|string|min:8|confirmed',
    ]);

    \App\Models\User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    return redirect()->route('superadmin.pengguna')
        ->with('success', 'Pengguna berhasil ditambahkan.');
})->name('superadmin.pengguna.store');

Route::put('/superadmin/pengguna/{id}', function (\Illuminate\Http\Request $request, $id) {
    $user = \App\Models\User::findOrFail($id);

    $rules = [
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
    ];

    if ($request->filled('password')) {
        $rules['password'] = 'string|min:8|confirmed';
    }

    $validated = $request->validate($rules);

    $user->update(array_filter([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => $request->filled('password') ? bcrypt($request->password) : null,
    ]));

    return redirect()->route('superadmin.pengguna')
        ->with('success', 'Data pengguna berhasil diperbarui.');
})->name('superadmin.pengguna.update');

// Pengaturan Super Admin
Route::get('/superadmin/pengaturan', function () {
    return view('auth.settingSuperAdmin');
})->name('superadmin.pengaturan');

Route::put('/superadmin/pengaturan', function (\Illuminate\Http\Request $request) {
    // Simpan ke config / database sesuai kebutuhan project
    // Saat ini redirect balik dengan pesan sukses
    return redirect()->route('superadmin.pengaturan')
        ->with('success', 'Pengaturan berhasil disimpan.');
})->name('superadmin.pengaturan.update');