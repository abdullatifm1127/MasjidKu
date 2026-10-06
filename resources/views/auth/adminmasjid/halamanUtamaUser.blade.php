<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mosque->mosque_name ?? 'Masjid' }} — Halaman Utama</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,ital@9..144,300..700,0;9..144,400..600,1&family=Inter:wght@400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/halamanUtamaUser.css') }}">
</head>
<body>

    @php
        $modules = $mosque->active_modules ?? [];
        $modOn = fn($key) => data_get($modules, $key, true); 
    @endphp

    {{-- TOP BAR: JADWAL SHALAT (modul: jadwal_shalat) --}}
    @if($modOn('jadwal_shalat'))
    <div class="hu-praybar">
        <div class="hu-praybar-left">
            <span class="hu-praybar-label">Jadwal Shalat</span>
            <span class="hu-praybar-date">— {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <div class="hu-praybar-times">
            @php
                $prayers = $prayers ?? [
                    ['name' => 'Subuh',   'time' => '04:32', 'active' => false],
                    ['name' => 'Dzuhur',  'time' => '12:05', 'active' => false],
                    ['name' => 'Ashar',   'time' => '15:21', 'active' => false],
                    ['name' => 'Maghrib', 'time' => '18:02', 'active' => false],
                    ['name' => 'Isya',    'time' => '19:14', 'active' => true],
                ];
            @endphp
            @foreach($prayers as $p)
            <div class="hu-prayer {{ $p['active'] ? 'active' : '' }}">
                <div class="hu-prayer-name">{{ $p['name'] }}</div>
                <div class="hu-prayer-time">{{ $p['time'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- NAVBAR --}}
    <header class="hu-navbar" id="huNavbar">
        <div class="hu-navbar-inner">

            <a href="#" class="hu-brand">
                <div class="hu-brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </div>
                <div class="hu-brand-text">
                    <span class="hu-brand-name">{{ $mosque->mosque_name ?? 'Masjid Ar-Rahman' }}</span>
                    <span class="hu-brand-city">{{ $mosque->city ?? '' }}</span>
                </div>
            </a>

          <nav class="hu-nav">
        <a href="#beranda" class="hu-nav-link active">Beranda</a>
        <a href="#profil" class="hu-nav-link">Profil</a>
        @if($modOn('jadwal_shalat'))<a href="#shalat" class="hu-nav-link">Waktu Shalat</a>@endif
        <a href="#program" class="hu-nav-link">Program & Fasilitas</a>
        @if($modOn('kegiatan'))<a href="#acara" class="hu-nav-link">Acara</a>@endif
        
        @if(isset($mosque) && $mosque->package_type !== 'free')
            <a href="#donasi" class="hu-nav-link">Donasi</a>
            <a href="#penyaluran" class="hu-nav-link">Penyaluran</a>
        @endif

        <a href="#kontak" class="hu-nav-link">Hubungi</a>
    </nav>               
            <button class="hu-hamburger" id="huHamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    {{-- INFO TICKER (modul: pengumuman) --}}
    @if($modOn('pengumuman'))
    <div class="hu-ticker">
        <span class="hu-ticker-label">INFO</span>
        <div class="hu-ticker-wrap">
            <div class="hu-ticker-track" id="huTickerTrack">
                @php
                    $announcements = $announcements ?? [
                        'Santunan anak yatim setiap Jumat pertama dalam bulan',
                        "Kajian Fiqih setiap Senin ba'da Isya",
                        'Pendaftaran TPA/TPQ dibuka sampai akhir bulan',
                    ];
                @endphp
                @foreach($announcements as $info)
                <span class="hu-ticker-text">{{ $info }}</span>
                <span class="hu-ticker-dot">●</span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- HERO SECTION --}}
    <section class="hu-hero" id="beranda" style="position: relative; overflow: hidden; color: {{ $landingPage->hero_text_color ?? $mosque->hero_text_color ?? '#ffffff' }};">
        @if(!empty($landingPage->hero_image))
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.45); z-index: 2;"></div>
                <img src="{{ asset('storage/' . $landingPage->hero_image) }}" alt="Hero Background" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">
            </div>
        @else
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: {{ $landingPage->hero_bg_color ?? '#0e3320' }}; z-index: 1;"></div>
        @endif

        <div class="hu-hero-overlay" style="position: relative; z-index: 3;"></div>
        
        <div class="hu-hero-content" style="position: relative; z-index: 4;">
            <div class="hu-hero-arabic">{{ $mosque->arabic_name ?? '' }}</div>
            <h1 class="hu-hero-title">{{ $landingPage->hero_title ?? $mosque->mosque_name ?? 'Selamat Datang' }}</h1>
            <p class="hu-hero-tagline">
                {{ $landingPage->hero_subtitle ?? $mosque->tagline ?? '' }}
                @if(!empty($landingPage->hero_desc)) — {{ $landingPage->hero_desc }} @endif
            </p>
            <div class="hu-hero-btns">
                @if(!empty($landingPage->btn_primary))
                <a href="{{ $landingPage->btn_primary_url ?? '#donasi' }}" class="hu-btn-primary">{{ $landingPage->btn_primary }}</a>
                @endif
                @if(!empty($landingPage->btn_secondary))
                <a href="{{ $landingPage->btn_secondary_url ?? '#profil' }}" class="hu-btn-outline">{{ $landingPage->btn_secondary }}</a>
                @endif
            </div>
        </div>
        
        <div class="hu-hero-scroll" style="position: relative; z-index: 4;">
            <span>SCROLL</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </div>
    </section>

   {{-- PROFIL SECTION (final + modal pengurus): latar krem, panel pasir keemasan di belakang foto mihrab --}}
    @php
        $pfCap = $mosque->capacity ?? null;
        $pfCapText = is_numeric($pfCap) ? number_format((float) $pfCap, 0, ',', '.') : $pfCap;

        $pfProgRaw = $mosque->programs ?? [];
        if (is_string($pfProgRaw)) { $pfProgRaw = json_decode($pfProgRaw, true) ?: []; }
        $pfProgs = collect(is_array($pfProgRaw) ? $pfProgRaw : [])
            ->map(fn ($p) => is_string($p) ? $p : ($p['name'] ?? $p['title'] ?? ''))
            ->filter()->values();

        $pfHasPengurus = !empty($mosque->organization_name) || !empty($mosque->chairman_name)
            || !empty($mosque->secretary_name) || !empty($mosque->treasurer_name);
    @endphp

    <section class="hu-section hu-profil-section pf-section" id="profil">
        {{-- bentuk lengkung mihrab (dipakai sebagai clip-path) --}}
        <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
            <defs>
                <clipPath id="pfArch" clipPathUnits="objectBoundingBox">
                    <path d="M0,1 L0,0.46 C0,0.30 0.30,0.20 0.5,0 C0.70,0.20 1,0.30 1,0.46 L1,1 Z"/>
                </clipPath>
            </defs>
        </svg>

        <style>
            .pf-section { position: relative; padding: 120px 0; overflow: hidden; background: #f8f4ea; color: #4b5a53; }
            .pf-section::before { content: ''; position: absolute; inset: 0; pointer-events: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath d='M40 6l7 16.8L64 15l-7.8 17L74 40l-17.8 8L64 65l-17-7.8L40 74l-7-16.8L16 65l7.8-17L6 40l17.8-8L16 15l17 7.8z' fill='none' stroke='%23c1913c' stroke-opacity='.14' stroke-width='1'/%3E%3C/svg%3E"); }
            .pf-section > .hu-container { position: relative; z-index: 1; }
            .pf-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: 72px; align-items: center; }
            @media (max-width: 960px) { .pf-section { padding: 72px 0; } .pf-grid { grid-template-columns: 1fr; gap: 72px; } }

            /* ===== teks ===== */
            .pf-section .pf-kicker { margin: 0 0 16px; display: inline-flex; align-items: center; gap: 10px; font-size: .95rem; font-weight: 600; color: #8a6420; }
            .pf-section .pf-kicker::before { content: ''; width: 30px; height: 1px; background: #c1913c; }
            .pf-section .pf-title { margin: 0 0 20px; font-size: clamp(2.8rem, 6vw, 4.6rem); line-height: 1; color: #0f4636; }
            .pf-section .pf-desc { margin: 0; max-width: 54ch; font-size: 1.04rem; line-height: 1.8; color: #4b5a53; }

            .pf-section .pf-vision { margin: 28px 0 0; max-width: 54ch; padding: 18px 22px; border-radius: 4px 22px 22px 22px; background: #fff; border: 1px solid rgba(193,145,60,.4); box-shadow: 0 10px 26px rgba(15,70,54,.07); }
            .pf-section .pf-vision b { display: block; margin-bottom: 4px; font-size: .86rem; font-weight: 600; color: #8a6420; }
            .pf-section .pf-vision p { margin: 0; font-size: 1rem; line-height: 1.65; font-style: italic; color: #0f4636; }

            /* statistik */
            .pf-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 28px 40px; margin: 40px 0 0; max-width: 560px; }
            @media (max-width: 520px) { .pf-stats { grid-template-columns: 1fr; gap: 22px; } }
            .pf-stat { position: relative; padding-left: 18px; min-width: 0; }
            .pf-stat::before { content: ''; position: absolute; left: 0; top: 4px; bottom: 4px; width: 2px; border-radius: 2px; background: linear-gradient(#c1913c, rgba(193,145,60,.12)); }
            .pf-section .pf-label { margin: 0 0 4px; font-size: .84rem; color: #74847c; }
            .pf-section .pf-val { margin: 0; font-size: clamp(1.7rem, 3vw, 2.2rem); line-height: 1.1; color: #0f4636; word-break: break-word; }
            .pf-val small { margin-left: 6px; font-family: inherit; font-size: .82rem; font-weight: 500; color: #74847c; }
            .pf-more { margin-top: 8px; padding: 0; background: none; border: 0; border-bottom: 1px solid #c1913c; font: inherit; font-size: .86rem; font-weight: 600; color: #8a6420; cursor: pointer; }
            .pf-more:hover { color: #0f4636; }
            .pf-more:focus-visible { outline: 2px solid #c1913c; outline-offset: 3px; }

            .pf-progs { display: flex; flex-wrap: wrap; gap: 8px; margin: 36px 0 0; padding: 0; list-style: none; max-width: 560px; }
            .pf-prog { padding: 7px 16px; border-radius: 999px; font-size: .84rem; font-weight: 600; color: #0f4636; background: #e8f1ec; border: 1px solid rgba(15,70,54,.14); }
            .pf-prog.more { border-style: dashed; color: #74847c; background: transparent; }

            /* ===== foto: bingkai mihrab di atas panel pasir keemasan ===== */
            .pf-media { position: relative; width: 100%; max-width: 420px; margin: 0 auto; padding-bottom: 20px; }
            /* panel pasir: meluas ke tepi kanan layar (desktop) */
            .pf-media::before { content: ''; position: absolute; z-index: 0; top: -64px; bottom: -64px; left: -80px; right: -100vw; border-radius: 44px 0 0 44px;
                background:
                    radial-gradient(420px 320px at 70% 20%, rgba(255,255,255,.55), transparent 70%),
                    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath d='M40 6l7 16.8L64 15l-7.8 17L74 40l-17.8 8L64 65l-17-7.8L40 74l-7-16.8L16 65l7.8-17L6 40l17.8-8L16 15l17 7.8z' fill='none' stroke='%23b8862d' stroke-opacity='.22' stroke-width='1'/%3E%3C/svg%3E"),
                    linear-gradient(160deg, #f3e8cc, #e8d6a6);
                box-shadow: 0 24px 50px rgba(138,100,32,.18); }
            @media (max-width: 960px) {
                .pf-media { max-width: 360px; }
                .pf-media::before { top: -44px; bottom: -44px; left: -28px; right: -28px; border-radius: 36px; }
            }
            .pf-arch { position: relative; z-index: 1; }
            .pf-arch { aspect-ratio: 4 / 5.4; clip-path: url(#pfArch); background: linear-gradient(170deg, #f0d99a, #c1913c 60%, #8a6420); }
            .pf-arch-wrap { position: relative; z-index: 1; filter: drop-shadow(0 20px 26px rgba(90,60,10,.28)); }
            .pf-arch-gap { position: absolute; inset: 12px; clip-path: url(#pfArch); background: #f0e2bd; }
            .pf-arch-img { position: absolute; inset: 20px; clip-path: url(#pfArch); background: #0f4636; overflow: hidden; }
            .pf-arch-img img { display: block; width: 100%; height: 100%; object-fit: cover; }

            .pf-mini { position: absolute; z-index: 2; left: -44px; bottom: -6px; width: 42%; filter: drop-shadow(0 16px 20px rgba(90,60,10,.32)); }
            .pf-mini .pf-arch { aspect-ratio: 1 / 1.15; }
            .pf-mini .pf-arch-gap { inset: 8px; }
            .pf-mini .pf-arch-img { inset: 14px; }
            @media (max-width: 520px) { .pf-mini { left: -14px; } }

            .pf-ph { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(226,195,127,.9);
                background: repeating-linear-gradient(45deg, rgba(255,255,255,.045) 0 14px, transparent 14px 28px), linear-gradient(160deg, #176a4f, #0f4636); }
            .pf-ph svg { width: 36%; max-width: 110px; fill: none; stroke: currentColor; stroke-width: 1.2; stroke-linecap: round; stroke-linejoin: round; }

            .pf-since { position: absolute; z-index: 3; right: -18px; top: 56px; padding: 12px 18px; border-radius: 16px 16px 4px 16px; background: #0f4636; color: #fff8e8; box-shadow: 0 16px 30px rgba(15,70,54,.35); text-align: center; line-height: 1.1; }
            .pf-since small { display: block; margin-bottom: 2px; font-size: .74rem; font-weight: 600; color: #e2c37f; }
            .pf-since b { font-size: 1.6rem; }
            @media (max-width: 520px) { .pf-since { right: -8px; } }
        </style>

        <div class="hu-container">
            <div class="pf-grid">

                {{-- ===== KIRI: teks ===== --}}
                <div class="pf-left">
                    <p class="pf-kicker">Profil Masjid</p>
                    <h2 class="pf-title">{{ $mosque->mosque_name ?? 'Rumah Ibadah' }}</h2>
                    <p class="pf-desc">
                        {{ $mosque->description ?? 'Belum ada deskripsi. Isi di halaman "Profil Masjid" pada panel admin.' }}
                    </p>

                    @if(!empty($mosque->about_vision))
                        <div class="pf-vision">
                            <b>Visi</b>
                            <p>{{ $mosque->about_vision }}</p>
                        </div>
                    @endif

                    <dl class="pf-stats">
                        <div class="pf-stat">
                            <dt class="pf-label">Tahun berdiri</dt>
                            <dd class="pf-val" style="margin-left:0">{{ $mosque->founded ?? '—' }}</dd>
                        </div>
                        <div class="pf-stat">
                            <dt class="pf-label">Kapasitas</dt>
                            <dd class="pf-val" style="margin-left:0">{{ $pfCapText ?: '—' }}@if($pfCapText && is_numeric($pfCap))<small>jamaah</small>@endif</dd>
                        </div>
                        <div class="pf-stat">
                            <dt class="pf-label">Imam besar</dt>
                            <dd class="pf-val" style="margin-left:0">{{ $mosque->imam_name ?? '—' }}</dd>
                            @if($pfHasPengurus)
                                <button type="button" id="btnBukaPengurus" class="pf-more">Lihat detail pengurus</button>
                            @endif
                        </div>
                        <div class="pf-stat">
                            <dt class="pf-label">Program aktif</dt>
                            <dd class="pf-val" style="margin-left:0">{{ $pfProgs->isNotEmpty() ? $pfProgs->count() . ' program' : '—' }}</dd>
                        </div>
                    </dl>

                    @if($pfProgs->isNotEmpty())
                        <ul class="pf-progs" aria-label="Daftar program">
                            @foreach($pfProgs->take(6) as $prog)
                                <li class="pf-prog">{{ $prog }}</li>
                            @endforeach
                            @if($pfProgs->count() > 6)
                                <li class="pf-prog more">+{{ $pfProgs->count() - 6 }} lainnya</li>
                            @endif
                        </ul>
                    @endif
                </div>

                {{-- ===== KANAN: foto bingkai mihrab di atas panel pasir ===== --}}
                <div class="pf-media">
                    <div class="pf-arch-wrap">
                        <div class="pf-arch">
                            <div class="pf-arch-gap"></div>
                            <div class="pf-arch-img">
                                @if(!empty($mosque->about_photo))
                                    <img src="{{ asset('storage/'.$mosque->about_photo) }}" alt="Foto {{ $mosque->mosque_name ?? 'masjid' }}" loading="lazy">
                                @else
                                    <div class="pf-ph" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M12 2.5c-1.6 1.5-3 2.7-3 4.8 0 .5.1.9.3 1.2h5.4c.2-.3.3-.7.3-1.2 0-2.1-1.4-3.3-3-4.8z"/><path d="M6 21v-9.5h12V21M3 21h18M9.5 21v-4a2.5 2.5 0 0 1 5 0v4M4 11.5V7M20 11.5V7"/></svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!empty($mosque->about_photo_secondary))
                        <div class="pf-mini">
                            <div class="pf-arch">
                                <div class="pf-arch-gap"></div>
                                <div class="pf-arch-img">
                                    <img src="{{ asset('storage/'.$mosque->about_photo_secondary) }}" alt="Suasana {{ $mosque->mosque_name ?? 'masjid' }}" loading="lazy">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($mosque->founded))
                        <div class="pf-since">
                            <small>Berdiri sejak</small>
                            <b>{{ $mosque->founded }}</b>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

{{-- MODAL PENGURUS & YAYASAN (dibuka oleh tombol #btnBukaPengurus) --}}
@php
    $pgPeople = collect([
        ['role' => 'Ketua DKM',            'name' => $mosque->chairman_name  ?? null, 'phone' => $mosque->chairman_phone  ?? null],
        ['role' => 'Imam Besar / Khatib',  'name' => $mosque->imam_name      ?? null, 'phone' => $mosque->imam_phone      ?? null],
        ['role' => 'Sekretaris',           'name' => $mosque->secretary_name ?? null, 'phone' => $mosque->secretary_phone ?? null],
        ['role' => 'Bendahara',            'name' => $mosque->treasurer_name ?? null, 'phone' => $mosque->treasurer_phone ?? null],
    ])->filter(fn ($p) => !empty($p['name']))->values();
@endphp

<dialog id="pfPengurusModal" class="pg-modal" aria-labelledby="pgTitle">
    <style>
        .pg-modal { width: min(720px, calc(100vw - 32px)); max-height: calc(100vh - 48px); padding: 0; border: 0; border-radius: 28px; overflow: hidden; color: #4b5a53;
            background: #fbf8f0; box-shadow: 0 40px 90px rgba(11,40,30,.35); opacity: 0; transform: translateY(16px) scale(.98); transition: opacity .25s, transform .25s, overlay .25s allow-discrete, display .25s allow-discrete; }
        .pg-modal[open] { opacity: 1; transform: none; }
        @starting-style { .pg-modal[open] { opacity: 0; transform: translateY(16px) scale(.98); } }
        .pg-modal::backdrop { background: rgba(20,28,24,.55); backdrop-filter: blur(4px); }
        .pg-wrap { display: flex; flex-direction: column; max-height: calc(100vh - 48px); }

        .pg-head { position: relative; flex: none; display: flex; align-items: center; gap: 16px; padding: 26px 28px 22px; border-bottom: 1px solid rgba(193,145,60,.35);
            background:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath d='M40 6l7 16.8L64 15l-7.8 17L74 40l-17.8 8L64 65l-17-7.8L40 74l-7-16.8L16 65l7.8-17L6 40l17.8-8L16 15l17 7.8z' fill='none' stroke='%23b8862d' stroke-opacity='.16' stroke-width='1'/%3E%3C/svg%3E"),
                linear-gradient(160deg, #f3e8cc, #ecdcb0); }
        .pg-arch { flex: none; width: 46px; height: 54px; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 10px; clip-path: path('M0,54 L0,25 C0,16 16,11 23,0 C30,11 46,16 46,25 L46,54 Z'); background: linear-gradient(170deg, #f0d99a, #c1913c 60%, #8a6420); color: #0f4636; }
        .pg-arch svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .pg-titles { min-width: 0; flex: 1; }
        .pg-title { margin: 0; font-size: 1.45rem; line-height: 1.2; color: #0f4636; }
        .pg-sub { margin: 4px 0 0; font-size: .9rem; color: #7a6a3e; }
        .pg-close { flex: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(15,70,54,.2); border-radius: 50%; background: rgba(255,255,255,.6); color: #0f4636; cursor: pointer; transition: background .2s, transform .2s; }
        .pg-close:hover { background: #fff; transform: rotate(90deg); }
        .pg-close:focus-visible, .pg-call:focus-visible { outline: 2px solid #c1913c; outline-offset: 2px; }
        .pg-close svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; }

        .pg-body { flex: 1; overflow-y: auto; padding: 26px 28px 30px; }

        .pg-org { margin: 0 0 22px; padding: 4px 0 4px 18px; border-left: 3px solid #c1913c; }
        .pg-org small { display: block; margin-bottom: 2px; font-size: .84rem; color: #8a6420; }
        .pg-org strong { display: block; font-size: 1.3rem; line-height: 1.25; color: #0f4636; word-break: break-word; }

        .pg-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin: 0; padding: 0; list-style: none; }
        @media (max-width: 600px) {
            .pg-list { grid-template-columns: 1fr; }
            .pg-head { padding: 20px 18px 18px; }
            .pg-body { padding: 20px 18px 24px; }
        }
        .pg-person { display: flex; flex-direction: column; gap: 14px; padding: 18px; border-radius: 4px 22px 22px 22px; background: #fff; border: 1px solid rgba(15,70,54,.1); }
        .pg-person-top { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .pg-avatar { flex: none; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.3rem; font-weight: 700; color: #0f4636;
            background: linear-gradient(145deg, #f6e4b0, #d9ac55); box-shadow: 0 0 0 3px #fff, 0 0 0 4.5px rgba(193,145,60,.6); }
        .pg-role { margin: 0; font-size: .82rem; color: #74847c; }
        .pg-name { margin: 2px 0 0; font-size: 1.12rem; line-height: 1.25; font-weight: 700; color: #0f4636; word-break: break-word; }
        .pg-call { align-self: flex-start; display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px; font-size: .88rem; font-weight: 600; text-decoration: none; color: #0f4636; background: #f3e8cc; border: 1px solid rgba(193,145,60,.5); transition: background .2s; }
        .pg-call:hover { background: #ecdcb0; }
        .pg-call svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .pg-nophone { font-size: .84rem; color: #9aa59f; }
        .pg-empty { margin: 0; padding: 24px 0; text-align: center; color: #74847c; }

        @media (prefers-reduced-motion: reduce) { .pg-modal, .pg-close { transition: none; } }
    </style>

    <div class="pg-wrap">
        <header class="pg-head">
            <span class="pg-arch" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            <div class="pg-titles">
                <h3 class="pg-title" id="pgTitle">Struktur Pengurus &amp; Yayasan</h3>
                <p class="pg-sub">Kontak pengurus {{ $mosque->mosque_name ?? 'masjid' }}</p>
            </div>
            <button type="button" class="pg-close" data-pg-close aria-label="Tutup">
                <svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </header>

        <div class="pg-body">
            @if(!empty($mosque->organization_name))
                <div class="pg-org">
                    <small>Organisasi / Yayasan</small>
                    <strong>{{ $mosque->organization_name }}</strong>
                </div>
            @endif

            @if($pgPeople->isNotEmpty())
                <ul class="pg-list">
                    @foreach($pgPeople as $person)
                        @php
                            $pgInitial = mb_strtoupper(mb_substr(trim($person['name']), 0, 1));
                            $pgTel = preg_replace('/[^0-9+]/', '', (string) $person['phone']);
                        @endphp
                        <li class="pg-person">
                            <div class="pg-person-top">
                                <span class="pg-avatar" aria-hidden="true">{{ $pgInitial }}</span>
                                <div style="min-width:0">
                                    <p class="pg-role">{{ $person['role'] }}</p>
                                    <p class="pg-name">{{ $person['name'] }}</p>
                                </div>
                            </div>
                            @if(!empty($pgTel))
                                <a class="pg-call" href="tel:{{ $pgTel }}">
                                    <svg viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
                                    {{ $person['phone'] }}
                                </a>
                            @else
                                <span class="pg-nophone">Nomor belum tersedia</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @elseif(empty($mosque->organization_name))
                <p class="pg-empty">Data pengurus belum diisi di panel admin.</p>
            @endif
        </div>
    </div>

    <script>
        (function () {
            var dlg = document.getElementById('pfPengurusModal');
            var openBtn = document.getElementById('btnBukaPengurus');
            if (!dlg) return;

            function openDlg() {
                if (typeof dlg.showModal === 'function') { dlg.showModal(); } else { dlg.setAttribute('open', ''); }
                document.documentElement.style.overflow = 'hidden';
            }
            function closeDlg() {
                if (typeof dlg.close === 'function') { dlg.close(); } else { dlg.removeAttribute('open'); }
            }

            // capture + stopImmediatePropagation: cegah script modal lama ikut terpanggil
            if (openBtn) openBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                openDlg();
            }, true);
            dlg.querySelectorAll('[data-pg-close]').forEach(function (b) { b.addEventListener('click', closeDlg); });
            // klik area gelap di luar kotak = tutup
            dlg.addEventListener('click', function (e) { if (e.target === dlg) closeDlg(); });
            dlg.addEventListener('close', function () {
                document.documentElement.style.overflow = '';
                if (openBtn) openBtn.focus();
            });
        })();
    </script>
</dialog>
    
    {{-- JADWAL SHALAT SECTION --}}
    @if($modOn('jadwal_shalat'))
    @php
        $bulanNama = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        $hariNama  = ['Ahad','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $kolom     = ['imsak'=>'Imsak','subuh'=>'Subuh','terbit'=>'Terbit','dhuha'=>'Dhuha',
                      'dzuhur'=>'Dzuhur','ashar'=>'Ashar','maghrib'=>'Maghrib','isya'=>"Isya'"];
        $arab      = ['Subuh'=>'الفجر','Dzuhur'=>'الظهر','Ashar'=>'العصر','Maghrib'=>'المغرب','Isya'=>'العشاء'];

        $tzName  = $timezone ?? 'Asia/Jakarta';
        $nowTz   = \Carbon\Carbon::now($tzName);
        $curBln  = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
        $curDate = \Carbon\Carbon::create((int) $tahun, (int) $curBln, 1);
        $prev    = $curDate->copy()->subMonth();
        $next    = $curDate->copy()->addMonth();
        $inRange = function ($d) use ($nowTz) { return $d->year >= $nowTz->year - 1 && $d->year <= $nowTz->year + 2; };
        $navUrl  = function ($d) use ($mosque) {
            return route('masjid.publik', ['slug' => $mosque->slug, 'bulan' => $d->format('m'), 'tahun' => $d->year]) . '#kalender-shalat';
        };

        $tanggalIni = $hariNama[$nowTz->dayOfWeek] . ', ' . $nowTz->day . ' ' . ($bulanNama[$nowTz->format('m')] ?? '') . ' ' . $nowTz->year;
        $activeIdx  = collect($prayers)->search(fn ($p) => !empty($p['active']));

        // Imam per shalat & jadwal shalat Id (pakai variabel dari controller bila dikirim, jika tidak ambil langsung)
        $todayStr = $today ?? $nowTz->toDateString();
        $imams    = $imams ?? \App\Models\PrayerImam::where('mosque_id', $mosque->id)->pluck('imam_name', 'prayer')->all();
        $eidList  = collect($eidPrayers ?? \App\Models\EidPrayer::where('mosque_id', $mosque->id)->get())
                        ->filter(fn ($e) => $e->event_date->toDateString() >= $todayStr)
                        ->sortBy(fn ($e) => $e->event_date->toDateString() . ' ' . $e->prayer_time)
                        ->take(3);
    @endphp

    <section class="hu-section hu-section-dark" id="shalat" aria-labelledby="shalat-title">
        <style>
            /* ===== Hero hitung mundur ===== */
            .hx-hero { display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: center; margin: 0 0 22px; padding: 26px 30px; border-radius: 22px; color: #f3ead7;
                       background: radial-gradient(600px 200px at 100% 0%, rgba(193,145,60,.30), transparent), linear-gradient(135deg, rgba(255,255,255,.09), rgba(255,255,255,.03));
                       border: 1px solid rgba(226,195,127,.35); box-shadow: 0 18px 40px rgba(0,0,0,.25); }
            .hx-lbl { font-size: .72rem; letter-spacing: .14em; text-transform: uppercase; opacity: .7; }
            .hx-hero-name { margin: 4px 0 2px; font-size: 1.9rem; font-weight: 700; color: #e2c37f; line-height: 1.15; }
            .hx-hero-meta { font-size: .85rem; opacity: .8; display: flex; flex-wrap: wrap; gap: 4px 14px; }
            .hx-cd { display: flex; gap: 10px; }
            .hx-cd div { min-width: 74px; text-align: center; padding: 12px 8px; border-radius: 14px; background: rgba(0,0,0,.28); border: 1px solid rgba(255,255,255,.1); }
            .hx-cd b { display: block; font-size: 1.9rem; line-height: 1.1; color: #fff; font-variant-numeric: tabular-nums; }
            .hx-cd span { font-size: .62rem; letter-spacing: .12em; text-transform: uppercase; opacity: .65; }
            @media (max-width: 760px) {
                .hx-hero { grid-template-columns: 1fr; text-align: center; padding: 22px 18px; }
                .hx-hero-meta, .hx-cd { justify-content: center; }
                .hx-hero-name { font-size: 1.6rem; }
            }

            /* ===== Kartu waktu shalat ===== */
            .hx-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; }
            .hx-card { position: relative; text-align: center; padding: 22px 12px 18px; border-radius: 20px; color: #f3ead7; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12); transition: transform .2s, border-color .2s; }
            .hx-card:hover { transform: translateY(-4px); border-color: #c1913c; }
            .hx-ar { font-family: 'Amiri','Scheherazade New','Noto Naskh Arabic',serif; font-size: 1.7rem; line-height: 1.25; color: #e2c37f; }
            .hx-name { margin-top: 2px; font-size: .85rem; opacity: .8; }
            .hx-time { margin: 8px 0 2px; font-size: 2rem; font-weight: 700; color: #fff; font-variant-numeric: tabular-nums; }
            .hx-iq { display: inline-block; margin-top: 8px; padding: 2px 11px; font-size: .72rem; border-radius: 999px; background: rgba(255,255,255,.1); }
            .hx-badge { position: absolute; top: 10px; right: 10px; display: none; padding: 2px 9px; font-size: .62rem; font-weight: 700; border-radius: 999px; }
            .hx-card.is-past { opacity: .5; }
            .hx-card.is-next { border-color: #e2c37f; box-shadow: 0 0 0 1px #e2c37f inset; }
            .hx-card.is-next .hx-badge.nxt { display: block; background: #e2c37f; color: #1c1405; }
            .hx-card.is-active { color: #1c1405; border-color: transparent; background: linear-gradient(160deg, #f0d99a, #c1913c); box-shadow: 0 14px 34px rgba(193,145,60,.4); }
            .hx-card.is-active .hx-ar, .hx-card.is-active .hx-time { color: #1c1405; }
            .hx-card.is-active .hx-iq { background: rgba(28,20,5,.12); }
            .hx-card.is-active .hx-badge.now { display: block; background: #1c1405; color: #e2c37f; }

            /* ===== Imam & jadwal Id ===== */
            .hx-imam { margin-top: 10px; padding-top: 10px; border-top: 1px dashed rgba(255,255,255,.18); font-size: .78rem; line-height: 1.35; }
            .hx-imam small { display: block; font-size: .62rem; letter-spacing: .12em; text-transform: uppercase; opacity: .6; }
            .hx-card.is-active .hx-imam { border-top-color: rgba(28,20,5,.25); }
            .hx-hero-meta b { color: #fff; }

            .hx-eid { margin-top: 36px; }
            .hx-eid-title { margin-bottom: 14px; }
            .hx-eid-title h3 { margin: 0; font-size: 1.15rem; color: #f3ead7; }
            .hx-eid-title p { margin: 2px 0 0; font-size: .78rem; opacity: .65; color: #f3ead7; }
            .hx-eid-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 14px; }
            .hx-eid-card { display: flex; gap: 16px; padding: 18px; border-radius: 20px; color: #f3ead7; border: 1px solid rgba(226,195,127,.4);
                           background: radial-gradient(420px 160px at 100% 0%, rgba(193,145,60,.25), transparent), rgba(255,255,255,.05); }
            .hx-eid-date { flex: none; width: 66px; height: fit-content; text-align: center; border-radius: 14px; overflow: hidden; background: rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.12); }
            .hx-eid-date small { display: block; padding: 4px 0; font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; background: #c1913c; color: #1c1405; }
            .hx-eid-date b { display: block; padding: 8px 0; font-size: 1.7rem; line-height: 1; color: #fff; }
            .hx-eid-body h4 { margin: 6px 0 8px; font-size: 1.05rem; color: #fff; }
            .hx-eid-chip { display: inline-block; padding: 2px 10px; font-size: .68rem; font-weight: 700; border-radius: 999px; background: #e2c37f; color: #1c1405; }
            .hx-eid-meta { margin: 0; padding: 0; list-style: none; display: grid; gap: 3px; font-size: .82rem; opacity: .9; }
            .hx-eid-meta b { color: #e2c37f; }
            .hx-eid-note { margin: 10px 0 0; font-size: .78rem; opacity: .7; }

            /* ===== Kalender (mini kalender + detail) ===== */
            .hu-kal { margin-top: 44px; }
            .hu-kal-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
            .hu-kal-title { margin: 0; font-size: 1.15rem; color: #f3ead7; }
            .hu-kal-title small { display: block; margin-top: 2px; font-size: .75rem; font-weight: 400; opacity: .65; }
            .hu-kal-nav { display: flex; gap: 8px; align-items: center; }
            .hu-kal-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 14px; border-radius: 999px; border: 1px solid rgba(255,255,255,.2); color: #f3ead7; background: rgba(255,255,255,.06); text-decoration: none; font-size: .8rem; transition: .15s; }
            .hu-kal-btn:hover { background: rgba(193,145,60,.28); border-color: #c1913c; }
            .hu-kal-btn[aria-disabled="true"] { opacity: .35; pointer-events: none; }
            .hu-kal-empty { padding: 32px; text-align: center; opacity: .8; border: 1px solid rgba(255,255,255,.14); border-radius: 16px; background: rgba(255,255,255,.04); }
            .hu-kal-note { margin-top: 14px; font-size: .8rem; opacity: .7; text-align: center; }

            .kx-layout { display: grid; grid-template-columns: minmax(280px, 380px) 1fr; gap: 18px; align-items: stretch; }
            @media (max-width: 860px) { .kx-layout { grid-template-columns: 1fr; } }

            .kx-mini, .kx-detail { padding: 20px; border-radius: 20px; color: #f3ead7; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.13); }
            .kx-dow, .kx-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
            .kx-dow { margin-bottom: 8px; text-align: center; font-size: .68rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: #e2c37f; }
            .kx-dow .sun { color: #f0a29a; }
            .kx-blank { aspect-ratio: 1; }
            .kx-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; position: relative; border-radius: 12px; border: 1px solid transparent; background: rgba(255,255,255,.05); color: #f3ead7; font: inherit; font-size: .88rem; font-weight: 600; cursor: pointer; transition: .15s; }
            .kx-day:hover { background: rgba(193,145,60,.25); }
            .kx-day.is-fri { color: #e2c37f; }
            .kx-day.is-fri::after { content: ''; position: absolute; bottom: 5px; width: 4px; height: 4px; border-radius: 50%; background: #e2c37f; }
            .kx-day.is-today { background: linear-gradient(160deg, #f0d99a, #c1913c); color: #1c1405; box-shadow: 0 6px 16px rgba(193,145,60,.35); }
            .kx-day.is-today::after { background: #1c1405; }
            .kx-day.is-sel { border-color: #fff; box-shadow: 0 0 0 2px rgba(255,255,255,.35); }
            .kx-day:focus-visible { outline: 2px solid #e2c37f; outline-offset: 2px; }
            .kx-hint { margin: 14px 0 0; font-size: .75rem; opacity: .65; text-align: center; }

            .kx-detail { display: flex; flex-direction: column; justify-content: center; background: radial-gradient(500px 200px at 100% 0%, rgba(193,145,60,.22), transparent), rgba(255,255,255,.05); }
            .kx-tag { font-size: .7rem; letter-spacing: .14em; text-transform: uppercase; color: #e2c37f; }
            .kx-label { margin: 4px 0 18px; font-size: 1.4rem; font-weight: 700; color: #fff; }
            .kx-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
            @media (max-width: 560px) { .kx-tiles { grid-template-columns: repeat(2, 1fr); } }
            .kx-tile { padding: 14px 8px; text-align: center; border-radius: 14px; background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.08); }
            .kx-tile span { display: block; font-size: .7rem; letter-spacing: .06em; text-transform: uppercase; opacity: .7; }
            .kx-tile b { display: block; margin-top: 4px; font-size: 1.45rem; color: #fff; font-variant-numeric: tabular-nums; }
            .kx-tile { transition: opacity .2s, background .2s, border-color .2s; }
            .kx-st { display: block; min-height: 14px; margin-top: 4px; font-size: .6rem; font-style: normal; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
            .kx-tile.is-past { opacity: .45; }
            .kx-tile.is-next { border-color: #e2c37f; box-shadow: 0 0 0 1px #e2c37f inset; }
            .kx-tile.is-next .kx-st { color: #e2c37f; }
            .kx-tile.is-active { background: linear-gradient(160deg, #f0d99a, #c1913c); border-color: transparent; box-shadow: 0 10px 24px rgba(193,145,60,.35); }
            .kx-tile.is-active span, .kx-tile.is-active b, .kx-tile.is-active .kx-st { color: #1c1405; opacity: 1; }
            .kx-now { margin: -8px 0 16px; font-size: .84rem; color: #f3ead7; opacity: .9; }
            .kx-now b { color: #e2c37f; font-variant-numeric: tabular-nums; }

            /* Tabel lengkap (dilipat) */
            .kx-more { margin-top: 18px; border: 1px solid rgba(255,255,255,.13); border-radius: 16px; background: rgba(255,255,255,.04); overflow: hidden; }
            .kx-more summary { cursor: pointer; padding: 14px 18px; font-size: .88rem; font-weight: 600; color: #e2c37f; list-style: none; display: flex; justify-content: space-between; align-items: center; }
            .kx-more summary::-webkit-details-marker { display: none; }
            .kx-more summary::after { content: '▾'; transition: transform .2s; }
            .kx-more[open] summary::after { transform: rotate(180deg); }
            .kx-more .hu-kal-scroll { max-height: 440px; overflow: auto; border-top: 1px solid rgba(255,255,255,.13); }
            .hu-kal-table { width: 100%; min-width: 640px; border-collapse: collapse; color: #f3ead7; font-size: .8rem; font-variant-numeric: tabular-nums; }
            .hu-kal-table th, .hu-kal-table td { padding: 8px 10px; text-align: center; white-space: nowrap; }
            .hu-kal-table thead th { position: sticky; top: 0; z-index: 1; background: #0f4636; font-weight: 600; font-size: .72rem; letter-spacing: .04em; color: #e2c37f; border-bottom: 1px solid rgba(255,255,255,.18); }
            .hu-kal-table tbody tr { border-bottom: 1px solid rgba(255,255,255,.07); }
            .hu-kal-table tbody tr:nth-child(even) { background: rgba(255,255,255,.03); }
            .hu-kal-table td.tgl { text-align: left; font-weight: 600; }
            .hu-kal-table td.tgl small { margin-left: 6px; font-weight: 400; opacity: .65; font-size: .7rem; }
            .hu-kal-table tr.is-friday { background: rgba(226,195,127,.1); }
            .hu-kal-table tr.is-friday td.tgl { color: #e2c37f; }
            .hu-kal-table tr.is-today { background: linear-gradient(90deg, #e2c37f, #c1913c); font-weight: 700; }
            .hu-kal-table tr.is-today td, .hu-kal-table tr.is-today td.tgl small { color: #1c1405; }
            .hu-kal-table td:nth-child(3), .hu-kal-table td:nth-child(9) { color: #e2c37f; font-weight: 600; }
            .hu-kal-table tr.is-today td:nth-child(3), .hu-kal-table tr.is-today td:nth-child(9) { color: #1c1405; }
        </style>

        <div class="hu-container">
            <div class="hu-section-head hu-section-head-light">
                <div class="hu-section-tag hu-tag-light">Hari Ini{{ !empty($timezoneLabel) ? ' · ' . $timezoneLabel : '' }}</div>
                <h2 class="hu-section-title hu-title-light" id="shalat-title">Jadwal Waktu Shalat</h2>
            </div>

            {{-- Hero: shalat berikutnya + hitung mundur --}}
            @if(!empty($prayers))
            <div class="hx-hero">
                <div>
                    <div class="hx-lbl">Menuju waktu shalat</div>
                    <div class="hx-hero-name"><span id="hxName">-</span> &middot; <span id="hxTime">--:--</span></div>
                    <div class="hx-hero-meta">
                        <span>{{ $tanggalIni }}</span>
                        <span>Sekarang <b id="hxClock">--:--:--</b> {{ $timezoneLabel ?? '' }}</span>
                        <span id="hxImamWrap" hidden>Imam: <b id="hxImam"></b></span>
                    </div>
                </div>
                <div class="hx-cd" aria-live="off">
                    <div><b id="hxH">00</b><span>Jam</span></div>
                    <div><b id="hxM">00</b><span>Menit</span></div>
                    <div><b id="hxS">00</b><span>Detik</span></div>
                </div>
            </div>
            @endif

            <div class="hx-grid">
                @foreach($prayers as $i => $p)
                    @php
                        $state    = !empty($p['active']) ? 'is-active' : (($activeIdx !== false && $i < $activeIdx) ? 'is-past' : '');
                        $imamNama = $imams[strtolower($p['name'])] ?? '';
                    @endphp
                    <div class="hx-card {{ $state }}" data-name="{{ $p['name'] }}" data-time="{{ $p['time'] }}" data-imam="{{ $imamNama }}" @if(!empty($p['active'])) aria-current="true" @endif>
                        <span class="hx-badge now">Sekarang</span>
                        <span class="hx-badge nxt">Berikutnya</span>
                        <div class="hx-ar" lang="ar" dir="rtl">{{ $arab[$p['name']] ?? '' }}</div>
                        <div class="hx-name">{{ $p['name'] }}</div>
                        <div class="hx-time">{{ $p['time'] }}</div>
                        @if($imamNama)
                            <div class="hx-imam"><small>Imam</small>{{ $imamNama }}</div>
                        @endif
                        @if(!empty($p['iqamah']))<div class="hx-iq">Iqamah {{ $p['iqamah'] }}</div>@endif
                    </div>
                @endforeach
            </div>

            @if(!empty($prayers[0]['is_fallback']))
                <p role="status" class="hu-kal-note">Jadwal resmi belum dapat dimuat, jadi waktu di atas hanya perkiraan.</p>
            @endif

            {{-- Jadwal shalat Id (Idul Fitri / Idul Adha) --}}
            @if($eidList->isNotEmpty())
                <div class="hx-eid">
                    <div class="hx-eid-title">
                        <h3>Jadwal Shalat Id</h3>
                        <p>Jadwal terbaru dari pengurus masjid</p>
                    </div>
                    <div class="hx-eid-grid">
                        @foreach($eidList as $eid)
                            @php
                                $dl     = (int) \Carbon\Carbon::parse($todayStr)->startOfDay()->diffInDays($eid->event_date->copy()->startOfDay(), false);
                                $dlText = $dl === 0 ? 'Hari ini' : ($dl === 1 ? 'Besok' : 'H-' . $dl);
                            @endphp
                            <article class="hx-eid-card">
                                <div class="hx-eid-date" aria-hidden="true">
                                    <small>{{ mb_substr($bulanNama[$eid->event_date->format('m')] ?? '', 0, 3) }}</small>
                                    <b>{{ $eid->event_date->day }}</b>
                                </div>
                                <div class="hx-eid-body">
                                    <span class="hx-eid-chip">{{ $dlText }}</span>
                                    <h4>{{ $eid->title }}</h4>
                                    <ul class="hx-eid-meta">
                                        <li>{{ $hariNama[$eid->event_date->dayOfWeek] }}, {{ $eid->event_date->day }} {{ $bulanNama[$eid->event_date->format('m')] ?? '' }} {{ $eid->event_date->year }}</li>
                                        <li>Pukul <b>{{ substr($eid->prayer_time, 0, 5) }}</b> {{ $timezoneLabel ?? '' }}</li>
                                        @if($eid->location)<li>Tempat: {{ $eid->location }}</li>@endif
                                        @if($eid->imam_name)<li>Imam: <b>{{ $eid->imam_name }}</b></li>@endif
                                        @if($eid->khatib_name)<li>Khatib: <b>{{ $eid->khatib_name }}</b></li>@endif
                                    </ul>
                                    @if($eid->notes)<p class="hx-eid-note">{{ $eid->notes }}</p>@endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Kalender bulanan: mini kalender + detail hari --}}
            <div class="hu-kal" id="kalender-shalat">
                <div class="hu-kal-head">
                    <h3 class="hu-kal-title">
                        Jadwal {{ $bulanNama[$curBln] ?? '' }} {{ $tahun }}
                        <small>Pilih tanggal untuk melihat Imsak sampai Isya' &middot; Jumat ditandai titik emas</small>
                    </h3>
                    <div class="hu-kal-nav">
                        <a class="hu-kal-btn" @if($inRange($prev)) href="{{ $navUrl($prev) }}" @else aria-disabled="true" @endif aria-label="Bulan sebelumnya">&larr;</a>
                        @if($curBln !== $nowTz->format('m') || (int) $tahun !== $nowTz->year)
                            <a class="hu-kal-btn" href="{{ route('masjid.publik', ['slug' => $mosque->slug]) }}#kalender-shalat">Bulan ini</a>
                        @endif
                        <a class="hu-kal-btn" @if($inRange($next)) href="{{ $navUrl($next) }}" @else aria-disabled="true" @endif aria-label="Bulan berikutnya">&rarr;</a>
                    </div>
                </div>

                @if(!empty($monthlySchedule))
                    @php
                        $rowsC    = collect($monthlySchedule);
                        $selected = $rowsC->firstWhere('date', $today ?? null) ?? $rowsC->first();
                        $selLabel = $hariNama[$selected['weekday'] ?? 0] . ', ' . ($selected['day'] ?? '') . ' ' . ($bulanNama[$curBln] ?? '') . ' ' . $tahun;
                        $selToday = ($selected['date'] ?? null) === ($today ?? null);
                    @endphp

                    <div class="kx-layout">
                        <div class="kx-mini">
                            <div class="kx-dow" aria-hidden="true">
                                @foreach(['Ahd','Sen','Sel','Rab','Kam','Jum','Sab'] as $d)
                                    <span class="{{ $loop->first ? 'sun' : '' }}">{{ $d }}</span>
                                @endforeach
                            </div>
                            <div class="kx-days">
                                @for($i = 0; $i < $curDate->dayOfWeek; $i++)<span class="kx-blank" aria-hidden="true"></span>@endfor
                                @foreach($monthlySchedule as $row)
                                    @php
                                        $isToday = ($row['date'] ?? null) === ($today ?? null);
                                        $isFri   = ($row['weekday'] ?? null) === 5;
                                        $isSel   = ($row['date'] ?? null) === ($selected['date'] ?? null);
                                        $times   = collect($kolom)->keys()->mapWithKeys(fn ($k) => [$k => $row[$k] ?? '-']);
                                    @endphp
                                    <button type="button"
                                            class="kx-day {{ $isToday ? 'is-today' : '' }} {{ $isFri ? 'is-fri' : '' }} {{ $isSel ? 'is-sel' : '' }}"
                                            data-t="{{ $times->toJson() }}"
                                            data-label="{{ $hariNama[$row['weekday'] ?? 0] }}, {{ $row['day'] }} {{ $bulanNama[$curBln] ?? '' }} {{ $tahun }}"
                                            aria-pressed="{{ $isSel ? 'true' : 'false' }}"
                                            @if($isToday) aria-current="date" @endif>{{ $row['day'] }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="kx-detail" aria-live="polite">
                            <div class="kx-tag" id="kxTag">{{ $selToday ? 'Hari ini' : (($selected['weekday'] ?? null) === 5 ? 'Hari Jumat' : 'Tanggal dipilih') }}</div>
                            <h4 class="kx-label" id="kxLabel">{{ $selLabel }}</h4>
                            <div class="kx-now" id="kxNow" {{ $selToday ? '' : 'hidden' }}></div>
                            <div class="kx-tiles">
                                @foreach($kolom as $key => $label)
                                    <div class="kx-tile k-{{ $key }}" data-k="{{ $key }}">
                                        <span>{{ $label }}</span>
                                        <b>{{ $selected[$key] ?? '-' }}</b>
                                        <em class="kx-st"></em>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Tabel lengkap sebulan (dilipat agar halaman tidak terlalu panjang) --}}
                    <details class="kx-more">
                        <summary>Lihat tabel lengkap bulan {{ $bulanNama[$curBln] ?? '' }} {{ $tahun }}</summary>
                        <div class="hu-kal-scroll">
                            <table class="hu-kal-table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align:left;">Tanggal</th>
                                        @foreach($kolom as $label)<th scope="col">{{ $label }}</th>@endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlySchedule as $row)
                                        @php
                                            $isToday  = ($row['date'] ?? null) === ($today ?? null);
                                            $isFriday = ($row['weekday'] ?? null) === 5;
                                        @endphp
                                        <tr class="{{ $isToday ? 'is-today' : '' }} {{ $isFriday ? 'is-friday' : '' }}" @if($isToday) aria-current="date" @endif>
                                            <td class="tgl">
                                                {{ $row['day'] ?? $loop->iteration }}
                                                <small>{{ $hariNama[$row['weekday'] ?? 0] ?? '' }}{{ $isToday ? ' · Hari ini' : '' }}</small>
                                            </td>
                                            @foreach($kolom as $key => $label)
                                                <td>{{ $row[$key] ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @else
                    <div class="hu-kal-empty">Jadwal bulan ini belum dapat dimuat. Coba lagi beberapa saat lagi.</div>
                @endif
            </div>
        </div>

        <script>
            // Hitung mundur & penanda waktu shalat (mengikuti zona waktu masjid)
            (function () {
                const root = document.getElementById('shalat');
                if (!root) return;
                const tz = @json($tzName);
                const cards = [...root.querySelectorAll('.hx-card[data-time]')].filter(c => /^\d{2}:\d{2}$/.test(c.dataset.time));
                if (!cards.length) return;

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

                    $('hxName').textContent = cards[next].dataset.name;
                    $('hxTime').textContent = cards[next].dataset.time;
                    const imam = cards[next].dataset.imam || '';
                    $('hxImam').textContent = imam;
                    $('hxImamWrap').hidden = !imam;
                    $('hxClock').textContent = fmt.format(new Date());
                    $('hxH').textContent = pad(Math.floor(diff / 3600));
                    $('hxM').textContent = pad(Math.floor((diff % 3600) / 60));
                    $('hxS').textContent = pad(diff % 60);
                }
                tick();
                setInterval(tick, 1000);
            })();
        </script>
        <script>
            // Pilih tanggal di mini kalender + sorot waktu shalat yang sedang berlangsung (khusus hari ini)
            (function () {
                const root = document.getElementById('kalender-shalat');
                if (!root) return;
                const days = [...root.querySelectorAll('.kx-day')];
                if (!days.length) return;

                const tz = @json($tzName);
                const fmt = new Intl.DateTimeFormat('en-GB', { timeZone: tz, hourCycle: 'h23', hour: '2-digit', minute: '2-digit', second: '2-digit' });
                const tiles = [...root.querySelectorAll('.kx-tile')];
                const nowEl = root.querySelector('#kxNow');
                const pad = n => String(n).padStart(2, '0');
                const hms = d => pad(Math.floor(d / 3600)) + ':' + pad(Math.floor((d % 3600) / 60)) + ':' + pad(d % 60);

                let viewingToday = !!root.querySelector('.kx-day.is-sel.is-today');
                let lastSec = -1;

                function nowSec() {
                    const p = fmt.formatToParts(new Date());
                    const g = t => parseInt(p.find(x => x.type === t).value, 10);
                    return g('hour') * 3600 + g('minute') * 60 + g('second');
                }

                function apply() {
                    tiles.forEach(t => { t.classList.remove('is-active', 'is-past', 'is-next'); t.querySelector('.kx-st').textContent = ''; });
                    if (!viewingToday) { nowEl.hidden = true; return; }

                    const s = nowSec();
                    if (lastSec >= 0 && s < lastSec - 3600) { location.reload(); return; } // lewat tengah malam
                    lastSec = s;

                    const times = tiles.map(t => {
                        const m = (t.querySelector('b').textContent || '').match(/^(\d{1,2}):(\d{2})$/);
                        return m ? parseInt(m[1], 10) * 3600 + parseInt(m[2], 10) * 60 : null;
                    });

                    let active = -1, next = -1;
                    times.forEach((t, i) => { if (t !== null && t <= s) active = i; });
                    for (let i = 0; i < times.length; i++) { if (times[i] !== null && times[i] > s) { next = i; break; } }

                    let diff;
                    if (next === -1) { next = times.findIndex(t => t !== null); diff = 86400 - s + times[next]; }
                    else { diff = times[next] - s; }

                    tiles.forEach((t, i) => {
                        if (i === active) { t.classList.add('is-active'); t.querySelector('.kx-st').textContent = 'Sekarang'; }
                        else if (i < active) { t.classList.add('is-past'); }
                        if (i === next && i !== active) { t.classList.add('is-next'); t.querySelector('.kx-st').textContent = 'Berikutnya'; }
                    });

                    nowEl.hidden = false;
                    nowEl.innerHTML = 'Sekarang pukul <b>' + fmt.format(new Date()) + '</b> &middot; ' +
                        tiles[next].querySelector('span').textContent + ' dalam <b>' + hms(diff) + '</b>';
                }

                days.forEach(btn => btn.addEventListener('click', () => {
                    days.forEach(b => { b.classList.remove('is-sel'); b.setAttribute('aria-pressed', 'false'); });
                    btn.classList.add('is-sel');
                    btn.setAttribute('aria-pressed', 'true');

                    const t = JSON.parse(btn.dataset.t);
                    tiles.forEach(el => { el.querySelector('b').textContent = t[el.dataset.k] || '-'; });
                    root.querySelector('#kxLabel').textContent = btn.dataset.label;
                    root.querySelector('#kxTag').textContent =
                        btn.classList.contains('is-today') ? 'Hari ini'
                        : btn.classList.contains('is-fri') ? 'Hari Jumat' : 'Tanggal dipilih';

                    viewingToday = btn.classList.contains('is-today');
                    lastSec = -1;
                    apply();
                }));

                apply();
                setInterval(apply, 1000);
            })();
        </script>
    </section>
    @endif

    {{-- PROGRAM & FASILITAS SECTION --}}
    <section class="hu-section hu-program-section" id="program">
        <div class="hu-container">
            <div class="hu-progfas-grid">
                <div>
                    <div class="hu-section-head hu-progfas-col-head">
                        <div class="hu-section-tag hu-tag-amber">Kegiatan & Program</div>
                        <h2 class="hu-section-title hu-title-dark">Program Unggulan</h2>
                    </div>
                    <div class="hu-program-v2-list">
                        @php
                            $programList = !empty($mosque->programs) ? $mosque->programs : [
                                'Hafalan Quran 30 Juz', 'Ekonomi Syariah', 'Koperasi Masjid', 'Kajian Tafsir', 'Program Yatim',
                            ];
                        @endphp
                        @foreach($programList as $idx => $prog)
                        <div class="hu-program-v2-item">
                            <span class="hu-program-v2-num">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="hu-program-v2-name">{{ $prog }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if(!empty($mosque->facilities))
                <div>
                    <div class="hu-section-head hu-progfas-col-head">
                        <div class="hu-section-tag hu-tag-amber">Fasilitas Masjid</div>
                        <h2 class="hu-section-title hu-title-dark">Fasilitas Dan Layanan</h2>
                    </div>
                    <div class="hu-fasilitas-wrap">
                        @foreach($mosque->facilities as $idx => $f)
                        <div class="hu-fasilitas-item">
                            <span class="hu-fasilitas-num">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="hu-fasilitas-name">{{ $f }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ACARA SECTION --}}
    @if($modOn('kegiatan'))
    <section class="hu-acara-section" id="acara">
        <div class="hu-container">
            <div class="hu-acara-v2-head">
                <div>
                    <h2 class="hu-section-title hu-title-dark">Acara Mendatang</h2>
                </div>
                <a href="javascript:void(0);" id="btnLihatSemuaAcara" class="hu-acara-lihat">Lihat semua →</a>
            </div>
            <div class="hu-acara-v2-grid">
                @forelse(($acaras ?? collect()) as $a)
                <div class="hu-acara-v2-card btn-buka-acara"
                     style="cursor: pointer;"
                     data-title="{{ $a->title }}"
                     data-desc="{{ $a->description ?? 'Tidak ada deskripsi lengkap untuk acara ini.' }}"
                     data-date="{{ \Illuminate\Support\Carbon::parse($a->event_date)->translatedFormat('d F Y') }}"
                     data-time="{{ $a->event_time ?? '-' }}"
                     data-organizer="{{ $a->organizer ?? 'Pengurus Masjid' }}"
                     data-photo="{{ !empty($a->photo) ? asset('storage/'.$a->photo) : '' }}">
                    
                    @if(!empty($a->photo))
                    <div class="hu-acara-v2-photo">
                        <img src="{{ asset('storage/'.$a->photo) }}" alt="{{ $a->title }}" loading="lazy">
                    </div>
                    @endif
                    <div class="hu-acara-v2-top">
                        <div class="hu-acara-v2-date">
                            <div class="hu-acara-v2-month">{{ \Illuminate\Support\Carbon::parse($a->event_date)->translatedFormat('M') }}</div>
                            <div class="hu-acara-v2-day">{{ \Illuminate\Support\Carbon::parse($a->event_date)->format('d') }}</div>
                        </div>
                        @if($a->is_featured)<span class="hu-acara-v2-badge">Terbaru</span>@endif
                    </div>
                    <div class="hu-acara-v2-judul">{{ $a->title }}</div>
                    <div class="hu-acara-v2-meta">
                        {{ $a->event_time ?? '-' }}
                        @if($a->organizer) · {{ $a->organizer }} @endif
                    </div>
                    <span class="hu-acara-v2-link">Detail Acara →</span>
                </div>
                @empty
                <p style="color:var(--ink-soft);grid-column:1/-1;">Belum ada acara mendatang.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- MODAL POPUP DETAIL ACARA (Tunggal) -->
    <div id="modalDetailAcara" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 90vh; display: flex; flex-direction: column;">
            <div style="position: relative; height: 250px; background-size: cover; background-position: center; display: none;" id="modalAcaraFoto">
                <button type="button" id="tutupModalAcara" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.5); color: #fff; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span id="modalAcaraPenyelenggara" style="font-size: 0.75rem; background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600;"></span>
                    <button type="button" id="tutupModalAcaraAlt" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #64748b;">✕</button>
                </div>
                <h3 id="modalAcaraJudul" style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0.5rem 0 0.75rem 0;"></h3>
                <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: #64748b; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                    <span>📅 <strong id="modalAcaraTanggal" style="color: #0d9488;"></strong></span>
                    <span>⏰ <strong id="modalAcaraWaktu" style="color: #0d9488;"></strong></span>
                </div>
                <p id="modalAcaraDeskripsi" style="font-size: 0.95rem; color: #475569; line-height: 1.6; margin: 0; white-space: pre-line;"></p>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP DAFTAR SEMUA ACARA -->
    <div id="modalSemuaAcara" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #fff; width: 100%; max-width: 650px; border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 85vh; display: flex; flex-direction: column;">
            <div style="padding: 1.25rem 1.5rem; background: #0e3320; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Semua Agenda & Acara Mendatang</h3>
                <button type="button" id="tutupModalSemuaAcara" style="background: rgba(255,255,255,0.2); color: #fff; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 1rem;">
                @php
                    $allAcaras = isset($mosque) ? $mosque->acaras()->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc')->get() : collect();
                @endphp

                @forelse($allAcaras as $itemAcara)
                    <div class="card-item-semua-acara" 
                         style="display: flex; gap: 1rem; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.85rem; border-radius: 0.75rem; cursor: pointer; transition: all 0.2s;"
                         data-title="{{ $itemAcara->title }}"
                         data-desc="{{ $itemAcara->description ?? 'Tidak ada deskripsi lengkap.' }}"
                         data-date="{{ \Illuminate\Support\Carbon::parse($itemAcara->event_date)->translatedFormat('d F Y') }}"
                         data-time="{{ $itemAcara->event_time ?? '-' }}"
                         data-organizer="{{ $itemAcara->organizer ?? 'Pengurus Masjid' }}"
                         data-photo="{{ !empty($itemAcara->photo) ? asset('storage/'.$itemAcara->photo) : '' }}">
                        
                        {{-- Thumbnail Foto Acara --}}
                        <div style="width: 75px; height: 60px; border-radius: 0.5rem; background-color: #cbd5e1; background-size: cover; background-position: center; flex-shrink: 0; @if(!empty($itemAcara->photo)) background-image: url('{{ asset('storage/'.$itemAcara->photo) }}'); @else display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #64748b; @endif">
                            @if(empty($itemAcara->photo)) No Photo @endif
                        </div>

                        {{-- Info Acara --}}
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
                                <span style="font-size: 0.7rem; background: #e0f2fe; color: #0369a1; padding: 0.1rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                                    📅 {{ \Illuminate\Support\Carbon::parse($itemAcara->event_date)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 0.2rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $itemAcara->title }}</h4>
                            <p style="font-size: 0.8rem; color: #64748b; margin: 0;">⏰ {{ $itemAcara->event_time ?? '-' }} &nbsp;·&nbsp; 👤 {{ $itemAcara->organizer ?? 'Pengurus Masjid' }}</p>
                        </div>

                        <span style="font-size: 0.85rem; font-weight: 600; color: #0d9488; white-space: nowrap; padding-right: 0.5rem;">Detail →</span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #64748b;">
                        Belum ada daftar acara mendatang saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- DONASI SECTION (GRID KARTU FOTO PROGRAM BESAR) --}}
@if($modOn('donasi') && isset($mosque) && $mosque->package_type != 'free')
<section class="hu-donasi-v2-section" id="donasi" style="padding: 6rem 0; background-color: #0e3320; color: #ffffff; position: relative; overflow: hidden;">
    <div class="hu-container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
        
        {{-- Header Section --}}
        <div style="text-align: center; max-width: 700px; margin: 0 auto 3.5rem auto;">
            <div style="display: inline-block; background: rgba(217, 119, 6, 0.2); color: #fbbf24; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 1rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                Donasi & Sedekah Terbuka
            </div>
            <h2 style="font-size: 2.5rem; font-weight: 700; line-height: 1.2; margin-bottom: 1rem; font-family: 'Fraunces', serif;">
                Pilih Program Kebaikan <br><span style="color: #fbbf24; font-style: italic;">Untuk Bekal Akhirat</span>
            </h2>
            <p style="font-size: 1rem; color: #cbd5e1; line-height: 1.6;">
                Salurkan sedekah terbaik Anda melalui berbagai program kemaslahatan umat, pemeliharaan masjid, dan bantuan sosial yang dikelola secara transparan.
            </p>
        </div>

        {{-- Grid Kartu Program Donasi Berbasis Foto Besar --}}
        @php
            $listCategories = (isset($categories) && count($categories) > 0) ? $categories : collect();
        @endphp

        @if($listCategories->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
                @foreach($listCategories as $cat)
                    @php
                        // Hitung atau estimasi dana terkumpul per kategori (jika ada relasi, atau gunakan dummy/global)
                        $catTerkumpul = $donasiTerkumpul ?? 1500000; 
                        $catTarget = $mosque->donation_target ?? 50000000;
                        $pct = $catTarget > 0 ? min(round($catTerkumpul / $catTarget * 100), 100) : 0;
                    @endphp
                    <div style="background: #ffffff; color: #1e293b; border-radius: 1.25rem; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.3s ease;">
                        
                        {{-- Foto Program di Atas Kartu --}}
<div style="width: 100%; height: 220px; background: #0f766e; position: relative; overflow: hidden;">
    @if(!empty($cat->image))
        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->title }}" style="width: 100%; height: 100%; object-fit: cover;">
    @else
        {{-- Foto dummy hanya tampil jika kolom image di database benar-benar kosong --}}
        <img src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=600&q=80" alt="{{ $cat->title }}" style="width: 100%; height: 100%; object-fit: cover;">
    @endif
    
    <div style="position: absolute; top: 12px; left: 12px; background: rgba(13, 148, 136, 0.95); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
        Aktif
    </div>
</div>

                        {{-- Konten Detail Program --}}
                        <div style="padding: 1.75rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem; font-family: 'Fraunces', serif;">
                                    {{ $cat->title }}
                                </h3>
                                <p style="font-size: 0.9rem; color: #64748b; line-height: 1.5; margin-bottom: 1.5rem;">
                                    {{ $cat->description ?? 'Mari ambil bagian dalam program kebaikan ini untuk membantu sesama dan memakmurkan rumah Allah.' }}
                                </p>
                            </div>

                            <div>
                                {{-- Progress Bar Dana --}}
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.85rem; padding: 1rem; margin-bottom: 1.25rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; font-size: 0.8rem;">
                                        <span style="color: #64748b;">Terkumpul</span>
                                        <span style="font-weight: 700; color: #0d9488;">{{ $pct }}%</span>
                                    </div>
                                    <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 9999px; overflow: hidden; margin-bottom: 0.5rem;">
                                        <div style="width: {{ $pct }}%; height: 100%; background: linear-gradient(90deg, #0d9488, #14b8a6); border-radius: 9999px;"></div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600;">
                                        <span style="color: #0f172a;">Rp {{ number_format($catTerkumpul, 0, ',', '.') }}</span>
                                        <span style="color: #64748b; font-size: 0.75rem;">Target: Rp {{ number_format($catTarget, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- Tombol Aksi Donasi --}}
                                <a href="{{ route('masjid.donasi.publik', ['slug' => $mosque->slug, 'jenis' => $cat->key ?? $cat->id]) }}" style="display: block; width: 100%; background: #0d9488; color: #ffffff; text-align: center; padding: 0.8rem; border-radius: 0.75rem; font-weight: 700; text-decoration: none; box-sizing: border-box; transition: background 0.2s;">
                                    Donasi Sekarang →
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback Jika Belum Ada Kategori/Program yang Dibuat Admin --}}
            <div style="background: rgba(255, 255, 255, 0.05); border: 1px dashed rgba(255, 255, 255, 0.2); border-radius: 1rem; padding: 3rem; text-align: center; color: #cbd5e1;">
                <p style="font-size: 1rem; margin-bottom: 1rem;">Belum ada program donasi khusus yang dipublikasikan saat ini.</p>
                <a href="{{ route('masjid.donasi.publik', $mosque->slug) }}" style="display: inline-block; background: #0d9488; color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; text-decoration: none;">
                    Buka Halaman Donasi Umum →
                </a>
            </div>
        @endif

    </div>
</section>
@endif

    {{-- DOKUMENTASI PENYALURAN SECTION --}}
    @if($modOn('donasi') && isset($mosque) && $mosque->package_type != 'free')
    <section class="hu-section" id="penyaluran" style="background-color: #f8fafc; padding: 5rem 0;">
        <div class="hu-container">
            <div class="hu-acara-v2-head" style="margin-bottom: 2.5rem;">
                <div>
                    <div class="hu-section-tag hu-tag-amber" style="margin-bottom: 0.5rem;">Transparansi</div>
                    <h2 class="hu-section-title hu-title-dark">Dokumentasi Penyaluran</h2>
                </div>
                <a href="{{ route('masjid.donasi.publik', $mosque->slug) }}" class="hu-acara-lihat">Lihat semua →</a>
            </div>

            @if(isset($items) && $items->isNotEmpty())
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                    @foreach($items as $item)
                        @php
                            $catTitle = 'Penyaluran';
                            if (isset($categories)) {
                                $matched = $categories->firstWhere('key', $item->kategori);
                                if ($matched) { $catTitle = $matched->title; }
                            }
                            $formattedDate = $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '';
                        @endphp
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                            <div style="height: 200px; background-size: cover; background-position: center; background-image: url('{{ $item->foto_url }}')"></div>
                            <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <span style="font-size: 0.75rem; background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600;">{{ $catTitle }}</span>
                                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0.75rem 0 0.5rem 0;">{{ $item->judul }}</h3>
                                    <p style="font-size: 0.9rem; color: #64748b; line-height: 1.5; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->deskripsi }}</p>
                                </div>
                                <div>
                                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.75rem;">
                                        <span style="color: #0d9488; font-weight: 700;">Rp {{ number_format($item->nominal_terpakai ?? 0, 0, ',', '.') }}</span>
                                        <span style="color: #94a3b8;">{{ $formattedDate }}</span>
                                    </div>
                                    <button type="button" 
                                        class="btn-buka-detail" 
                                        data-judul="{{ $item->judul }}" 
                                        data-kategori="{{ $catTitle }}" 
                                        data-deskripsi="{{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}" 
                                        data-nominal="Rp {{ number_format($item->nominal_terpakai ?? 0, 0, ',', '.') }}" 
                                        data-tanggal="{{ $formattedDate }}" 
                                        data-foto="{{ $item->foto_url }}"
                                        style="background: none; border: none; padding: 0; font-size: 0.85rem; font-weight: 600; color: #0d9488; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        Lihat detail →
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #64748b; background: #ffffff; border-radius: 1rem; border: 2px dashed #cbd5e1;">
                    Belum ada dokumentasi penyaluran yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    {{-- MODAL POPUP DETAIL PENYALURAN --}}
    <div id="modalDetailPenyaluran" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 90vh; display: flex; flex-direction: column;">
            <div style="position: relative; height: 250px; background-size: cover; background-position: center;" id="modalFoto">
                <button type="button" id="tutupModal" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.5); color: #fff; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                <span id="modalKategori" style="font-size: 0.75rem; background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600;"></span>
                <h3 id="modalJudul" style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0.75rem 0 0.5rem 0;"></h3>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #64748b; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                    <span>Dana Tersalurkan: <strong id="modalNominal" style="color: #0d9488;"></strong></span>
                    <span id="modalTanggal"></span>
                </div>
                <p id="modalDeskripsi" style="font-size: 0.95rem; color: #475569; line-height: 1.6; margin: 0; white-space: pre-line;"></p>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL POPUP DETAIL STRUKTUR PENGURUS MASJID --}}
    <div id="modalStrukturPengurus" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #fff; width: 100%; max-width: 550px; border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 90vh; display: flex; flex-direction: column;">
            <div style="padding: 1.25rem 1.5rem; background: #0e3320; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Struktur Pengurus & Yayasan</h3>
                <button type="button" id="tutupModalPengurus" style="background: rgba(255,255,255,0.2); color: #fff; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                @if(!empty($mosque->organization_name))
                    <div style="margin-bottom: 1rem; font-size: 0.95rem; color: #334155; background: #f8fafc; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <strong style="color: #0d9488;">Organisasi / Yayasan:</strong> {{ $mosque->organization_name }}
                    </div>
                @endif

                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    @if(!empty($mosque->chairman_name))
                    <div style="background: #f8fafc; padding: 0.85rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">KETUA DKM</div>
                            <div style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-top: 0.1rem;">{{ $mosque->chairman_name }}</div>
                        </div>
                        @if(!empty($mosque->chairman_phone))
                            <div style="font-size: 0.8rem; color: #0d9488; background: #e0f2fe; padding: 0.25rem 0.5rem; border-radius: 0.35rem;">📞 {{ $mosque->chairman_phone }}</div>
                        @endif
                    </div>
                    @endif

                    @if(!empty($mosque->imam_name))
                    <div style="background: #f8fafc; padding: 0.85rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">IMAM BESAR / KHATIB</div>
                            <div style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-top: 0.1rem;">{{ $mosque->imam_name }}</div>
                        </div>
                        @if(!empty($mosque->imam_phone))
                            <div style="font-size: 0.8rem; color: #0d9488; background: #e0f2fe; padding: 0.25rem 0.5rem; border-radius: 0.35rem;">📞 {{ $mosque->imam_phone }}</div>
                        @endif
                    </div>
                    @endif

                    @if(!empty($mosque->secretary_name))
                    <div style="background: #f8fafc; padding: 0.85rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">SEKRETARIS</div>
                            <div style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-top: 0.1rem;">{{ $mosque->secretary_name }}</div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($mosque->treasurer_name))
                    <div style="background: #f8fafc; padding: 0.85rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">BENDAHARA</div>
                            <div style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-top: 0.1rem;">{{ $mosque->treasurer_name }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KONTAK SECTION --}}
    <section class="hu-hubungi-section" id="kontak">
        <div class="hu-container">
            <div class="hu-section-head">
                <div class="hu-section-tag hu-tag-amber">Kontak & Lokasi</div>
                <h2 class="hu-section-title hu-title-dark">Hubungi Kami</h2>
            </div>

            <div class="hu-hubungi-grid">
                <div class="hu-hubungi-card">
                    <div class="hu-hubungi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hu-hubungi-label">Alamat</div>
                        <div class="hu-hubungi-val">
                            @php
                                $alamatLengkap = $mosque->contact_address ?? $mosque->address ?? '—';
                                $gmapsUrl = !empty($mosque->contact_maps) ? $mosque->contact_maps : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($alamatLengkap . ' ' . ($mosque->city ?? ''));
                            @endphp
                            <a href="{{ $gmapsUrl }}" target="_blank" style="color: inherit; text-decoration: none;">
                                {{ $alamatLengkap }}
                                @if(!empty($alamatLengkap) && $alamatLengkap !== '—')
                                    <br><span style="font-size:0.8rem; color:#0d9488; font-weight:600;">Lihat di Google Maps →</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <div class="hu-hubungi-card">
                    <div class="hu-hubungi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hu-hubungi-label">Telepon / WhatsApp</div>
                        <div class="hu-hubungi-val">
                            @php
                                $noTelp = $mosque->contact_phone ?? $mosque->phone ?? '';
                                $cleanPhone = preg_replace('/\D/', '', $noTelp);
                                if(str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            @if(!empty($noTelp))
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" style="color: inherit; text-decoration: none;">
                                    {{ $noTelp }}
                                    <br><span style="font-size:0.8rem; color:#16a34a; font-weight:600;">Chat WhatsApp →</span>
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>

                <div class="hu-hubungi-card">
                    <div class="hu-hubungi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hu-hubungi-label">Email</div>
                        <div class="hu-hubungi-val">
                            @php
                                $emailMasjid = $mosque->contact_email ?? $mosque->email ?? '';
                            @endphp
                            @if(!empty($emailMasjid))
                                <a href="mailto:{{ $emailMasjid }}" style="color: inherit; text-decoration: none;">
                                    {{ $emailMasjid }}
                                    <br><span style="font-size:0.8rem; color:#2563eb; font-weight:600;">Kirim Email →</span>
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($mosque->social_ig || $mosque->social_fb || $mosque->social_yt || $mosque->social_wa)
            <div class="hu-hubungi-social" style="display:flex;gap:14px;margin-top:24px;">
                @if(!empty($mosque->social_ig))<a href="{{ $mosque->social_ig }}" target="_blank">Instagram</a>@endif
                @if(!empty($mosque->social_fb))<a href="{{ $mosque->social_fb }}" target="_blank">Facebook</a>@endif
                @if(!empty($mosque->social_yt))<a href="{{ $mosque->social_yt }}" target="_blank">YouTube</a>@endif
                @if(!empty($mosque->social_wa))<a href="https://wa.me/{{ preg_replace('/\D/', '', $mosque->social_wa) }}" target="_blank">WhatsApp</a>@endif
            </div>
            @endif
        </div>
    </section>

    {{-- FOOTER SECTION --}}
    <footer class="hu-footer-v2">
        <div class="hu-footer-v2-inner">
            <div class="hu-footer-v2-grid">
                <div class="hu-footer-v2-brand">
                    <div class="hu-footer-v2-name">{{ $mosque->mosque_name ?? '' }}</div>
                    <div class="hu-footer-v2-tagline">
                        {{ $mosque->hero_subtitle ?? $mosque->tagline ?? '' }}<br>
                    </div>
                </div>

                <div class="hu-footer-v2-arabic-col">
                    <div class="hu-footer-v2-arabic">{{ $mosque->arabic_name ?? '' }}</div>
                </div>

                <div class="hu-footer-v2-col">
                    <div class="hu-footer-v2-col-title">Masjid Lainnya</div>
                    <a href="#" class="hu-footer-v2-link">Baitul Digital</a>
                    <a href="#" class="hu-footer-v2-link hu-footer-v2-link-active">{{ $mosque->mosque_name ?? '' }}</a>
                </div>

                <div class="hu-footer-v2-col">
                    <div class="hu-footer-v2-col-title">Tautan</div>
                    <a href="#profil" class="hu-footer-v2-link">Profil Masjid</a>
                    @if($modOn('jadwal_shalat'))<a href="#shalat" class="hu-footer-v2-link">Jadwal Shalat</a>@endif
                    <a href="#program" class="hu-footer-v2-link">Program & Fasilitas</a>
                    @if($modOn('kegiatan'))<a href="#acara" class="hu-footer-v2-link">Acara</a>@endif
                    @if($modOn('donasi'))<a href="#donasi" class="hu-footer-v2-link">Donasi</a>@endif
                    <a href="#penyaluran" class="hu-footer-v2-link">Penyaluran</a>
                    <a href="#kontak" class="hu-footer-v2-link">Hubungi Kami</a>
                </div>
            </div>

            <div class="hu-footer-v2-bottom">
                <span>© {{ date('Y') }} {{ $mosque->mosque_name ?? '' }} · Semua Hak Dilindungi</span>
                <span>Platform Masjid Digital · Multitenant</span>
            </div>
        </div>

        <button class="hu-kembali-btn" onclick="window.history.back()">← Kembali</button>
    </footer>

    <button class="help-fab" aria-label="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

    <script>
        window.addEventListener('scroll', () => {
            const nb = document.getElementById('huNavbar');
            nb.classList.toggle('scrolled', window.scrollY > 60);
        });

        document.getElementById('huHamburger').addEventListener('click', function () {
            document.querySelector('.hu-nav').classList.toggle('open');
            this.classList.toggle('active');
        });

        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.hu-nav-link');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(s => { if (window.scrollY >= s.offsetTop - 100) current = s.id; });
            navLinks.forEach(l => {
                l.classList.remove('active');
                if (l.getAttribute('href') === '#' + current) l.classList.add('active');
            });
        });

        document.querySelectorAll('.hu-nominal-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.hu-nominal-btn').forEach(b => {
                    b.style.background = '#f8fafc';
                    b.style.borderColor = '#e2e8f0';
                    b.style.color = '#1e293b';
                });
                this.style.background = '#f0fdf4';
                this.style.borderColor = '#10b981';
                this.style.color = '#047857';
                document.getElementById('donasiNominal').value = this.dataset.val;
            });
        });

        // Script Interaksi Modal Detail Penyaluran
        const modal = document.getElementById('modalDetailPenyaluran');
        const btnTutup = document.getElementById('tutupModal');

        document.querySelectorAll('.btn-buka-detail').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('modalJudul').innerText = this.dataset.judul;
                document.getElementById('modalKategori').innerText = this.dataset.kategori;
                document.getElementById('modalDeskripsi').innerText = this.dataset.deskripsi;
                document.getElementById('modalNominal').innerText = this.dataset.nominal;
                document.getElementById('modalTanggal').innerText = this.dataset.tanggal;
                document.getElementById('modalFoto').style.backgroundImage = `url('${this.dataset.foto}')`;
                
                modal.style.display = 'flex';
            });
        });

        if (btnTutup) {
            btnTutup.addEventListener('click', () => { modal.style.display = 'none'; });
        }
        if (modal) {
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });
        }

        // Script Interaksi Modal Detail Acara (Tunggal)
        const modalAcara = document.getElementById('modalDetailAcara');
        const tutupModalAcara = document.getElementById('tutupModalAcara');
        const tutupModalAcaraAlt = document.getElementById('tutupModalAcaraAlt');

        document.querySelectorAll('.btn-buka-acara').forEach(card => {
            card.addEventListener('click', function() {
                document.getElementById('modalAcaraJudul').innerText = this.dataset.title;
                document.getElementById('modalAcaraPenyelenggara').innerText = this.dataset.organizer;
                document.getElementById('modalAcaraDeskripsi').innerText = this.dataset.desc;
                document.getElementById('modalAcaraTanggal').innerText = this.dataset.date;
                document.getElementById('modalAcaraWaktu').innerText = this.dataset.time;

                const fotoUrl = this.dataset.photo;
                const fotoDiv = document.getElementById('modalAcaraFoto');
                if (fotoUrl) {
                    fotoDiv.style.backgroundImage = `url('${fotoUrl}')`;
                    fotoDiv.style.display = 'block';
                } else {
                    fotoDiv.style.display = 'none';
                }

                modalAcara.style.display = 'flex';
            });
        });

        if (tutupModalAcara) {
            tutupModalAcara.addEventListener('click', () => { modalAcara.style.display = 'none'; });
        }
        if (tutupModalAcaraAlt) {
            tutupModalAcaraAlt.addEventListener('click', () => { modalAcara.style.display = 'none'; });
        }
        if (modalAcara) {
            modalAcara.addEventListener('click', (e) => { if (e.target === modalAcara) modalAcara.style.display = 'none'; });
        }

        // Script Interaksi Modal Daftar "Lihat Semua" Acara
        const modalSemuaAcara = document.getElementById('modalSemuaAcara');
        const btnLihatSemuaAcara = document.getElementById('btnLihatSemuaAcara');
        const tutupModalSemuaAcara = document.getElementById('tutupModalSemuaAcara');

        if (btnLihatSemuaAcara) {
            btnLihatSemuaAcara.addEventListener('click', (e) => {
                e.preventDefault();
                if (modalSemuaAcara) modalSemuaAcara.style.display = 'flex';
            });
        }
        if (tutupModalSemuaAcara) {
            tutupModalSemuaAcara.addEventListener('click', () => {
                if (modalSemuaAcara) modalSemuaAcara.style.display = 'none';
            });
        }
        if (modalSemuaAcara) {
            modalSemuaAcara.addEventListener('click', (e) => {
                if (e.target === modalSemuaAcara) modalSemuaAcara.style.display = 'none';
            });
        }

        // Klik item dari dalam daftar "Lihat Semua" untuk membuka detail acara
        document.querySelectorAll('.card-item-semua-acara').forEach(card => {
            card.addEventListener('click', function() {
                if (modalSemuaAcara) modalSemuaAcara.style.display = 'none';

                document.getElementById('modalAcaraJudul').innerText = this.dataset.title;
                document.getElementById('modalAcaraPenyelenggara').innerText = this.dataset.organizer;
                document.getElementById('modalAcaraDeskripsi').innerText = this.dataset.desc;
                document.getElementById('modalAcaraTanggal').innerText = this.dataset.date;
                document.getElementById('modalAcaraWaktu').innerText = this.dataset.time;

                const fotoUrl = this.dataset.photo;
                const fotoDiv = document.getElementById('modalAcaraFoto');
                if (fotoUrl) {
                    fotoDiv.style.backgroundImage = `url('${fotoUrl}')`;
                    fotoDiv.style.display = 'block';
                } else {
                    fotoDiv.style.display = 'none';
                }

                if (modalAcara) modalAcara.style.display = 'flex';
            });
        });

        // Script Interaksi Modal Struktur Pengurus
        const modalPengurus = document.getElementById('modalStrukturPengurus');
        const btnBukaPengurus = document.getElementById('btnBukaPengurus');
        const tutupModalPengurus = document.getElementById('tutupModalPengurus');

        if (btnBukaPengurus) {
            btnBukaPengurus.addEventListener('click', () => {
                modalPengurus.style.display = 'flex';
            });
        }
        if (tutupModalPengurus) {
            tutupModalPengurus.addEventListener('click', () => {
                modalPengurus.style.display = 'none';
            });
        }
        if (modalPengurus) {
            modalPengurus.addEventListener('click', (e) => {
                if (e.target === modalPengurus) modalPengurus.style.display = 'none';
            });
        }
    </script>
</body>
</html>