<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Shalat &amp; Kalender Imsakiyah - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <style>
        :root {
            --jx-pri: #0f766e; --jx-pri2: #0d9488; --jx-dark: #0b3d3a; --jx-gold: #f59e0b;
            --jx-text: #1e293b; --jx-muted: #64748b; --jx-border: #e2e8f0; --jx-soft: #f8fafc; --jx-danger: #dc2626;
        }
        body { font-family: 'Poppins', sans-serif; }
        .jx-wrap { padding: 24px; display: flex; flex-direction: column; gap: 22px; }

        /* ===== Hero ===== */
        .jx-hero { border-radius: 22px; padding: 26px 30px; color: #fff; display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;
                   background: radial-gradient(900px 260px at 95% -30%, rgba(245,158,11,.38), transparent), linear-gradient(135deg, var(--jx-dark), var(--jx-pri));
                   box-shadow: 0 12px 32px rgba(15,118,110,.28); }
        .jx-eyebrow { display: inline-flex; gap: 8px; align-items: center; font-size: .78rem; font-weight: 500; padding: 4px 12px; border-radius: 999px; background: rgba(255,255,255,.14); }
        .jx-date { margin: 10px 0 2px; font-size: 1.5rem; font-weight: 700; }
        .jx-clock { font-size: .95rem; opacity: .85; font-variant-numeric: tabular-nums; }
        .jx-next-lbl { font-size: .72rem; letter-spacing: .12em; text-transform: uppercase; opacity: .75; text-align: right; }
        .jx-next-name { font-size: 1.25rem; font-weight: 700; color: #fde68a; text-align: right; margin-bottom: 8px; }
        .jx-cd { display: flex; gap: 8px; justify-content: flex-end; }
        .jx-cd div { min-width: 64px; text-align: center; padding: 9px 6px; border-radius: 12px; background: rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.12); }
        .jx-cd b { display: block; font-size: 1.55rem; font-variant-numeric: tabular-nums; line-height: 1.1; }
        .jx-cd span { font-size: .6rem; text-transform: uppercase; letter-spacing: .1em; opacity: .7; }

        .jx-alert { display: flex; gap: 10px; padding: 12px 14px; border-radius: 12px; background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; font-size: .85rem; }
        .jx-flash { display: flex; gap: 10px; padding: 12px 14px; border-radius: 12px; font-size: .85rem; }
        .jx-flash.ok { background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; }
        .jx-flash.err { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .jx-flash ul { margin: 4px 0 0; padding-left: 18px; }

        /* ===== Judul seksi & panel ===== */
        .jx-h { display: flex; align-items: center; gap: 10px; margin: 0 0 4px; font-size: 1.05rem; font-weight: 700; color: var(--jx-text); }
        .jx-h i { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: #ccfbf1; color: var(--jx-pri); font-size: .9rem; }
        .jx-sub { margin: 0 0 16px 44px; font-size: .82rem; color: var(--jx-muted); }
        .jx-panel { background: #fff; border: 1px solid var(--jx-border); border-radius: 20px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
        .jx-empty { padding: 24px; text-align: center; color: var(--jx-muted); background: var(--jx-soft); border: 1px dashed #cbd5e1; border-radius: 12px; }

        /* ===== Kartu shalat hari ini ===== */
        .jx-today { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; }
        .jx-pcard { position: relative; text-align: center; padding: 20px 12px 16px; border-radius: 18px; background: #fff; border: 1px solid var(--jx-border); transition: .2s; }
        .jx-pcard:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(15,23,42,.08); }
        .jx-ar { font-family: 'Amiri', serif; font-size: 1.6rem; line-height: 1.2; color: var(--jx-pri); }
        .jx-pname { font-size: .82rem; color: var(--jx-muted); font-weight: 500; }
        .jx-ptime { margin: 8px 0 2px; font-size: 1.85rem; font-weight: 800; color: var(--jx-text); font-variant-numeric: tabular-nums; }
        .jx-pimam { margin-top: 6px; font-size: .75rem; color: var(--jx-muted); }
        .jx-flag { position: absolute; top: 10px; right: 10px; display: none; font-size: .62rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
        .jx-pcard.is-past { opacity: .55; }
        .jx-pcard.is-next { border-color: var(--jx-pri2); box-shadow: 0 0 0 1px var(--jx-pri2) inset; }
        .jx-pcard.is-next .jx-flag.nxt { display: block; background: #ccfbf1; color: var(--jx-pri); }
        .jx-pcard.is-active { color: #fff; border-color: transparent; background: linear-gradient(150deg, var(--jx-pri2), var(--jx-dark)); box-shadow: 0 12px 28px rgba(15,118,110,.35); }
        .jx-pcard.is-active .jx-ar, .jx-pcard.is-active .jx-ptime, .jx-pcard.is-active .jx-pname, .jx-pcard.is-active .jx-pimam { color: #fff; }
        .jx-pcard.is-active .jx-flag.now { display: block; background: var(--jx-gold); color: #3b2300; }

        /* ===== Form ===== */
        .jx-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; }
        .jx-field label { display: block; margin-bottom: 5px; font-size: .75rem; font-weight: 600; color: var(--jx-muted); }
        .jx-field input, .jx-field textarea { width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 10px; font: inherit; font-size: .85rem; background: #fff; transition: .15s; }
        .jx-field input:focus, .jx-field textarea:focus { outline: none; border-color: var(--jx-pri2); box-shadow: 0 0 0 3px rgba(13,148,136,.15); }
        .jx-field.wide { grid-column: 1 / -1; }
        .jx-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; }
        .jx-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; min-width: 38px; height: 38px; padding: 0 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff; color: var(--jx-text); font: inherit; font-size: .85rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: .15s; }
        .jx-btn:hover { border-color: var(--jx-pri2); color: var(--jx-pri); }
        .jx-btn[aria-disabled="true"] { opacity: .4; pointer-events: none; }
        .jx-btn.pri { background: var(--jx-pri); border-color: var(--jx-pri); color: #fff; font-weight: 600; }
        .jx-btn.pri:hover { background: #115e59; color: #fff; }
        .jx-btn.danger { color: var(--jx-danger); border-color: #fca5a5; }
        .jx-btn.danger:hover { background: #fef2f2; border-color: var(--jx-danger); color: var(--jx-danger); }
        .jx-btn:focus-visible, .jx-field input:focus-visible { outline: 2px solid var(--jx-pri2); outline-offset: 2px; }

        /* ===== Idul Fitri ===== */
        .jx-eid-list { display: grid; gap: 12px; margin-bottom: 16px; }
        .jx-eid { display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: start; padding: 16px; border: 1px solid var(--jx-border); border-radius: 16px; background: var(--jx-soft); }
        .jx-eid-date { width: 62px; text-align: center; border-radius: 14px; overflow: hidden; background: #fff; border: 1px solid var(--jx-border); }
        .jx-eid-date small { display: block; padding: 3px 0; font-size: .65rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #fff; background: var(--jx-pri); }
        .jx-eid-date b { display: block; padding: 6px 0; font-size: 1.5rem; line-height: 1; color: var(--jx-text); }
        .jx-eid-title { margin: 0 0 6px; font-size: .98rem; font-weight: 600; }
        .jx-tag { margin-left: 6px; font-size: .68rem; font-weight: 600; padding: 2px 9px; border-radius: 999px; background: #ccfbf1; color: var(--jx-pri); }
        .jx-tag.past { background: #e2e8f0; color: var(--jx-muted); }
        .jx-meta { display: flex; flex-wrap: wrap; gap: 4px 16px; font-size: .8rem; color: #475569; }
        .jx-meta i { color: var(--jx-pri2); width: 14px; }
        .jx-note { margin: 8px 0 0; font-size: .8rem; color: var(--jx-muted); }
        .jx-eid details { grid-column: 1 / -1; }
        .jx-eid summary, .jx-add summary { cursor: pointer; font-size: .82rem; font-weight: 600; color: var(--jx-pri); }
        .jx-eid details form { margin-top: 14px; }
        .jx-add { border: 1px dashed #99d5cf; border-radius: 16px; padding: 14px 16px; background: #f0fdfa; }
        .jx-add[open] { background: #fff; border-style: solid; }
        .jx-add form { margin-top: 14px; }
        @media (max-width: 640px) { .jx-eid { grid-template-columns: auto 1fr; } .jx-eid > form { grid-column: 1 / -1; } }

        /* ===== Toolbar kalender ===== */
        .jx-toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px; }
        .jx-filter { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .jx-filter select { height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; font: inherit; font-size: .85rem; background: #fff; }

        /* ===== Grid kalender ===== */
        .cal-wrap { container-type: inline-size; }
        .cal-head, .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
        .cal-head { margin-bottom: 10px; }
        .cal-head-day { text-align: center; padding: 10px; font-weight: 600; font-size: .8rem; color: #fff; border-radius: 10px; background: linear-gradient(135deg, var(--jx-pri), var(--jx-pri2)); }
        .cal-head-day.is-sun { background: linear-gradient(135deg, #b91c1c, var(--jx-danger)); }
        .cal-cell { display: flex; flex-direction: column; gap: 8px; min-height: 160px; padding: 12px; background: #fff; border: 1px solid var(--jx-border); border-radius: 14px; transition: .15s; }
        .cal-cell:hover { border-color: var(--jx-pri2); box-shadow: 0 6px 16px rgba(15,23,42,.07); }
        .cal-cell.is-friday { background: linear-gradient(180deg, #fffbeb, #fff 45%); }
        .cal-cell.today { background: #f0fdf4; border: 2px solid var(--jx-pri2); box-shadow: 0 8px 20px rgba(13,148,136,.18); }
        .cal-cell.empty { background: var(--jx-soft); border: 1px dashed #cbd5e1; opacity: .4; }
        .cal-date { display: flex; align-items: center; gap: 8px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9; }
        .cal-date-num { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .95rem; border-radius: 50%; background: var(--jx-soft); color: var(--jx-text); }
        .cal-cell.today .cal-date-num { background: var(--jx-pri); color: #fff; }
        .cal-weekday { display: none; font-size: .8rem; color: var(--jx-muted); }
        .cal-badge { margin-left: auto; font-size: .62rem; font-weight: 700; background: var(--jx-pri); color: #fff; padding: 2px 8px; border-radius: 999px; }
        .cal-prayer-list { margin: 0; font-size: .73rem; color: #475569; display: flex; flex-direction: column; gap: 2px; }
        .cal-prayer-row { display: flex; justify-content: space-between; padding: 2px 6px; border-radius: 6px; }
        .cal-prayer-row:nth-child(even) { background: var(--jx-soft); }
        .cal-prayer-row dt, .cal-prayer-row dd { margin: 0; }
        .cal-prayer-row dd { font-weight: 700; font-variant-numeric: tabular-nums; }
        .cal-prayer-row.k-imsak { color: var(--jx-muted); }
        .cal-prayer-row.k-subuh { color: var(--jx-pri2); font-weight: 600; }
        .cal-prayer-row.k-dhuha { color: #b45309; }
        .cal-prayer-row.k-maghrib { color: #d97706; font-weight: 600; }
        @container (max-width: 900px) {
            .cal-head { display: none; }
            .cal-grid { grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); }
            .cal-cell.empty { display: none; }
            .cal-weekday { display: inline; }
            .cal-cell { min-height: 0; }
        }

        /* ===== Cetak ===== */
        .print-header { display: none; text-align: center; margin-bottom: 16px; }
        .print-header h2 { margin: 0; font-size: 1.4rem; color: var(--jx-pri); }
        .print-header h3 { margin: 4px 0; font-size: 1.1rem; }
        .print-header p { margin: 0; font-size: .9rem; color: #555; }
        @page { size: A4 landscape; margin: 10mm; }
        @media print {
            .no-print, .ba2-sidebar, .ba2-topbar { display: none !important; }
            .ba2-main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .jx-wrap { padding: 0 !important; }
            body { background: #fff !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .jx-panel { border: 0; box-shadow: none; padding: 0; }
            .print-header { display: block; }
            .cal-head { display: grid !important; gap: 4px; margin-bottom: 4px; }
            .cal-head-day { padding: 5px; font-size: .7rem; }
            .cal-grid { grid-template-columns: repeat(7, 1fr) !important; gap: 4px; }
            .cal-cell { min-height: 0; padding: 4px 5px; gap: 3px; border-radius: 6px; break-inside: avoid; page-break-inside: avoid; box-shadow: none; }
            .cal-cell.empty { display: flex !important; border-color: transparent; background: transparent; }
            .cal-weekday { display: none !important; }
            .cal-date { padding-bottom: 2px; }
            .cal-date-num { width: 22px; height: 22px; font-size: .75rem; }
            .cal-prayer-list { font-size: .6rem; gap: 1px; }
            .cal-prayer-row { padding: 0 3px; }
        }
    </style>
</head>
<body class="ba2-body" id="ba2Body">

@php
    $prayerRows = [
        'imsak' => 'Imsak', 'subuh' => 'Subuh', 'terbit' => 'Terbit', 'dhuha' => 'Dhuha',
        'dzuhur' => 'Dzuhur', 'ashar' => 'Ashar', 'maghrib' => 'Maghrib', 'isya' => "Isya'",
    ];
    $arab       = ['Subuh' => 'الفجر', 'Dzuhur' => 'الظهر', 'Ashar' => 'العصر', 'Maghrib' => 'المغرب', 'Isya' => 'العشاء'];
    $dayNames   = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $hasToday   = !empty($jadwalShalat);
    $isFallback = $hasToday && !empty($jadwalShalat[0]['is_fallback']);
    $periodName = ($months[$bulan] ?? '') . ' ' . $tahun;
    $nowC       = \Carbon\Carbon::now($timezone);
    $tanggalIni = $dayNames[$nowC->dayOfWeek] . ', ' . $nowC->day . ' ' . ($months[$nowC->format('m')] ?? '') . ' ' . $nowC->year;
    $activeIdx  = collect($jadwalShalat)->search(fn ($j) => !empty($j['active']));
@endphp

    <aside class="ba2-sidebar no-print" id="ba2Sidebar">
        <div class="ba2-brand">
            <div class="ba2-brand-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</div>
                <div class="ba2-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</div>
            </div>
        </div>

        <nav class="ba2-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="{{ route('admin.jadwal-sholat') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                <span class="ba2-nav-badge">3</span>
            </a>
            <a href="{{ route('admin.acara') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
            </a>
            @if(isset($mosque) && $mosque->package_type === 'free')
                <a href="{{ route('masjid.perpanjangan.create') }}" class="ba2-nav-item" style="opacity: 0.8;" title="Upgrade paket">
                    <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="ba2-nav-label">Donasi</span>
                    <span class="ba2-nav-soon" style="background: #e74c3c; color: white;">Locked</span>
                </a>
            @else
                <a href="{{ route('admin.donasi') }}" class="ba2-nav-item">
                    <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="ba2-nav-label">Donasi</span>
                    <span class="ba2-nav-soon" style="background: #27ae60; color: white;">Aktif</span>
                </a>
            @endif
            <a href="{{ route('admin.jamaah') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
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

    <div class="ba2-main" id="ba2Main">

        <header class="ba2-topbar no-print">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Jadwal Shalat &amp; Kalender Imsakiyah</div>
                <div class="ba2-page-sub">
                    Masjid: <strong>{{ $mosque->mosque_name ?? '-' }}</strong>
                    &middot; Lokasi: <strong style="text-transform: capitalize;">{{ $mosque->city ?? 'Belum diatur' }}</strong>
                </div>
            </div>
            <div class="ba2-topbar-right">
                <button type="button" onclick="window.print()" class="jx-btn pri">
                    <i class="fa-solid fa-print" aria-hidden="true"></i> Cetak kalender
                </button>
                <a href="{{ isset($mosque->slug) ? route('masjid.publik', $mosque->slug) : url('/') }}" class="ba2-btn-back">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Kembali ke halaman publik
                </a>
            </div>
        </header>

        <main class="ba2-content jx-wrap">

            @if(session('success'))
                <div class="jx-flash ok no-print" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true" style="margin-top:2px;"></i><span>{{ session('success') }}</span></div>
            @endif
            @if($errors->any())
                <div class="jx-flash err no-print" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true" style="margin-top:2px;"></i>
                    <div>Data belum tersimpan, periksa isian berikut:
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            {{-- ===== HERO: tanggal, jam, hitung mundur ===== --}}
            <section class="jx-hero no-print" id="jxRoot" aria-label="Ringkasan waktu shalat">
                <div>
                    <span class="jx-eyebrow"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <span style="text-transform:capitalize;">{{ $mosque->city ?? '-' }}</span> &middot; {{ $timezoneLabel }}</span>
                    <h2 class="jx-date">{{ $tanggalIni }}</h2>
                    <div class="jx-clock"><i class="fa-regular fa-clock" aria-hidden="true"></i> <span id="jxClock">--:--:--</span> {{ $timezoneLabel }}</div>
                </div>
                @if($hasToday)
                <div>
                    <div class="jx-next-lbl">Menuju waktu shalat</div>
                    <div class="jx-next-name"><span id="jxNextName">-</span> &middot; <span id="jxNextTime">--:--</span></div>
                    <div class="jx-cd" aria-live="off">
                        <div><b id="jxH">00</b><span>Jam</span></div>
                        <div><b id="jxM">00</b><span>Menit</span></div>
                        <div><b id="jxS">00</b><span>Detik</span></div>
                    </div>
                </div>
                @endif
            </section>

            {{-- ===== Waktu shalat hari ini ===== --}}
            <section class="no-print" aria-labelledby="jx-today-title">
                <h3 class="jx-h" id="jx-today-title"><i class="fa-solid fa-sun" aria-hidden="true"></i> Waktu shalat hari ini</h3>
                <p class="jx-sub">Kartu menyala menandakan waktu shalat yang sedang berlangsung.</p>

                @if($isFallback)
                    <div class="jx-alert" role="status" style="margin-bottom:14px;">
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true" style="margin-top:2px;"></i>
                        <span>Data resmi belum dapat dimuat, jadi waktu di bawah hanya perkiraan. Periksa nama kota di menu <strong>Profil Masjid</strong>, lalu muat ulang halaman ini.</span>
                    </div>
                @endif

                <div class="jx-today">
                    @forelse($jadwalShalat as $i => $jadwal)
                        @php
                            $imamNama = $imams[strtolower($jadwal['name'])] ?? null;
                            $state    = !empty($jadwal['active']) ? 'is-active' : (($activeIdx !== false && $i < $activeIdx) ? 'is-past' : '');
                        @endphp
                        <div class="jx-pcard {{ $state }}" data-name="{{ $jadwal['name'] }}" data-time="{{ $jadwal['time'] }}">
                            <span class="jx-flag now">Sekarang</span>
                            <span class="jx-flag nxt">Berikutnya</span>
                            <div class="jx-ar" lang="ar" dir="rtl">{{ $arab[$jadwal['name']] ?? '' }}</div>
                            <div class="jx-pname">{{ $jadwal['name'] }}</div>
                            <div class="jx-ptime">{{ $jadwal['time'] }}</div>
                            @if($imamNama)
                                <div class="jx-pimam"><i class="fa-solid fa-user" aria-hidden="true"></i> {{ $imamNama }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="jx-empty" style="grid-column:1/-1;">Jadwal belum tersedia. Isi nama kota di menu <strong>Profil Masjid</strong>.</div>
                    @endforelse
                </div>
            </section>

            {{-- ===== Imam setiap shalat ===== --}}
            <section class="jx-panel no-print" aria-labelledby="jx-imam-title">
                <h3 class="jx-h" id="jx-imam-title"><i class="fa-solid fa-user-tie" aria-hidden="true"></i> Imam setiap shalat</h3>
                <p class="jx-sub">Nama imam akan tampil di kartu waktu shalat. Kosongkan kolom untuk menghapus imam pada shalat itu.</p>
                <form method="POST" action="{{ route('admin.jadwal-sholat.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="jx-grid">
                        @foreach($prayerLabels as $key => $label)
                            <div class="jx-field">
                                <label for="imam-{{ $key }}">{{ $label }}</label>
                                <input type="text" id="imam-{{ $key }}" name="imam[{{ $key }}]" maxlength="100"
                                       value="{{ old('imam.' . $key, $imams[$key] ?? '') }}" placeholder="Nama imam">
                            </div>
                        @endforeach
                    </div>
                    <div class="jx-actions">
                        <button type="submit" class="jx-btn pri"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Simpan imam</button>
                    </div>
                </form>
            </section>

            {{-- ===== Jadwal shalat Idul Fitri ===== --}}
            <section class="jx-panel no-print" aria-labelledby="jx-eid-title">
                <h3 class="jx-h" id="jx-eid-title"><i class="fa-solid fa-moon" aria-hidden="true"></i> Jadwal shalat Idul Fitri</h3>
                <p class="jx-sub">Atur tanggal, jam, tempat, imam, dan khatib shalat Id.</p>

                @if($eidPrayers->isNotEmpty())
                    <div class="jx-eid-list">
                        @foreach($eidPrayers as $eid)
                            @php $isPast = $eid->event_date->toDateString() < $today; @endphp
                            <article class="jx-eid">
                                <div class="jx-eid-date" aria-hidden="true">
                                    <small>{{ mb_substr($months[$eid->event_date->format('m')], 0, 3) }}</small>
                                    <b>{{ $eid->event_date->day }}</b>
                                </div>
                                <div>
                                    <h4 class="jx-eid-title">{{ $eid->title }} <span class="jx-tag {{ $isPast ? 'past' : '' }}">{{ $isPast ? 'Sudah lewat' : 'Akan datang' }}</span></h4>
                                    <div class="jx-meta">
                                        <span><i class="fa-solid fa-calendar-day" aria-hidden="true"></i> {{ $dayNames[$eid->event_date->dayOfWeek] }}, {{ $eid->event_date->day }} {{ $months[$eid->event_date->format('m')] }} {{ $eid->event_date->year }}</span>
                                        <span><i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $eid->prayer_time }} {{ $timezoneLabel }}</span>
                                        @if($eid->location)<span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $eid->location }}</span>@endif
                                        @if($eid->imam_name)<span><i class="fa-solid fa-user-tie" aria-hidden="true"></i> Imam: {{ $eid->imam_name }}</span>@endif
                                        @if($eid->khatib_name)<span><i class="fa-solid fa-microphone" aria-hidden="true"></i> Khatib: {{ $eid->khatib_name }}</span>@endif
                                    </div>
                                    @if($eid->notes)<p class="jx-note">{{ $eid->notes }}</p>@endif
                                </div>
                                <form method="POST" action="{{ route('admin.jadwal-sholat.eid.destroy', $eid) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="jx-btn danger"><i class="fa-solid fa-trash" aria-hidden="true"></i> Hapus</button>
                                </form>

                                <details>
                                    <summary>Ubah jadwal ini</summary>
                                    <form method="POST" action="{{ route('admin.jadwal-sholat.eid.update', $eid) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="jx-grid">
                                            <div class="jx-field wide"><label>Judul</label><input type="text" name="title" value="{{ $eid->title }}" maxlength="120" required></div>
                                            <div class="jx-field"><label>Tanggal</label><input type="date" name="event_date" value="{{ $eid->event_date->format('Y-m-d') }}" required></div>
                                            <div class="jx-field"><label>Jam</label><input type="time" name="prayer_time" value="{{ $eid->prayer_time }}" required></div>
                                            <div class="jx-field"><label>Tempat</label><input type="text" name="location" value="{{ $eid->location }}" maxlength="150"></div>
                                            <div class="jx-field"><label>Imam</label><input type="text" name="imam_name" value="{{ $eid->imam_name }}" maxlength="100"></div>
                                            <div class="jx-field"><label>Khatib</label><input type="text" name="khatib_name" value="{{ $eid->khatib_name }}" maxlength="100"></div>
                                            <div class="jx-field wide"><label>Catatan</label><textarea name="notes" rows="2" maxlength="500">{{ $eid->notes }}</textarea></div>
                                        </div>
                                        <div class="jx-actions"><button type="submit" class="jx-btn pri">Simpan perubahan</button></div>
                                    </form>
                                </details>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="jx-empty" style="margin-bottom:16px;">Belum ada jadwal shalat Idul Fitri. Tambahkan lewat form di bawah.</div>
                @endif

                @php $fromNew = old('_form') === 'eid-new'; @endphp
                <details class="jx-add" {{ $fromNew ? 'open' : '' }}>
                    <summary><i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah jadwal baru</summary>
                    <form method="POST" action="{{ route('admin.jadwal-sholat.eid.store') }}">
                        @csrf
                        <input type="hidden" name="_form" value="eid-new">
                        <div class="jx-grid">
                            <div class="jx-field wide"><label for="eid-title">Judul</label><input type="text" id="eid-title" name="title" maxlength="120" required value="{{ $fromNew ? old('title') : '' }}" placeholder="Shalat Idul Fitri 1448 H"></div>
                            <div class="jx-field"><label for="eid-date">Tanggal</label><input type="date" id="eid-date" name="event_date" required value="{{ $fromNew ? old('event_date') : '' }}"></div>
                            <div class="jx-field"><label for="eid-time">Jam ({{ $timezoneLabel }})</label><input type="time" id="eid-time" name="prayer_time" required value="{{ $fromNew ? old('prayer_time') : '' }}"></div>
                            <div class="jx-field"><label for="eid-loc">Tempat</label><input type="text" id="eid-loc" name="location" maxlength="150" value="{{ $fromNew ? old('location') : '' }}" placeholder="Halaman masjid / lapangan"></div>
                            <div class="jx-field"><label for="eid-imam">Imam</label><input type="text" id="eid-imam" name="imam_name" maxlength="100" value="{{ $fromNew ? old('imam_name') : '' }}"></div>
                            <div class="jx-field"><label for="eid-khatib">Khatib</label><input type="text" id="eid-khatib" name="khatib_name" maxlength="100" value="{{ $fromNew ? old('khatib_name') : '' }}"></div>
                            <div class="jx-field wide"><label for="eid-notes">Catatan (opsional)</label><textarea id="eid-notes" name="notes" rows="2" maxlength="500" placeholder="Mis. jamaah membawa sajadah sendiri">{{ $fromNew ? old('notes') : '' }}</textarea></div>
                        </div>
                        <div class="jx-actions"><button type="submit" class="jx-btn pri"><i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah jadwal</button></div>
                    </form>
                </details>
            </section>

            {{-- ===== Kalender bulanan ===== --}}
            <section class="jx-panel" aria-labelledby="jx-cal-title">

                <div class="jx-toolbar no-print">
                    <div>
                        <h3 class="jx-h" id="jx-cal-title" style="margin:0;"><i class="fa-solid fa-calendar-alt" aria-hidden="true"></i> Kalender {{ $periodName }}</h3>
                        <p class="jx-sub" style="margin:4px 0 0 44px;">Imsak, Subuh, Terbit, Dhuha, Dzuhur, Ashar, Maghrib, dan Isya' tiap tanggal. Hari Jumat diberi warna khusus.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.jadwal-sholat') }}" class="jx-filter">
                        <a class="jx-btn" @if($prevUrl) href="{{ $prevUrl }}" @else aria-disabled="true" @endif aria-label="Bulan sebelumnya">
                            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <select name="bulan" aria-label="Bulan" onchange="this.form.submit()">
                            @foreach($months as $key => $name)
                                <option value="{{ $key }}" {{ $bulan === $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <select name="tahun" aria-label="Tahun" onchange="this.form.submit()">
                            @foreach($yearOptions as $y)
                                <option value="{{ $y }}" {{ $tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <a class="jx-btn" @if($nextUrl) href="{{ $nextUrl }}" @else aria-disabled="true" @endif aria-label="Bulan berikutnya">
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        @unless($isCurrentPeriod)
                            <a class="jx-btn" href="{{ $currentUrl }}">Bulan ini</a>
                        @endunless
                        <noscript><button type="submit" class="jx-btn pri">Tampilkan</button></noscript>
                    </form>
                </div>

                <div class="print-header">
                    <h2>JADWAL IMSAKIYAH &amp; SHALAT</h2>
                    <h3>{{ mb_strtoupper($mosque->mosque_name ?? 'SIM Masjid') }}</h3>
                    <p>Kota: {{ mb_strtoupper($mosque->city ?? '-') }} ({{ $timezoneLabel }}) &mdash; Periode: {{ $periodName }}</p>
                </div>

                <div class="cal-wrap">
                    <div class="cal-head" aria-hidden="true">
                        @foreach($dayNames as $i => $name)
                            <div class="cal-head-day {{ $i === 0 ? 'is-sun' : '' }}">{{ $name }}</div>
                        @endforeach
                    </div>

                    <div class="cal-grid">
                        @forelse($monthlySchedule as $row)
                            @if($loop->first)
                                @for($i = 0; $i < $startOffset; $i++)
                                    <div class="cal-cell empty" aria-hidden="true"></div>
                                @endfor
                            @endif

                            @php
                                $isToday  = ($row['date'] ?? null) === $today;
                                $isFriday = ($row['weekday'] ?? null) === 5;
                            @endphp

                            <article class="cal-cell {{ $isToday ? 'today' : '' }} {{ $isFriday ? 'is-friday' : '' }}" @if($isToday) aria-current="date" @endif>
                                <div class="cal-date">
                                    <span class="cal-date-num">{{ $row['day'] }}</span>
                                    <span class="cal-weekday">{{ $dayNames[$row['weekday']] ?? '' }}</span>
                                    @if($isToday)<span class="cal-badge">Hari ini</span>@endif
                                </div>
                                <dl class="cal-prayer-list">
                                    @foreach($prayerRows as $key => $label)
                                        <div class="cal-prayer-row k-{{ $key }}">
                                            <dt>{{ $label }}</dt>
                                            <dd>{{ $row[$key] ?? '-' }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </article>
                        @empty
                            <div class="jx-empty" style="grid-column:1/-1;">
                                Kalender {{ $periodName }} belum dapat dimuat.
                                Periksa nama kota di <strong>Profil Masjid</strong>, atau <a href="{{ url()->full() }}">coba muat ulang</a>.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Jam & hitung mundur ke shalat berikutnya (berdasarkan zona waktu masjid)
        (function () {
            const tz = @json($timezone ?? 'Asia/Jakarta');
            const cards = [...document.querySelectorAll('.jx-pcard[data-time]')].filter(c => /^\d{2}:\d{2}$/.test(c.dataset.time));
            const fmt = new Intl.DateTimeFormat('en-GB', { timeZone: tz, hourCycle: 'h23', hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const $ = id => document.getElementById(id);
            const pad = n => String(n).padStart(2, '0');
            const times = cards.map(c => { const [h, m] = c.dataset.time.split(':').map(Number); return h * 3600 + m * 60; });

            function nowSec() {
                const p = fmt.formatToParts(new Date());
                const g = t => parseInt(p.find(x => x.type === t).value, 10);
                return g('hour') * 3600 + g('minute') * 60 + g('second');
            }

            function tick() {
                if ($('jxClock')) $('jxClock').textContent = fmt.format(new Date());
                if (!cards.length) return;

                const s = nowSec();
                let next = times.findIndex(t => t > s);
                let diff;
                if (next === -1) { next = 0; diff = 86400 - s + times[0]; } else { diff = times[next] - s; }

                let active = -1;
                times.forEach((t, i) => { if (t <= s) active = i; });

                cards.forEach((c, i) => {
                    c.classList.toggle('is-active', i === active);
                    c.classList.toggle('is-past', i < active);
                    c.classList.toggle('is-next', i === next && i !== active);
                });

                if ($('jxNextName')) {
                    $('jxNextName').textContent = cards[next].dataset.name;
                    $('jxNextTime').textContent = cards[next].dataset.time;
                    $('jxH').textContent = pad(Math.floor(diff / 3600));
                    $('jxM').textContent = pad(Math.floor((diff % 3600) / 60));
                    $('jxS').textContent = pad(diff % 60);
                }
            }
            tick();
            setInterval(tick, 1000);
        })();
    </script>
</body>
</html>