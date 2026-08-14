<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="sa-page" id="saBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sa-sidebar" id="saSidebar">

        {{-- Brand --}}
        <div class="sa-brand">
            <div class="sa-brand-avatar">SA</div>
            <div class="sa-brand-info">
                <strong>MasjidKu Admin</strong>
                <span>Super Administrator</span>
            </div>
            <button class="sa-sidebar-toggle" id="saSidebarToggle" aria-label="Collapse sidebar">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="sa-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item active">
                <span class="sa-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="sa-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item sa-nav-has-badge">
                <span class="sa-nav-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                <span class="sa-nav-badge-dot amber"></span>
            </a>

            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="sa-nav-label">Manajemen Masjid</span>
            </a>

            <a href="{{ route('superadmin.pengguna') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="sa-nav-label">Pengguna</span>
            </a>

            <a href="{{ route('superadmin.pengaturan') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-gear"></i></span>
                <span class="sa-nav-label">Pengaturan</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="sa-user-footer">
            <div class="sa-user-avatar-sm">SA</div>
            <div class="sa-user-info">
                <div class="sa-user-name">Super Admin</div>
                <div class="sa-user-email">admin@masjidku.id</div>
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

        {{-- Topbar --}}
        <header class="sa-topbar">
            <div class="sa-topbar-title">Dashboard</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    Lihat Website
                </a>
                <button class="sa-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="sa-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="sa-content">

            {{-- ===== STATS CARDS ===== --}}
            <div class="sa-stats-grid">

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">🕌</span>
                        <span class="sa-stat-dot green"></span>
                    </div>
                    <div class="sa-stat-value">3</div>
                    <div class="sa-stat-label">Total Masjid</div>
                    <div class="sa-stat-sub">2 aktif, 1 pending</div>
                </div>

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">💳</span>
                        <span class="sa-stat-dot amber"></span>
                    </div>
                    <div class="sa-stat-value">Rp 1.9 M</div>
                    <div class="sa-stat-label">Omzet Bulan ini</div>
                    <div class="sa-stat-sub">dari target Rp 2.6 M</div>
                </div>

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">✅</span>
                        <span class="sa-stat-dot green"></span>
                    </div>
                    <div class="sa-stat-value">2</div>
                    <div class="sa-stat-label">Masjid Aktif</div>
                    <div class="sa-stat-sub">Beroperasi normal</div>
                </div>

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">⏳</span>
                        <span class="sa-stat-dot amber"></span>
                    </div>
                    <div class="sa-stat-value">1</div>
                    <div class="sa-stat-label">Menunggu Verifikasi</div>
                    <div class="sa-stat-sub">perlu tindakan</div>
                </div>

            </div>

            {{-- ===== ROW: SEMUA MASJID + DONASI TERKINI ===== --}}
            <div class="sa-row-main">

                {{-- Semua Masjid --}}
                <div class="sa-card sa-card-mosques">
                    <div class="sa-card-head">
                        <span class="sa-card-title">Semua Masjid</span>
                        <span class="sa-card-meta">3 tenant</span>
                    </div>
                    <div class="sa-card-body">

                        @php
                            $mosques = [
                                [
                                    'initial' => 'B',
                                    'color'   => '#1a4731',
                                    'name'    => 'Baitul Digital',
                                    'status'  => 'aktif',
                                    'donasi'  => 'Rp 612 Jt',
                                    'kota'    => 'Jakarta Selatan',
                                    'jamaah'  => '2.500 jamaah',
                                    'progress'=> 72,
                                ],
                                [
                                    'initial' => 'M',
                                    'color'   => '#1e40af',
                                    'name'    => 'Masjid Ar-Rahman',
                                    'status'  => 'aktif',
                                    'donasi'  => 'Rp 387 Jt',
                                    'kota'    => 'Bandung',
                                    'jamaah'  => '1.800 jamaah',
                                    'progress'=> 77,
                                ],
                                [
                                    'initial' => 'M',
                                    'color'   => '#374151',
                                    'name'    => 'Masjid Al-Aqsa',
                                    'status'  => 'pending',
                                    'donasi'  => 'Rp 940 Jt',
                                    'kota'    => 'Surabaya',
                                    'jamaah'  => '3.200 jamaah',
                                    'progress'=> 78,
                                ],
                            ];
                        @endphp

                        @foreach($mosques as $m)
                        <div class="sa-mosque-row">
                            <div class="sa-mosque-avatar" style="background:{{ $m['color'] }}">{{ $m['initial'] }}</div>
                            <div class="sa-mosque-info">
                                <div class="sa-mosque-name-row">
                                    <span class="sa-mosque-name">{{ $m['name'] }}</span>
                                    <span class="sa-mosque-status {{ $m['status'] }}">{{ $m['status'] }}</span>
                                </div>
                                <div class="sa-mosque-progress-bar">
                                    <div class="sa-mosque-progress-fill {{ $m['status'] }}" style="width:{{ $m['progress'] }}%"></div>
                                </div>
                                <div class="sa-mosque-donasi">{{ $m['donasi'] }}</div>
                            </div>
                            <div class="sa-mosque-meta">
                                <div class="sa-mosque-kota">{{ $m['kota'] }}</div>
                                <div class="sa-mosque-jamaah">{{ $m['jamaah'] }}</div>
                            </div>
                            <a href="#" class="sa-mosque-kelola">Kelola →</a>
                        </div>
                        @endforeach

                    </div>
                </div>

                {{-- Donasi Terkini --}}
                <div class="sa-card sa-card-donasi">
                    <div class="sa-card-head">
                        <span class="sa-card-title">Donasi Terkini</span>
                    </div>
                    <div class="sa-card-body">

                        @php
                            $donasi = [
                                ['nominal' => 'Rp 500.000', 'dari' => 'Hamba Allah',  'ke' => 'Baitul Digital',    'waktu' => '5 mnt lalu'],
                                ['nominal' => 'Rp 250.000', 'dari' => 'Ahmad F.',      'ke' => 'Masjid Ar-Rahman',  'waktu' => '1 jam lalu'],
                                ['nominal' => 'Rp 1 Jt',   'dari' => 'Anonim',        'ke' => 'Masjid Al-Aqsa',    'waktu' => '3 jam lalu'],
                                ['nominal' => 'Rp 150.000','dari' => 'Hj. Siti',      'ke' => 'Baitul Digital',    'waktu' => '5 jam lalu'],
                            ];
                        @endphp

                        @foreach($donasi as $d)
                        <div class="sa-donasi-item">
                            <div class="sa-donasi-icon">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                            <div class="sa-donasi-detail">
                                <div class="sa-donasi-nominal">{{ $d['nominal'] }}</div>
                                <div class="sa-donasi-meta">{{ $d['dari'] }} → {{ $d['ke'] }}</div>
                            </div>
                            <div class="sa-donasi-waktu">{{ $d['waktu'] }}</div>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>

            {{-- ===== PROGRESS DONASI PER MASJID ===== --}}
            <div class="sa-card sa-card-progress">
                <div class="sa-card-head">
                    <span class="sa-card-title">Progress Donasi per Masjid</span>
                </div>
                <div class="sa-card-body sa-progress-list">

                    @php
                        $progressData = [
                            [
                                'dot'    => '#1a4731',
                                'name'   => 'Baitul Digital',
                                'kampanye'=> 'Renovasi Lantai 2 & Menara',
                                'terkumpul'=> 'Rp 612 Jt',
                                'pct'    => 72,
                                'target' => 'Target: Rp 850 Jt',
                                'color'  => '#f59e0b',
                            ],
                            [
                                'dot'    => '#1e40af',
                                'name'   => 'Masjid Ar-Rahman',
                                'kampanye'=> 'Pembangunan Aula Serbaguna',
                                'terkumpul'=> 'Rp 387 Jt',
                                'pct'    => 77,
                                'target' => 'Target: Rp 500 Jt',
                                'color'  => '#f59e0b',
                            ],
                            [
                                'dot'    => '#374151',
                                'name'   => 'Masjid Al-Aqsa',
                                'kampanye'=> 'Restorasi & Pelestarian Bangunan Cagar Budaya',
                                'terkumpul'=> 'Rp 940 Jt',
                                'pct'    => 78,
                                'target' => 'Target: Rp 1.2 M',
                                'color'  => '#1a4731',
                            ],
                        ];
                    @endphp

                    @foreach($progressData as $p)
                    <div class="sa-progress-item">
                        <div class="sa-progress-header">
                            <span class="sa-progress-dot" style="background:{{ $p['dot'] }}"></span>
                            <span class="sa-progress-mosque-name">{{ $p['name'] }}</span>
                            <span class="sa-progress-kampanye">— {{ $p['kampanye'] }}</span>
                            <span class="sa-progress-terkumpul">{{ $p['terkumpul'] }}</span>
                            <span class="sa-progress-pct">{{ $p['pct'] }}%</span>
                        </div>
                        <div class="sa-progress-track">
                            <div class="sa-progress-fill" style="width:{{ $p['pct'] }}%; background:{{ $p['color'] }}"></div>
                        </div>
                        <div class="sa-progress-target">{{ $p['target'] }}</div>
                    </div>
                    @endforeach

                </div>
            </div>

        </main>
    </div>

    <script>
        // Sidebar collapse toggle
        document.getElementById('saSidebarToggle').addEventListener('click', () => {
            document.getElementById('saSidebar').classList.toggle('collapsed');
            document.getElementById('saMain').classList.toggle('expanded');
        });
    </script>
</body>
</html>
