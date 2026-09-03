<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use App\Models\Donasi;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonasiController extends Controller
{
    /**
     * Daftar kategori donasi.
     */
    protected function categories(): array
    {
        return [
            'zakat' => [
                'title' => 'Zakat',
                'desc'  => 'Zakat fitrah & zakat mal, wajib bagi yang memenuhi nisab dan haul.',
                'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            ],
            'infaq' => [
                'title' => 'Infaq',
                'desc'  => 'Pemberian sukarela rutin untuk mendukung kegiatan masjid sehari-hari.',
                'icon'  => '<path d="M12 3v18M5 8h14M5 16h14"/>',
            ],
            'sedekah' => [
                'title' => 'Sedekah',
                'desc'  => 'Sedekah bebas, bisa untuk siapa saja yang membutuhkan bantuan.',
                'icon'  => '<path d="M12 21s-7-4.6-9.5-9C1 8 3 4 7 4c2 0 4 1.5 5 3 1-1.5 3-3 5-3 4 0 6 4 4.5 8-2.5 4.4-9.5 9-9.5 9Z"/>',
            ],
            'pembangunan' => [
                'title' => 'Pembangunan Masjid',
                'desc'  => 'Mendukung renovasi dan perluasan bangunan masjid yang sedang berjalan.',
                'icon'  => '<path d="M4 21V10l8-6 8 6v11M9 21v-7h6v7"/>',
            ],
            'yatim' => [
                'title' => 'Santunan Yatim & Dhuafa',
                'desc'  => 'Bantuan rutin bagi anak yatim dan keluarga dhuafa binaan masjid.',
                'icon'  => '<circle cx="12" cy="8" r="3.2"/><path d="M5 21c0-4 3-6.5 7-6.5S19 17 19 21"/>',
            ],
            'bencana' => [
                'title' => 'Bantuan Bencana',
                'desc'  => 'Donasi cepat untuk bencana alam atau musibah terkini di sekitar kita.',
                'icon'  => '<path d="M13 2 3 14h7l-1 8 11-14h-7l0-6Z"/>',
            ],
            'wakaf' => [
                'title' => 'Wakaf',
                'desc'  => "Aset produktif jangka panjang seperti tanah, sumur, atau Al-Qur'an.",
                'icon'  => '<path d="M12 3v18M6 7h12M6 7c0 5-2 6-2 6h16s-2-1-2-6"/>',
            ],
            'qurban' => [
                'title' => 'Qurban',
                'desc'  => 'Tabungan atau donasi hewan qurban untuk Idul Adha mendatang.',
                'icon'  => '<circle cx="12" cy="13" r="7"/><path d="M8 8 6 4M16 8l2-4"/>',
            ],
            'lainnya' => [
                'title' => 'Donasi Bebas',
                'desc'  => 'Donasi tanpa kategori khusus, disalurkan sesuai kebutuhan masjid.',
                'icon'  => '<path d="M12 5v14M5 12h14"/>',
            ],
        ];
    }

    /**
     * GET /masjid/{masjid}/donasi
     * Menampilkan halaman pilih jenis donasi.
     */
    public function index(Mosque $masjid)
    {
        return view('donasi.donasi', [
            'masjid'             => $masjid,
            'categories'         => $this->categories(),
            'zakatFitrahDefault' => $masjid->zakat_fitrah_default ?? 45000,
        ]);
    }

    /**
     * POST /masjid/{masjid}/donasi
     * Menyimpan transaksi donasi.
     */
    public function store(Request $request, Mosque $masjid)
    {
        $data = $request->validate([
            'jenis'             => ['required', Rule::in(array_keys($this->categories()))],
            'zakat_subtype'     => ['nullable', Rule::in(['fitrah', 'mal'])],
            'nominal'           => ['required', 'numeric', 'min:1000'],
            'nama_donatur'      => ['nullable', 'string', 'max:100'],
            'metode_pembayaran' => ['required', Rule::in(['QRIS', 'Transfer Bank', 'Dompet Digital'])],
        ]);

        $noReferensi = 'DN-' . now()->format('ym') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);

        $donasi = Donasi::create([
            'masjid_id'         => $masjid->id,
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
            ->route('donasi.index', $masjid->slug)
            ->with('success', 'Donasi berhasil dicatat, nomor referensi: ' . $donasi->no_referensi);
    }
}