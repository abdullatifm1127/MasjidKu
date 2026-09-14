<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan Model User sudah di-import

class PenggunaController extends Controller
{
    public function index()
    {
        $users = User::all();
        $totalPengguna = $users->count();

        return view('auth.superadmin.penggunaSuperAdmin', compact('users', 'totalPengguna'));
    }
}