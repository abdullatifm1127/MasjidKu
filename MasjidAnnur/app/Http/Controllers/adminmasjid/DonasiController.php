<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonasiController extends Controller
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
     * Daftar semua donasi + form tambah/edit (satu halaman).
     */
    public function index()
    {
        $mosque = $this->currentMosque();

        $donasis = Donasi::where('mosque_id', $mosque->id)
            ->orderByDesc('tanggal_donasi')
            ->orderByDesc('created_at')
            ->get();

        $edit = null;
        if (request()->has('edit')) {
            $edit = Donasi::where('mosque_id', $mosque->id)
                ->findOrFail(request('edit'));
        }

        // Statistik ringkasan
        $stats = [
            'total_semua'   => Donasi::totalLunas($mosque->id),
            'total_bulan'   => Donasi::totalBulanIni($mosque->id),
            'jumlah_donasi' => $donasis->count(),
            'pending'       => $donasis->where('status', 'pending')->count(),
        ];

        return view('auth.adminmasjid.donasi', compact('mosque', 'donasis', 'edit', 'stats'));
    }

    /**
     * Simpan donasi baru.
     */
    public function store(Request $request)
    {
        $mosque = $this->currentMosque();

        $validated = $this->validatedData($request);

        if ($request->hasFile('bukti_transfer')) {
            $validated['bukti_transfer'] = $request->file('bukti_transfer')
                ->store('donasi/bukti', 'public');
        }

        Donasi::create(array_merge($validated, ['mosque_id' => $mosque->id]));

        return redirect()->route('admin.donasi')->with('success', 'Data donasi berhasil ditambahkan.');
    }

    /**
     * Redirect ke index dengan mode edit.
     */
    public function edit(Donasi $donasi)
    {
        $this->authorizeOwnership($donasi, $this->currentMosque());

        return redirect()->route('admin.donasi', ['edit' => $donasi->id]);
    }

    /**
     * Update donasi.
     */
    public function update(Request $request, Donasi $donasi)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($donasi, $mosque);

        $validated = $this->validatedData($request);

        if ($request->hasFile('bukti_transfer')) {
            // Hapus file lama
            if ($donasi->bukti_transfer) {
                Storage::disk('public')->delete($donasi->bukti_transfer);
            }
            $validated['bukti_transfer'] = $request->file('bukti_transfer')
                ->store('donasi/bukti', 'public');
        }

        $donasi->update($validated);

        return redirect()->route('admin.donasi')->with('success', 'Data donasi berhasil diperbarui.');
    }

    /**
     * Hapus donasi.
     */
    public function destroy(Donasi $donasi)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($donasi, $mosque);

        if ($donasi->bukti_transfer) {
            Storage::disk('public')->delete($donasi->bukti_transfer);
        }

        $donasi->delete();

        return redirect()->route('admin.donasi')->with('success', 'Data donasi berhasil dihapus.');
    }

    /**
     * Toggle status: lunas ↔ pending.
     */
    public function toggleStatus(Donasi $donasi)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($donasi, $mosque);

        $donasi->update([
            'status' => $donasi->status === 'lunas' ? 'pending' : 'lunas',
        ]);

        return redirect()->route('admin.donasi')->with('success', 'Status donasi berhasil diubah.');
    }

    // -------------------------------------------------------------------------

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'nama_donatur'   => 'required|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
            'jumlah'         => 'required|numeric|min:0',
            'kategori'       => 'required|in:infaq,sedekah,zakat,wakaf,lainnya',
            'keterangan'     => 'nullable|string|max:500',
            'metode'         => 'required|in:tunai,transfer,qris,lainnya',
            'status'         => 'required|in:lunas,pending,batal',
            'tanggal_donasi' => 'required|date',
            'bukti_transfer' => 'nullable|mimes:jpeg,png,jpg,webp,pdf|max:2048',
        ]);

        // Jangan simpan UploadedFile mentah, path di-set setelah store()
        unset($validated['bukti_transfer']);

        return $validated;
    }

    protected function authorizeOwnership(Donasi $donasi, Mosque $mosque): void
    {
        if ($donasi->mosque_id !== $mosque->id) {
            abort(403);
        }
    }
}
