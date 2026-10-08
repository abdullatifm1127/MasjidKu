<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengumumanController extends Controller
{
    /** Masjid milik user yang login (sama seperti di middleware CheckMosqueStatus). */
    private function mosque(): Mosque
    {
        return Mosque::where('user_id', auth()->id())->firstOrFail();
    }

    private function mosqueId(): int
    {
        return $this->mosque()->id;
    }

    private function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:150'],
            'content'    => ['required', 'string', 'max:3000'],
            'category'   => ['required', Rule::in(array_keys(Pengumuman::CATEGORIES))],
            'status'     => ['required', Rule::in(['draft', 'terbit'])],
            'expires_at' => ['nullable', 'date'],
            'is_pinned'  => ['nullable', 'boolean'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'], // 3 MB
        ];
    }

    private function messages(): array
    {
        return [
            'title.required'   => 'Judul wajib diisi.',
            'content.required' => 'Isi pengumuman wajib diisi.',
            'image.image'      => 'File harus berupa gambar.',
            'image.mimes'      => 'Format gambar harus JPG, PNG, atau WebP.',
            'image.max'        => 'Ukuran gambar maksimal 3 MB.',
        ];
    }

    public function index(Request $request)
    {
        $query = Pengumuman::where('mosque_id', $this->mosqueId());

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);

        $items = $query->orderByDesc('is_pinned')->latest()->paginate(9)->withQueryString();

        return view('auth.adminmasjid.Pengumuman', [
            'items'      => $items,
            'categories' => Pengumuman::CATEGORIES,
            'mosque'     => $this->mosque(), // dipakai sidebar
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $data['mosque_id'] = $this->mosqueId();
        $data['is_pinned'] = $request->boolean('is_pinned');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('pengumuman', 'public');
        }
        unset($data['image']);

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $item = Pengumuman::where('mosque_id', $this->mosqueId())->findOrFail($id);

        $data = $request->validate($this->rules(), $this->messages());
        $data['is_pinned'] = $request->boolean('is_pinned');

        if ($request->hasFile('image')) {
            if ($item->image_path) Storage::disk('public')->delete($item->image_path);
            $data['image_path'] = $request->file('image')->store('pengumuman', 'public');
        } elseif ($request->boolean('remove_image') && $item->image_path) {
            Storage::disk('public')->delete($item->image_path);
            $data['image_path'] = null;
        }
        unset($data['image']);

        $item->update($data);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Pengumuman::where('mosque_id', $this->mosqueId())->findOrFail($id);

        if ($item->image_path) Storage::disk('public')->delete($item->image_path);
        $item->delete();

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman dihapus.');
    }
}