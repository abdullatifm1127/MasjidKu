<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Shalat &amp; Kalender Imsakiyah - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/jadwalSholat.css') }}">
    <style>
        :root {
            --js-primary: #0f766e;
            --js-primary-2: #0d9488;
            --js-accent: #10b981;
            --js-amber: #b45309;
            --js-danger: #dc2626;
            --js-text: #1e293b;
            --js-muted: #64748b;
            --js-border: #e2e8f0;
            --js-soft: #f8fafc;
        }

        /* ---------- Kartu waktu hari ini ---------- */
        .js-section-title { font-size: 1.1rem; font-weight: 600; color: var(--js-text); margin: 0 0 12px; }
        .js-section-title i { color: var(--js-primary-2); }
        .js-tz { font-size: .75rem; font-weight: 600; color: var(--js-primary); background: #ccfbf1; padding: 2px 8px; border-radius: 999px; margin-left: 6px; vertical-align: middle; }

        .js-today-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; margin-bottom: 28px; }
        .js-prayer-card { position: relative; text-align: center; padding: 18px; border-radius: 10px; background: #fff; color: var(--js-text); border: 1px solid var(--js-border); box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .js-prayer-card.active { background: var(--js-primary-2); border-color: var(--js-primary-2); color: #fff; }
        .js-prayer-name { font-size: .9rem; font-weight: 600; margin-bottom: 6px; opacity: .75; }
        .js-prayer-card.active .js-prayer-name { opacity: .9; }
        .js-prayer-time { font-size: 1.4rem; font-weight: 700; letter-spacing: .5px; font-variant-numeric: tabular-nums; }
        .js-prayer-flag { position: absolute; top: 8px; right: 8px; background: #fff; color: var(--js-primary-2); font-size: .65rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; }

        .js-alert { display: flex; gap: 10px; align-items: flex-start; padding: 12px 14px; margin-bottom: 14px; border-radius: 8px; background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; font-size: .85rem; }
        .js-empty { grid-column: 1 / -1; padding: 24px; text-align: center; color: var(--js-muted); background: #fff; border-radius: 10px; border: 1px dashed var(--js-border); }

        /* ---------- Panel kalender ---------- */
        .js-panel { background: #fff; border-radius: 12px; padding: 24px; border: 1px solid var(--js-border); box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .js-toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
        .js-toolbar h3 { margin: 0 0 4px; font-size: 1.1rem; font-weight: 600; color: var(--js-text); }
        .js-toolbar h3 i { color: var(--js-primary-2); }
        .js-toolbar p { margin: 0; font-size: .85rem; color: var(--js-muted); }

        .js-filter { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .js-filter select { padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font: inherit; font-size: .85rem; background: #fff; }
        .js-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-width: 36px; height: 36px; padding: 0 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: var(--js-text); font: inherit; font-size: .85rem; font-weight: 500; text-decoration: none; cursor: pointer; }
        .js-btn:hover { border-color: var(--js-primary-2); color: var(--js-primary); }
        .js-btn[aria-disabled="true"] { opacity: .4; pointer-events: none; }
        .js-btn:focus-visible, .js-filter select:focus-visible { outline: 2px solid var(--js-primary-2); outline-offset: 2px; }
        .js-btn-primary { background: var(--js-primary); border-color: var(--js-primary); color: #fff; font-weight: 600; }
        .js-btn-primary:hover { background: #115e59; color: #fff; }

        /* ---------- Grid kalender ---------- */
        .cal-wrap { container-type: inline-size; }
        .cal-head, .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 12px; }
        .cal-head { margin-bottom: 12px; }
        .cal-head-day { background: var(--js-primary); color: #fff; text-align: center; padding: 12px; font-weight: 600; border-radius: 8px; font-size: .85rem; }
        .cal-head-day.is-sun { background: var(--js-danger); }

        .cal-cell { display: flex; flex-direction: column; gap: 8px; min-height: 160px; padding: 12px; background: #fff; border: 1px solid var(--js-border); border-radius: 12px; }
        .cal-cell:hover { border-color: var(--js-primary-2); }
        .cal-cell.today { background: #f0fdf4; border: 2px solid var(--js-accent); }
        .cal-cell.empty { background: var(--js-soft); border: 1px dashed #cbd5e1; opacity: .4; }

        .cal-date { display: flex; align-items: center; gap: 8px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9; }
        .cal-date-num { font-weight: 700; font-size: 1rem; color: var(--js-text); }
        .cal-weekday { display: none; font-size: .8rem; color: var(--js-muted); }
        .cal-badge { margin-left: auto; font-size: .65rem; background: var(--js-accent); color: #fff; padding: 1px 6px; border-radius: 4px; }

        .cal-prayer-list { margin: 0; font-size: .72rem; color: #475569; display: flex; flex-direction: column; gap: 3px; }
        .cal-prayer-row { display: flex; justify-content: space-between; padding: 2px 5px; border-radius: 4px; }
        .cal-prayer-row:nth-child(even) { background: var(--js-soft); }
        .cal-prayer-row dt, .cal-prayer-row dd { margin: 0; }
        .cal-prayer-row dd { font-weight: 700; font-variant-numeric: tabular-nums; }
        .cal-prayer-row.k-imsak   { color: var(--js-muted); }
        .cal-prayer-row.k-subuh   { color: var(--js-primary-2); font-weight: 600; }
        .cal-prayer-row.k-dhuha   { color: var(--js-amber); }
        .cal-prayer-row.k-maghrib { color: #d97706; font-weight: 600; }

        /* Layar sempit: header hari & sel kosong disembunyikan, nama hari tampil di tiap sel */
        @container (max-width: 900px) {
            .cal-head { display: none; }
            .cal-grid { grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); }
            .cal-cell.empty { display: none; }
            .cal-weekday { display: inline; }
            .cal-cell { min-height: 0; }
        }

        /* ---------- Cetak ---------- */
        .print-header { display: none; text-align: center; margin-bottom: 16px; }
        .print-header h2 { margin: 0; font-size: 1.4rem; color: var(--js-primary); }
        .print-header h3 { margin: 4px 0; font-size: 1.1rem; }
        .print-header p { margin: 0; font-size: .9rem; color: #555; }

        @page { size: A4 landscape; margin: 10mm; }
        @media print {
            .no-print, .ba2-sidebar, .ba2-topbar { display: none !important; }
            .ba2-main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .ba2-content { padding: 0 !important; }
            body { background: #fff !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            .js-panel { border: 0; box-shadow: none; padding: 0; }
            .print-header { display: block; }
            .cal-head { display: grid !important; gap: 4px; margin-bottom: 4px; }
            .cal-head-day { padding: 5px; font-size: .7rem; }
            .cal-grid { grid-template-columns: repeat(7, 1fr) !important; gap: 4px; }
            .cal-cell { min-height: 0; padding: 4px 5px; gap: 3px; border-radius: 6px; break-inside: avoid; page-break-inside: avoid; }
            .cal-cell.empty { display: flex !important; border-color: transparent; background: transparent; }
            .cal-weekday { display: none !important; }
            .cal-date { padding-bottom: 2px; }
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
    $dayNames   = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $hasToday   = !empty($jadwalShalat);
    $isFallback = $hasToday && !empty($jadwalShalat[0]['is_fallback']);
    $periodName = ($months[$bulan] ?? '') . ' ' . $tahun;
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
                <button type="button" onclick="window.print()" class="js-btn js-btn-primary">
                    <i class="fa-solid fa-print" aria-hidden="true"></i> Cetak kalender
                </button>
                <a href="{{ isset($mosque->slug) ? route('masjid.publik', $mosque->slug) : url('/') }}" class="ba2-btn-back">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Kembali ke halaman publik
                </a>
            </div>
        </header>

        <main class="ba2-content" style="padding: 24px;">

            {{-- ===== Waktu shalat hari ini ===== --}}
            <section class="no-print" aria-labelledby="js-today-title">
                <h3 class="js-section-title" id="js-today-title">
                    <i class="fa-solid fa-calendar-day" aria-hidden="true"></i> Waktu shalat hari ini
                    <span class="js-tz">{{ $timezoneLabel }}</span>
                </h3>

                @if($isFallback)
                    <div class="js-alert" role="status">
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true" style="margin-top: 2px;"></i>
                        <span>Data resmi belum dapat dimuat, jadi waktu di bawah hanya perkiraan. Periksa nama kota di menu <strong>Profil Masjid</strong>, lalu muat ulang halaman ini.</span>
                    </div>
                @endif

                <div class="js-today-grid">
                    @forelse($jadwalShalat as $jadwal)
                        <div class="js-prayer-card {{ $jadwal['active'] ? 'active' : '' }}">
                            @if($jadwal['active'])<span class="js-prayer-flag">Sekarang</span>@endif
                            <div class="js-prayer-name">{{ $jadwal['name'] }}</div>
                            <div class="js-prayer-time">{{ $jadwal['time'] }}</div>
                        </div>
                    @empty
                        <div class="js-empty">
                            Jadwal belum tersedia. Isi nama kota di menu <strong>Profil Masjid</strong>.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- ===== Kalender bulanan ===== --}}
            <section class="js-panel" aria-labelledby="js-cal-title">

                <div class="js-toolbar no-print">
                    <div>
                        <h3 id="js-cal-title"><i class="fa-solid fa-calendar-alt" aria-hidden="true"></i> Kalender {{ $periodName }}</h3>
                        <p>Imsak, Subuh, Terbit, Dhuha, Dzuhur, Ashar, Maghrib, dan Isya' untuk setiap tanggal.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.jadwal-sholat') }}" class="js-filter">
                        <a class="js-btn" @if($prevUrl) href="{{ $prevUrl }}" @else aria-disabled="true" @endif aria-label="Bulan sebelumnya">
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

                        <a class="js-btn" @if($nextUrl) href="{{ $nextUrl }}" @else aria-disabled="true" @endif aria-label="Bulan berikutnya">
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </a>

                        @unless($isCurrentPeriod)
                            <a class="js-btn" href="{{ $currentUrl }}">Bulan ini</a>
                        @endunless

                        <noscript><button type="submit" class="js-btn js-btn-primary">Tampilkan</button></noscript>
                    </form>
                </div>

                {{-- Judul khusus cetak --}}
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

                            @php $isToday = ($row['date'] ?? null) === $today; @endphp

                            <article class="cal-cell {{ $isToday ? 'today' : '' }}" @if($isToday) aria-current="date" @endif>
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
                            <div class="js-empty">
                                Kalender {{ $periodName }} belum dapat dimuat.
                                Periksa nama kota di <strong>Profil Masjid</strong>, atau <a href="{{ url()->full() }}">coba muat ulang</a>.
                            </div>
                        @endforelse
                    </div>
                </div>

            </section>
        </main>
    </div>

</body>
</html>