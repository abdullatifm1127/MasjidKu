<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mikhrabku - Beranda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/halaman-utama.css">
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ url('/') }}" class="navbar-brand">
                <span class="brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                        <path d="M3 21h18M5 21V10M19 21V10M12 3c0 0-4 2-4 7h8c0-5-4-7-4-7z"/>
                        <path d="M9 10V7.5C9 6 10 5 12 5s3 1 3 2.5V10"/>
                        <path d="M2 10h4M18 10h4M12 3v2"/>
                    </svg>
                </span>
                <span class="brand-text">Masjid<strong>Ku</strong></span>
            </a>
            <ul class="navbar-menu" id="navMenu">
                <li><a href="#beranda" class="nav-link active">Beranda</a></li>
                <li><a href="#tentang" class="nav-link">Fitur</a></li>
                <li><a href="#direktori" class="nav-link">Masjid</a></li>
                <li><a href="#program" class="nav-link">Program</a></li>
                <li><a href="#donasi" class="nav-link">Artikel</a></li>
                <li><a href="#kontak" class="nav-link">Kontak</a></li>
            </ul>
            <div class="navbar-actions">
         @auth
    @php
        $mosque = \App\Models\Mosque::where('user_id', Auth::id())->first();
    @endphp

    @if(!$mosque)
        {{-- Belum daftar masjid --}}
        <a href="{{ route('daftar.masjid') }}" class="btn-nav-primary">Daftarkan Masjid</a>
    @elseif($mosque->status === 'pending')
        {{-- Jika status pending, arahkan langsung ke halaman waiting, JANGAN ke daftar-masjid --}}
        <a href="{{ route('waiting') }}" class="btn-nav-primary" style="background-color: #d97706;">Menunggu Verifikasi</a>
    @elseif($mosque->status === 'approved')
        {{-- Sudah disetujui --}}
        <a href="{{ route('dashboard') }}" class="btn-nav-primary" style="background-color: #059669;">Dashboard Masjid</a>
    @endif

    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn-nav-outline">Logout</button>
    </form>
@else
    <a href="{{ route('login') }}" class="btn-nav-outline">Masuk</a>
    <a href="{{ route('register') }}" class="btn-nav-primary">Daftar Akun</a>
@endauth

        </div>
                    
            <button class="navbar-toggle" id="navToggle" aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="hero" id="beranda">
        <div class="hero-inner">
            <div class="hero-content">
                <span class="hero-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13" style="vertical-align:-1px">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                    Platform Masjid Digital
                </span>
                <h1 class="hero-title">Kelola Masjid Anda<br>dengan <span class="text-green">Lebih Mudah</span></h1>
                <p class="hero-subtitle">
                    Satu platform lengkap untuk mengelola keuangan, jadwal shalat,
                    program dakwah, dan komunitas masjid Anda.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('daftar.masjid') }}" class="btn-primary">
                        Daftarkan Masjid
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="#tentang" class="btn-secondary">Pelajari Lebih Lanjut</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <strong>1.200+</strong>
                        <span>Masjid Terdaftar</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <strong>34</strong>
                        <span>Provinsi</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <strong>500rb+</strong>
                        <span>Jamaah Aktif</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ===== FITUR ===== --}}
    <section class="features" id="tentang">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">FITRUR UNGGULAN</span>
                <h2>Semua yang Anda Butuhkan<br>dalam Satu Platform</h2>
                <p>Dirancang khusus untuk kebutuhan masjid modern di Indonesia.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <h3>Manajemen Keuangan</h3>
                    <p>Catat pemasukan, pengeluaran, dan laporan keuangan masjid secara transparan dan mudah dipahami.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                        </svg>
                    </div>
                    <h3>Jadwal & Agenda</h3>
                    <p>Kelola jadwal imam, khatib, kajian, dan acara masjid dalam satu kalender terintegrasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"/>
                        </svg>
                    </div>
                    <h3>Pengumuman Digital</h3>
                    <p>Kirim informasi dan pengumuman kepada jamaah melalui notifikasi dan papan pengumuman digital.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                        </svg>
                    </div>
                    <h3>Pengelolaan Donasi</h3>
                    <p>Terima donasi online dan offline, kelola zakat, infaq, dan sedekah dengan laporan yang transparan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                    <h3>Program Dakwah</h3>
                    <p>Daftarkan dan pantau program TPA, tahfidz, majelis taklim, dan kegiatan dakwah lainnya.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                    </div>
                    <h3>Data Jamaah</h3>
                    <p>Kelola data anggota jamaah, pantau kehadiran, dan bangun komunitas masjid yang solid.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== DIREKTORI MASJID ===== --}}
    <section class="direktori" id="direktori">
        <div class="section-container">
            <div class="dir-header">
                <div class="dir-header-text">
                    <span class="section-badge">DAFTAR MASJID</span>
                    <h2>Temukan Masjid<br><em>di Sekitar Anda</em></h2>
                </div>
                <form method="GET" action="{{ url('/') }}#direktori" class="dir-search-form" id="dirSearchForm">
                    <div class="dir-search-wrap">
                        <svg class="dir-search-ico" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.15 6.15a7.5 7.5 0 0 0 10.5 10.5Z"/>
                        </svg>
                        <input type="text" name="q" class="dir-search-input"
                               placeholder="Cari nama atau kota…"
                               value="{{ request('q') }}">
                        @if(request('q'))
                            <a href="{{ url('/') }}#direktori" class="dir-search-clear" title="Hapus pencarian">✕</a>
                        @endif
                    </div>
                </form>
            </div>

            @php
                $warna = ['#1a4731','#1a3a6e','#6b2a2a','#0d5c5c','#4a3100','#2a1a5e'];
            @endphp

            @if($direktoriMasjid->isEmpty() && request('q'))
                {{-- Hasil pencarian kosong --}}
                <div class="dir-empty">
                    <div class="dir-empty-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803a7.5 7.5 0 0 0 10.607 0Z"/>
                        </svg>
                    </div>
                    <p class="dir-empty-title">Tidak ada masjid yang cocok dengan "<strong>{{ request('q') }}</strong>"</p>
                    <p class="dir-empty-sub">Coba kata kunci lain atau hapus filter pencarian.</p>
                    <a href="{{ url('/') }}#direktori" class="btn-primary" style="margin-top:16px;">Reset Pencarian</a>
                </div>
            @elseif($direktoriMasjid->isEmpty())
                {{-- Belum ada data sama sekali — tampilkan placeholder card --}}
                @php
                    $placeholders = [
                        ['nama' => 'Masjid Ar-Rahman',  'kota' => 'Jakarta Selatan', 'tagline' => 'Pusat Ilmu dan Ibadah',      'kapasitas' => '2.500', 'sejak' => '1987', 'bg' => '#1a4731'],
                        ['nama' => 'Masjid Al-Ikhlas',  'kota' => 'Bandung',         'tagline' => 'Rahmat untuk Semua',          'kapasitas' => '1.800', 'sejak' => '1994', 'bg' => '#1a3a6e'],
                        ['nama' => 'Masjid Baitussalam', 'kota' => 'Surabaya',        'tagline' => 'Kiblat Ilmu & Peradaban',     'kapasitas' => '3.200', 'sejak' => '1965', 'bg' => '#6b2a2a'],
                    ];
                @endphp
                <div class="dir-grid">
                    @foreach($placeholders as $p)
                    <div class="dir-card dir-card-placeholder">
                        <div class="dir-card-img" style="background: {{ $p['bg'] }};">
                            <span class="dir-card-initial">{{ mb_strtoupper(mb_substr($p['nama'], 7, 2)) }}</span>
                            <span class="dir-card-city">{{ $p['kota'] }}</span>
                        </div>
                        <div class="dir-card-body">
                            <h3 class="dir-card-name">{{ $p['nama'] }}</h3>
                            <p class="dir-card-tagline">{{ $p['tagline'] }}</p>
                            <div class="dir-card-meta">
                                <div class="dir-meta-item">
                                    <span class="dir-meta-label">Kapasitas</span>
                                    <strong>{{ $p['kapasitas'] }} orang</strong>
                                </div>
                                <div class="dir-meta-item">
                                    <span class="dir-meta-label">Sejak</span>
                                    <strong>{{ $p['sejak'] }}</strong>
                                </div>
                            </div>
                            <span class="dir-card-btn dir-card-btn-dummy" style="background: {{ $p['bg'] }};">
                                Segera Hadir →
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="dir-more" style="margin-top:32px;">
                    <a href="{{ route('daftar.masjid') }}" class="dir-more-btn">
                        + Daftarkan Masjid Anda
                    </a>
                </div>
            @else
                <div class="dir-grid">
                    @foreach($direktoriMasjid as $i => $m)
                        @php
                            $bg      = $warna[$i % count($warna)];
                            $initial = mb_strtoupper(mb_substr($m->mosque_name, 0, 2));
                            // Ambil donasi lunas dari model jika ada, fallback 0
                            $totalDonasi = method_exists(\App\Models\Donasi::class, 'totalLunas')
                                ? \App\Models\Donasi::totalLunas($m->id)
                                : 0;
                            $targetDonasi = 1000000000; // 1 Milyar sebagai default target
                            $persen = $targetDonasi > 0 ? min(100, round($totalDonasi / $targetDonasi * 100)) : 0;
                        @endphp
                        <div class="dir-card">
                            {{-- Gambar / hero banner --}}
                            <div class="dir-card-img" style="background-color: {{ $bg }};">
                                @if($m->hero_image)
                                    <img src="{{ Storage::url($m->hero_image) }}"
                                         alt="{{ $m->mosque_name }}"
                                         class="dir-card-photo">
                                @else
                                    <span class="dir-card-initial">{{ $initial }}</span>
                                @endif
                                <span class="dir-card-city">{{ $m->city ?? 'Indonesia' }}</span>
                            </div>

                            {{-- Body --}}
                            <div class="dir-card-body">
                                <h3 class="dir-card-name">{{ $m->mosque_name }}</h3>
                                <p class="dir-card-tagline">{{ Str::limit($m->tagline ?? $m->description ?? 'Masjid di Indonesia', 48) }}</p>

                                <div class="dir-card-meta">
                                    <div class="dir-meta-item">
                                        <span class="dir-meta-label">Kapasitas</span>
                                        @php
                                            $kapasitas = $m->capacity
                                                ? number_format((int) str_replace(['.', ','], ['', ''], $m->capacity), 0, ',', '.')
                                                : null;
                                        @endphp
                                        <strong>{{ $kapasitas ? $kapasitas . ' orang' : '—' }}</strong>
                                    </div>
                                    <div class="dir-meta-item">
                                        <span class="dir-meta-label">Sejak</span>
                                        <strong>{{ $m->founded ?? '—' }}</strong>
                                    </div>
                                </div>

                                {{-- Progress donasi --}}
                                @if($totalDonasi > 0)
                                    <div class="dir-card-prog">
                                        <div class="dir-prog-label">
                                            <span class="dir-prog-name">{{ Str::limit('Donasi Terkumpul', 32) }}</span>
                                            <span class="dir-prog-pct">{{ $persen }}%</span>
                                        </div>
                                        <div class="dir-prog-bar">
                                            <div class="dir-prog-fill" style="width: {{ $persen }}%; background: {{ $bg }};"></div>
                                        </div>
                                        <div class="dir-prog-amount">
                                            Rp {{ number_format($totalDonasi, 0, ',', '.') }} terkumpul
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('masjid.publik', $m->slug) }}"
                                   class="dir-card-btn"
                                   style="background: {{ $bg }};">
                                    Kunjungi Halaman →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Lihat semua --}}
                @if($direktoriMasjid->count() >= 6 && !request('q'))
                    <div class="dir-more">
                        <a href="{{ url('/') }}?semua=1#direktori" class="dir-more-btn">
                            Lihat Semua Masjid
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </section>

    {{-- ===== PROGRAM ===== --}}
    <section class="programs" id="program">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Program</span>
                <h2>Program Unggulan Masjid</h2>
                <p>Berbagai program untuk membangun jamaah yang berkualitas.</p>
            </div>
            <div class="programs-grid">
                <div class="program-card">
                    <div class="program-img green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(255,255,255,0.9)" width="48" height="48">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                    <div class="program-body">
                        <span class="program-tag">Pendidikan</span>
                        <h3>TPA & Tahfidz Al-Quran</h3>
                        <p>Program belajar membaca, menulis, dan menghafal Al-Quran untuk anak-anak dan remaja.</p>
                        <a href="#" class="program-link">Selengkapnya →</a>
                    </div>
                </div>
                <div class="program-card">
                    <div class="program-img gold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(255,255,255,0.9)" width="48" height="48">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                        </svg>
                    </div>
                    <div class="program-body">
                        <span class="program-tag">Ibadah</span>
                        <h3>Kajian Rutin & Majelis Taklim</h3>
                        <p>Kajian ilmu agama mingguan dan bulanan bersama ustadz dan ulama terpercaya.</p>
                        <a href="#" class="program-link">Selengkapnya →</a>
                    </div>
                </div>
                <div class="program-card">
                    <div class="program-img teal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(255,255,255,0.9)" width="48" height="48">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                    </div>
                    <div class="program-body">
                        <span class="program-tag">Sosial</span>
                        <h3>Pemberdayaan Ekonomi Umat</h3>
                        <p>Program zakat produktif, koperasi syariah, dan pelatihan wirausaha untuk jamaah dhuafa.</p>
                        <a href="#" class="program-link">Selengkapnya →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA DONASI ===== --}}
    <section class="cta-donasi" id="donasi">
        <div class="section-container">
            <div class="cta-box">
                <div class="cta-text">
                    <h2>Menambah Ilmu, Mencerahkan Iman</h2>
                    <p>Selamat datang di ruang literasi masjid untuk menambah ilmu dan mencerahkan iman..</p>
                    <a href="#" class="btn-primary">Baca Artikel Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="footer" id="kontak">
        <div class="footer-inner">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <span class="brand-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="22" height="22">
                            <path d="M3 21h18M5 21V10M19 21V10M12 3c0 0-4 2-4 7h8c0-5-4-7-4-7z"/>
                            <path d="M9 10V7.5C9 6 10 5 12 5s3 1 3 2.5V10"/>
                            <path d="M2 10h4M18 10h4M12 3v2"/>
                        </svg>
                    </span>
                    <span class="brand-text">Masjid<strong>Ku</strong></span>
                </a>
                <p>Platform digital untuk kemakmuran masjid Indonesia.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="footer-links">
                <div class="footer-col">
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="#">Fitur</a></li>
                        <li><a href="#">Harga</a></li>
                        <li><a href="#">Panduan</a></li>
                        <li><a href="#">API</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Masjid</h4>
                    <ul>
                        <li><a href="{{ route('daftar.masjid') }}">Daftarkan Masjid</a></li>
                        <li><a href="#">Cari Masjid</a></li>
                        <li><a href="#">Jadwal Shalat</a></li>
                        <li><a href="#">Donasi</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Perusahaan</h4>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Baitul Digital. Semua hak dilindungi.</p>
            <div class="footer-bottom-links">
                <a href="#">Privasi</a>
                <a href="#">Syarat</a>
                <a href="#">Cookie</a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile nav toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu   = document.getElementById('navMenu');
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            navToggle.classList.toggle('active');
        });

        // Smooth scroll active link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });

        // Navbar scroll shadow
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });
    </script>
</body>
</html>