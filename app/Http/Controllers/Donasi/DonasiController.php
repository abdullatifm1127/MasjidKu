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
 * - Halaman publik donasi (GET) dirender oleh
 *   App\Http\Controllers\PublicMosqueController@showDonasi, BUKAN oleh index() di controller ini.
 *   index() dibiarkan ada untuk kompatibilitas/masa depan, tapi tidak terhubung ke route manapun.
 * - Yang benar-benar dipakai dari controller ini adalah store(), untuk menerima
 *   POST /masjid/{slug}/donasi dari donasi.js (AJAX fetch).
 * - Kolom memakai `mosque_id` sesuai migration tabel `donasis`.
 *   Pastikan $fillable di App\Models\Donasi memuat 'mosque_id' dan 'keterangan'.
 */
class DonasiController extends Controller
{
    /** Nominal minimum donasi (samakan dengan teks "Minimal donasi Rp 5.000" di halaman donasi). */
    private const MIN_NOMINAL = 5000;

    /**
     * Fitur donasi aktif jika paket bukan free, pembayaran sudah approved,
     * dan masa langganan belum berakhir. Aturannya sama dengan PublicMosqueController.
     */
    protected function hasDonationFeature(Mosque $mosque): bool
    {
        $berbayar = ($mosque->package_type ?? 'free') !== 'free'
            && strtolower(trim($mosque->payment_status ?? '')) === 'approved';

        if (!$berbayar) {
            return false;
        }

        $akhir = $mosque->package_expires_at ?? $mosque->subscription_expires_at;
        if ($akhir && \Carbon\Carbon::parse($akhir)->isPast()) {
            return false;
        }

        return true;
    }

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

    /** Nomor referensi unik, mis. DN-2610-04821. */
    protected function generateReference(): string
    {
        do {
            $no = 'DN-' . now()->format('ym') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Donasi::where('no_referensi', $no)->exists());

        return $no;
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

        // Tolak bila fitur donasi belum aktif (paket free / pembayaran belum disetujui / langganan habis)
        abort_unless($this->hasDonationFeature($mosque), 403, 'Fitur donasi belum aktif untuk masjid ini.');

        $validKeys = $this->categories($mosque)->pluck('key')->all();

        $data = $request->validate([
            'jenis'             => ['required', Rule::in($validKeys)],
            'zakat_subtype'     => ['nullable', Rule::in(['fitrah', 'mal'])],
            'nominal'           => ['required', 'numeric', 'min:' . self::MIN_NOMINAL],
            'nama_donatur'      => ['nullable', 'string', 'max:100'],
            'keterangan'        => ['nullable', 'string', 'max:200'],
            'metode_pembayaran' => ['required', Rule::in(['QRIS', 'Transfer Bank', 'Dompet Digital'])],
        ]);

        $donasi = Donasi::create([
            'mosque_id'         => $mosque->id,
            // Diambil dari sesi login saja (bukan dari request). Null jika donatur belum login.
            // Kolom user_id di tabel donasis harus nullable.
            'user_id'           => auth()->id(),
            'jenis'             => $data['jenis'],
            'zakat_subtype'     => $data['zakat_subtype'] ?? null,
            'nominal'           => $data['nominal'],
            'nama_donatur'      => $data['nama_donatur'] ?: 'Hamba Allah',
            'keterangan'        => isset($data['keterangan']) && trim($data['keterangan']) !== ''
                ? trim($data['keterangan'])
                : null,
            'metode_pembayaran' => $data['metode_pembayaran'],
            'no_referensi'      => $this->generateReference(),
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