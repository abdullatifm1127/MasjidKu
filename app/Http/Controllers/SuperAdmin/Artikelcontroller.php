<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    // Semua aksi kembali ke tab Artikel di halaman "Halaman Utama"
    private function kembali(array $query = [])
    {
        return redirect()->route('superadmin.halaman-utama.edit', ['tab' => 'artikel'] + $query);
    }

    // Simpan artikel baru / hasil edit
    public function simpan(Request $request)
    {
        // abort_unless(auth()->user()->role === 'superadmin', 403);

        $cek = Validator::make($request->all(), [
            'judul' => ['required', 'max:200'],
            'isi'   => ['required'],
            'file'  => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip', 'max:10240'], // maks 10 MB
        ], [
            'file.mimes' => 'Format file harus PDF, Word, Excel, PowerPoint, TXT, atau ZIP.',
            'file.max'   => 'Ukuran file maksimal 10 MB.',
            'file.uploaded' => 'File gagal diunggah. Cek batas upload_max_filesize di php.ini.',
        ]);
        if ($cek->fails()) {
            return $this->kembali($request->filled('id') ? ['edit' => $request->input('id')] : [])
                ->withErrors($cek)->withInput();
        }

        // Key array = nama kolom di database
        $data = [
            'title'        => $request->input('judul'),
            'isi'          => $request->input('isi'),
            'is_published' => $request->boolean('is_published'),
        ];

        $artikel = $request->filled('id') ? Article::findOrFail($request->input('id')) : new Article();

        if ($request->hasFile('file')) {
            if ($artikel->file) Storage::disk('public')->delete($artikel->file);
            $data['file']      = $request->file('file')->store('artikel', 'public');
            $data['file_nama'] = $request->file('file')->getClientOriginalName();
        }

        if (!$artikel->exists) $data['slug'] = $this->slugUnik($data['title']);

        $artikel->fill($data)->save();

        return $this->kembali()->with('success', 'Artikel disimpan.');
    }

    public function terbit($id)
    {
        $a = Article::findOrFail($id);
        $a->update(['is_published' => !$a->is_published]);
        return $this->kembali()->with('success', $a->is_published ? 'Artikel diterbitkan.' : 'Artikel dijadikan draft.');
    }

    public function hapus($id)
    {
        $a = Article::findOrFail($id);
        if ($a->file) Storage::disk('public')->delete($a->file);
        $a->delete();
        return $this->kembali()->with('success', 'Artikel dihapus.');
    }

    private function slugUnik(string $judul): string
    {
        $base = Str::slug(Str::limit($judul, 80, '')) ?: 'artikel';
        $slug = $base;
        $i = 2;
        while (Article::where('slug', $slug)->exists()) $slug = $base . '-' . $i++;
        return $slug;
    }
}