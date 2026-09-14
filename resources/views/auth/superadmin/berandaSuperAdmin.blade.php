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
        <main class="sa-content">

            {{-- ===== STATS CARDS ===== --}}
            <div class="sa-stats-grid">
                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">🕌</span>
                        <span class="sa-stat-dot green"></span>
                    </div>
                    <div class="sa-stat-value">{{ $totalMasjid }}</div>
                    <div class="sa-stat-label">Total Masjid</div>
                    <div class="sa-stat-sub">{{ $masjidAktif }} aktif, {{ $masjidPending }} pending</div>
                </div>

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">💳</span>
                        <span class="sa-stat-dot amber"></span>
                    </div>
                    <div class="sa-stat-value">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</div>
                    <div class="sa-stat-label">Total Donasi Terkumpul</div>
                    <div class="sa-stat-sub">Akumulasi sistem</div>
                </div>

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">✅</span>
                        <span class="sa-stat-dot green"></span>
                    </div>
                    <div class="sa-stat-value">{{ $masjidAktif }}</div>
                    <div class="sa-stat-label">Masjid Aktif</div>
                    <div class="sa-stat-sub">Beroperasi normal</div>
                </div>

                <div class="sa-stat-card">
                    <div class="sa-stat-top">
                        <span class="sa-stat-emoji">⏳</span>
                        <span class="sa-stat-dot amber"></span>
                    </div>
                    <div class="sa-stat-value">{{ $masjidPending }}</div>
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
                        <span class="sa-card-meta">{{ $totalMasjid }} tenant</span>
                    </div>
                    <div class="sa-card-body">

                        @forelse($mosques as $m)
                        <div class="sa-mosque-row">
                            <div class="sa-mosque-avatar" style="background: #1a4731">{{ strtoupper(substr($m->nama_masjid ?? 'M', 0, 1)) }}</div>
                            <div class="sa-mosque-info">
                                <div class="sa-mosque-name-row">
                                    <span class="sa-mosque-name">{{ $m->nama_masjid ?? '-' }}</span>
                                    <span class="sa-mosque-status {{ $m->status ?? 'pending' }}">{{ $m->status ?? 'pending' }}</span>
                                </div>
                                <div class="sa-mosque-progress-bar">
                                    <div class="sa-mosque-progress-fill {{ $m->status ?? 'pending' }}" style="width: {{ $m->persentase ?? 0 }}%"></div>
                                </div>
                                <div class="sa-mosque-donasi">Rp {{ number_format($m->total_donasi ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="sa-mosque-meta">
                                <div class="sa-mosque-kota">{{ $m->kota ?? 'Indonesia' }}</div>
                                <div class="sa-mosque-jamaah">{{ $m->jumlah_jamaah ?? '0' }} jamaah</div>
                            </div>
                            <a href="#" class="sa-mosque-kelola">Kelola →</a>
                        </div>
                        @empty
                        <p style="text-align: center; color: #6b7280; padding: 20px;">Belum ada data masjid di database.</p>
                        @endforelse

                    </div>
                </div>

                {{-- Donasi Terkini --}}
                <div class="sa-card sa-card-donasi">
                    <div class="sa-card-head">
                        <span class="sa-card-title">Donasi Terkini</span>
                    </div>
                    <div class="sa-card-body">
                        @forelse($donasiTerkini as $d)
                        <div class="sa-donasi-item">
                            <div class="sa-donasi-icon">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                            <div class="sa-donasi-detail">
                                <div class="sa-donasi-nominal">Rp {{ number_format($d->nominal ?? 0, 0, ',', '.') }}</div>
                                <div class="sa-donasi-meta">{{ $d->nama_donatur ?? 'Hamba Allah' }} → {{ $d->masjid->nama_masjid ?? 'Pusat' }}</div>
                            </div>
                            <div class="sa-donasi-waktu">Baru saja</div>
                        </div>
                        @empty
                        <p style="text-align: center; color: #6b7280; padding: 20px;">Belum ada data transaksi donasi.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ===== PROGRESS DONASI PER MASJID ===== --}}
            <div class="sa-card sa-card-progress">
                <div class="sa-card-head">
                    <span class="sa-card-title">Progress Donasi per Masjid</span>
                </div>
                <div class="sa-card-body sa-progress-list">

                    @forelse($mosques as $m)
                    <div class="sa-progress-item">
                        <div class="sa-progress-header">
                            <span class="sa-progress-dot" style="background:#1a4731"></span>
                            <span class="sa-progress-mosque-name">{{ $m->nama_masjid ?? '-' }}</span>
                            <span class="sa-progress-kampanye">— Pembangunan & Operasional</span>
                            <span class="sa-progress-terkumpul">Rp {{ number_format($m->total_donasi ?? 0, 0, ',', '.') }}</span>
                            <span class="sa-progress-pct">{{ $m->persentase ?? 0 }}%</span>
                        </div>
                        <div class="sa-progress-track">
                            <div class="sa-progress-fill" style="width: {{ $m->persentase ?? 0 }}%; background: #f59e0b"></div>
                        </div>
                        <div class="sa-progress-target">Target: Rp {{ number_format($m->target_donasi ?? 0, 0, ',', '.') }}</div>
                    </div>
                    @empty
                    <p style="text-align: center; color: #6b7280; padding: 20px;">Belum ada data progress.</p>
                    @endforelse

                </div>
            </div>

        </main>
    </div>

</body>
</html>