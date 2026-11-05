<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Models\LandingPage;
use App\Models\Mosque;
use App\Models\MosqueBankAccount;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller
{
    public function index()
    {
        $setting = Setting::current();

        $stats = [
            'total_masjid'   => Mosque::count(),
            'aktif'          => Mosque::where('status', 'approved')->count(),
            'pending'        => Mosque::where('status', 'pending')->count(),
            'total_pengguna' => User::count(),
        ];

        return view('auth.superadmin.settingSuperAdmin', compact('setting', 'stats'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'platform_name'    => 'required|string|max:100',
            'support_email'    => 'nullable|email|max:150',
            'whatsapp'         => 'nullable|string|max:30',
            'max_mosque_free'  => 'required|integer|min:1|max:100',
            'max_announcement' => 'required|integer|min:1|max:1000',
            'max_event'        => 'required|integer|min:1|max:1000',
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password'     => ['nullable', 'confirmed', Password::min(8)],
        ]);

        DB::transaction(function () use ($request, $data, $user) {
            Setting::current()->update([
                'platform_name'      => $data['platform_name'],
                'support_email'      => $data['support_email'] ?? null,
                'whatsapp'           => $data['whatsapp'] ?? null,
                'max_mosque_free'    => $data['max_mosque_free'],
                'max_announcement'   => $data['max_announcement'],
                'max_event'          => $data['max_event'],
                'notif_registration' => $request->boolean('notif_registration'),
                'notif_donation'     => $request->boolean('notif_donation'),
                'maintenance_mode'   => $request->boolean('maintenance_mode'),
            ]);

            if ($request->filled('new_password')) {
                $user->password = Hash::make($data['new_password']);
                $user->save();
            }
        });

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Kumpulkan path file dulu, dihapus setelah transaksi database berhasil
        $files = Mosque::get(['payment_proof', 'qris_image', 'about_photo', 'about_photo_secondary'])
            ->flatMap(fn ($m) => [
                $m->payment_proof,
                $m->qris_image,
                $m->about_photo,
                $m->about_photo_secondary,
            ])
            ->filter()
            ->unique()
            ->values();

        DB::transaction(function () {
            // Tabel anak dulu, baru induk.
            Acara::query()->delete();
            Subscription::query()->delete();
            MosqueBankAccount::query()->delete();
            LandingPage::query()->delete();

            // TAMBAHKAN model lain yang punya mosque_id, misalnya:
            // Donation::query()->delete();
            // DonationCategory::query()->delete();
            // Pengumuman::query()->delete();
            // Jamaah::query()->delete();

            Mosque::query()->delete();

            // Hapus akun admin masjid, JANGAN hapus super admin dan akun yang sedang login.
            // (whereNull dipakai karena "role != x" di SQL tidak menyertakan baris NULL)
            User::where('id', '!=', Auth::id())
                ->where(function ($q) {
                    $q->whereNull('role')->orWhere('role', '!=', 'superadmin');
                })
                ->delete();
        });

        // Hapus file fisik (asumsi disk 'public', sesuai asset('storage/...') di model Anda)
        foreach ($files as $path) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Semua data tenant berhasil dihapus.');
    }
}