<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Tampilkan halaman kelola Program Unggulan milik masjid yang sedang login.
     */
    public function index()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
        }

        $programs = $mosque->programs ?? [];

        return view('auth.adminmasjid.programMasjid', compact('mosque', 'programs'));
    }

    /**
     * Simpan urutan & isi Program Unggulan.
     * Menerima array 'programs' dari form (urutan input = urutan tampil).
     */
    public function update(Request $request)
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            return redirect()->route('admin.program')->with('error', 'Data masjid belum tersedia.');
        }

        $validated = $request->validate([
            'programs'   => 'nullable|array',
            'programs.*' => 'required|string|max:255',
        ]);

        // Buang entri kosong & re-index array secara berurutan
        $programs = collect($validated['programs'] ?? [])
            ->map(fn ($p) => trim($p))
            ->filter(fn ($p) => $p !== '')
            ->values()
            ->all();

        $mosque->update(['programs' => $programs]);

        return redirect()->route('admin.program')->with('success', 'Program unggulan berhasil disimpan.');
    }
}