<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\DonationCategory;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * CATATAN INTEGRASI:
 * - Halaman publik donasi (GET) sekarang dirender oleh
 *   App\Http\Controllers\PublicMosqueController@showDonasi, BUKAN oleh index() di controller ini.
 *   index() dibiarkan ada untuk kompatibilitas/masa depan, tapi tidak terhubung ke route manapun.
 * - Yang benar-benar dipakai dari controller ini adalah store(), untuk menerima
 *   POST /masjid/{slug}/donasi dari donasi.js (AJAX fetch).
 * - Signature diubah dari `Mosque $masjid` (route-model-binding) menjadi `string $slug`
 *   lalu resolve manual, supaya konsisten dengan pola yang sudah dipakai di
 *   PublicMosqueController@showDonasi (route Anda pakai segmen {slug}, bukan {masjid}).
 * - Kolom disamakan jadi `mosque_id` (bukan `masjid_id`) sesuai migration tabel `donasis`
 *   dan konfirmasi Anda. Pastikan $fillable di App\Models\Donasi juga memakai 'mosque_id'.
 */
class DonasiController extends Controller
{
    /**
     * Kategori donasi aktif milik satu masjid, diambil dari database
     * (dikelola admin lewat halaman "Kelola Jenis Donasi").
     */
    protected function categories(Mosque $mosque)
    {
        return DonationCategory::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * GET /masjid/{slug}/donasi
     * Tidak dipakai saat ini (lihat catatan di atas) — dibiarkan untuk kompatibilitas.
     */
    public function index(string $slug)
    {
        $mosque = Mosque::where('slug', $slug)->firstOrFail();
        $categories = $this->categories($mosque);

        return view('donasi.donasi', [
            'mosque'             => $mosque,
            'categories'         => $categories,
            'items'              => class_exists('\App\Models\DonasiGaleri')
                ? \App\Models\DonasiGaleri::where('mosque_id', $mosque->id)->latest()->get()
                : collect(),
            'zakatFitrahDefault' => $mosque->zakat_fitrah_default ?? 45000,
        ]);
    }

    /**
     * POST /masjid/{slug}/donasi
     * Menyimpan transaksi donasi. Dipanggil via AJAX oleh donasi.js.
     */
    public function store(Request $request, string $slug)
    {
        $mosque = Mosque::where('slug', $slug)->firstOrFail();

        $validKeys = $this->categories($mosque)->pluck('key')->all();

        $data = $request->validate([
            'jenis'             => ['required', Rule::in($validKeys)],
            'zakat_subtype'     => ['nullable', Rule::in(['fitrah', 'mal'])],
            'nominal'           => ['required', 'numeric', 'min:1000'],
            'nama_donatur'      => ['nullable', 'string', 'max:100'],
            'metode_pembayaran' => ['required', Rule::in(['QRIS', 'Transfer Bank', 'Dompet Digital'])],
        ]);

        $noReferensi = 'DN-' . now()->format('ym') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);

        $donasi = Donasi::create([
            'mosque_id'         => $mosque->id, // sudah dibetulkan dari 'masjid_id' -> 'mosque_id'
            'jenis'             => $data['jenis'],
            'zakat_subtype'     => $data['zakat_subtype'] ?? null,
            'nominal'           => $data['nominal'],
            'nama_donatur'      => $data['nama_donatur'] ?: 'Hamba Allah',
            'metode_pembayaran' => $data['metode_pembayaran'],
            'no_referensi'      => $noReferensi,
            'status'            => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'no_referensi' => $donasi->no_referensi,
            ]);
        }

        return redirect()
            ->route('masjid.donasi.publik', $mosque->slug)
            ->with('success', 'Donasi berhasil dicatat, nomor referensi: ' . $donasi->no_referensi);
    }
}