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
                @endif

                <a href="#penyaluran" class="hu-nav-link">Penyaluran</a>
                <a href="#kontak" class="hu-nav-link">Hubungi</a>
            </nav>

            <button class="hu-ganti-btn" id="huGantiBtn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                </svg>
                Ganti Masjid
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2.5" stroke="currentColor" width="12" height="12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>

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

    {{-- PROFIL SECTION --}}
    <section class="hu-section hu-profil-section" id="profil">
        <div class="hu-container">
            <div class="hu-profil-v2-grid">
                <div class="hu-profil-v2-left">
                    <div class="hu-profil-v2-tag">Profil Masjid</div>
                    <h2 class="hu-profil-v2-title">
                        {{ $mosque->mosque_name ?? 'Rumah Ibadah' }}
                    </h2>
                    <p class="hu-profil-v2-desc">
                        {{ $mosque->description ?? 'Belum ada deskripsi. Isi di halaman "Profil Masjid" pada panel admin.' }}
                    </p>

                    <div class="hu-profil-v2-divider"><span>✦</span></div>

                    <div class="hu-profil-v2-stats">
                        <div class="hu-profil-v2-stat">
                            <div class="hu-profil-v2-stat-label">Tahun Berdiri</div>
                            <div class="hu-profil-v2-stat-val">{{ $mosque->founded ?? '—' }}</div>
                        </div>
                        <div class="hu-profil-v2-stat">
                            <div class="hu-profil-v2-stat-label">Kapasitas</div>
                            <div class="hu-profil-v2-stat-val">{{ $mosque->capacity ?? '—' }}</div>
                        </div>
                        <div class="hu-profil-v2-stat">
                            <div class="hu-profil-v2-stat-label">Imam Besar</div>
                            <div class="hu-profil-v2-stat-val">{{ $mosque->imam_name ?? '—' }}</div>
                            {{-- TOMBOL LIHAT DETAIL PENGURUS DI BAWAH IMAM BESAR --}}
                            @if(!empty($mosque->organization_name) || !empty($mosque->chairman_name) || !empty($mosque->secretary_name) || !empty($mosque->treasurer_name))
                                <button type="button" id="btnBukaPengurus" style="background: none; border: none; padding: 0; font-size: 0.85rem; font-weight: 600; color: #0d9488; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; margin-top: 0.4rem;">
                                    Lihat detail →
                                </button>
                            @endif
                        </div>
                        <div class="hu-profil-v2-stat">
                            <div class="hu-profil-v2-stat-label">Program Aktif</div>
                            <div class="hu-profil-v2-stat-val">
                                {{ !empty($mosque->programs) ? count($mosque->programs).' program' : '—' }}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="hu-profil-v2-right">
                    <div class="hu-profil-v2-images">
                        <div class="hu-profil-v2-img hu-img-tall">
                            @if(!empty($mosque->about_photo))
                                <img src="{{ asset('storage/'.$mosque->about_photo) }}" alt="Foto Masjid" loading="lazy">
                            @endif
                        </div>
                        <div class="hu-profil-v2-img hu-img-short">
                            @if(!empty($mosque->about_photo_secondary))
                                <img src="{{ asset('storage/'.$mosque->about_photo_secondary) }}" alt="Masjid" loading="lazy">
                            @endif
                        </div>
                    </div>
                    
                    @if(!empty($mosque->about_vision))
                    <div class="hu-profil-v2-ayat">
                        <div class="hu-profil-v2-trans">{{ $mosque->about_vision }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- JADWAL SHALAT SECTION --}}
    @if($modOn('jadwal_shalat'))
    <section class="hu-section hu-section-dark" id="shalat">
        <div class="hu-container">
            <div class="hu-section-head hu-section-head-light">
                <div class="hu-section-tag hu-tag-light">Hari Ini</div>
                <h2 class="hu-section-title hu-title-light">Jadwal Waktu Shalat</h2>
            </div>
            <div class="hu-shalat-grid">
                @foreach($prayers as $p)
                <div class="hu-shalat-card {{ $p['active'] ? 'active' : '' }}">
                    <div class="hu-shalat-name">{{ $p['name'] }}</div>
                    <div class="hu-shalat-time">{{ $p['time'] }}</div>
                    @if($p['active'])<div class="hu-shalat-now">Waktu Sekarang</div>@endif
                </div>
                @endforeach
            </div>
        </div>
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
                    <div class="hu-section-tag hu-tag-amber">Agenda</div>
                    <h2 class="hu-section-title hu-title-dark">Acara Mendatang</h2>
                </div>
                <a href="#" class="hu-acara-lihat">Lihat semua →</a>
            </div>
            <div class="hu-acara-v2-grid">
                @forelse(($acaras ?? collect()) as $a)
                <div class="hu-acara-v2-card">
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
                    <a href="#" class="hu-acara-v2-link">Detail Acara →</a>
                </div>
                @empty
                <p style="color:var(--ink-soft);grid-column:1/-1;">Belum ada acara mendatang.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    {{-- DONASI SECTION --}}
    @if($modOn('donasi') && isset($mosque) && $mosque->package_type != 'free')
    <section class="hu-donasi-v2-section" id="donasi" style="padding: 6rem 0; background-color: #0e3320; color: #ffffff; position: relative; overflow: hidden;">
        <div class="hu-container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem; align-items: center;">
                
                {{-- Sisi Kiri: Informasi & Target Dana --}}
                <div>
                    <div style="display: inline-block; background: rgba(217, 119, 6, 0.2); color: #fbbf24; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.85rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                        Donasi & Sedekah Terbuka
                    </div>
                    <h2 style="font-size: 2.5rem; font-weight: 700; line-height: 1.2; margin-bottom: 1rem; font-family: 'Fraunces', serif;">
                        Investasi Terbaik <br><span style="color: #fbbf24; font-style: italic;">Untuk Akhirat</span>
                    </h2>
                    <p style="font-size: 1rem; color: #cbd5e1; line-height: 1.6; margin-bottom: 2rem;">
                        Salurkan sebagian rezeki Anda untuk mendukung pembangunan, pemeliharaan, serta program operasional masjid. Setiap uluran tangan Anda sangat berarti bagi kemakmuran umat.
                    </p>

                    @php
                        // Menggunakan data otomatis dari controller, jika kosong bernilai 0
                        $terkumpul = $donasiTerkumpul ?? 0;
                        $target = $donasiTarget ?? 500000000;
                        $donasiPct = $target > 0 ? min(round($terkumpul / $target * 100), 100) : 0;
                    @endphp

                    <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; font-size: 0.9rem;">
                            <span style="color: #94a3b8;">Dana Terkumpul</span>
                            <span style="font-weight: 700; color: #34d399;">{{ $donasiPct }}% Tercapai</span>
                        </div>
                        <div style="width: 100%; height: 10px; background: rgba(255, 255, 255, 0.1); border-radius: 9999px; overflow: hidden; margin-bottom: 0.75rem;">
                            <div style="width: {{ $donasiPct }}%; height: 100%; background: linear-gradient(90deg, #10b981, #34d399); border-radius: 9999px;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.95rem; font-weight: 600;">
                            <span style="color: #ffffff;">Rp {{ number_format($terkumpul, 0, ',', '.') }}</span>
                            <span style="color: #94a3b8;">Target: Rp {{ number_format($target, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Sisi Kanan: Form Nominal & Aksi Donasi --}}
                <div>
                    <div style="background: #ffffff; color: #1e293b; border-radius: 1.25rem; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);">
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; text-align: center;">
                            Pilih Nominal Donasi
                        </h3>

                        {{-- Tombol Pilihan Nominal Cepat --}}
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 1.25rem;">
                            <button type="button" class="hu-nominal-btn" data-val="50000" style="padding: 0.75rem; border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Rp 50.000</button>
                            <button type="button" class="hu-nominal-btn" data-val="100000" style="padding: 0.75rem; border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Rp 100.000</button>
                            <button type="button" class="hu-nominal-btn" data-val="250000" style="padding: 0.75rem; border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Rp 250.000</button>
                            <button type="button" class="hu-nominal-btn" data-val="500000" style="padding: 0.75rem; border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Rp 500.000</button>
                        </div>

                        <div style="text-align: center; font-size: 0.85rem; color: #64748b; margin-bottom: 0.75rem;">Atau masukkan nominal lain</div>

                        {{-- Input Nominal Manual --}}
                        <div style="position: relative; margin-bottom: 1rem;">
                            <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: 600; color: #64748b;">Rp</span>
                            <input type="number" id="donasiNominal" placeholder="0" min="1000" style="width: 100%; padding: 0.75rem 0.75rem 0.75rem 2.75rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; font-weight: 600; outline: none; box-sizing: border-box;">
                        </div>

                        {{-- Input Nama --}}
                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem;">Nama Donatur (Opsional)</label>
                            <input type="text" class="hu-donasi-v2-input-name" placeholder="Hamba Allah" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>

                        {{-- Tombol Aksi --}}
                        <a href="{{ route('masjid.donasi.publik', $mosque->slug) }}" style="display: block; width: 100%; background: #0d9488; color: #ffffff; text-align: center; padding: 0.85rem; border-radius: 0.75rem; font-weight: 700; text-decoration: none; box-sizing: border-box; transition: background 0.2s;">
                            Lanjut Pembayaran →
                        </a>

                        <div style="text-align: center; font-size: 0.75rem; color: #94a3b8; margin-top: 1rem;">
                            🔒 Pembayaran aman & terpercaya · QRIS / Transfer Bank / Dompet Digital
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endif

   {{-- DOKUMENTASI PENYALURAN SECTION (Hanya muncul jika fitur donasi aktif & paket berbayar) --}}
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
                
                {{-- Alamat (Terkoneksi ke Google Maps) --}}
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

                {{-- Telepon (Terkoneksi ke WhatsApp) --}}
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

                {{-- Email (Terkoneksi ke Aplikasi Email / Gmail) --}}
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
                        Bersama kita makmurkan masjid Allah.
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