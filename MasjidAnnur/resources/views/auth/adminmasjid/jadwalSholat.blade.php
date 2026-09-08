<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Shalat - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/jadwalSholat.css') }}">
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">

        <div class="ba2-brand">
            <div class="ba2-brand-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</div>
                <div class="ba2-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</div>
            </div>
        </div>

        <nav class="ba2-nav">
           <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="ba2-nav-item {{ request()->routeIs('admin.landing-page') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="ba2-nav-item {{ request()->routeIs('admin.profil-masjid') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="{{ route('admin.jadwal-sholat') }}" class="ba2-nav-item {{ request()->routeIs('admin.jadwal-sholat') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                <span class="ba2-nav-badge">3</span>
            </a>
            <a href="{{ route('admin.acara') }}" class="ba2-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
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

        <div class="ba2-user">
            <div class="ba2-user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 2) }}</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">{{ auth()->user()->name ?? 'Admin Masjid' }}</div>
                <div class="ba2-user-email">{{ auth()->user()->email ?? 'admin@baituldigital.id' }}</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

        {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Jadwal Shalat</div>
                <div class="ba2-page-sub">Kelola modul Jadwal Shalat</div>
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
        <main class="js-content">
            <div class="js-coming-soon">
                <div class="js-icon">🚧</div>
                <h2 class="js-title">Modul Jadwal Shalat</h2>
                <p class="js-desc">
                    Modul ini sedang dalam pengembangan.<br>
                    Akan tersedia di versi berikutnya.
                </p>
                <span class="js-badge">Segera hadir — Stay tuned!</span>
            </div>
        </main>

    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

</body>
</html>