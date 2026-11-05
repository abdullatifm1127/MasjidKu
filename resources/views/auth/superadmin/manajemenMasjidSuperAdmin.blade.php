{{--
    Manajemen Masjid (Super Admin)

    Variabel dari controller (yang sudah ada):
      $masjids, $totalSemua, $totalAktif, $totalPending, $totalNonaktif
    Variabel baru (opsional, ada cadangan di bawah):
      $keuangan  => App\Support\FinanceStats::build()      (ringkasan pendapatan platform)
      $trx       => App\Support\FinanceStats::transaksi()  (rincian tiap pembayaran)

    Semua gaya ada di css/superadmin/manajemenMasjidSuperAdmin.css (tidak ada <style> inline).
--}}
@php
    use Illuminate\Support\Str;

    $authUser = auth()->user();
    $initials = Str::upper(Str::substr($authUser->name ?? 'SA', 0, 2));

    // Cadangan: kalau controller belum mengirim $keuangan, hitung di sini.
    // Sebaiknya pindahkan ke controller lalu hapus dua baris ini.
    $keuangan = $keuangan ?? \App\Support\FinanceStats::build($masjids);
    $trx      = $trx ?? \App\Support\FinanceStats::transaksi();

    // Nama paket yang mudah dibaca ("1 Bulan", "1 Tahun") dari kode paket
    $cacheNamaPaket = [];
    $namaPaket = function ($kode) use (&$cacheNamaPaket) {
        $kode = (string) $kode;
        if ($kode === '') { return '-'; }
        if (!isset($cacheNamaPaket[$kode])) {
            $info = \App\Support\PackagePricing::resolve(strtolower($kode));
            $b = (int) ($info['months'] ?? 0);
            $cacheNamaPaket[$kode] = $b > 0 ? ($b % 12 === 0 ? ($b / 12) . ' Tahun' : $b . ' Bulan') : $kode;
        }
        return $cacheNamaPaket[$kode];
    };

    $rp  = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $sub = $keuangan['sub'];

    /**
     * Tanggal berakhir terakhir dari riwayat transaksi (tabel subscriptions),
     * cadangan jika kolom di tabel masjid belum terisi. Satu query untuk semua masjid.
     */
    $akhirDariRiwayat = \App\Models\Subscription::whereIn('mosque_id', collect($masjids)->pluck('id'))
        ->whereIn('status', ['paid', 'approved'])
        ->whereNotNull('expired_date')
        ->selectRaw('mosque_id, MAX(expired_date) as akhir')
        ->groupBy('mosque_id')
        ->pluck('akhir', 'mosque_id');

    $cachePaket = [];

    /**
     * Status langganan satu masjid.
     * kode : aktif | segera | habis | menunggu | free
     * grup : berlangganan | tidak | habis   (dipakai untuk filter)
     */
    $hitungLangganan = function ($m) use ($akhirDariRiwayat, &$cachePaket) {
        $status   = strtolower((string) ($m->payment_status ?? ''));
        $paket    = strtolower((string) ($m->package_type ?? ''));
        $lunas    = in_array($status, ['approved', 'paid', 'settlement', 'capture', 'lunas'], true);
        $berbayar = $paket !== '' && $paket !== 'free';

        $akhirRaw = data_get($m, 'subscription_expires_at')
            ?: data_get($m, 'package_expires_at')
            ?: ($akhirDariRiwayat[$m->id] ?? null);
        $akhir = $akhirRaw ? \Illuminate\Support\Carbon::parse($akhirRaw) : null;
        $sisa  = $akhir ? (int) now()->startOfDay()->diffInDays($akhir->copy()->startOfDay(), false) : null;

        $paketLabel = null;
        if ($berbayar) {
            if (!isset($cachePaket[$paket])) {
                $cachePaket[$paket] = \App\Support\PackagePricing::resolve($paket);
            }
            $info  = $cachePaket[$paket];
            $bulan = (int) ($info['months'] ?? 0);

            if ($bulan > 0) {
                $lama = $bulan % 12 === 0 ? ($bulan / 12) . ' Tahun' : $bulan . ' Bulan';
                $paketLabel = $lama . ' · Rp ' . number_format((int) $info['amount'], 0, ',', '.');
            }
        }

        if ($status === 'pending') {
            $kode = 'menunggu'; $label = 'Menunggu verifikasi'; $grup = 'tidak';
        } elseif ($lunas && $berbayar && !($akhir && $akhir->isPast())) {
            $kode  = ($sisa !== null && $sisa <= 7) ? 'segera' : 'aktif';
            $label = $kode === 'segera' ? 'Segera berakhir' : 'Berlangganan';
            $grup  = 'berlangganan';
        } elseif ($akhir && $akhir->isPast()) {
            $kode = 'habis'; $label = 'Berakhir'; $grup = 'habis';
        } else {
            $kode = 'free'; $label = 'Tidak berlangganan'; $grup = 'tidak';
        }

        return compact('kode', 'label', 'grup', 'akhir', 'sisa', 'paketLabel');
    };

    $lg = [];
    foreach ($masjids as $mm) { $lg[$mm->id] = $hitungLangganan($mm); }

    $nBerlangganan = count(array_filter($lg, fn ($x) => $x['grup'] === 'berlangganan'));
    $nTidak        = count(array_filter($lg, fn ($x) => $x['grup'] === 'tidak'));
    $nHabis        = count(array_filter($lg, fn ($x) => $x['grup'] === 'habis'));
    $nSegera       = count(array_filter($lg, fn ($x) => $x['kode'] === 'segera'));

    $navItems = [
        ['superadmin.dashboard',          'superadmin.dashboard',          'fa-table-cells-large', 'Dashboard'],
        ['superadmin.verifikasi',         'superadmin.verifikasi*',        'fa-shield-halved',     'Verifikasi Pendaftaran'],
        ['superadmin.manajemen-masjid',   'superadmin.manajemen-masjid*',  'fa-mosque',            'Manajemen Masjid'],
        ['superadmin.pembayaran.index',   'superadmin.pembayaran*',        'fa-credit-card',       'Pembayaran'],
        ['superadmin.pengguna',           'superadmin.pengguna*',          'fa-users',             'Pengguna'],
        ['superadmin.halaman-utama.edit', 'superadmin.halaman-utama*',     'fa-pen-to-square',     'Halaman Utama'],
        ['superadmin.themes.index',       'superadmin.themes*',            'fa-palette',           'Manajemen Tema'],
        ['superadmin.pengaturan',         'superadmin.pengaturan*',        'fa-gear',              'Pengaturan'],
    ];

    $modalTerbuka = $errors->any() && old('mosque_name') !== null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Masjid - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    {{-- ?v=filemtime memaksa browser memuat ulang CSS setiap file berubah --}}
    <link rel="stylesheet" href="{{ asset('css/superadmin/manajemenMasjidSuperAdmin.css') }}?v={{ @filemtime(public_path('css/superadmin/manajemenMasjidSuperAdmin.css')) }}">
</head>
<body class="sa-page" id="saBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sa-sidebar" id="saSidebar">
        <div class="sa-brand">
            <div class="sa-brand-avatar">SA</div>
            <div class="sa-brand-info">
                <strong>MasjidKu Admin</strong>
                <span>Super Administrator</span>
            </div>
        </div>

        <nav class="sa-nav">
            @foreach($navItems as [$routeName, $match, $icon, $label])
                @php $badge = $routeName === 'superadmin.verifikasi' && $totalPending > 0; @endphp
                <a href="{{ route($routeName) }}"
                   class="sa-nav-item {{ $badge ? 'sa-nav-has-badge' : '' }} {{ request()->routeIs($match) ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="fa-solid {{ $icon }}"></i></span>
                    <span class="sa-nav-label">{{ $label }}</span>
                    @if($badge)<span class="sa-nav-badge-dot amber" title="{{ $totalPending }} masjid pending"></span>@endif
                </a>
            @endforeach
        </nav>

        <div class="sa-user-footer">
            <div class="sa-user-avatar-sm">{{ $initials }}</div>
            <div class="sa-user-info">
                <div class="sa-user-name">{{ $authUser->name }}</div>
                <div class="sa-user-email">{{ $authUser->email }}</div>
            </div>
            <a href="{{ route('logout') }}" class="sa-logout-btn"
               onclick="event.preventDefault(); document.getElementById('sa-logout-form').submit();"
               aria-label="Logout">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
            <form id="sa-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="sa-main" id="saMain">

        <header class="sa-topbar">
            <div class="sa-topbar-title">Manajemen Masjid</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank" rel="noopener">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    Lihat Website
                </a>
                <a href="{{ route('superadmin.verifikasi') }}" class="sa-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    @if($totalPending > 0)<span class="sa-notif-dot"></span>@endif
                </a>
            </div>
        </header>

        <main class="sa-content mm-content">

            @if(session('success'))
                <div class="mm-alert mm-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mm-alert mm-alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ===== KEUANGAN PLATFORM (khusus Super Admin) ===== --}}
            @php
                $selisih = null;
                if ($sub['ok'] && $sub['bulan_lalu'] > 0) {
                    $selisih = (int) round(($sub['bulan_ini'] - $sub['bulan_lalu']) / $sub['bulan_lalu'] * 100);
                }
            @endphp

            <div class="mk-head">
                <h2 class="mk-section-title">Keuangan Platform</h2>
                <span>
                    <a href="#rincian-pembayaran" class="mk-link">Rincian tiap pembayaran ↓</a>
                    &nbsp;·&nbsp;
                    <a href="{{ route('superadmin.riwayat-pembayaran') }}" class="mk-link">Riwayat pembayaran →</a>
                </span>
            </div>

            @if(!$sub['ok'])
                <div class="mk-warn">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Data keuangan belum bisa dibaca: {{ $sub['reason'] }}.
                    Sesuaikan nama tabel/kolom di <code>App\Support\FinanceStats</code>.
                </div>
            @endif

            <div class="mk-cards">
                <div class="mk-card {{ $sub['ok'] ? 'green' : 'muted' }}">
                    <div class="mk-card-label">Total pendapatan langganan</div>
                    <div class="mk-card-val">{{ $sub['ok'] ? $rp($sub['total']) : '-' }}</div>
                    <div class="mk-card-sub">{{ $sub['ok'] ? number_format($sub['count'], 0, ',', '.') . ' transaksi lunas' : 'Data belum tersedia' }}</div>
                </div>

                <div class="mk-card {{ $sub['ok'] ? 'green' : 'muted' }}">
                    <div class="mk-card-label">Pendapatan bulan ini</div>
                    <div class="mk-card-val">{{ $sub['ok'] ? $rp($sub['bulan_ini']) : '-' }}</div>
                    <div class="mk-card-sub">
                        @if($sub['ok'])
                            Bulan lalu {{ $rp($sub['bulan_lalu']) }}
                            @if($selisih !== null)
                                · <span class="{{ $selisih >= 0 ? 'mk-up' : 'mk-down' }}">{{ $selisih >= 0 ? '▲' : '▼' }} {{ abs($selisih) }}%</span>
                            @endif
                        @else
                            Data belum tersedia
                        @endif
                    </div>
                </div>

                <div class="mk-card">
                    <div class="mk-card-label">Masjid berlangganan aktif</div>
                    <div class="mk-card-val">{{ $keuangan['berbayar'] }} <span class="mk-card-unit">dari {{ $totalSemua }} masjid</span></div>
                    <div class="mk-card-sub">{{ $keuangan['gratis'] }} paket gratis · {{ $nSegera }} segera berakhir (7 hari)</div>
                </div>

                <div class="mk-card {{ $sub['ok'] && $sub['pending'] > 0 ? 'amber' : ($sub['ok'] ? '' : 'muted') }}">
                    <div class="mk-card-label">Pembayaran menunggu verifikasi</div>
                    <div class="mk-card-val">{{ $sub['ok'] ? $sub['pending'] : '-' }}</div>
                    <div class="mk-card-sub">Transaksi langganan berstatus pending</div>
                </div>
            </div>

            @if($sub['ok'] && count($sub['per_paket']) > 0)
                <div class="mk-paket">
                    <span class="mk-paket-title">Pendapatan per paket:</span>
                    @foreach($sub['per_paket'] as $pk)
                        <button type="button" class="mk-chip" data-rdpaket="{{ $pk['paket'] }}" title="Lihat transaksi paket ini">
                            <b>{{ $namaPaket($pk['paket']) }}</b>
                            @if(strtolower($namaPaket($pk['paket'])) !== strtolower($pk['paket']) && $pk['paket'] !== '')
                                <small>({{ $pk['paket'] }})</small>
                            @endif
                            · {{ $pk['count'] }}x · {{ $rp($pk['total']) }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="mk-note">
                Angka di atas adalah <b>pendapatan platform</b> dari langganan masjid. Donasi yang diterima
                masing-masing masjid tidak dihitung di sini.
            </div>

            {{-- ===== TOOLBAR ===== --}}
            <div class="mm-toolbar">
                <div class="mm-filter-tabs">
                    <button type="button" class="mm-filter-tab active" data-filter="semua">Semua ({{ $totalSemua }})</button>
                    <button type="button" class="mm-filter-tab" data-filter="aktif">Aktif ({{ $totalAktif }})</button>
                    <button type="button" class="mm-filter-tab" data-filter="pending">Pending ({{ $totalPending }})</button>
                    <button type="button" class="mm-filter-tab" data-filter="nonaktif">Nonaktif ({{ $totalNonaktif }})</button>
                </div>

                <div class="mm-toolbar-right">
                    <div class="mm-search-wrap">
                        <i class="fa-solid fa-magnifying-glass mm-search-icon"></i>
                        <input type="text" id="mmSearch" class="mm-search" placeholder="Cari masjid atau kota...">
                    </div>
                    <button type="button" class="mm-btn-tambah" id="mmTambahBtn">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Masjid
                    </button>
                </div>
            </div>

            {{-- ===== FILTER LANGGANAN ===== --}}
            <div class="mm-lg-bar">
                <span class="mm-lg-label">Langganan:</span>
                <button type="button" class="mm-lg-tab active" data-lg="semua">Semua</button>
                <button type="button" class="mm-lg-tab" data-lg="berlangganan">Berlangganan ({{ $nBerlangganan }})</button>
                <button type="button" class="mm-lg-tab" data-lg="tidak">Tidak berlangganan ({{ $nTidak }})</button>
                <button type="button" class="mm-lg-tab" data-lg="habis">Berakhir ({{ $nHabis }})</button>
                @if($nSegera > 0)
                    <span class="mm-lg-warn"><i class="fa-solid fa-triangle-exclamation"></i> {{ $nSegera }} masjid segera berakhir</span>
                @endif
            </div>

            {{-- ===== TABEL ===== --}}
            <div class="mm-table-wrap">
                <table class="mm-table" id="mmTable">
                    <thead>
                        <tr>
                            <th class="mm-th-masjid">Masjid</th>
                            <th class="mm-th-kota">Kota</th>
                            <th class="mm-th-imam">Imam</th>
                            <th class="mm-th-status">Status</th>
                            <th class="mm-th-langganan">Langganan</th>
                            <th class="mm-th-keuangan">Total Bayar</th>
                            <th class="mm-th-donasi">Donasi</th>
                            <th class="mm-th-aksi"></th>
                        </tr>
                    </thead>
                    <tbody id="mmTbody">

                        @foreach($masjids as $m)
                            @php
                                $statusClass = match (true) {
                                    in_array($m->status, ['approved', 'aktif'], true) => 'aktif',
                                    $m->status === 'rejected'                         => 'nonaktif',
                                    default                                           => $m->status ?: 'pending',
                                };

                                $l   = $lg[$m->id];
                                $pct = max(0, min(100, (int) ($m->persentase ?? 0)));

                                $bayarMasjid = (float) ($sub['per_mosque'][$m->id] ?? 0);
                                $bayarCount  = (int) ($sub['per_mosque_count'][$m->id] ?? 0);
                            @endphp
                            <tr class="mm-row"
                                data-status="{{ $statusClass }}"
                                data-langganan="{{ $l['grup'] }}"
                                data-search="{{ mb_strtolower($m->mosque_name . ' ' . $m->city) }}">
                                <td class="mm-td-masjid">
                                    <div class="mm-mosque-cell">
                                        <div class="mm-mosque-avatar">{{ Str::upper(Str::substr($m->mosque_name, 0, 1)) }}</div>
                                        <div class="mm-mosque-names">
                                            <div class="mm-mosque-nama">{{ $m->mosque_name }}</div>
                                            <div class="mm-mosque-arab">{{ $m->arabic_name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="mm-td-kota">{{ $m->city }}</td>
                                <td class="mm-td-imam">{{ $m->imam_name ?? '-' }}</td>
                                <td class="mm-td-status">
                                    <div class="mm-status-select-wrap">
                                        <select class="mm-status-select {{ $statusClass }}"
                                                data-id="{{ $m->id }}"
                                                data-prev="{{ $statusClass }}"
                                                onchange="mmChangeStatus(this)">
                                            <option value="aktif"    {{ $statusClass === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                            <option value="pending"  {{ $statusClass === 'pending'  ? 'selected' : '' }}>Pending</option>
                                            <option value="nonaktif" {{ $statusClass === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                        <i class="fa-solid fa-chevron-down mm-select-arrow"></i>
                                    </div>
                                </td>
                                <td class="mm-td-langganan">
                                    <span class="mm-lg-badge {{ $l['kode'] }}">{{ $l['label'] }}</span>
                                    <div class="mm-lg-sub">
                                        @if($l['paketLabel'])<b>{{ $l['paketLabel'] }}</b><br>@endif
                                        @if($l['akhir'])
                                            {{ $l['kode'] === 'habis' ? 'Berakhir' : 'Sampai' }} {{ $l['akhir']->translatedFormat('d M Y') }}
                                            @if($l['sisa'] !== null && $l['sisa'] >= 0) · {{ $l['sisa'] }} hari lagi @endif
                                        @elseif($l['kode'] === 'aktif')
                                            Tanggal berakhir belum tercatat
                                        @endif
                                    </div>
                                </td>
                                <td class="mm-td-keuangan">
                                    <div class="mk-money">
                                        @if($sub['ok'] && $bayarCount > 0)
                                            <b>{{ $rp($bayarMasjid) }}</b><br>
                                            <small>{{ $bayarCount }} transaksi lunas</small>
                                        @else
                                            <span class="none">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="mm-td-donasi">
                                    <div class="mm-donasi-wrap">
                                        <div class="mm-donasi-bar-track">
                                            <div class="mm-donasi-bar-fill" style="width:{{ $pct }}%; background:#f59e0b"></div>
                                        </div>
                                        <span class="mm-donasi-pct">{{ $pct }}%</span>
                                    </div>
                                </td>
                                <td class="mm-td-aksi">
                                    <button type="button" class="mm-kelola-link" data-rd-mosque="{{ $m->id }}"
                                            style="border:0;cursor:pointer;font:inherit;">Pembayaran →</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="mm-empty" id="mmEmpty" style="display:none;">
                    <div class="mm-empty-icon">🕌</div>
                    <div class="mm-empty-text">Tidak ada masjid ditemukan</div>
                </div>
            </div>


            {{-- ===== RINCIAN PEMBAYARAN LANGGANAN ===== --}}
            @php
                $paidSt = \App\Support\FinanceStats::paidStatuses();
                $pendSt = \App\Support\FinanceStats::pendingStatuses();
                $grupTrx = function ($st) use ($paidSt, $pendSt) {
                    $st = strtolower((string) $st);
                    return in_array($st, $paidSt, true) ? 'lunas' : (in_array($st, $pendSt, true) ? 'menunggu' : 'lain');
                };
                $labelTrx = function ($st) use ($grupTrx) {
                    $g = $grupTrx($st);
                    if ($g === 'lunas')    { return 'Lunas'; }
                    if ($g === 'menunggu') { return 'Menunggu'; }
                    return [
                        'rejected' => 'Ditolak', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa',
                        'cancel' => 'Dibatalkan', 'canceled' => 'Dibatalkan', 'cancelled' => 'Dibatalkan',
                        'deny' => 'Ditolak',
                    ][strtolower((string) $st)] ?? Str::ucfirst((string) $st);
                };

                $trxRows    = collect($trx['rows']);
                $nTrxLunas  = $trxRows->filter(fn ($r) => $grupTrx($r['status']) === 'lunas')->count();
                $nTrxTunggu = $trxRows->filter(fn ($r) => $grupTrx($r['status']) === 'menunggu')->count();
                $nTrxLain   = $trxRows->count() - $nTrxLunas - $nTrxTunggu;
                $paketOpsi  = $trxRows->pluck('paket')->filter()->unique()->sort()->values();
                $masjidOpsi = $trxRows->pluck('mosque_name', 'mosque_id')->sort();
            @endphp

            <section class="rd-section" id="rincian-pembayaran">
                <div class="mk-head">
                    <h2 class="mk-section-title">Rincian Pembayaran Langganan</h2>
                    @if($trx['ok'] && $trx['truncated'])
                        <span class="rd-meta">Menampilkan {{ count($trx['rows']) }} transaksi terbaru dari {{ number_format($trx['total'], 0, ',', '.') }}</span>
                    @endif
                </div>

                @if(!$trx['ok'])
                    <div class="mk-warn">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Rincian pembayaran belum bisa dibaca: {{ $trx['reason'] }}.
                    </div>
                @else
                    <div class="rd-toolbar">
                        <div class="rd-tabs">
                            <button type="button" class="mm-lg-tab active" data-rdgrup="semua">Semua ({{ $trxRows->count() }})</button>
                            <button type="button" class="mm-lg-tab" data-rdgrup="lunas">Lunas ({{ $nTrxLunas }})</button>
                            <button type="button" class="mm-lg-tab" data-rdgrup="menunggu">Menunggu ({{ $nTrxTunggu }})</button>
                            <button type="button" class="mm-lg-tab" data-rdgrup="lain">Lainnya ({{ $nTrxLain }})</button>
                        </div>
                        <div class="rd-filters">
                            <input type="text" id="rdSearch" class="rd-input" placeholder="Cari masjid / kota / referensi...">
                            @if($trx['has']['paket'])
                                <select id="rdPaket" class="rd-input">
                                    <option value="">Semua paket</option>
                                    @foreach($paketOpsi as $kodePaket)
                                        <option value="{{ $kodePaket }}">{{ $namaPaket($kodePaket) }} ({{ $kodePaket }})</option>
                                    @endforeach
                                </select>
                            @endif
                            <select id="rdMosque" class="rd-input">
                                <option value="">Semua masjid</option>
                                @foreach($masjidOpsi as $idMasjid => $namaMasjid)
                                    <option value="{{ $idMasjid }}">{{ $namaMasjid }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="rd-reset" id="rdReset">Reset</button>
                        </div>
                    </div>

                    <div class="rd-table-wrap">
                        <table class="rd-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Masjid</th>
                                    @if($trx['has']['paket'])<th>Paket</th>@endif
                                    <th class="rd-num">Nominal</th>
                                    <th>Status</th>
                                    @if($trx['has']['method'])<th>Metode</th>@endif
                                    @if($trx['has']['ref'])<th>Referensi</th>@endif
                                    @if($trx['has']['berlaku'])<th>Berlaku sampai</th>@endif
                                </tr>
                            </thead>
                            <tbody id="rdTbody">
                                @foreach($trxRows as $r)
                                    @php
                                        $g = $grupTrx($r['status']);
                                        $tgl = $r['tgl'] ? \Illuminate\Support\Carbon::parse($r['tgl']) : null;
                                        $berlaku = $r['berlaku'] ? \Illuminate\Support\Carbon::parse($r['berlaku']) : null;
                                    @endphp
                                    <tr class="rd-row"
                                        data-grup="{{ $g }}"
                                        data-paket="{{ $r['paket'] }}"
                                        data-mosque="{{ $r['mosque_id'] }}"
                                        data-amount="{{ $r['amount'] }}"
                                        data-search="{{ mb_strtolower($r['mosque_name'] . ' ' . $r['city'] . ' ' . $r['ref'] . ' ' . $r['paket']) }}">
                                        <td class="rd-nowrap">{{ $tgl ? $tgl->translatedFormat('d M Y, H:i') : '-' }}</td>
                                        <td>
                                            <div class="rd-name">{{ $r['mosque_name'] }}</div>
                                            @if($r['city'])<div class="rd-sub">{{ $r['city'] }}</div>@endif
                                        </td>
                                        @if($trx['has']['paket'])
                                            <td>
                                                <div class="rd-name">{{ $namaPaket($r['paket']) }}</div>
                                                @if($r['paket'] && strtolower($namaPaket($r['paket'])) !== strtolower($r['paket']))
                                                    <div class="rd-sub">{{ $r['paket'] }}</div>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="rd-num rd-nowrap"><b>{{ $rp($r['amount']) }}</b></td>
                                        <td><span class="rd-badge {{ $g }}">{{ $labelTrx($r['status']) }}</span></td>
                                        @if($trx['has']['method'])<td>{{ $r['metode'] ?: '-' }}</td>@endif
                                        @if($trx['has']['ref'])<td class="rd-sub rd-ref">{{ $r['ref'] ?: '-' }}</td>@endif
                                        @if($trx['has']['berlaku'])<td class="rd-nowrap">{{ $berlaku ? $berlaku->translatedFormat('d M Y') : '-' }}</td>@endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="rd-empty" id="rdEmpty" style="display:none;">Tidak ada transaksi yang cocok dengan filter.</div>
                    </div>

                    <div class="rd-foot">
                        Menampilkan <b id="rdCount">{{ $trxRows->count() }}</b> transaksi ·
                        Total lunas (sesuai filter): <b id="rdSum">{{ $rp($trxRows->filter(fn ($r) => $grupTrx($r['status']) === 'lunas')->sum('amount')) }}</b>
                    </div>
                @endif
            </section>

        </main>
    </div>

    <button type="button" class="help-fab" aria-label="Bantuan">?</button>

    {{-- ===== MODAL TAMBAH MASJID ===== --}}
    <div class="mm-modal-overlay {{ $modalTerbuka ? 'active' : '' }}" id="mmModalOverlay">
        <div class="mm-modal">
            <div class="mm-modal-head">
                <span class="mm-modal-title">Tambah Masjid</span>
                <button type="button" class="mm-modal-close" id="mmModalClose" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form class="mm-modal-body" method="POST" action="{{ route('superadmin.manajemen-masjid.store') }}">
                @csrf

                @if($errors->any())
                    <div class="mm-alert mm-alert-error" style="margin-bottom:12px;">
                        <ul style="margin:0;padding-left:18px;">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="mm-modal-grid">
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Masjid <span class="mm-req">*</span></label>
                        <input type="text" name="mosque_name" class="mm-modal-input" placeholder="Nama masjid" value="{{ old('mosque_name') }}" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Arab</label>
                        <input type="text" name="arabic_name" class="mm-modal-input mm-rtl" placeholder="الاسم بالعربية" dir="rtl" value="{{ old('arabic_name') }}">
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Kota <span class="mm-req">*</span></label>
                        <input type="text" name="city" class="mm-modal-input" placeholder="Kota / Kabupaten" value="{{ old('city') }}" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Provinsi <span class="mm-req">*</span></label>
                        <input type="text" name="province" class="mm-modal-input" placeholder="Provinsi" value="{{ old('province') }}" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Imam <span class="mm-req">*</span></label>
                        <input type="text" name="imam_name" class="mm-modal-input" placeholder="Nama imam" value="{{ old('imam_name') }}" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Ketua <span class="mm-req">*</span></label>
                        <input type="text" name="chairman_name" class="mm-modal-input" placeholder="Nama ketua DKM" value="{{ old('chairman_name') }}" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Email <span class="mm-req">*</span></label>
                        <input type="email" name="email" class="mm-modal-input" placeholder="email@masjid.id" value="{{ old('email') }}" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Telepon</label>
                        <input type="text" name="phone" class="mm-modal-input" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="mm-modal-field mm-modal-field-full">
                    <label class="mm-modal-label">Status</label>
                    <select name="status" class="mm-modal-input">
                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Aktif</option>
                        <option value="pending"  {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="mm-modal-footer">
                    <button type="button" class="mm-btn-cancel" id="mmCancelBtn">Batal</button>
                    <button type="submit" class="mm-btn-simpan">Simpan Masjid</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ===== Filter: status + langganan + pencarian (digabung) =====
            var rows = document.querySelectorAll('.mm-row');
            var filterStatus = 'semua', filterLangganan = 'semua';

            function applyFilter() {
                var search = document.getElementById('mmSearch').value.trim().toLowerCase();
                var visible = 0;

                rows.forEach(function (row) {
                    var show =
                        (filterStatus === 'semua'    || row.dataset.status === filterStatus) &&
                        (filterLangganan === 'semua' || row.dataset.langganan === filterLangganan) &&
                        (!search || row.dataset.search.indexOf(search) !== -1);

                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                document.getElementById('mmEmpty').style.display = visible === 0 ? 'flex' : 'none';
            }

            function bindTabs(selector, attr, setter) {
                var tabs = document.querySelectorAll(selector);
                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function () {
                        tabs.forEach(function (t) { t.classList.remove('active'); });
                        tab.classList.add('active');
                        setter(tab.getAttribute(attr));
                        applyFilter();
                    });
                });
            }

            bindTabs('.mm-filter-tab', 'data-filter', function (v) { filterStatus = v; });
            bindTabs('[data-lg]',      'data-lg',     function (v) { filterLangganan = v; });
            document.getElementById('mmSearch').addEventListener('input', applyFilter);

            // ===== Rincian pembayaran langganan =====
            if (document.getElementById('rdTbody')) {
                var rdRows   = document.querySelectorAll('.rd-row');
                var rdSearch = document.getElementById('rdSearch');
                var rdPaket  = document.getElementById('rdPaket');     // bisa null jika tidak ada kolom paket
                var rdMosque = document.getElementById('rdMosque');
                var rdState  = { grup: 'semua', paket: '', mosque: '' };
                var rdFmt    = new Intl.NumberFormat('id-ID');

                var rdApply = function () {
                    var q = rdSearch.value.trim().toLowerCase(), n = 0, sum = 0;
                    rdRows.forEach(function (row) {
                        var show =
                            (rdState.grup === 'semua' || row.dataset.grup === rdState.grup) &&
                            (!rdState.paket  || row.dataset.paket === rdState.paket) &&
                            (!rdState.mosque || row.dataset.mosque === rdState.mosque) &&
                            (!q || row.dataset.search.indexOf(q) !== -1);
                        row.style.display = show ? '' : 'none';
                        if (show) {
                            n++;
                            if (row.dataset.grup === 'lunas') { sum += parseFloat(row.dataset.amount) || 0; }
                        }
                    });
                    document.getElementById('rdCount').textContent = n;
                    document.getElementById('rdSum').textContent = 'Rp ' + rdFmt.format(Math.round(sum));
                    document.getElementById('rdEmpty').style.display = n === 0 ? 'block' : 'none';
                };

                var rdGoto = function () {
                    document.getElementById('rincian-pembayaran').scrollIntoView({ behavior: 'smooth', block: 'start' });
                };

                var rdTabs = document.querySelectorAll('[data-rdgrup]');
                rdTabs.forEach(function (tab) {
                    tab.addEventListener('click', function () {
                        rdTabs.forEach(function (t) { t.classList.remove('active'); });
                        tab.classList.add('active');
                        rdState.grup = tab.getAttribute('data-rdgrup');
                        rdApply();
                    });
                });

                rdSearch.addEventListener('input', rdApply);
                if (rdPaket) {
                    rdPaket.addEventListener('change', function () { rdState.paket = rdPaket.value; rdApply(); });
                }
                rdMosque.addEventListener('change', function () { rdState.mosque = rdMosque.value; rdApply(); });

                document.getElementById('rdReset').addEventListener('click', function () {
                    rdState = { grup: 'semua', paket: '', mosque: '' };
                    rdSearch.value = '';
                    if (rdPaket) { rdPaket.value = ''; }
                    rdMosque.value = '';
                    rdTabs.forEach(function (t) { t.classList.toggle('active', t.getAttribute('data-rdgrup') === 'semua'); });
                    rdApply();
                });

                // Klik "Pembayaran →" di tabel masjid -> tampilkan transaksi masjid itu
                document.querySelectorAll('[data-rd-mosque]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        rdState.mosque = btn.getAttribute('data-rd-mosque');
                        rdMosque.value = rdState.mosque;   // jika masjid belum punya transaksi, tabel kosong
                        rdApply();
                        rdGoto();
                    });
                });

                // Klik chip paket di ringkasan -> tampilkan transaksi paket itu
                document.querySelectorAll('[data-rdpaket]').forEach(function (chip) {
                    chip.addEventListener('click', function () {
                        rdState.paket = chip.getAttribute('data-rdpaket');
                        if (rdPaket) { rdPaket.value = rdState.paket; }
                        rdApply();
                        rdGoto();
                    });
                });
            }

            // ===== Modal Tambah =====
            var overlay = document.getElementById('mmModalOverlay');
            function closeModal() { overlay.classList.remove('active'); }
            document.getElementById('mmTambahBtn').addEventListener('click', function () { overlay.classList.add('active'); });
            document.getElementById('mmModalClose').addEventListener('click', closeModal);
            document.getElementById('mmCancelBtn').addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
        });

        // ===== Ubah status masjid (AJAX) =====
        var MM_STATUS_URL = @json(route('superadmin.manajemen-masjid.updateStatus', ['id' => '__ID__']));
        var MM_CSRF       = @json(csrf_token());

        function mmChangeStatus(sel) {
            var prev = sel.dataset.prev;
            sel.disabled = true;
            sel.className = 'mm-status-select ' + sel.value;

            fetch(MM_STATUS_URL.replace('__ID__', sel.dataset.id), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': MM_CSRF
                },
                body: JSON.stringify({ status: sel.value })
            })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                if (!data.success) throw new Error('Respons gagal');
                location.reload(); // memperbarui angka di tab filter
            })
            .catch(function (err) {
                console.error(err);
                sel.value = prev;                                   // kembalikan pilihan
                sel.className = 'mm-status-select ' + prev;
                sel.disabled = false;
                alert('Gagal memperbarui status masjid. Silakan coba lagi.');
            });
        }
    </script>
</body>
</html>