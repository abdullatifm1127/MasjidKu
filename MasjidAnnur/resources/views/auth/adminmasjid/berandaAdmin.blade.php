<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">

        {{-- Brand --}}
        <div class="ba2-brand">
            <div class="ba2-brand-avatar">A</div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">SIM Masjid</div>
                <div class="ba2-brand-sub">Baitul Digital</div>
            </div>
            <button class="ba2-collapse-btn" id="ba2CollapseBtn" aria-label="Collapse">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="ba2-nav">
            <a href="{{ route('admin2.dashboard') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin2.landing-page') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin2.profil-masjid') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                <span class="ba2-nav-badge">3</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="ba2-user">
            <div class="ba2-user-avatar">A</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">Admin Masjid</div>
                <div class="ba2-user-email">admin@baituldigital.id</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

        {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Dashboard</div>
                <div class="ba2-page-sub">Selamat datang di panel admin SIM Masjid</div>
            </div>
            <div class="ba2-topbar-right">
                <a href="{{ url('/') }}" class="ba2-btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Publik
                </a>
                <button class="ba2-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="ba2-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="ba2-content">

            {{-- ===== STATS CARDS ===== --}}
            <div class="ba2-stats-grid">

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon"><i class="fa-solid fa-users" style="color:#6366f1;font-size:1.3rem;"></i></span>
                        <span class="ba2-stat-badge green">+38</span>
                    </div>
                    <div class="ba2-stat-value">2.412</div>
                    <div class="ba2-stat-label">Total Jamaah</div>
                </div>

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon"><i class="fa-solid fa-credit-card" style="color:#0ea5e9;font-size:1.3rem;"></i></span>
                        <span class="ba2-stat-badge blue">+15%</span>
                    </div>
                    <div class="ba2-stat-value">Rp 12,4 Jt</div>
                    <div class="ba2-stat-label">Donasi Bulan Ini</div>
                </div>

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon"><i class="fa-solid fa-calendar-days" style="color:#f59e0b;font-size:1.3rem;"></i></span>
                        <span class="ba2-stat-badge amber">bulan ini</span>
                    </div>
                    <div class="ba2-stat-value">3</div>
                    <div class="ba2-stat-label">Acara Aktif</div>
                </div>

                <div class="ba2-stat-card">
                    <div class="ba2-stat-top">
                        <span class="ba2-stat-icon"><i class="fa-solid fa-bullhorn" style="color:#ef4444;font-size:1.3rem;"></i></span>
                        <span class="ba2-stat-badge red">2 belum dibaca</span>
                    </div>
                    <div class="ba2-stat-value">7</div>
                    <div class="ba2-stat-label">Pengumuman</div>
                </div>

            </div>

            {{-- ===== ROW: AKTIVITAS + STATUS MODUL ===== --}}
            <div class="ba2-row-2">

                {{-- Aktivitas --}}
                <div class="ba2-card">
                    <div class="ba2-card-head">
                        <span class="ba2-card-title">Aktivitas Terkini</span>
                        <span class="ba2-card-meta">Hari ini</span>
                    </div>
                    <div class="ba2-card-body">
                        @php
                            $aktivitas = [
                                ['color' => 'green',  'teks' => 'Donasi baru dari Hamba Allah — Rp 250.000', 'waktu' => '09:32'],
                                ['color' => 'blue',   'teks' => 'Jamaah baru terdaftar: Ahmad Fulan',         'waktu' => '08:15'],
                                ['color' => 'amber',  'teks' => 'Pengumuman "Jadwal Ramadan" dipublikasikan', 'waktu' => 'Kemarin'],
                                ['color' => 'purple', 'teks' => 'Acara "Kajian Fiqih" diperbarui',            'waktu' => 'Kemarin'],
                                ['color' => 'gray',   'teks' => 'Profil masjid diperbarui oleh Admin',        'waktu' => '3 hari lalu'],
                            ];
                        @endphp
                        @foreach($aktivitas as $a)
                        <div class="ba2-activity-item">
                            <span class="ba2-dot {{ $a['color'] }}"></span>
                            <span class="ba2-activity-text">{{ $a['teks'] }}</span>
                            <span class="ba2-activity-time">{{ $a['waktu'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status Modul --}}
                <div class="ba2-card">
                    <div class="ba2-card-head">
                        <span class="ba2-card-title">Status Modul</span>
                    </div>
                    <div class="ba2-card-body">
                        @php
                            $moduls = [
                                ['icon' => 'fa-globe',            'name' => 'Landing Page',    'status' => 'aktif'],
                                ['icon' => 'fa-mosque',           'name' => 'Profil Masjid',   'status' => 'aktif'],
                                ['icon' => 'fa-clock',            'name' => 'Jadwal Shalat',   'status' => 'segera'],
                                ['icon' => 'fa-bullhorn',         'name' => 'Pengumuman',      'status' => 'segera'],
                                ['icon' => 'fa-calendar-days',    'name' => 'Kegiatan & Acara','status' => 'segera'],
                                ['icon' => 'fa-hand-holding-dollar','name' => 'Donasi',        'status' => 'segera'],
                                ['icon' => 'fa-users',            'name' => 'Data Jamaah',     'status' => 'segera'],
                            ];
                        @endphp
                        @foreach($moduls as $m)
                        <div class="ba2-modul-item">
                            <span class="ba2-modul-icon"><i class="fa-solid {{ $m['icon'] }}"></i></span>
                            <span class="ba2-modul-name">{{ $m['name'] }}</span>
                            <span class="ba2-modul-status {{ $m['status'] }}">
                                {{ $m['status'] === 'aktif' ? 'Aktif' : 'Segera' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ===== BANNER ===== --}}
            <div class="ba2-banner">
                <div class="ba2-banner-title">SIM Masjid — Baitul Digital</div>
                <div class="ba2-banner-sub">Sistem Informasi Masjid versi 1.0 · Modul aktif: Landing Page, Profil Masjid</div>
                <div class="ba2-banner-tags">
                    <span class="ba2-banner-tag active">Modul Landing Page ✓</span>
                    <span class="ba2-banner-tag active">Modul Profil Masjid ✓</span>
                    <span class="ba2-banner-tag soon">Modul Donasi (coming soon)</span>
                    <span class="ba2-banner-tag soon">Modul Berita (coming soon)</span>
                </div>
            </div>

        </main>
    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
        document.getElementById('ba2CollapseBtn').addEventListener('click', () => {
            document.getElementById('ba2Sidebar').classList.toggle('collapsed');
            document.getElementById('ba2Main').classList.toggle('expanded');
        });
    </script>
</body>
</html>
