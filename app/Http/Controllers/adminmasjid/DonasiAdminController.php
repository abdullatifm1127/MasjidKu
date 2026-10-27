<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\DonasiGaleri;
use App\Models\DonationCategory;
use App\Models\Mosque;
use App\Models\MosqueBankAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DonasiAdminController extends Controller
{
    private const TABS = ['kategori', 'pengaturan', 'penyaluran', 'rekap'];

    /**
     * Masjid milik user yang sedang login.
     *
     * PENTING: model TIDAK boleh diubah atributnya di sini. Sebelumnya package_type
     * di-set 'paid' di memori, lalu $mosque->update([...]) di method lain ikut
     * menyimpannya ke database dan menimpa package_type asli (100000_1 / 1000000_12).
     */
    protected function mosque(): Mosque
    {
        return Mosque::where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * Fitur donasi aktif jika paket bukan free DAN pembayaran sudah approved
     * (baik lewat Midtrans maupun diaktifkan manual oleh super admin).
     */
    protected function hasDonationFeature(Mosque $mosque): bool
    {
        return ($mosque->package_type ?? 'free') !== 'free'
            && strtolower(trim($mosque->payment_status ?? '')) === 'approved';
    }

    /**
     * GET /admin/donasi            -> tab default "kategori"
     * GET /admin/donasi/{tab}      -> kategori | pengaturan | penyaluran | rekap
     */
    public function index(Request $request, string $tab = 'kategori')
    {
        abort_unless(in_array($tab, self::TABS, true), 404);

        $mosque = $this->mosque();

        // Paket masih free / pembayaran belum lunas -> arahkan ke halaman pembayaran
        if (!$this->hasDonationFeature($mosque)) {
            $packageType = session('pending_renewal')['package'] ?? $mosque->package_type ?? '100000_1';
            $paket = \App\Support\PackagePricing::resolve($packageType);

            return view('paymentmasjid.payment', [
                'mosque'          => $mosque,
                'packageName'     => $paket['name'],
                'amountFormatted' => 'Rp ' . number_format($paket['amount'], 0, ',', '.'),
            ]);
        }

        $donationCategories = DonationCategory::where('mosque_id', $mosque->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        $categoryTitles = $donationCategories->pluck('title', 'key');

        // Uang masuk (donasi diterima) per jenis/kategori
        $masukPerJenis = Donasi::where('mosque_id', $mosque->id)
            ->where('status', 'diterima')
            ->selectRaw('jenis, SUM(nominal) as total')
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        // Uang keluar (penyaluran) per kategori
        $keluarPerKategori = DonasiGaleri::where('mosque_id', $mosque->id)
            ->selectRaw('kategori, SUM(nominal_terpakai) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        // Riwayat per kategori (dipakai tab Kategori untuk edit uang masuk/keluar)
        $riwayatKeluar = collect();
        $riwayatMasuk  = collect();
        if ($tab === 'kategori') {
            $riwayatKeluar = DonasiGaleri::where('mosque_id', $mosque->id)
                ->latest('tanggal')->latest('id')->get()
                ->groupBy(fn ($i) => strtolower(trim((string) $i->kategori)));

            // Hanya donasi yang dicatat manual (MNL-) yang boleh diedit/dihapus dari sini
            $riwayatMasuk = Donasi::where('mosque_id', $mosque->id)
                ->where('no_referensi', 'like', 'MNL-%')
                ->latest()->get()
                ->groupBy('jenis');
        }

        $data = [
            'mosque'             => $mosque,
            'tab'                => $tab,
            'riwayatKeluar'      => $riwayatKeluar,
            'riwayatMasuk'       => $riwayatMasuk,
            'donationCategories' => $donationCategories,
            'bankAccounts'       => $mosque->bankAccounts,
            'masukPerJenis'      => $masukPerJenis,
            'keluarPerKategori'  => $keluarPerKategori,
            'summary'            => [
                'total_diterima'    => (int) Donasi::where('mosque_id', $mosque->id)->where('status', 'diterima')->sum('nominal'),
                'total_menunggu'    => (int) Donasi::where('mosque_id', $mosque->id)->where('status', 'pending')->sum('nominal'),
                'jumlah_donatur'    => Donasi::where('mosque_id', $mosque->id)->where('status', 'diterima')->count(),
                'total_tersalurkan' => (int) DonasiGaleri::where('mosque_id', $mosque->id)->sum('nominal_terpakai'),
            ],
        ];

        // ---- Tab: Penyaluran ----
        if ($tab === 'penyaluran') {
            $data['items'] = DonasiGaleri::where('mosque_id', $mosque->id)
                ->latest('tanggal')
                ->get();
        }

        // ---- Tab: Rekap / Pelaporan ----
        if ($tab === 'rekap') {
            $request->validate([
                'status'   => ['nullable', Rule::in(['diterima', 'ditolak', 'pending'])],
                'kategori' => ['nullable', 'string', 'max:50'],
                'dari'     => ['nullable', 'date'],
                'sampai'   => ['nullable', 'date'],
            ]);

            $base = Donasi::where('mosque_id', $mosque->id)
                ->when($request->filled('kategori'), fn ($q) => $q->where('jenis', $request->kategori))
                ->when($request->filled('dari'), fn ($q) => $q->whereDate('created_at', '>=', $request->dari))
                ->when($request->filled('sampai'), fn ($q) => $q->whereDate('created_at', '<=', $request->sampai));

            $donations = (clone $base)
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(15)
                ->withQueryString();

            $donations->getCollection()->transform(function ($d) use ($categoryTitles) {
                $d->category_title = $categoryTitles[$d->jenis] ?? $d->jenis;
                return $d;
            });

            // Rekap per kategori (hanya donasi yang sudah diterima)
            $rekapKategori = (clone $base)
                ->where('status', 'diterima')
                ->selectRaw('jenis, COUNT(*) as jumlah, SUM(nominal) as total')
                ->groupBy('jenis')
                ->orderByDesc('total')
                ->get()
                ->map(fn ($r) => [
                    'title'  => $categoryTitles[$r->jenis] ?? $r->jenis,
                    'jumlah' => (int) $r->jumlah,
                    'total'  => (int) $r->total,
                ]);

            $data['donations']     = $donations;
            $data['rekapKategori'] = $rekapKategori;
        }

        return view('auth.adminmasjid.donasiAdmin', $data);
    }

    /**
     * PUT /admin/donasi/pengaturan
     */
    public function updateSettings(Request $request)
    {
        $mosque = $this->mosque();

        $data = $request->validate([
            'zakat_fitrah_default' => ['required', 'numeric', 'min:0'],
            'zakat_nisab'          => ['required', 'numeric', 'min:0'],
        ]);

        $mosque->update([
            'zakat_fitrah_default' => $data['zakat_fitrah_default'],
            'zakat_nisab'          => $data['zakat_nisab'],
        ]);

        return back()->with('success', 'Pengaturan zakat fitrah berhasil disimpan.');
    }

    /**
     * Alias untuk mengantisipasi pemanggilan rute updatePengaturan
     */
    public function updatePengaturan(Request $request)
    {
        return $this->updateSettings($request);
    }

    // ==========================================
    // GALERI / DOKUMENTASI PENYALURAN
    // ==========================================

    /**
     * POST /admin/donasi/galeri
     */
    public function storeGaleri(Request $request)
    {
        $mosque = $this->mosque();

        $data = $request->validate([
            'judul'            => 'required|string|max:150',
            'kategori'         => 'nullable|string|max:50',
            'deskripsi'        => 'nullable|string|max:1000',
            'tujuan'           => 'nullable|string|max:150',
            'tanggal'          => 'required|date|before_or_equal:today',
            'nominal_terpakai' => 'nullable|numeric|min:0',
            'foto'             => 'required|image|max:4096',
        ]);

        $path = $request->file('foto')->store('donasi-galeri', 'public');

        DonasiGaleri::create([
            'mosque_id'        => $mosque->id,
            'judul'            => $data['judul'],
            'kategori'         => $data['kategori'] ?: null,
            'deskripsi'        => $data['deskripsi'] ?? null,
            'tujuan'           => $data['tujuan'] ?? null,
            'tanggal'          => $data['tanggal'],
            'nominal_terpakai' => $data['nominal_terpakai'] ?? null,
            'foto'             => $path,
        ]);

        return back()->with('success', 'Foto realisasi donasi berhasil ditambahkan.');
    }

    /**
     * PUT /admin/donasi/galeri/{id}
     */
    public function updateGaleri(Request $request, int $id)
    {
        $mosque = $this->mosque();
        $item = DonasiGaleri::where('mosque_id', $mosque->id)->findOrFail($id);

        // Buang baris rincian yang kosong total
        $request->merge([
            'rincian' => collect($request->input('rincian', []))
                ->filter(fn ($r) => filled($r['label'] ?? null) || filled($r['nominal'] ?? null))
                ->values()
                ->all(),
        ]);

        $data = $request->validate([
            'judul'                => 'required|string|max:150',
            'kategori'             => 'nullable|string|max:50',
            'deskripsi'            => 'nullable|string|max:1000',
            'tujuan'               => 'nullable|string|max:150',
            'tanggal'              => 'required|date|before_or_equal:today',
            'nominal_terpakai'     => 'nullable|numeric|min:0',
            'foto'                 => 'nullable|image|max:4096',
            'rincian'              => 'nullable|array',
            'rincian.*.label'      => 'required|string|max:150',
            'rincian.*.nominal'    => 'required|numeric|min:0',
            'rincian.*.keterangan' => 'nullable|string|max:200',
        ]);

        $rincian = collect($data['rincian'] ?? [])->map(fn ($r) => [
            'label'      => $r['label'],
            'nominal'    => (float) $r['nominal'],
            'keterangan' => $r['keterangan'] ?? null,
        ])->values()->all();

        // Uang keluar = jumlah rincian (jika ada rincian). Jika tidak, pakai isian manual.
        $totalKeluar = count($rincian)
            ? collect($rincian)->sum('nominal')
            : ($data['nominal_terpakai'] ?? null);

        if ($request->hasFile('foto')) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            $item->foto = $request->file('foto')->store('donasi-galeri', 'public');
        }

        $item->fill([
            'judul'            => $data['judul'],
            'kategori'         => $data['kategori'] ?: null,
            'deskripsi'        => $data['deskripsi'] ?? null,
            'tujuan'           => $data['tujuan'] ?? null,
            'tanggal'          => $data['tanggal'],
            'nominal_terpakai' => $totalKeluar,
            'rincian'          => $rincian ?: null,
        ])->save();

        return back()->with('success', 'Data penyaluran berhasil diperbarui.');
    }

    /**
     * DELETE /admin/donasi/galeri/{id}
     */
    public function destroyGaleri(int $id)
    {
        $mosque = $this->mosque();

        $item = DonasiGaleri::where('mosque_id', $mosque->id)->findOrFail($id);

        // Foto boleh kosong (penyaluran yang dicatat tanpa foto)
        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }
        $item->delete();

        return back()->with('success', 'Data penyaluran dihapus.');
    }

    // ==========================================
    // CATAT UANG MASUK & KELUAR PER KATEGORI
    // ==========================================

    /**
     * POST /admin/donasi/kategori/{id}/uang-masuk
     * Catat donasi tunai/manual (langsung berstatus diterima).
     */
    public function storeUangMasuk(Request $request, int $id)
    {
        $mosque = $this->mosque();
        $cat = DonationCategory::where('mosque_id', $mosque->id)->findOrFail($id);

        $data = $request->validate([
            'nama_donatur'      => ['nullable', 'string', 'max:100'],
            'nominal'           => ['required', 'numeric', 'min:1000'],
            'tanggal'           => ['required', 'date', 'before_or_equal:today'],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
            'keterangan'        => ['nullable', 'string', 'max:500'],
        ]);

        // forceFill supaya aman dari $fillable pada model Donasi
        $donasi = new Donasi();
        $donasi->forceFill([
            'mosque_id'         => $mosque->id,
            'jenis'             => $cat->key,
            'nominal'           => $data['nominal'],
            'status'            => 'diterima',
            'no_referensi'      => 'MNL-' . strtoupper(Str::random(8)),
            'nama_donatur'      => $data['nama_donatur'] ?: 'Hamba Allah',
            'metode_pembayaran' => $data['metode_pembayaran'],
            'keterangan'        => $data['keterangan'] ?? null,
            'created_at'        => Carbon::parse($data['tanggal'])->setTimeFrom(now()),
        ])->save();

        return back()->with('success', 'Uang masuk untuk "' . $cat->title . '" berhasil dicatat.');
    }

    /**
     * POST /admin/donasi/kategori/{id}/penyaluran
     * Catat uang keluar / penyaluran (foto opsional).
     */
    public function storePenyaluran(Request $request, int $id)
    {
        $mosque = $this->mosque();
        $cat = DonationCategory::where('mosque_id', $mosque->id)->findOrFail($id);

        $data = $request->validate([
            'judul'            => ['nullable', 'string', 'max:150'],
            'tanggal'          => ['required', 'date', 'before_or_equal:today'],
            'nominal_terpakai' => ['required', 'numeric', 'min:1'],
            'tujuan'           => ['nullable', 'string', 'max:150'],
            'deskripsi'        => ['nullable', 'string', 'max:1000'],
            'foto'             => ['nullable', 'image', 'max:4096'],
        ]);

        // Cegah uang keluar melebihi sisa dana program
        $masuk = (float) Donasi::where('mosque_id', $mosque->id)
            ->where('jenis', $cat->key)
            ->where('status', 'diterima')
            ->sum('nominal');

        $keluar = (float) DonasiGaleri::where('mosque_id', $mosque->id)
            ->where('kategori', $cat->key)
            ->sum('nominal_terpakai');

        $sisa = $masuk - $keluar;

        if ($data['nominal_terpakai'] > $sisa) {
            return back()->withInput()->withErrors([
                'nominal_terpakai' => 'Nominal melebihi sisa dana program "' . $cat->title
                    . '" (Rp ' . number_format(max($sisa, 0), 0, ',', '.') . '). Catat uang masuk terlebih dahulu.',
            ]);
        }

        DonasiGaleri::create([
            'mosque_id'        => $mosque->id,
            'judul'            => $data['judul'] ?: 'Penyaluran ' . $cat->title,
            'kategori'         => $cat->key,
            'deskripsi'        => $data['deskripsi'] ?? null,
            'tujuan'           => $data['tujuan'] ?: 'Penyaluran Tunai / Kas Masjid',
            'tanggal'          => $data['tanggal'],
            'nominal_terpakai' => $data['nominal_terpakai'],
            'foto'             => $request->hasFile('foto')
                                    ? $request->file('foto')->store('donasi-galeri', 'public')
                                    : null,
        ]);

        return back()->with('success', 'Penyaluran "' . $cat->title . '" berhasil dicatat.');
    }

    /**
     * PUT /admin/donasi/uang-masuk/{id}
     * Edit donasi yang dicatat manual (no. referensi MNL-...).
     */
    public function updateUangMasuk(Request $request, int $id)
    {
        $mosque = $this->mosque();
        $donasi = Donasi::where('mosque_id', $mosque->id)
            ->where('no_referensi', 'like', 'MNL-%')
            ->findOrFail($id);

        $data = $request->validate([
            'nama_donatur'      => ['nullable', 'string', 'max:100'],
            'nominal'           => ['required', 'numeric', 'min:1000'],
            'tanggal'           => ['required', 'date', 'before_or_equal:today'],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
            'keterangan'        => ['nullable', 'string', 'max:500'],
        ]);

        // Uang masuk baru tidak boleh lebih kecil dari uang yang sudah keluar
        $masukLain = (float) Donasi::where('mosque_id', $mosque->id)
            ->where('jenis', $donasi->jenis)->where('status', 'diterima')
            ->where('id', '!=', $donasi->id)->sum('nominal');
        $keluar = (float) DonasiGaleri::where('mosque_id', $mosque->id)
            ->where('kategori', $donasi->jenis)->sum('nominal_terpakai');

        if ($donasi->status === 'diterima' && ($masukLain + $data['nominal']) < $keluar) {
            return back()->withInput()->withErrors([
                'nominal' => 'Uang masuk tidak boleh lebih kecil dari uang yang sudah disalurkan (Rp '
                    . number_format($keluar, 0, ',', '.') . ').',
            ]);
        }

        $donasi->forceFill([
            'nama_donatur'      => $data['nama_donatur'] ?: 'Hamba Allah',
            'nominal'           => $data['nominal'],
            'metode_pembayaran' => $data['metode_pembayaran'],
            'keterangan'        => $data['keterangan'] ?? null,
            'created_at'        => Carbon::parse($data['tanggal'])->setTimeFrom($donasi->created_at ?? now()),
        ])->save();

        return back()->with('success', 'Uang masuk ' . $donasi->no_referensi . ' berhasil diperbarui.');
    }

    /**
     * DELETE /admin/donasi/uang-masuk/{id}
     */
    public function destroyUangMasuk(int $id)
    {
        $mosque = $this->mosque();
        $donasi = Donasi::where('mosque_id', $mosque->id)
            ->where('no_referensi', 'like', 'MNL-%')
            ->findOrFail($id);

        $masukLain = (float) Donasi::where('mosque_id', $mosque->id)
            ->where('jenis', $donasi->jenis)->where('status', 'diterima')
            ->where('id', '!=', $donasi->id)->sum('nominal');
        $keluar = (float) DonasiGaleri::where('mosque_id', $mosque->id)
            ->where('kategori', $donasi->jenis)->sum('nominal_terpakai');

        if ($donasi->status === 'diterima' && $masukLain < $keluar) {
            return back()->withErrors([
                'nominal' => 'Tidak bisa dihapus: dana program akan lebih kecil dari uang yang sudah disalurkan.',
            ]);
        }

        $donasi->delete();

        return back()->with('success', 'Catatan uang masuk dihapus.');
    }

    // ==========================================
    // STATUS DONASI
    // ==========================================

    /**
     * PATCH /admin/donasi/{donasi}/status
     */
    public function updateStatus(Request $request, Donasi $donasi)
    {
        $mosque = $this->mosque();
        abort_unless($donasi->mosque_id === $mosque->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['diterima', 'ditolak', 'pending'])],
        ]);

        $donasi->update(['status' => $data['status']]);

        return back()->with('success', 'Status donasi ' . $donasi->no_referensi . ' diperbarui menjadi "' . $data['status'] . '".');
    }

    // ==========================================
    // MANAJEMEN REKENING BANK & QRIS
    // ==========================================

    /**
     * POST /admin/donasi/rekening
     */
    public function storeBankAccount(Request $request)
    {
        $mosque = $this->mosque();

        $data = $request->validate([
            'bank_name'      => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ]);

        MosqueBankAccount::create([
            'mosque_id'      => $mosque->id,
            'bank_name'      => $data['bank_name'],
            'account_number' => $data['account_number'],
            'account_holder' => $data['account_holder'],
            'sort_order'     => (MosqueBankAccount::where('mosque_id', $mosque->id)->max('sort_order') ?? -1) + 1,
            'is_active'      => true,
        ]);

        return back()->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    /**
     * PUT /admin/donasi/rekening/{id}
     */
    public function updateBankAccount(Request $request, int $id)
    {
        $mosque = $this->mosque();
        $rekening = MosqueBankAccount::where('mosque_id', $mosque->id)->findOrFail($id);

        $data = $request->validate([
            'bank_name'      => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ]);

        $rekening->update($data);

        return back()->with('success', 'Rekening bank berhasil diperbarui.');
    }

    /**
     * PATCH /admin/donasi/rekening/{id}/toggle
     */
    public function toggleBankAccount(int $id)
    {
        $mosque = $this->mosque();
        $rekening = MosqueBankAccount::where('mosque_id', $mosque->id)->findOrFail($id);

        $rekening->update(['is_active' => ! $rekening->is_active]);

        return back()->with('success', $rekening->is_active ? 'Rekening diaktifkan.' : 'Rekening dinonaktifkan.');
    }

    /**
     * DELETE /admin/donasi/rekening/{id}
     */
    public function destroyBankAccount(int $id)
    {
        $mosque = $this->mosque();
        $rekening = MosqueBankAccount::where('mosque_id', $mosque->id)->findOrFail($id);

        $rekening->delete();

        return back()->with('success', 'Rekening bank dihapus.');
    }

    /**
     * POST /admin/donasi/qris
     */
    public function updateQris(Request $request)
    {
        $mosque = $this->mosque();

        $request->validate([
            'qris_image' => ['required', 'image', 'max:2048'], // maks 2MB
        ]);

        // Hapus file QRIS lama supaya storage tidak menumpuk
        if ($mosque->qris_image) {
            Storage::disk('public')->delete($mosque->qris_image);
        }

        $path = $request->file('qris_image')->store('qris', 'public');
        $mosque->update(['qris_image' => $path]);

        return back()->with('success', 'Gambar QRIS berhasil diperbarui.');
    }

    /**
     * DELETE /admin/donasi/qris
     */
    public function destroyQris()
    {
        $mosque = $this->mosque();

        if ($mosque->qris_image) {
            Storage::disk('public')->delete($mosque->qris_image);
            $mosque->update(['qris_image' => null]);
        }

        return back()->with('success', 'Gambar QRIS dihapus.');
    }
}