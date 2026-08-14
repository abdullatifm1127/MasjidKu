<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::all();
        return view('auth.penggunaSuperAdmin', compact('pengguna'));
    }

    public function store(Request $request)
{
    // Cek data yang masuk dari form
    // dd($request->all()); 

    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255|unique:users,email',
        'role'     => 'required|in:tenant_admin,super_admin',
        'masjid'   => 'nullable|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'masjid' => $request->masjid,
    ]);

    return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
}

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'     => 'required|in:tenant_admin,super_admin',
            'masjid'   => 'nullable|string|max:255', // Pastikan ini ada
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name   = $validated['name'];
        $user->email  = $validated['email'];
        $user->role   = $validated['role'];
        $user->masjid = $validated['masjid'] ?? null; // Pastikan ini ada

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui.');
    }
}