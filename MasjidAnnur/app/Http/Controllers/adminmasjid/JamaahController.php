<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JamaahController extends Controller
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
     * Daftar semua jamaah + form tambah/edit (satu halaman).
     * Mendukung pencarian via ?q=keyword dan filter ?peran= / ?status=
     */
    public function index(Request $request)
    {
        $mosque = $this->currentMosque();

        $query = Jamaah::where('mosque_id', $mosque->id);

        // Pencarian nama / no HP / NIK
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'ilike', "%{$q}%")
                    ->orWhere('no_hp', 'ilike', "%{$q}%")
                    ->orWhere('nik', 'ilike', "%{$q}%");
            });
        }

        if ($request->filled('peran')) {
            $query->where('peran', $request->peran);
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $jamaahs = $query->orderBy('nama')->get();

        // Edit mode
        $edit = null;
        if ($request->has('edit')) {
            $edit = Jamaah::where('mosque_id', $mosque->id)
                ->findOrFail($request->edit);
        }

        // Statistik
        $stats = [
            'total'    => Jamaah::where('mosque_id', $mosque->id)->count(),
            'aktif'    => Jamaah::where('mosque_id', $mosque->id)->where('status', 'aktif')->count(),
            'pengurus' => Jamaah::where('mosque_id', $mosque->id)->where('peran', 'pengurus')->count(),
            'baru'     => Jamaah::where('mosque_id', $mosque->id)
                ->whereMonth('tanggal_bergabung', now()->month)
                ->whereYear('tanggal_bergabung', now()->year)
                ->count(),
        ];

        return view('auth.adminmasjid.dataJamaah', compact('mosque', 'jamaahs', 'edit', 'stats'));
    }

    /**
     * Simpan jamaah baru.
     */
    public function store(Request $request)
    {
        $mosque = $this->currentMosque();
        $validated = $this->validatedData($request);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('jamaah/foto', 'public');
        }

        Jamaah::create(array_merge($validated, ['mosque_id' => $mosque->id]));

        return redirect()->route('admin.jamaah')->with('success', 'Data jamaah berhasil ditambahkan.');
    }

    /**
     * Redirect ke index dengan mode edit.
     */
    public function edit(Jamaah $jamaah)
    {
        $this->authorizeOwnership($jamaah, $this->currentMosque());
        return redirect()->route('admin.jamaah', ['edit' => $jamaah->id]);
    }

    /**
     * Update jamaah.
     */
    public function update(Request $request, Jamaah $jamaah)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($jamaah, $mosque);

        $validated = $this->validatedData($request, $jamaah->id);

        if ($request->hasFile('foto')) {
            if ($jamaah->foto) {
                Storage::disk('public')->delete($jamaah->foto);
            }
            $validated['foto'] = $request->file('foto')->store('jamaah/foto', 'public');
        }

        $jamaah->update($validated);

        return redirect()->route('admin.jamaah')->with('success', 'Data jamaah berhasil diperbarui.');
    }

    /**
     * Hapus jamaah.
     */
    public function destroy(Jamaah $jamaah)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($jamaah, $mosque);

        if ($jamaah->foto) {
            Storage::disk('public')->delete($jamaah->foto);
        }

        $jamaah->delete();

        return redirect()->route('admin.jamaah')->with('success', 'Data jamaah berhasil dihapus.');
    }

    // -------------------------------------------------------------------------

    protected function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:laki-laki,perempuan',
            'tempat_lahir'       => 'nullable|string|max:100',
            'tanggal_lahir'      => 'nullable|date',
            'nik'                => 'nullable|string|max:20',
            'no_hp'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'alamat'             => 'nullable|string',
            'rt_rw'              => 'nullable|string|max:10',
            'kelurahan'          => 'nullable|string|max:100',
            'kecamatan'          => 'nullable|string|max:100',
            'status'             => 'required|in:aktif,nonaktif,pindah,meninggal',
            'peran'              => 'required|in:jamaah,pengurus,remaja,anak',
            'tanggal_bergabung'  => 'nullable|date',
            'catatan'            => 'nullable|string|max:1000',
            'foto'               => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        unset($validated['foto']);

        return $validated;
    }

    protected function authorizeOwnership(Jamaah $jamaah, Mosque $mosque): void
    {
        if ($jamaah->mosque_id !== $mosque->id) {
            abort(403);
        }
    }
}
