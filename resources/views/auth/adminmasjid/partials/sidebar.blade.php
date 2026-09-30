{{--
    Sidebar admin masjid.
    Pemakaian: @include('auth.adminmasjid.partials.sidebar', ['active' => 'jadwal-sholat'])
    Nilai $active: dashboard | landing-page | profil-masjid | jadwal-sholat | acara | donasi | jamaah
--}}
@php
    $active = $active ?? '';
    $cls    = fn (string $key) => $active === $key ? 'ba2-nav-item active' : 'ba2-nav-item';
    $aria   = fn (string $key) => $active === $key ? 'aria-current="page"' : '';

    // Aturan yang sama dengan PublicMosqueController::hasDonationFeature()
    $donationLocked = !isset($mosque)
        || ($mosque->package_type ?? 'free') === 'free'
        || strtolower(trim($mosque->payment_status ?? '')) !== 'approved';

    $userName = auth()->user()->name ?? 'Admin Masjid';
@endphp

<aside class="ba2-sidebar no-print" id="ba2Sidebar">
    <div class="ba2-brand">
        <div class="ba2-brand-avatar">
            <i class="fa-solid fa-mosque" aria-hidden="true"></i>
        </div>
        <div class="ba2-brand-info">
            <div class="ba2-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</div>
            <div class="ba2-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</div>
        </div>
    </div>

    <nav class="ba2-nav" aria-label="Menu admin">
        <a href="{{ route('admin.dashboard') }}" class="{{ $cls('dashboard') }}" {!! $aria('dashboard') !!}>
            <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
            <span class="ba2-nav-label">Dashboard</span>
        </a>
        <a href="{{ route('admin.landing-page') }}" class="{{ $cls('landing-page') }}" {!! $aria('landing-page') !!}>
            <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
            <span class="ba2-nav-label">Landing Page</span>
        </a>
        <a href="{{ route('admin.profil-masjid') }}" class="{{ $cls('profil-masjid') }}" {!! $aria('profil-masjid') !!}>
            <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
            <span class="ba2-nav-label">Profil Masjid</span>
        </a>
        <a href="{{ route('admin.jadwal-sholat') }}" class="{{ $cls('jadwal-sholat') }}" {!! $aria('jadwal-sholat') !!}>
            <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
            <span class="ba2-nav-label">Jadwal Shalat</span>
        </a>
        <a href="#" class="ba2-nav-item" aria-disabled="true">
            <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
            <span class="ba2-nav-label">Pengumuman</span>
            <span class="ba2-nav-soon">Segera</span>
        </a>
        <a href="{{ route('admin.acara') }}" class="{{ $cls('acara') }}" {!! $aria('acara') !!}>
            <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
            <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
        </a>

        @if($donationLocked)
            <a href="{{ route('masjid.perpanjangan.create') }}" class="ba2-nav-item" style="opacity: .8;" title="Upgrade paket untuk membuka fitur donasi">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                <span class="ba2-nav-soon" style="background: #e74c3c; color: #fff;">Terkunci</span>
            </a>
        @else
            <a href="{{ route('admin.donasi') }}" class="{{ $cls('donasi') }}" {!! $aria('donasi') !!}>
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                <span class="ba2-nav-soon" style="background: #27ae60; color: #fff;">Aktif</span>
            </a>
        @endif

        <a href="{{ route('admin.jamaah') }}" class="{{ $cls('jamaah') }}" {!! $aria('jamaah') !!}>
            <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
            <span class="ba2-nav-label">Data Jamaah</span>
        </a>
    </nav>

    <div class="ba2-user">
        <div class="ba2-user-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($userName, 0, 2)) }}</div>
        <div class="ba2-user-info">
            <div class="ba2-user-name">{{ $userName }}</div>
            <div class="ba2-user-email">{{ auth()->user()->email ?? 'admin@baituldigital.id' }}</div>
        </div>
    </div>
</aside>