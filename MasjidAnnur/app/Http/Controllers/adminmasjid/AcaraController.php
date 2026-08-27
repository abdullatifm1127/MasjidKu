<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    /**
     * Ambil masjid milik user yang sedang login, atau redirect kalau belum ada.
     */
    protected function currentMosque()
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();

        if (!$mosque) {
            abort(redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.'));
        }

        return $mosque;
    }

    /**
     * Halaman kelola acara: daftar + form tambah/edit (satu halaman).
     */
    public function index()
    {
        $mosque = $this->currentMosque();

        $acaras = Acara::where('mosque_id', $mosque->id)
            ->orderBy('event_date', 'asc')
            ->get();

        return view('auth.adminmasjid.acaraMasjid', compact('mosque', 'acaras'));
    }

    /**
     * Simpan acara baru.
     */
    public function store(Request $request)
    {
        $mosque = $this->currentMosque();

        $validated = $this->validated($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('acara', 'public');
        }

        if (!empty($validated['is_featured'])) {
            // Hanya boleh ada 1 acara featured aktif per masjid
            Acara::where('mosque_id', $mosque->id)->update(['is_featured' => false]);
        }

        Acara::create(array_merge($validated, ['mosque_id' => $mosque->id]));

        return redirect()->route('admin.acara')->with('success', 'Acara berhasil ditambahkan.');
    }

    /**
     * Update acara.
     */
    public function update(Request $request, Acara $acara)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($acara, $mosque);

        $validated = $this->validated($request);

        if ($request->hasFile('photo')) {
            // Hapus foto lama biar tidak menumpuk file yatim di storage
            if ($acara->photo) {
                Storage::disk('public')->delete($acara->photo);
            }
            $validated['photo'] = $request->file('photo')->store('acara', 'public');
        }

        if (!empty($validated['is_featured'])) {
            Acara::where('mosque_id', $mosque->id)->where('id', '!=', $acara->id)->update(['is_featured' => false]);
        }

        $acara->update($validated);

        return redirect()->route('admin.acara')->with('success', 'Acara berhasil diperbarui.');
    }

    /**
     * Hapus acara.
     */
    public function destroy(Acara $acara)
    {
        $mosque = $this->currentMosque();
        $this->authorizeOwnership($acara, $mosque);

        if ($acara->photo) {
            Storage::disk('public')->delete($acara->photo);
        }

        $acara->delete();

        return redirect()->route('admin.acara')->with('success', 'Acara berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'event_date'   => 'required|date',
            'event_time'   => 'nullable|string|max:50',
            'organizer'    => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'photo'        => 'nullable|image|max:2048',
            'is_featured'  => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        // 'photo' di sini masih berupa UploadedFile kalau ada — jangan ikut
        // disimpan mentah-mentah ke array $validated yang dipakai untuk create/update,
        // path-nya baru di-set setelah berhasil di-store() ke disk.
        unset($validated['photo']);

        return $validated;
    }

    /**
     * Pastikan acara yang diakses memang milik masjid user yang login.
     */
    protected function authorizeOwnership(Acara $acara, Mosque $mosque): void
    {
        if ($acara->mosque_id !== $mosque->id) {
            abort(403);
        }
    }
}