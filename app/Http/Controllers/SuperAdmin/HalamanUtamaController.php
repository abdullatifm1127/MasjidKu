<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HalamanUtamaController extends Controller
{
    public function edit(Request $request)
    {
        // abort_unless(auth()->user()->role === 'superadmin', 403); // sesuaikan kolom role Anda

        $ada = Schema::hasTable('articles');

        return view('auth.superadmin.halamanUtamaSuperAdmin', [
            'c'        => SiteSetting::content(),
            'artikels' => $ada ? Article::orderByDesc('id')->get() : collect(),
            'edit'     => ($ada && $request->filled('edit')) ? Article::find($request->query('edit')) : null,
        ]);
    }

    public function update(Request $request)
    {
        // abort_unless(auth()->user()->role === 'superadmin', 403);
        $request->validate([
            'kontak.email' => ['nullable', 'email', 'max:150'],
            'footer.*'     => ['nullable', 'string', 'max:255'],
            'hero.*'       => ['nullable', 'string', 'max:500'],
            'ayat.*'       => ['nullable', 'string', 'max:1000'],
        ]);

        foreach (array_keys(SiteSetting::defaults()) as $group) {
            if (!$request->has($group)) continue;

            $data = $request->input($group);
            if ($group === 'hero') {
                $data['show_bismillah'] = $request->boolean('hero.show_bismillah') ? 1 : 0;
            }
            SiteSetting::saveGroup($group, $data);
        }

        SiteSetting::flush();

        return redirect()->route('superadmin.halaman-utama.edit')
            ->with('success', 'Halaman utama berhasil diperbarui.');
    }

    public function reset()
    {
        // abort_unless(auth()->user()->role === 'superadmin', 403);
        SiteSetting::query()->delete();
        SiteSetting::flush();

        return redirect()->route('superadmin.halaman-utama.edit')
            ->with('success', 'Isi halaman utama dikembalikan ke bawaan.');
    }
}