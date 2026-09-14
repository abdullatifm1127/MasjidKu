<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('auth.superadmin.settingSuperAdmin');
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validasi jika user mengisi password baru
        if ($request->filled('new_password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', 'confirmed', 'min:8'],
            ]);

            // Update password
            $user->password = Hash::make($request->new_password);
        }

        // Simpan perubahan data user
        $user->save();

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
    public function reset()
{
    // Logika untuk menghapus data tenant di sini
    // Contoh: Tenant::truncate();

    return back()->with('success', 'Semua data tenant berhasil dihapus.');
}
}