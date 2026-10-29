@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;

    /*
    | SATU SUMBER DATA MODUL (dipakai sidebar, kartu akses cepat, dan ringkasan paket).
    | Status dihitung OTOMATIS:
    |  - 'segera' : route modul belum ada di routes/web.php
    |  - 'locked' : modul berbayar tapi paket masjid masih 'free' / masa aktif sudah habis
    |  - 'aktif'  : route ada dan paket mengizinkan
    */
    $paketFree = ($mosque->package_type ?? null) === 'free'
        || ($mosque->subscription_expires_at
            && \Illuminate\Support\Carbon::parse($mosque->subscription_expires_at)->isPast());

    $defs = [
        ['label' => 'Landing Page',     'icon' => 'fa-globe',               'route' => 'admin.landing-page',  'match' => 'admin.landing-page*',  'paid' => false, 'desc' => 'Atur halaman utama masjid'],
        ['label' => 'Profil Masjid',    'icon' => 'fa-mosque',              'route' => 'admin.profil-masjid', 'match' => 'admin.profil-masjid*', 'paid' => false, 'desc' => 'Identitas & data masjid'],
        ['label' => 'Jadwal Shalat',    'icon' => 'fa-clock',               'route' => 'admin.jadwal-sholat', 'match' => 'admin.jadwal-sholat*', 'paid' => false, 'desc' => 'Waktu shalat harian'],
        ['label' => 'Pengumuman',       'icon' => 'fa-bullhorn',            'route' => 'admin.pengumuman',    'match' => 'admin.pengumuman*',    'paid' => false, 'desc' => 'Informasi untuk jamaah', 'count' => $totalPengumuman ?? 0],
        ['label' => 'Kegiatan & Acara', 'icon' => 'fa-calendar-days',       'route' => 'admin.acara',         'match' => 'admin.acara*',         'paid' => false, 'desc' => 'Agenda & kegiatan masjid', 'count' => $totalAcara ?? 0],
        ['label' => 'Donasi',           'icon' => 'fa-hand-holding-dollar', 'route' => 'admin.donasi',        'match' => 'admin.donasi*',        'paid' => true,  'desc' => 'Zakat, infaq & wakaf'],
        ['label' => 'Data Jamaah',      'icon' => 'fa-users',               'route' => 'admin.jamaah',        'match' => 'admin.jamaah*',        'paid' => false, 'desc' => 'Data anggota jamaah'],
        ['label' => 'Tema Tampilan',    'icon' => 'fa-palette',             'route' => 'admin.tema',          'match' => 'admin.tema*',          'paid' => false, 'desc' => 'Warna & gaya tampilan'],
    ];

    $modules = collect($defs)->map(function ($m) use ($paketFree) {
        if (! Route::has($m['route'])) {
            $m['status'] = 'segera';
            $m['url']    = '#';
        } elseif ($m['paid'] && $paketFree) {
            $m['status'] = 'locked';
            $m['url']    = Route::has('masjid.perpanjangan.create') ? route('masjid.perpanjangan.create') : '#';
        } else {
            $m['status'] = 'aktif';
            $m['url']    = route($m['route']);
        }
        return $m;
    });

    $aktifList   = $modules->where('status', 'aktif');
    $lockedList  = $modules->where('status', 'locked');
    $jumlahAktif = $aktifList->count();
    $jumlahTotal = $modules->count();
    $persen      = $jumlahTotal ? round($jumlahAktif / $jumlahTotal * 100) : 0;
    $labelStatus = ['aktif' => 'Aktif', 'locked' => 'Terkunci', 'segera' => 'Segera'];

    // Aktivitas: kirim $aktivitas dari controller bila ada, jika tidak dibuat dari status modul
    $aktivitas = $aktivitas ?? collect([['color' => 'green', 'teks' => 'Sistem dashboard masjid berhasil dimuat', 'waktu' => 'Baru saja']])
        ->merge($aktifList->take(4)->map(fn ($m) => [
            'color' => 'blue', 'teks' => 'Modul ' . $m['label'] . ' aktif', 'waktu' => 'Hari ini',
        ]))->all();

    $namaMasjid = $mosque->mosque_name ?? 'SIM Masjid';
    $namaUser   = Auth::user()->name ?? 'Admin Masjid';
    $namaDepan  = explode(' ', trim($namaUser))[0] ?: 'Admin';
    $inisial    = Str::upper(Str::substr($namaUser, 0, 2));
    $urlPublik  = isset($mosque->slug) ? route('masjid.publik', $mosque->slug) : url('/');

    // Sapaan sesuai jam
    $jam       = (int) now()->format('H');
    $sapa      = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));
    $sapaIcon  = ($jam >= 5 && $jam < 18) ? 'fa-sun' : 'fa-moon';
    $tanggal   = now()->translatedFormat('l, d F Y');

    // Paket: nama diambil dari pengaturan Super Admin (bukan kode mentah seperti "100000_1")
    $kodePaket = $mosque->package_type ?? null;
    if ($paketFree) {
        $paketLabel = 'Paket Free';
    } elseif (empty($kodePaket)) {
        $paketLabel = 'Paket Premium';
    } else {
        $paketLabel = \App\Support\PackagePricing::resolve($kodePaket)['name'];
    }

    // Tanggal berakhir paket (jika tercatat)
    $akhirRaw   = data_get($mosque, 'subscription_expires_at') ?: data_get($mosque, 'package_expires_at');
    $akhirPaket = (! $paketFree && $akhirRaw) ? \Illuminate\Support\Carbon::parse($akhirRaw) : null;

    // Sisa masa aktif (hari) + persentase dari lama paket
    $infoPaket   = (! $paketFree && ! empty($kodePaket)) ? \App\Support\PackagePricing::resolve($kodePaket) : null;
    $sisaHari    = $akhirPaket ? (int) now()->startOfDay()->diffInDays($akhirPaket->copy()->startOfDay(), false) : null;
    $totalHari   = ($infoPaket && ($infoPaket['months'] ?? 0) > 0) ? $infoPaket['months'] * 30 : null;
    $persenWaktu = ($sisaHari !== null && $totalHari) ? max(0, min(100, (int) round($sisaHari / $totalHari * 100))) : null;
    $segeraHabis = $sisaHari !== null && $sisaHari <= 7;

    // Ring progres (r = 52)
    $ringC    = round(2 * M_PI * 52, 1);
    $ringDash = round($persen / 100 * $ringC, 1);

    // ===== Notifikasi dari Super Admin untuk masjid ini =====
    $notifs     = isset($mosque->id)
        ? \App\Models\MosqueNotification::where('mosque_id', $mosque->id)->latest()->limit(20)->get()
        : collect();
    $notifBelum = isset($mosque->id)
        ? \App\Models\MosqueNotification::where('mosque_id', $mosque->id)->unread()->count()
        : 0;
    $ntIkon = [
        'info'    => 'fa-circle-info',
        'success' => 'fa-circle-check',
        'warning' => 'fa-triangle-exclamation',
        'danger'  => 'fa-circle-exclamation',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - {{ $namaMasjid }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/dashboardAdmin.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">

        <div class="ba2-brand">
            <div class="ba2-brand-avatar"><i class="fa-solid fa-mosque"></i></div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">{{ $namaMasjid }}</div>
                <div class="ba2-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</div>
            </div>
        </div>

        <nav class="ba2-nav">
            <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="ba2-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>

            @foreach($modules as $m)
                <a href="{{ $m['url'] }}"
                   title="{{ $m['status'] === 'locked' ? 'Upgrade paket untuk membuka ' . $m['label'] : $m['label'] }}"
                   class="ba2-nav-item {{ $m['status'] !== 'segera' && request()->routeIs($m['match']) ? 'active' : '' }}"
                   @if($m['status'] === 'segera') style="opacity:.5;pointer-events:none;" aria-disabled="true" tabindex="-1"
                   @elseif($m['status'] === 'locked') style="opacity:.8;" @endif>
                    <span class="ba2-nav-icon"><i class="fa-solid {{ $m['icon'] }}"></i></span>
                    <span class="ba2-nav-label">{{ $m['label'] }}</span>

                    @if($m['status'] === 'locked')
                        <span class="ba2-nav-soon" style="background:#e74c3c;color:#fff;padding:2px 7px;border-radius:100px;"><i class="fa-solid fa-lock"></i></span>
                    @elseif($m['status'] === 'segera')
                        <span class="ba2-nav-soon">Segera</span>
                    @elseif(($m['count'] ?? 0) > 0)
                        <span class="ba2-nav-badge">{{ $m['count'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="ba2-user">
            <div class="ba2-user-avatar">{{ $inisial }}</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">{{ $namaUser }}</div>
                <div class="ba2-user-email">{{ Auth::user()->email ?? 'admin@baituldigital.id' }}</div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

        <header class="ba2-topbar">
            <div class="ba2-topbar-left" style="display:flex;align-items:center;gap:14px;">
                <button type="button" class="ba2-notif-btn" id="ba2CollapseBtn" aria-label="Ciutkan atau lebarkan menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <div class="ba2-page-title">Dashboard</div>
                    <div class="ba2-page-sub">Selamat datang di panel admin {{ $namaMasjid }}</div>
                </div>
            </div>
            <div class="ba2-topbar-right">
                <a href="{{ $urlPublik }}" class="ba2-btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Publik
                </a>

                {{-- ===== LONCENG NOTIFIKASI (dari Super Admin) ===== --}}
                <div class="nt-wrap" id="ntWrap"
                     data-read-url="{{ route('admin.notifikasi.read', '__ID__') }}"
                     data-read-all-url="{{ route('admin.notifikasi.read-all') }}">

                    <button type="button" class="ba2-notif-btn" id="ntBtn" aria-label="Notifikasi"
                            aria-haspopup="true" aria-expanded="false" aria-controls="ntPanel">
                        <i class="fa-solid fa-bell"></i>
                        <span class="nt-badge" id="ntBadge" {{ $notifBelum > 0 ? '' : 'hidden' }}>{{ $notifBelum > 99 ? '99+' : $notifBelum }}</span>
                    </button>

                    <div class="nt-panel" id="ntPanel" role="dialog" aria-label="Notifikasi" hidden>
                        <div class="nt-head">
                            <div>
                                <strong>Notifikasi</strong>
                                <span id="ntSub">{{ $notifBelum > 0 ? $notifBelum . ' belum dibaca' : 'Semua sudah dibaca' }}</span>
                            </div>
                            <button type="button" class="nt-readall" id="ntReadAll" {{ $notifBelum > 0 ? '' : 'hidden' }}>Tandai semua dibaca</button>
                        </div>

                        <div class="nt-list" id="ntList">
                            @forelse($notifs as $n)
                                @php
                                    $tp = array_key_exists($n->type, $ntIkon) ? $n->type : 'info';
                                    $unread = is_null($n->read_at);
                                @endphp
                                <button type="button" class="nt-item {{ $tp }} {{ $unread ? 'unread' : '' }}"
                                        data-id="{{ $n->id }}" data-url="{{ $n->url }}">
                                    <span class="nt-ico {{ $tp }}"><i class="fa-solid {{ $ntIkon[$tp] }}"></i></span>
                                    <span class="nt-body">
                                        <strong>{{ $n->title }}</strong>
                                        <span class="nt-msg">{{ $n->message }}</span>
                                        <small>{{ $n->created_at->locale('id')->diffForHumans() }}@if($n->url) &middot; ketuk untuk membuka @endif</small>
                                    </span>
                                    <span class="nt-dot" aria-hidden="true"></span>
                                </button>
                            @empty
                                <div class="nt-empty"><i class="fa-regular fa-bell"></i><p>Belum ada notifikasi.</p></div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="ba2-content">
            <div class="db-page">

                {{-- ===== HERO ===== --}}
                <section class="db-hero">
                    <div class="db-hero-main">
                        <span class="db-greet"><i class="fa-solid {{ $sapaIcon }}"></i> {{ $sapa }} &middot; {{ $tanggal }}</span>

                        <h1 class="db-hero-title">Assalamu'alaikum, <span>{{ $namaDepan }}</span></h1>
                        <p class="db-hero-sub">
                            Kelola <b>{{ $namaMasjid }}</b>@if(!empty($mosque->city)) di {{ $mosque->city }}@endif.
                            Semua modul masjid dapat Anda atur dari satu tempat.
                        </p>

                        <div class="db-chips">
                            <span class="db-chip {{ $paketFree ? '' : 'gold' }}"><i class="fa-solid {{ $paketFree ? 'fa-seedling' : 'fa-crown' }}"></i> {{ $paketLabel }}</span>
                            @if($akhirPaket)
                                <span class="db-chip {{ $segeraHabis ? 'warn' : '' }}"><i class="fa-solid fa-hourglass-half"></i> {{ $sisaHari > 0 ? $sisaHari . ' hari lagi' : 'Berakhir hari ini' }}</span>
                            @endif
                            <span class="db-chip"><i class="fa-solid fa-circle-check"></i> {{ $jumlahAktif }} modul aktif</span>
                        </div>

                        <div class="db-actions">
                            <a href="{{ $urlPublik }}" class="db-btn db-btn-gold"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman Publik</a>
                            @if($paketFree && Route::has('masjid.perpanjangan.create'))
                                <a href="{{ route('masjid.perpanjangan.create') }}" class="db-btn db-btn-ghost"><i class="fa-solid fa-rocket"></i> Upgrade Paket</a>
                            @endif
                        </div>
                    </div>

                    <div class="db-ring-wrap">
                        <div class="db-ring">
                            <svg viewBox="0 0 124 124" aria-hidden="true">
                                <defs>
                                    <linearGradient id="dbRingGrad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#ffd98a"/>
                                        <stop offset="100%" stop-color="#f5a623"/>
                                    </linearGradient>
                                </defs>
                                <circle class="trk" cx="62" cy="62" r="52"/>
                                <circle class="bar" cx="62" cy="62" r="52" stroke-dasharray="{{ $ringDash }} {{ $ringC }}"/>
                            </svg>
                            <div class="db-ring-text"><b>{{ $persen }}%</b><span>modul aktif</span></div>
                        </div>
                        <div class="db-ring-cap">{{ $jumlahAktif }} dari {{ $jumlahTotal }} modul<br>sudah dapat dipakai</div>
                    </div>
                </section>

                {{-- ===== STATS CARDS ===== --}}
                <div class="ba2-stats-grid">
                    <div class="ba2-stat-card">
                        <div class="ba2-stat-top">
                            <span class="ba2-stat-icon indigo"><i class="fa-solid fa-users"></i></span>
                            <span class="ba2-stat-badge green">Aktif</span>
                        </div>
                        <div class="ba2-stat-value">{{ number_format($totalJamaah ?? 0, 0, ',', '.') }}</div>
                        <div class="ba2-stat-label">Total Jamaah</div>
                    </div>

                    <div class="ba2-stat-card">
                        <div class="ba2-stat-top">
                            <span class="ba2-stat-icon sky"><i class="fa-solid fa-credit-card"></i></span>
                            <span class="ba2-stat-badge blue">Bulan Ini</span>
                        </div>
                        <div class="ba2-stat-value">Rp {{ number_format($totalDonasiBulanIni ?? 0, 0, ',', '.') }}</div>
                        <div class="ba2-stat-label">Donasi Bulan Ini</div>
                    </div>

                    <div class="ba2-stat-card">
                        <div class="ba2-stat-top">
                            <span class="ba2-stat-icon amber"><i class="fa-solid fa-calendar-days"></i></span>
                            <span class="ba2-stat-badge amber">Agenda</span>
                        </div>
                        <div class="ba2-stat-value">{{ $totalAcara ?? 0 }}</div>
                        <div class="ba2-stat-label">Acara Aktif</div>
                    </div>

                    <div class="ba2-stat-card">
                        <div class="ba2-stat-top">
                            <span class="ba2-stat-icon rose"><i class="fa-solid fa-bullhorn"></i></span>
                            <span class="ba2-stat-badge red">Informasi</span>
                        </div>
                        <div class="ba2-stat-value">{{ $totalPengumuman ?? 0 }}</div>
                        <div class="ba2-stat-label">Pengumuman</div>
                    </div>
                </div>

                {{-- ===== AKSES CEPAT / MODUL ===== --}}
                <section>
                    <div class="db-head">
                        <div>
                            <h2>Akses Cepat Modul</h2>
                            <p>Klik kartu untuk langsung membuka modul.</p>
                        </div>
                        <span class="db-head-badge">{{ $jumlahAktif }}/{{ $jumlahTotal }} aktif</span>
                    </div>

                    <div class="db-tiles">
                        @foreach($modules as $m)
                            <a href="{{ $m['url'] }}"
                               class="db-tile {{ $m['status'] }} tone-{{ $loop->index % 6 }}"
                               @if($m['status'] === 'segera') aria-disabled="true" tabindex="-1" @endif>
                                <span class="db-tile-chip">{{ $labelStatus[$m['status']] }}</span>
                                <span class="db-tile-icon"><i class="fa-solid {{ $m['icon'] }}"></i></span>
                                <span class="db-tile-name">{{ $m['label'] }}</span>

                                @if($m['status'] === 'locked')
                                    <span class="db-tile-meta">Upgrade untuk membuka</span>
                                @elseif($m['status'] === 'segera')
                                    <span class="db-tile-meta">Segera hadir</span>
                                @elseif(($m['count'] ?? 0) > 0)
                                    <span class="db-tile-count"><i class="fa-solid fa-circle" style="font-size:.4rem;"></i> {{ $m['count'] }} aktif</span>
                                @else
                                    <span class="db-tile-meta">{{ $m['desc'] }}</span>
                                @endif

                                @if($m['status'] !== 'segera')
                                    <span class="db-tile-go"><i class="fa-solid {{ $m['status'] === 'locked' ? 'fa-lock' : 'fa-arrow-right' }}"></i></span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>

                {{-- ===== AKTIVITAS + PAKET ===== --}}
                <div class="ba2-row-2">
                    <div class="ba2-card">
                        <div class="ba2-card-head">
                            <span class="ba2-card-title">Aktivitas Terkini</span>
                            <span class="ba2-card-meta">Sistem</span>
                        </div>
                        <ul class="db-timeline">
                            @forelse($aktivitas as $a)
                                <li class="db-tl-item">
                                    <span class="db-tl-dot {{ $a['color'] }}"></span>
                                    <div class="db-tl-body">
                                        <div class="db-tl-text">{{ $a['teks'] }}</div>
                                        <div class="db-tl-time">{{ $a['waktu'] }}</div>
                                    </div>
                                </li>
                            @empty
                                <li class="db-tl-item">
                                    <span class="db-tl-dot gray"></span>
                                    <div class="db-tl-body"><div class="db-tl-text">Belum ada aktivitas.</div></div>
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- ===== RINGKASAN PAKET ===== --}}
                    <div class="ba2-card">
                        <div class="ba2-card-head">
                            <span class="ba2-card-title">Ringkasan Paket</span>
                            <span class="ba2-card-meta">{{ $jumlahAktif }}/{{ $jumlahTotal }} aktif</span>
                        </div>

                        <div class="db-plan-body">

                            {{-- Panel utama paket --}}
                            <div class="db-plan-hero {{ $paketFree ? 'free' : '' }} {{ $segeraHabis ? 'warn' : '' }}">
                                <div class="db-plan-hero-top">
                                    <span class="db-plan-icon"><i class="fa-solid {{ $paketFree ? 'fa-seedling' : 'fa-crown' }}"></i></span>
                                    <div class="db-plan-id">
                                        <div class="db-plan-name">{{ $paketLabel }}</div>
                                        <div class="db-plan-sub">{{ $paketFree ? 'Fitur dasar masjid' : 'Semua fitur tersedia' }}</div>
                                    </div>
                                    <span class="db-plan-state">
                                        {{ $paketFree ? 'Gratis' : ($segeraHabis ? 'Segera berakhir' : 'Aktif') }}
                                    </span>
                                </div>

                                @if(! $paketFree && $akhirPaket)
                                    <div class="db-plan-count">
                                        @if($sisaHari > 0)
                                            <b>{{ $sisaHari }}</b><span>hari tersisa</span>
                                        @else
                                            <b>Hari ini</b><span>hari terakhir masa aktif</span>
                                        @endif
                                    </div>

                                    <div class="db-plan-track"><i style="width: {{ $persenWaktu ?? 100 }}%"></i></div>

                                    <div class="db-plan-date">
                                        <i class="fa-regular fa-calendar"></i>
                                        Berakhir {{ $akhirPaket->translatedFormat('d F Y') }}
                                    </div>
                                @elseif($paketFree)
                                    <p class="db-plan-free-note">Upgrade untuk membuka Donasi dan fitur berbayar lainnya.</p>
                                @endif
                            </div>

                            @if($segeraHabis)
                                <div class="db-plan-warn">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    Paket segera berakhir. Perpanjang agar fitur Donasi tetap aktif.
                                </div>
                            @endif

                            {{-- Status modul: satu segmen per modul --}}
                            <div class="db-plan-mods">
                                <div class="db-plan-mods-head">
                                    <span>Modul</span>
                                    <b>{{ $jumlahAktif }} dari {{ $jumlahTotal }} terbuka</b>
                                </div>

                                <div class="db-seg" aria-hidden="true">
                                    @foreach($modules as $m)
                                        <i class="{{ $m['status'] }}" title="{{ $m['label'] }}"></i>
                                    @endforeach
                                </div>

                                @if($lockedList->isNotEmpty())
                                    <div class="db-lock-list">
                                        @foreach($lockedList as $m)
                                            <span class="db-lock-item"><i class="fa-solid fa-lock"></i> {{ $m['label'] }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="db-plan-ok"><i class="fa-solid fa-circle-check"></i> Tidak ada modul yang terkunci.</div>
                                @endif
                            </div>

                            @if(Route::has('masjid.perpanjangan.create'))
                                @if($paketFree)
                                    <a href="{{ route('masjid.perpanjangan.create') }}" class="db-plan-cta"><i class="fa-solid fa-rocket"></i> Upgrade untuk membuka</a>
                                @else
                                    <a href="{{ route('masjid.perpanjangan.create') }}" class="db-plan-cta {{ $segeraHabis ? 'gold' : 'outline' }}"><i class="fa-solid fa-rotate"></i> Perpanjang Paket</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
        (function () {
            var sidebar = document.getElementById('ba2Sidebar');
            var main    = document.getElementById('ba2Main');
            var key     = 'ba2SidebarCollapsed';

            function apply(collapsed) {
                sidebar.classList.toggle('collapsed', collapsed);
                main.classList.toggle('expanded', collapsed);
            }

            // Ingat pilihan menu ciut/lebar
            try { apply(localStorage.getItem(key) === '1'); } catch (e) {}

            document.getElementById('ba2CollapseBtn').addEventListener('click', function () {
                var collapsed = !sidebar.classList.contains('collapsed');
                apply(collapsed);
                try { localStorage.setItem(key, collapsed ? '1' : '0'); } catch (e) {}
            });
        })();

        /* ===== Notifikasi dari Super Admin ===== */
        (function () {
            var wrap = document.getElementById('ntWrap');
            if (!wrap) return;

            var btn     = document.getElementById('ntBtn');
            var panel   = document.getElementById('ntPanel');
            var badge   = document.getElementById('ntBadge');
            var sub     = document.getElementById('ntSub');
            var readAll = document.getElementById('ntReadAll');
            var csrf    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function open(show) {
                panel.hidden = !show;
                btn.setAttribute('aria-expanded', show ? 'true' : 'false');
            }
            btn.addEventListener('click', function (e) { e.stopPropagation(); open(panel.hidden); });
            document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) open(false); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') open(false); });

            // Perbarui angka pada lonceng sesuai jumlah yang belum dibaca
            function refreshCount() {
                var n = panel.querySelectorAll('.nt-item.unread').length;
                badge.hidden = n === 0;
                badge.textContent = n > 99 ? '99+' : n;
                sub.textContent = n > 0 ? n + ' belum dibaca' : 'Semua sudah dibaca';
                readAll.hidden = n === 0;
            }

            function post(url) {
                return fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                    keepalive: true
                });
            }

            panel.querySelectorAll('.nt-item').forEach(function (it) {
                it.addEventListener('click', function () {
                    var url = it.dataset.url;
                    var go  = function () {
                        // hanya tautan internal atau http(s)
                        if (url && /^(\/(?!\/)|https?:\/\/)/.test(url)) window.location.href = url;
                    };

                    if (it.classList.contains('unread')) {
                        it.classList.remove('unread');
                        refreshCount();
                        post(wrap.dataset.readUrl.replace('__ID__', it.dataset.id)).then(go, go);
                    } else {
                        go();
                    }
                });
            });

            readAll.addEventListener('click', function () {
                panel.querySelectorAll('.nt-item.unread').forEach(function (it) { it.classList.remove('unread'); });
                refreshCount();
                post(wrap.dataset.readAllUrl);
            });
        })();
    </script>
</body>
</html>