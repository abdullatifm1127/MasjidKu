<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Models\MosqueNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotifikasiController extends Controller
{
    /* =============================================================
     |  SUPER ADMIN
     | ============================================================= */

    /**
     * Daftar masjid sesuai target pengiriman.
     *  all      : semua masjid yang aktif
     *  berbayar : masjid dengan paket berbayar
     *  free     : masjid dengan paket free
     *  segera   : paket berbayar yang berakhir dalam 7 hari
     *  pilih    : masjid yang dipilih manual
     */
    private function targetQuery(string $target, array $ids = []): Builder
    {
        $q = Mosque::query();

        if ($target === 'pilih') {
            return $q->whereIn('id', $ids);
        }

        // selain "pilih", hanya masjid yang aktif (bukan yang ditolak / pending)
        $q->whereIn(DB::raw('LOWER(status)'), ['approved', 'aktif']);

        switch ($target) {
            case 'berbayar':
                $q->whereNotNull('package_type')->where('package_type', '!=', 'free');
                break;
            case 'free':
                $q->where(function ($w) {
                    $w->whereNull('package_type')->orWhere('package_type', 'free');
                });
                break;
            case 'segera':
                $q->whereNotNull('subscription_expires_at')
                  ->whereBetween('subscription_expires_at', [now(), now()->addDays(7)->endOfDay()]);
                break;
        }

        return $q;
    }

    /**
     * Alamat beranda Super Admin, langsung ke panel notifikasi.
     */
    private function keBeranda(): string
    {
        return route('superadmin.dashboard') . '#notifikasi';
    }

    /**
     * Data untuk panel notifikasi di beranda Super Admin.
     * Dipanggil dari berandaSuperAdmin.blade.php.
     */
    public function dataBeranda(): array
    {
        $masjids = Mosque::orderBy('mosque_name')
            ->get(['id', 'mosque_name', 'city', 'package_type']);

        // Jumlah penerima untuk tiap pilihan target (ditampilkan di form)
        $jumlah = [
            'all'      => $this->targetQuery('all')->count(),
            'berbayar' => $this->targetQuery('berbayar')->count(),
            'free'     => $this->targetQuery('free')->count(),
            'segera'   => $this->targetQuery('segera')->count(),
        ];

        // Riwayat pengiriman, dikelompokkan per batch
        $riwayat = MosqueNotification::select(
                'batch_id',
                DB::raw('MIN(title) as title'),
                DB::raw('MIN(message) as message'),
                DB::raw('MIN(type) as type'),
                DB::raw('MAX(created_at) as dikirim'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN read_at IS NULL THEN 0 ELSE 1 END) as dibaca')
            )
            ->groupBy('batch_id')
            ->orderByDesc('dikirim')
            ->limit(30)
            ->get();

        return compact('masjids', 'jumlah', 'riwayat');
    }

    /**
     * Halaman notifikasi sekarang menyatu dengan beranda,
     * jadi alamat lama /superadmin/notifikasi dialihkan ke sana.
     */
    public function superIndex()
    {
        return redirect()->to($this->keBeranda());
    }

    public function superStore(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:120',
            'message'      => 'required|string|max:1000',
            'type'         => 'required|in:info,success,warning,danger',
            'target'       => 'required|in:all,berbayar,free,segera,pilih',
            'mosque_ids'   => 'required_if:target,pilih|array',
            'mosque_ids.*' => 'integer|exists:mosques,id',
            // hanya tautan internal ("/...") atau http(s), mencegah "javascript:..."
            'url'          => ['nullable', 'max:255', 'regex:/^(\/|https?:\/\/)/'],
        ], [
            'mosque_ids.required_if' => 'Pilih minimal satu masjid.',
            'url.regex'              => 'Tautan harus diawali "/" (halaman internal) atau "https://".',
        ]);

        $ids = $this->targetQuery($data['target'], $data['mosque_ids'] ?? [])->pluck('id');

        if ($ids->isEmpty()) {
            return redirect()->to($this->keBeranda())
                ->withInput()
                ->with('error', 'Tidak ada masjid yang sesuai dengan target tersebut.');
        }

        $batch = (string) Str::uuid();
        $now   = now();

        $rows = $ids->map(fn ($id) => [
            'batch_id'   => $batch,
            'mosque_id'  => $id,
            'title'      => $data['title'],
            'message'    => $data['message'],
            'type'       => $data['type'],
            'url'        => $data['url'] ?? null,
            'sent_by'    => Auth::id(),
            'read_at'    => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            MosqueNotification::insert($chunk);
        }

        return redirect()->to($this->keBeranda())
            ->with('success', 'Notifikasi berhasil dikirim ke ' . $ids->count() . ' masjid.');
    }

    public function superDestroy(string $batch)
    {
        MosqueNotification::where('batch_id', $batch)->delete();

        return redirect()->to($this->keBeranda())
            ->with('success', 'Notifikasi ditarik dari semua penerima.');
    }

    /* =============================================================
     |  ADMIN MASJID
     | ============================================================= */

    private function masjidSaya(): Mosque
    {
        $mosque = Mosque::where('user_id', Auth::id())->first();
        abort_unless($mosque, 404);

        return $mosque;
    }

    public function adminRead($id)
    {
        $mosque = $this->masjidSaya();

        // hanya boleh menandai notifikasi miliknya sendiri
        $notif = MosqueNotification::where('mosque_id', $mosque->id)->findOrFail($id);

        if (! $notif->read_at) {
            $notif->update(['read_at' => now()]);
        }

        return response()->json(['ok' => true]);
    }

    public function adminReadAll()
    {
        $mosque = $this->masjidSaya();

        MosqueNotification::where('mosque_id', $mosque->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}