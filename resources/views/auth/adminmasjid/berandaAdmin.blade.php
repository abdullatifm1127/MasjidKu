@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;

    /*
    | SATU SUMBER DATA MODUL (dipakai sidebar, kartu status, dan banner).
    | Status dihitung OTOMATIS:
    |  - 'segera' : route modul belum ada di routes/web.php
    |  - 'locked' : modul berbayar tapi paket masjid masih 'free'
    |  - 'aktif'  : route ada dan paket mengizinkan
    */
    $paketFree = ($mosque->package_type ?? null) === 'free';

    $defs = [
        ['label' => 'Landing Page',     'icon' => 'fa-globe',               'route' => 'admin.landing-page',  'match' => 'admin.landing-page*',  'paid' => false],
        ['label' => 'Profil Masjid',    'icon' => 'fa-mosque',              'route' => 'admin.profil-masjid', 'match' => 'admin.profil-masjid*', 'paid' => false],
        ['label' => 'Jadwal Shalat',    'icon' => 'fa-clock',               'route' => 'admin.jadwal-sholat', 'match' => 'admin.jadwal-sholat*', 'paid' => false],
        ['label' => 'Pengumuman',       'icon' => 'fa-bullhorn',            'route' => 'admin.pengumuman',    'match' => 'admin.pengumuman*',    'paid' => false, 'count' => $totalPengumuman ?? 0],
        ['label' => 'Kegiatan & Acara', 'icon' => 'fa-calendar-days',       'route' => 'admin.acara',         'match' => 'admin.acara*',         'paid' => false, 'count' => $totalAcara ?? 0],
        ['label' => 'Donasi',           'icon' => 'fa-hand-holding-dollar', 'route' => 'admin.donasi',        'match' => 'admin.donasi*',        'paid' => true],
        ['label' => 'Data Jamaah',      'icon' => 'fa-users',               'route' => 'admin.jamaah',        'match' => 'admin.jamaah*',        'paid' => false],
        ['label' => 'Tema Tampilan',    'icon' => 'fa-palette',             'route' => 'admin.tema',          'match' => 'admin.tema*',          'paid' => false],
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
    $inisial    = Str::upper(Str::substr($namaUser, 0, 2));
    $urlPublik  = isset($mosque->slug) ? route('masjid.publik', $mosque->slug) : url('/');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - {{ $namaMasjid }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
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
            <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>

            @foreach($modules as $m)
                <a href="{{ $m['url'] }}"
                   class="ba2-nav-item {{ $m['status'] !== 'segera' && request()->routeIs($m['match']) ? 'active' : '' }}"
                   @if($m['status'] === 'segera') style="opacity:.5;pointer-events:none;" aria-disabled="true" tabindex="-1" @endif
                   @if($m['status'] === 'locked') style="opacity:.8;" title="Upgrade paket untuk membuka {{ $m['label'] }}" @endif>
                    <span class="ba2-nav-icon"><i class="fa-solid {{ $m['icon'] }}"></i></span>
                    <span class="ba2-nav-label">{{ $m['label'] }}</span>

                    @if($m['status'] === 'locked')
                        <span class="ba2-nav-soon" style="background:#e74c3c;color:#fff;padding:1px 6px;border-radius:100px;"><i class="fa-solid fa-lock"></i></span>
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
            <div class="ba2-topbar-left" style="display:flex;align-items:center;gap:12px;">
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
                <button type="button" class="ba2-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    @if(($totalPengumuman ?? 0) > 0)<span class="ba2-notif-dot"></span>@endif
                </button>
            </div>
        </header>

        <main class="ba2-content">

            {{-- ===== BANNER (di atas, otomatis dari status modul) ===== --}}
            <div class="ba2-banner">
                <div class="ba2-banner-title">{{ $namaMasjid }} — {{ $mosque->city ?? 'Indonesia' }}</div>
                <div class="ba2-banner-sub">
                    Sistem Informasi Masjid versi 1.0 &middot; {{ $jumlahAktif }} dari {{ $jumlahTotal }} modul aktif
                    @if($paketFree) &middot; Upgrade paket untuk membuka modul terkunci @endif
                </div>

                {{-- Progress modul aktif --}}
                <div style="height:8px;border-radius:100px;background:rgba(255,255,255,.18);overflow:hidden;margin-bottom:14px;max-width:420px;">
                    <div style="height:100%;width:{{ $persen }}%;background:#f5a623;border-radius:100px;"></div>
                </div>

                <div class="ba2-banner-tags">
                    @foreach($modules as $m)
                        <span class="ba2-banner-tag {{ $m['status'] === 'aktif' ? 'active' : 'soon' }}">
                            {{ $m['label'] }}
                            @if($m['status'] === 'aktif') ✓ @elseif($m['status'] === 'locked') 🔒 @endif
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- ===== STATS CARDS ===== --}}
            @php
                $iconBox = 'width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;';
            @endphp
            <div class="ba2-stats-grid">
                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon" style="{{ $iconBox }}background:#e8e9fd;color:#5457e0;"><i class="fa-solid fa-users"></i></span>
                        <span class="ba2-stat-badge green">Aktif</span>
                    </div>
                    <div class="ba2-stat-value">{{ number_format($totalJamaah ?? 0, 0, ',', '.') }}</div>
                    <div class="ba2-stat-label">Total Jamaah</div>
                </div>

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon" style="{{ $iconBox }}background:#dff1fb;color:#0b86c4;"><i class="fa-solid fa-credit-card"></i></span>
                        <span class="ba2-stat-badge blue">Bulan Ini</span>
                    </div>
                    <div class="ba2-stat-value">Rp {{ number_format($totalDonasiBulanIni ?? 0, 0, ',', '.') }}</div>
                    <div class="ba2-stat-label">Donasi Bulan Ini</div>
                </div>

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon" style="{{ $iconBox }}background:#fdf0d5;color:#a8741a;"><i class="fa-solid fa-calendar-days"></i></span>
                        <span class="ba2-stat-badge amber">Agenda</span>
                    </div>
                    <div class="ba2-stat-value">{{ $totalAcara ?? 0 }}</div>
                    <div class="ba2-stat-label">Acara Aktif</div>
                </div>

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon" style="{{ $iconBox }}background:#fde4e3;color:#cf4440;"><i class="fa-solid fa-bullhorn"></i></span>
                        <span class="ba2-stat-badge red">Informasi</span>
                    </div>
                    <div class="ba2-stat-value">{{ $totalPengumuman ?? 0 }}</div>
                    <div class="ba2-stat-label">Pengumuman</div>
                </div>
            </div>

            {{-- ===== AKTIVITAS + STATUS MODUL ===== --}}
            <div class="ba2-row-2">
                <div class="ba2-card">
                    <div class="ba2-card-head">
                        <span class="ba2-card-title">Aktivitas Terkini</span>
                        <span class="ba2-card-meta">Sistem</span>
                    </div>
                    <div class="ba2-card-body">
                        @forelse($aktivitas as $a)
                            <div class="ba2-activity-item">
                                <span class="ba2-dot {{ $a['color'] }}"></span>
                                <span class="ba2-activity-text">{{ $a['teks'] }}</span>
                                <span class="ba2-activity-time">{{ $a['waktu'] }}</span>
                            </div>
                        @empty
                            <div class="ba2-activity-item">
                                <span class="ba2-dot gray"></span>
                                <span class="ba2-activity-text">Belum ada aktivitas.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="ba2-card">
                    <div class="ba2-card-head">
                        <span class="ba2-card-title">Status Modul</span>
                        <span class="ba2-card-meta">{{ $jumlahAktif }}/{{ $jumlahTotal }} aktif</span>
                    </div>
                    <div class="ba2-card-body">
                        @foreach($modules as $m)
                            <a href="{{ $m['url'] }}" class="ba2-modul-item"
                               style="text-decoration:none;color:inherit;{{ $m['status'] === 'segera' ? 'opacity:.6;pointer-events:none;' : '' }}">
                                <span class="ba2-modul-icon"><i class="fa-solid {{ $m['icon'] }}"></i></span>
                                <span class="ba2-modul-name">{{ $m['label'] }}</span>
                                <span class="ba2-modul-status {{ $m['status'] === 'aktif' ? 'aktif' : 'segera' }}"
                                      @if($m['status'] === 'locked') style="background:#fee2e2;color:#991b1b;" @endif>
                                    {{ $labelStatus[$m['status']] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </main>
    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
        document.getElementById('ba2CollapseBtn').addEventListener('click', function () {
            document.getElementById('ba2Sidebar').classList.toggle('collapsed');
            document.getElementById('ba2Main').classList.toggle('expanded');
        });
    </script>
</body>
</html>