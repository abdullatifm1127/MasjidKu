<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Ambil masjid milik user yang sedang login.
     */
    protected function currentMosque(): Mosque
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            abort(redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.'));
        }

        return $mosque;
    }

    /**
     * Daftar semua pengumuman + form tambah/edit (satu halaman).
     */
    public function index()
    {
        $mosque = $this->currentMosque();

        $pengumumans = Pengumuman::where('mosque_id', $mosque->id)
            ->orderByDesc('is_penting')
            ->orderByDesc('created_at')
            ->get();

        $edit = null;
        if (request()->has('edit')) {
            $edit = Pengumuman::where('mosque_id', $mosque->id)
                ->findOrFail(request('edit'));
        }

        return view('auth.adminmasjid.pengumuman', compact('mosque', 'pengumumans', 'edit'));
    }

    /**
     * Simpan pengumuman baru.
     */
    public function store(Request $request)
    {
        $mosque = $this->currentMosque();

        $validated = $this->validatedData($request);

        Pengumuman::create(array_merge($validated, ['mosque_id' => $mosque->id]));

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit (redirect ke index dengan query ?edit=id).
     */
    public function edit(Pengumuman $pengumuman)
    {
        $this->authorizeOwnership($pengumuman, $this->currentMosque());

        return redirect()->route('admin.pengumuman', ['edit' => $pengumuman->id]);
    }

    /**
     * Update pengumuman.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($pengumuman, $mosque);

        $validated = $this->validatedData($request);
        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($pengumuman, $mosque);

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus(Pengumuman $pengumuman)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($pengumuman, $mosque);

        $pengumuman->update([
            'status' => $pengumuman->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return redirect()->route('admin.pengumuman')->with('success', 'Status pengumuman berhasil diubah.');
    }

    // -------------------------------------------------------------------------

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:umum,ibadah,kegiatan,sosial,darurat',
            'status'          => 'required|in:aktif,nonaktif',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_penting'      => 'nullable|boolean',
        ]) + ['is_penting' => $request->boolean('is_penting')];
    }

    protected function authorizeOwnership(Pengumuman $pengumuman, Mosque $mosque): void
    {
        if ($pengumuman->mosque_id !== $mosque->id) {
            abort(403);
        }
    }
}
