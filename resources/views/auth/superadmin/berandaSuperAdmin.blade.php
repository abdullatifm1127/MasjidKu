<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body class="sa-page" id="saBody">

{{-- Sidebar dipakai bersama halaman lain (tidak lagi ditulis ulang di sini) --}}
@include('auth.superadmin.partials.sidebar', ['active' => 'dashboard'])

@php
    // Daftar masjid sebagai Collection (aman untuk paginator maupun collection biasa)
    $list = $mosques instanceof \Illuminate\Pagination\AbstractPaginator
        ? $mosques->getCollection()
        : collect($mosques);

    $pct = fn ($m) => min(100, max(0, (int) ($m->persentase ?? 0)));

    // Warna avatar tetap per masjid (berdasarkan nama)
    $warna = ['#14532d', '#1d4ed8', '#9a3412', '#6d28d9', '#0f766e', '#b45309', '#be123c'];
    $warnaFor = fn ($nama) => $warna[crc32((string) $nama) % count($warna)];

    // Normalisasi status -> kelas & label
    $statusKelas = function ($s) {
        $s = strtolower(trim((string) ($s ?: 'pending')));
        if (in_array($s, ['aktif', 'approved', 'active', 'disetujui', 'terverifikasi'])) return 'aktif';
        if (in_array($s, ['pending', 'menunggu', 'waiting', 'submitted'])) return 'pending';
        return 'lain';
    };
    $statusLabel = function ($s) use ($statusKelas) {
        $k = $statusKelas($s);
        return $k === 'aktif' ? 'Aktif' : ($k === 'pending' ? 'Menunggu' : ucfirst(strtolower((string) $s)));
    };

    // Hitungan status: pakai daftar bila lengkap (menangani status 'approved'), jika tidak pakai angka controller
    $lengkap = $list->count() >= (int) $totalMasjid;
    $aktifN  = $lengkap ? $list->filter(fn ($m) => $statusKelas($m->status ?? null) === 'aktif')->count()   : (int) $masjidAktif;
    $pendN   = $lengkap ? $list->filter(fn ($m) => $statusKelas($m->status ?? null) === 'pending')->count() : (int) $masjidPending;

    // Komposisi status untuk donut
    $total   = max(1, (int) $totalMasjid);
    $pAktif  = round($aktifN / $total * 100, 1);
    $pPend   = round($pendN / $total * 100, 1);
    $lainnya = max(0, $totalMasjid - $aktifN - $pendN);
    $donut   = 'conic-gradient(#16a34a 0 '.$pAktif.'%, #f59e0b '.$pAktif.'% '.($pAktif + $pPend).'%, #cbd5cb '.($pAktif + $pPend).'% 100%)';

    // Capaian total donasi terhadap target seluruh masjid
    $sumDonasi = $list->sum('total_donasi');
    $sumTarget = $list->sum('target_donasi');
    $pctTotal  = $sumTarget > 0 ? min(100, (int) round($sumDonasi / $sumTarget * 100)) : 0;

    // Peringkat 5 masjid dengan donasi terbanyak
    $peringkat = $list->sortByDesc('total_donasi')->take(5)->values();

    // Feed aktivitas: gabungan donasi terbaru + pendaftaran masjid
    $aktivitas = collect();
    foreach (($donasiTerkini ?? []) as $d) {
        $aktivitas->push([
            'tipe'  => 'donasi',
            'waktu' => $d->created_at ?? null,
            'judul' => 'Donasi Rp '.number_format($d->nominal ?? 0, 0, ',', '.'),
            'ket'   => ($d->nama_donatur ?? 'Hamba Allah').' untuk '.($d->masjid->nama_masjid ?? 'Pusat'),
        ]);
    }
    foreach ($list as $m) {
        $st = $statusKelas($m->status ?? null);
        $aktivitas->push([
            'tipe'  => 'masjid',
            'waktu' => $m->created_at ?? null,
            'judul' => ($m->nama_masjid ?? 'Masjid').' terdaftar',
            'ket'   => $st === 'pending' ? 'Menunggu verifikasi' : ($st === 'aktif' ? 'Sudah aktif beroperasi' : 'Status: '.$statusLabel($m->status ?? null)),
        ]);
    }
    $aktivitas = $aktivitas
        ->sortByDesc(fn ($a) => $a['waktu'] ? \Illuminate\Support\Carbon::parse($a['waktu'])->timestamp : 0)
        ->take(10)->values();

    $jam = (int) now()->format('G');
    $salam = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));

    // ===== Data pengaturan notifikasi (diambil dari NotifikasiController@dataBeranda) =====
    $nf        = app(\App\Http\Controllers\SuperAdmin\NotifikasiController::class)->dataBeranda();
    $nfMasjids = $nf['masjids'];
    $nfJumlah  = $nf['jumlah'];
    $nfRiwayat = $nf['riwayat'];

    $nfIkon  = ['info' => 'fa-circle-info', 'success' => 'fa-circle-check', 'warning' => 'fa-triangle-exclamation', 'danger' => 'fa-circle-exclamation'];
    $nfLabel = ['info' => 'Informasi', 'success' => 'Berhasil', 'warning' => 'Peringatan', 'danger' => 'Penting'];
    $nfTarget = [
        'all'      => ['Semua masjid aktif', 'Seluruh masjid yang sudah disetujui'],
        'berbayar' => ['Paket berbayar',     'Selain paket free'],
        'free'     => ['Paket free',         'Masjid tanpa langganan'],
        'segera'   => ['Segera berakhir',    'Langganan habis dalam 7 hari'],
        'pilih'    => ['Pilih manual',       'Tentukan masjid sendiri'],
    ];
    $nfOldTarget = old('target', 'all');
    $nfOldType   = old('type', 'info');
    $nfOldSel    = collect(old('mosque_ids', []))->map(fn ($v) => (int) $v)->all();

    $nfKirim = $nfRiwayat->sum('total');
    $nfBaca  = $nfRiwayat->sum('dibaca');
    $nfPct   = $nfKirim > 0 ? (int) round($nfBaca / $nfKirim * 100) : 0;
@endphp

<div class="sa-main" id="saMain">
<main class="sa-content">
<div class="db-wrap">

    {{-- ===== BANNER SAMBUTAN ===== --}}
    <section class="db-hero">
        <div class="db-hero-main">
            <div class="db-live"><span class="db-live-dot"></span> Live</div>
            <h1>{{ $salam }}, Super Admin</h1>
            <p>{{ now()->locale('id')->translatedFormat('l, d F Y') }} · <span id="dbClock">{{ now()->format('H:i:s') }}</span></p>

            <div class="db-qa">
                <a href="{{ route('superadmin.verifikasi') }}" class="db-qa-btn">
                    <i class="fa-solid fa-shield-halved"></i> Verifikasi
                    @if($pendN > 0)<span class="db-qa-count">{{ $pendN }}</span>@endif
                </a>
                <a href="{{ route('superadmin.manajemen-masjid') }}" class="db-qa-btn"><i class="fa-solid fa-mosque"></i> Kelola Masjid</a>
                <a href="{{ route('superadmin.pembayaran.index') }}" class="db-qa-btn"><i class="fa-solid fa-credit-card"></i> Pembayaran</a>
                <a href="{{ route('superadmin.halaman-utama.edit') }}" class="db-qa-btn"><i class="fa-solid fa-pen-to-square"></i> Edit Halaman Utama</a>
                <a href="#notifikasi" class="db-qa-btn"><i class="fa-solid fa-bell"></i> Notifikasi</a>
            </div>
        </div>

        <div class="db-hero-side">
            <div class="db-refresh">
                <button type="button" class="db-refresh-btn" id="dbRefresh"><i class="fa-solid fa-rotate"></i> Segarkan</button>
                <label class="db-auto">
                    <input type="checkbox" id="dbAuto" checked>
                    <span>Otomatis <b id="dbCountdown">60</b> dtk</span>
                </label>
            </div>
            <div class="db-hero-donasi">
                <span>Total donasi terkumpul</span>
                <strong data-rp="{{ (int) $totalDonasi }}">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</strong>
                <div class="db-hero-track"><i style="width: {{ $pctTotal }}%"></i></div>
                <small>{{ $pctTotal }}% dari target seluruh masjid</small>
            </div>
        </div>
    </section>

    {{-- ===== PERINGATAN VERIFIKASI ===== --}}
    @if($pendN > 0)
        <a href="{{ route('superadmin.verifikasi') }}" class="db-alert">
            <span class="db-alert-ico"><i class="fa-solid fa-hourglass-half"></i></span>
            <span><strong>{{ $pendN }} masjid</strong> menunggu verifikasi dari Anda.</span>
            <span class="db-alert-go">Tinjau sekarang <i class="fa-solid fa-arrow-right"></i></span>
        </a>
    @endif

    {{-- ===== STATISTIK ===== --}}
    <div class="db-stats">
        <div class="db-stat">
            <span class="db-stat-ico green"><i class="fa-solid fa-mosque"></i></span>
            <div>
                <div class="db-stat-label">Total Masjid</div>
                <div class="db-stat-value" data-count="{{ (int) $totalMasjid }}">{{ $totalMasjid }}</div>
                <div class="db-stat-sub">{{ $aktifN }} aktif, {{ $pendN }} pending</div>
            </div>
        </div>
        <div class="db-stat">
            <span class="db-stat-ico gold"><i class="fa-solid fa-hand-holding-dollar"></i></span>
            <div>
                <div class="db-stat-label">Total Donasi</div>
                <div class="db-stat-value small" data-rp="{{ (int) $totalDonasi }}">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</div>
                <div class="db-stat-sub">Akumulasi seluruh sistem</div>
            </div>
        </div>
        <div class="db-stat">
            <span class="db-stat-ico green"><i class="fa-solid fa-circle-check"></i></span>
            <div>
                <div class="db-stat-label">Masjid Aktif</div>
                <div class="db-stat-value" data-count="{{ (int) $aktifN }}">{{ $aktifN }}</div>
                <div class="db-stat-sub">{{ $pAktif }}% dari seluruh masjid</div>
            </div>
        </div>
        <div class="db-stat {{ $pendN > 0 ? 'needs' : '' }}">
            <span class="db-stat-ico amber"><i class="fa-solid fa-hourglass-half"></i></span>
            <div>
                <div class="db-stat-label">Menunggu Verifikasi</div>
                <div class="db-stat-value" data-count="{{ (int) $pendN }}">{{ $pendN }}</div>
                <div class="db-stat-sub">{{ $pendN > 0 ? 'Perlu tindakan Anda' : 'Semua sudah diproses' }}</div>
            </div>
        </div>
    </div>

    {{-- ===== UTAMA: DAFTAR MASJID + SISI KANAN ===== --}}
    <div class="db-grid">

        {{-- Daftar masjid --}}
        <section class="db-card">
            <div class="db-card-head">
                <div>
                    <h2>Semua Masjid</h2>
                    <p>{{ $totalMasjid }} tenant terdaftar</p>
                </div>
                <a href="{{ route('superadmin.manajemen-masjid') }}" class="db-link">Lihat semua <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="db-tools">
                <div class="db-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="dbSearch" placeholder="Cari nama masjid atau kota..." autocomplete="off">
                </div>
                <div class="db-chips" id="dbMFilter">
                    <button type="button" class="db-chipbtn active" data-filter="all">Semua</button>
                    <button type="button" class="db-chipbtn" data-filter="aktif">Aktif</button>
                    <button type="button" class="db-chipbtn" data-filter="pending">Pending</button>
                </div>
            </div>

            <div class="db-mlist" id="dbMList">
                @forelse($list as $m)
                    @php
                        $nama = $m->nama_masjid ?? '-';
                        $st   = $statusKelas($m->status ?? null);
                        $p    = $pct($m);
                    @endphp
                    <div class="db-mrow" data-status="{{ $st }}" data-text="{{ strtolower($nama.' '.($m->kota ?? '')) }}">
                        <div class="db-avatar" style="background: {{ $warnaFor($nama) }}">{{ strtoupper(mb_substr($nama, 0, 1)) }}</div>

                        <div class="db-minfo">
                            <div class="db-mtitle">
                                <span class="db-mname">{{ $nama }}</span>
                                <span class="db-badge {{ $st }}">{{ $statusLabel($m->status ?? null) }}</span>
                            </div>
                            <div class="db-mmeta">
                                <span><i class="fa-solid fa-location-dot"></i> {{ $m->kota ?? 'Indonesia' }}</span>
                                <span><i class="fa-solid fa-users"></i> {{ $m->jumlah_jamaah ?? '0' }} jamaah</span>
                            </div>
                            <div class="db-bar"><i class="{{ $st }}" style="width: {{ $p }}%"></i></div>
                            <div class="db-mdonasi">
                                <b>Rp {{ number_format($m->total_donasi ?? 0, 0, ',', '.') }}</b>
                                <span>dari Rp {{ number_format($m->target_donasi ?? 0, 0, ',', '.') }} · {{ $p }}%</span>
                            </div>
                        </div>

                        <a href="{{ route('superadmin.manajemen-masjid') }}" class="db-kelola">Kelola</a>
                    </div>
                @empty
                    <div class="db-empty"><i class="fa-solid fa-mosque"></i><p>Belum ada data masjid di database.</p></div>
                @endforelse
                <div class="db-empty" id="dbMEmpty" hidden><i class="fa-solid fa-magnifying-glass"></i><p>Tidak ada masjid yang cocok dengan pencarian.</p></div>
            </div>
        </section>

        {{-- Sisi kanan --}}
        <div class="db-side">

            {{-- Komposisi status --}}
            <section class="db-card">
                <div class="db-card-head"><div><h2>Komposisi Status</h2><p>Sebaran status seluruh masjid</p></div></div>
                <div class="db-donut-wrap">
                    <div class="db-donut" style="background: {{ $donut }}">
                        <div class="db-donut-hole"><strong>{{ $totalMasjid }}</strong><span>masjid</span></div>
                    </div>
                    <ul class="db-legend">
                        <li><i style="background:#16a34a"></i> Aktif <b>{{ $aktifN }}</b></li>
                        <li><i style="background:#f59e0b"></i> Pending <b>{{ $pendN }}</b></li>
                        <li><i style="background:#cbd5cb"></i> Lainnya <b>{{ $lainnya }}</b></li>
                    </ul>
                </div>
            </section>

            {{-- Aktivitas --}}
            <section class="db-card">
                <div class="db-card-head"><div><h2>Aktivitas Terbaru</h2><p>Donasi masuk &amp; pendaftaran masjid</p></div></div>
                <div class="db-chips" id="dbFeedFilter">
                    <button type="button" class="db-chipbtn active" data-feed="all">Semua</button>
                    <button type="button" class="db-chipbtn" data-feed="donasi">Donasi</button>
                    <button type="button" class="db-chipbtn" data-feed="masjid">Masjid</button>
                </div>

                <ol class="db-feed" id="dbFeed">
                    @forelse($aktivitas as $a)
                        @php $w = $a['waktu'] ? \Illuminate\Support\Carbon::parse($a['waktu']) : null; @endphp
                        <li class="db-act" data-tipe="{{ $a['tipe'] }}">
                            <span class="db-act-ico {{ $a['tipe'] }}">
                                <i class="fa-solid {{ $a['tipe'] === 'donasi' ? 'fa-hand-holding-heart' : 'fa-mosque' }}"></i>
                            </span>
                            <div class="db-act-tx">
                                <strong>{{ $a['judul'] }}</strong>
                                <span>{{ $a['ket'] }}</span>
                            </div>
                            @if($w)
                                <time class="db-act-time" datetime="{{ $w->toIso8601String() }}">{{ $w->copy()->locale('id')->diffForHumans() }}</time>
                            @endif
                        </li>
                    @empty
                        <li class="db-empty"><i class="fa-regular fa-bell"></i><p>Belum ada aktivitas.</p></li>
                    @endforelse
                </ol>
            </section>
        </div>
    </div>

    {{-- ===== PERINGKAT DONASI ===== --}}
    <section class="db-card">
        <div class="db-card-head">
            <div>
                <h2>Peringkat Donasi per Masjid</h2>
                <p>5 masjid dengan donasi terbanyak · total capaian {{ $pctTotal }}% dari target</p>
            </div>
        </div>

        <div class="db-rank">
            @forelse($peringkat as $i => $m)
                @php $p = $pct($m); $nama = $m->nama_masjid ?? '-'; @endphp
                <div class="db-rank-row">
                    <span class="db-rank-no n{{ $i + 1 }}">{{ $i + 1 }}</span>
                    <div class="db-rank-body">
                        <div class="db-rank-top">
                            <span class="db-rank-name">{{ $nama }}</span>
                            <span class="db-rank-val">Rp {{ number_format($m->total_donasi ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="db-bar big"><i class="gold" style="width: {{ $p }}%"></i></div>
                        <div class="db-rank-foot">
                            <span>Target Rp {{ number_format($m->target_donasi ?? 0, 0, ',', '.') }}</span>
                            <b>{{ $p }}%</b>
                        </div>
                    </div>
                </div>
            @empty
                <div class="db-empty"><i class="fa-solid fa-chart-simple"></i><p>Belum ada data progress.</p></div>
            @endforelse
        </div>
    </section>

    {{-- ===== PENGATURAN NOTIFIKASI KE ADMIN MASJID ===== --}}
    <section id="notifikasi">

        <div class="nf-head">
            <div>
                <h2>Notifikasi ke Admin Masjid</h2>
                <p>Kirim pengumuman atau pengingat. Pesan muncul di lonceng dashboard tiap masjid.</p>
            </div>
            <span class="nf-badge">{{ $nfPct }}% dibaca ({{ $nfBaca }} dari {{ $nfKirim }} pesan terakhir)</span>
        </div>

        @if(session('success'))
            <div class="nf-flash ok" role="status"><i class="fa-solid fa-circle-check"></i><div>{{ session('success') }}</div></div>
        @endif
        @if(session('error'))
            <div class="nf-flash err" role="alert"><i class="fa-solid fa-circle-exclamation"></i><div>{{ session('error') }}</div></div>
        @endif
        @if($errors->any())
            <div class="nf-flash err" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Notifikasi belum terkirim. Periksa isian berikut:</strong>
                    <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            </div>
        @endif

        <div class="nf-grid">

            {{-- Form kirim --}}
            <div class="db-card">
                <div class="db-card-head"><div><h2>Tulis notifikasi</h2><p>Isi pesan, pilih penerima, lalu kirim</p></div></div>

                <form method="POST" action="{{ route('superadmin.notifikasi.store') }}" id="nfForm" novalidate>
                    @csrf

                    <div class="nf-field">
                        <span class="nf-legend">Jenis pesan</span>
                        <div class="nf-types">
                            @foreach($nfLabel as $val => $lbl)
                                <label class="nf-type {{ $val }}">
                                    <input type="radio" name="type" value="{{ $val }}" {{ $nfOldType === $val ? 'checked' : '' }}>
                                    <span><i class="fa-solid {{ $nfIkon[$val] }}"></i> {{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="nf-field">
                        <label for="nfTitle">Judul <span class="nf-count" id="nfTitleCount">0/120</span></label>
                        <input type="text" id="nfTitle" name="title" class="nf-input {{ $errors->has('title') ? 'bad' : '' }}"
                               maxlength="120" value="{{ old('title') }}" placeholder="Contoh: Langganan Anda berakhir minggu ini">
                    </div>

                    <div class="nf-field">
                        <label for="nfMessage">Isi pesan <span class="nf-count" id="nfMsgCount">0/1000</span></label>
                        <textarea id="nfMessage" name="message" class="nf-textarea {{ $errors->has('message') ? 'bad' : '' }}"
                                  maxlength="1000" placeholder="Tulis pesan yang singkat dan jelas.">{{ old('message') }}</textarea>
                    </div>

                    <div class="nf-field">
                        <label for="nfUrl">Tautan saat diklik (opsional)</label>
                        <input type="text" id="nfUrl" name="url" class="nf-input {{ $errors->has('url') ? 'bad' : '' }}"
                               maxlength="255" value="{{ old('url') }}" placeholder="/masjid/perpanjangan atau https://...">
                        <small>Awali dengan "/" untuk halaman di dalam aplikasi, atau "https://" untuk situs luar.</small>
                    </div>

                    <div class="nf-field">
                        <span class="nf-legend">Kirim ke</span>
                        <div class="nf-targets">
                            @foreach($nfTarget as $val => [$tNama, $tKet])
                                <label class="nf-target">
                                    <input type="radio" name="target" value="{{ $val }}" {{ $nfOldTarget === $val ? 'checked' : '' }}>
                                    <span>
                                        <span><b>{{ $tNama }}</b><em>{{ $tKet }}</em></span>
                                        @if($val !== 'pilih')
                                            <span class="nf-n">{{ $nfJumlah[$val] ?? 0 }}</span>
                                        @else
                                            <span class="nf-n"><i class="fa-solid fa-hand-pointer"></i></span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="nf-pick" id="nfPick" {{ $nfOldTarget === 'pilih' ? '' : 'hidden' }}>
                            <div class="nf-pick-bar">
                                <div class="db-search">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="search" id="nfPickSearch" placeholder="Cari nama masjid atau kota..." autocomplete="off">
                                </div>
                                <button type="button" class="nf-mini" id="nfPickAll">Pilih yang tampil</button>
                                <button type="button" class="nf-mini" id="nfPickNone">Kosongkan</button>
                            </div>
                            <div class="nf-pick-list" id="nfPickList">
                                @foreach($nfMasjids as $nm)
                                    <label class="nf-pick-item" data-text="{{ strtolower($nm->mosque_name.' '.$nm->city) }}">
                                        <input type="checkbox" name="mosque_ids[]" value="{{ $nm->id }}" {{ in_array($nm->id, $nfOldSel, true) ? 'checked' : '' }}>
                                        <span>{{ $nm->mosque_name }}</span>
                                        <small>{{ $nm->city ?: '-' }} · {{ ($nm->package_type && $nm->package_type !== 'free') ? ucfirst($nm->package_type) : 'Free' }}</small>
                                    </label>
                                @endforeach
                                @if($nfMasjids->isEmpty())
                                    <div class="db-empty"><i class="fa-solid fa-mosque"></i><p>Belum ada masjid terdaftar.</p></div>
                                @endif
                            </div>
                            <div class="nf-pick-count"><b id="nfPickN">0</b> masjid dipilih</div>
                        </div>
                    </div>

                    <div class="nf-actions">
                        <button type="submit" class="nf-send" id="nfSend"><i class="fa-solid fa-paper-plane"></i> Kirim notifikasi</button>
                        <span class="nf-hint" id="nfHint"></span>
                    </div>
                </form>
            </div>

            {{-- Sisi kanan: pratinjau + riwayat --}}
            <div class="nf-side">

                <div class="db-card">
                    <div class="db-card-head"><div><h2>Pratinjau</h2><p>Tampilan di lonceng admin masjid</p></div></div>
                    <div class="nf-prev info" id="nfPrev">
                        <span class="nf-ico info" id="nfPrevIco"><i class="fa-solid fa-circle-info"></i></span>
                        <div>
                            <strong id="nfPrevTitle">Judul notifikasi</strong>
                            <p id="nfPrevMsg">Isi pesan akan tampil di sini.</p>
                            <small>Baru saja</small>
                        </div>
                    </div>
                </div>

                <div class="db-card">
                    <div class="db-card-head"><div><h2>Riwayat pengiriman</h2><p>30 pengiriman terakhir</p></div></div>
                    <ul class="nf-hist">
                        @forelse($nfRiwayat as $r)
                            @php
                                $tp = array_key_exists($r->type, $nfIkon) ? $r->type : 'info';
                                $pb = $r->total > 0 ? (int) round($r->dibaca / $r->total * 100) : 0;
                                $wk = \Illuminate\Support\Carbon::parse($r->dikirim)->locale('id');
                            @endphp
                            <li class="nf-h">
                                <span class="nf-ico {{ $tp }}"><i class="fa-solid {{ $nfIkon[$tp] }}"></i></span>
                                <div class="nf-h-body">
                                    <strong>{{ $r->title }}</strong>
                                    <p>{{ $r->message }}</p>
                                    <div class="nf-h-meta">
                                        <span><i class="fa-regular fa-clock"></i>{{ $wk->translatedFormat('d M Y, H:i') }}</span>
                                        <span><i class="fa-solid fa-mosque"></i>{{ $r->total }} masjid</span>
                                        <span><i class="fa-regular fa-eye"></i>{{ $r->dibaca }} dibaca</span>
                                    </div>
                                    <div class="nf-read" title="{{ $pb }}% dibaca"><i style="width: {{ $pb }}%"></i></div>
                                </div>
                                <form method="POST" action="{{ route('superadmin.notifikasi.destroy', $r->batch_id) }}"
                                      onsubmit="return confirm('Tarik notifikasi ini dari semua penerima? Pesan akan hilang dari lonceng admin masjid.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="nf-del">Tarik</button>
                                </form>
                            </li>
                        @empty
                            <li class="db-empty"><i class="fa-regular fa-bell"></i><p>Belum ada notifikasi terkirim. Tulis pesan pertama di formulir.</p></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </section>

</div>
</main>
</div>

<script>
(function () {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var rp = new Intl.NumberFormat('id-ID');

    /* Jam langsung */
    var clock = document.getElementById('dbClock');
    setInterval(function () {
        clock.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
    }, 1000);

    /* Hitung naik pada angka statistik (sekali saat halaman dibuka) */
    function countUp(el, to, prefix) {
        if (reduce || to <= 0) return;
        var t0 = performance.now(), dur = 900;
        (function step(t) {
            var k = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - k, 3);
            el.textContent = prefix + rp.format(Math.round(to * e));
            if (k < 1) requestAnimationFrame(step);
        })(t0);
    }
    document.querySelectorAll('[data-count]').forEach(function (el) { countUp(el, +el.dataset.count, ''); });
    document.querySelectorAll('[data-rp]').forEach(function (el) { countUp(el, +el.dataset.rp, 'Rp '); });

    /* Cari + filter status masjid */
    var rows = [].slice.call(document.querySelectorAll('.db-mrow'));
    var search = document.getElementById('dbSearch');
    var mEmpty = document.getElementById('dbMEmpty');
    var mFilter = 'all';
    function applyM() {
        var q = search.value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (r) {
            var ok = (mFilter === 'all' || r.dataset.status === mFilter) && r.dataset.text.indexOf(q) !== -1;
            r.hidden = !ok; if (ok) shown++;
        });
        mEmpty.hidden = !(rows.length && shown === 0);
    }
    search.addEventListener('input', applyM);
    document.querySelectorAll('#dbMFilter .db-chipbtn').forEach(function (b) {
        b.addEventListener('click', function () {
            mFilter = b.dataset.filter;
            document.querySelectorAll('#dbMFilter .db-chipbtn').forEach(function (x) { x.classList.toggle('active', x === b); });
            applyM();
        });
    });

    /* Filter feed aktivitas */
    document.querySelectorAll('#dbFeedFilter .db-chipbtn').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('#dbFeedFilter .db-chipbtn').forEach(function (x) { x.classList.toggle('active', x === b); });
            document.querySelectorAll('#dbFeed .db-act').forEach(function (li) {
                li.hidden = !(b.dataset.feed === 'all' || li.dataset.tipe === b.dataset.feed);
            });
        });
    });

    /* Waktu relatif ("5 menit lalu") diperbarui terus */
    var rtf = new Intl.RelativeTimeFormat('id', { numeric: 'auto' });
    function relTime() {
        document.querySelectorAll('time.db-act-time').forEach(function (t) {
            var s = Math.round((new Date(t.getAttribute('datetime')) - Date.now()) / 1000), a = Math.abs(s);
            if (isNaN(s)) return;
            t.textContent = a < 60 ? 'Baru saja'
                : a < 3600 ? rtf.format(Math.round(s / 60), 'minute')
                : a < 86400 ? rtf.format(Math.round(s / 3600), 'hour')
                : rtf.format(Math.round(s / 86400), 'day');
        });
    }
    relTime(); setInterval(relTime, 30000);

    /* ===== Pengaturan notifikasi ===== */
    var nfForm = document.getElementById('nfForm');
    var nfTitle = document.getElementById('nfTitle');
    var nfMsg = document.getElementById('nfMessage');
    var nfIcons = {info:'fa-circle-info', success:'fa-circle-check', warning:'fa-triangle-exclamation', danger:'fa-circle-exclamation'};

    function nfText() {
        document.getElementById('nfTitleCount').textContent = nfTitle.value.length + '/120';
        document.getElementById('nfMsgCount').textContent = nfMsg.value.length + '/1000';
        document.getElementById('nfPrevTitle').textContent = nfTitle.value.trim() || 'Judul notifikasi';
        document.getElementById('nfPrevMsg').textContent = nfMsg.value.trim() || 'Isi pesan akan tampil di sini.';
    }
    function nfType() {
        var t = nfForm.querySelector('input[name="type"]:checked').value;
        document.getElementById('nfPrev').className = 'nf-prev ' + t;
        var ico = document.getElementById('nfPrevIco');
        ico.className = 'nf-ico ' + t;
        ico.innerHTML = '<i class="fa-solid ' + nfIcons[t] + '"></i>';
    }
    var nfPick = document.getElementById('nfPick');
    var nfItems = [].slice.call(document.querySelectorAll('#nfPickList .nf-pick-item'));
    var nfSearch = document.getElementById('nfPickSearch');
    function nfCount() {
        document.getElementById('nfPickN').textContent = nfForm.querySelectorAll('input[name="mosque_ids[]"]:checked').length;
    }
    function nfTarget() {
        nfPick.hidden = (nfForm.querySelector('input[name="target"]:checked').value !== 'pilih');
    }

    nfTitle.addEventListener('input', nfText);
    nfMsg.addEventListener('input', nfText);
    nfForm.querySelectorAll('input[name="type"]').forEach(function (r) { r.addEventListener('change', nfType); });
    nfForm.querySelectorAll('input[name="target"]').forEach(function (r) { r.addEventListener('change', nfTarget); });
    nfSearch.addEventListener('input', function () {
        var q = nfSearch.value.trim().toLowerCase();
        nfItems.forEach(function (it) { it.hidden = it.dataset.text.indexOf(q) === -1; });
    });
    document.getElementById('nfPickAll').addEventListener('click', function () {
        nfItems.forEach(function (it) { if (!it.hidden) it.querySelector('input').checked = true; });
        nfCount();
    });
    document.getElementById('nfPickNone').addEventListener('click', function () {
        nfItems.forEach(function (it) { it.querySelector('input').checked = false; });
        nfCount();
    });
    nfItems.forEach(function (it) { it.querySelector('input').addEventListener('change', nfCount); });

    nfForm.addEventListener('submit', function (e) {
        var hint = document.getElementById('nfHint');
        var t = nfForm.querySelector('input[name="target"]:checked').value;
        var problem = '';
        if (!nfTitle.value.trim()) problem = 'Judul belum diisi.';
        else if (!nfMsg.value.trim()) problem = 'Isi pesan belum diisi.';
        else if (t === 'pilih' && nfForm.querySelectorAll('input[name="mosque_ids[]"]:checked').length === 0) problem = 'Pilih minimal satu masjid.';

        if (problem) { e.preventDefault(); hint.textContent = problem; hint.style.color = '#b91c1c'; return; }
        var btn = document.getElementById('nfSend');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
    });

    nfText(); nfType(); nfTarget(); nfCount();

    @if(session('success') || session('error') || $errors->any())
        document.getElementById('notifikasi').scrollIntoView();
    @endif

    /* Segarkan data: manual & otomatis tiap 60 detik */
    var auto = document.getElementById('dbAuto'), cd = document.getElementById('dbCountdown'), left = 60;
    try { if (localStorage.getItem('dbAuto') === '0') auto.checked = false; } catch (e) {}
    auto.addEventListener('change', function () {
        try { localStorage.setItem('dbAuto', auto.checked ? '1' : '0'); } catch (e) {}
        left = 60; cd.textContent = left;
    });
    document.getElementById('dbRefresh').addEventListener('click', function () {
        this.querySelector('i').classList.add('fa-spin'); location.reload();
    });
    setInterval(function () {
        if (!auto.checked || document.hidden) return;
        if (document.activeElement === search || search.value) return; // jangan ganggu saat mencari
        // jangan refresh saat notifikasi sedang ditulis
        if (nfForm.contains(document.activeElement) || nfTitle.value !== '' || nfMsg.value !== '') return;
        left--; cd.textContent = left;
        if (left <= 0) location.reload();
    }, 1000);
})();
</script>
</body>
</html>