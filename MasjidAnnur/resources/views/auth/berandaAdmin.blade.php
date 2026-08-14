<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Masjid Annur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/berandaAdmin.css') }}">
</head>
<body class="admin-page" id="adminBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba-sidebar" id="baSidebar">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="ba-brand">
            <div class="ba-brand-icon">
                {{-- Mosque / building SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="ba-brand-text">
                <strong>SIM Masjid</strong>
                <span>Baitul Digital</span>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="ba-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba-nav-item active">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Profil Masjid</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Jadwal Shalat</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Pengumuman</span>
                <span class="ba-nav-badge">3</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Kegiatan &amp; Acara</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Donasi</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Data Jamaah</span>
                <span class="ba-nav-soon">dev</span>
            </a>
        </nav>

        {{-- User --}}
        <div class="ba-user">
            <div class="ba-user-avatar">AM</div>
            <div class="ba-user-info">
                <div class="ba-user-name">Admin Masjid</div>
                <div class="ba-user-email">admin@baituldigital.id</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba-main">

        {{-- Topbar --}}
        <header class="ba-topbar">
            <div class="ba-topbar-left">
                <button class="ba-toggle-btn" id="baToggle" aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <div class="ba-page-title">Dashboard</div>
                    <div class="ba-page-sub">Selamat datang di panel admin SIM Masjid</div>
                </div>
            </div>
            <div class="ba-topbar-right">
                <a href="{{ url('/') }}" class="ba-btn-public">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Kembali ke Publik
                </a>
                <button class="ba-notif-btn" aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <span class="ba-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="ba-content">

            {{-- Stats Cards --}}
            <div class="ba-stats-grid">
                <div class="ba-stat-card">
                    <div class="ba-stat-top">
                        <span class="ba-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#6366f1" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </span>
                        <span class="ba-stat-badge green">+38</span>
                    </div>
                    <div class="ba-stat-value">2.412</div>
                    <div class="ba-stat-label">Total Jamaah</div>
                </div>

                <div class="ba-stat-card">
                    <div class="ba-stat-top">
                        <span class="ba-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#0ea5e9" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                            </svg>
                        </span>
                        <span class="ba-stat-badge blue">+15%</span>
                    </div>
                    <div class="ba-stat-value">Rp 12,4 Jt</div>
                    <div class="ba-stat-label">Donasi Bulan Ini</div>
                </div>

                <div class="ba-stat-card">
                    <div class="ba-stat-top">
                        <span class="ba-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#f59e0b" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </span>
                        <span class="ba-stat-badge amber">bulan ini</span>
                    </div>
                    <div class="ba-stat-value">3</div>
                    <div class="ba-stat-label">Acara Aktif</div>
                </div>

                <div class="ba-stat-card">
                    <div class="ba-stat-top">
                        <span class="ba-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#ef4444" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/>
                            </svg>
                        </span>
                        <span class="ba-stat-badge red">2 belum dibaca</span>
                    </div>
                    <div class="ba-stat-value">7</div>
                    <div class="ba-stat-label">Pengumuman</div>
                </div>
            </div>

            {{-- Aktivitas + Status Modul --}}
            <div class="ba-row-2">

                <div class="ba-card">
                    <div class="ba-card-head">
                        <span class="ba-card-title">Aktivitas Terkini</span>
                        <span class="ba-card-meta">Hari ini</span>
                    </div>
                    <div class="ba-card-body">
                        <div class="ba-activity-item">
                            <span class="ba-dot green"></span>
                            <span class="ba-activity-text">Donasi baru dari Hamba Allah — Rp 250.000</span>
                            <span class="ba-activity-time">09:32</span>
                        </div>
                        <div class="ba-activity-item">
                            <span class="ba-dot blue"></span>
                            <span class="ba-activity-text">Jamaah baru terdaftar: Ahmad Fulan</span>
                            <span class="ba-activity-time">08:15</span>
                        </div>
                        <div class="ba-activity-item">
                            <span class="ba-dot amber"></span>
                            <span class="ba-activity-text">Pengumuman "Jadwal Ramadan" dipublikasikan</span>
                            <span class="ba-activity-time">Kemarin</span>
                        </div>
                        <div class="ba-activity-item">
                            <span class="ba-dot purple"></span>
                            <span class="ba-activity-text">Acara "Kajian Fiqih" diperbarui</span>
                            <span class="ba-activity-time">Kemarin</span>
                        </div>
                        <div class="ba-activity-item">
                            <span class="ba-dot gray"></span>
                            <span class="ba-activity-text">Profil masjid diperbarui oleh Admin</span>
                            <span class="ba-activity-time">3 hari lalu</span>
                        </div>
                    </div>
                </div>

                <div class="ba-card">
                    <div class="ba-card-head">
                        <span class="ba-card-title">Status Modul</span>
                    </div>
                    <div class="ba-card-body">
                        @php
                            $modules = [
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/>', 'name' => 'Landing Page',      'status' => 'aktif'],
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21"/>', 'name' => 'Profil Masjid',      'status' => 'aktif'],
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'name' => 'Jadwal Shalat',     'status' => 'segera'],
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09"/>', 'name' => 'Pengumuman',        'status' => 'segera'],
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"/>', 'name' => 'Kegiatan &amp; Acara', 'status' => 'segera'],
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75"/>', 'name' => 'Donasi',           'status' => 'segera'],
                                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"/>', 'name' => 'Data Jamaah',      'status' => 'segera'],
                            ];
                        @endphp
                        @foreach($modules as $mod)
                            <div class="ba-modul-item">
                                <span class="ba-modul-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.8" stroke="currentColor" width="18" height="18">
                                        {!! $mod['icon'] !!}
                                    </svg>
                                </span>
                                <span class="ba-modul-name">{!! $mod['name'] !!}</span>
                                <span class="ba-modul-status {{ $mod['status'] }}">
                                    {{ $mod['status'] === 'aktif' ? 'Aktif' : 'Segera' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Info Banner --}}
            <div class="ba-banner">
                <div class="ba-banner-title">SIM Masjid — Baitul Digital</div>
                <div class="ba-banner-sub">Sistem Informasi Masjid versi 1.0 · Modul aktif: Landing Page, Profil Masjid</div>
                <div class="ba-banner-tags">
                    <span class="ba-banner-tag active">Modul Landing Page &#10003;</span>
                    <span class="ba-banner-tag active">Modul Profil Masjid &#10003;</span>
                    <span class="ba-banner-tag soon">Modul Donasi (coming soon)</span>
                    <span class="ba-banner-tag soon">Modul Berita (coming soon)</span>
                </div>
            </div>

        </main>
    </div>

    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

    <script>
        document.getElementById('baToggle').addEventListener('click', () => {
            document.getElementById('baSidebar').classList.toggle('collapsed');
            document.getElementById('adminBody').classList.toggle('sidebar-collapsed');
        });
    </script>
</body>
</html>
