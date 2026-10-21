<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeManagementController extends Controller
{
    public function index()
    {
        $themes = Theme::all();
        return view('auth.superadmin.themesSuperAdmin', compact('themes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:themes,key|alpha_dash',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'preview_color' => 'nullable|string',
        ]);

        Theme::create([
            'key' => strtolower($request->key),
            'name' => $request->name,
            'description' => $request->description,
            'preview_color' => $request->preview_color,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Tema berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $theme = Theme::findOrFail($id);
        
        // Mencegah penghapusan tema default utama
        if ($theme->key === 'default') {
            return redirect()->back()->with('error', 'Tema default utama tidak dapat dihapus.');
        }

        $theme->delete();

        return redirect()->back()->with('success', 'Tema berhasil dihapus.');
    }
}
