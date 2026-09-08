<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mosque;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
{
    // Mengambil data pengguna beserta relasi masjidnya
    $pengguna = User::all();
    $mosques = Mosque::all(); 

    return view('auth.superadmin.penggunaSuperAdmin', compact('pengguna', 'mosques'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'role'     => 'required|in:tenant_admin,super_admin',
            'masjid'   => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'masjid'   => $validated['masjid'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'role'     => 'required|in:tenant_admin,super_admin',
        'masjid'   => 'nullable|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // 1. Update data dasar pada tabel users (termasuk email baru)
    $user->name   = $validated['name'];
    $user->email  = $validated['email']; // Email pengguna diperbarui di sini
    $user->role   = $validated['role'];
    $user->masjid = $validated['masjid'] ?? null;

    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }
    $user->save();

   // SINKRONISASI EMAIL OTOMATIS KE TABEL MOSQUE BERDASARKAN USER ID ATAU NAMA
    if (!empty($validated['masjid'])) {
        // Cari berdasarkan user_id milik user ini terlebih dahulu (paling akurat),
        // atau fallback ke pencarian berdasarkan nama masjid
        $mosque = Mosque::where('user_id', $user->id)
                         ->orWhere('mosque_name', $validated['masjid'])
                         ->first();

        if ($mosque) {
            $mosque->email = $validated['email']; 
            $mosque->save();
        }
    }

    return redirect()->back()->with('success', 'Email pengguna dan data masjid berhasil diperbarui secara otomatis.');
}
}