<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mosque->mosque_name ?? 'Masjid' }} — Halaman Utama</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/halamanUtamaUser.css') }}">
</head>
<body>

     <!-- NAVBAR -->
    <header class="hu-navbar" id="huNavbar">
        <div class="hu-navbar-inner">

            <!-- Brand -->
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
                    <span class="hu-brand-city">{{ $mosque->city ?? 'Bandung' }}</span>
                </div>
            </a>

            <!-- Nav links -->
            <nav class="hu-nav">
                <a href="#beranda"  class="hu-nav-link active">Beranda</a>
                <a href="#profil"   class="hu-nav-link">Profil</a>
                <a href="#shalat"   class="hu-nav-link">Waktu Shalat</a>
                <a href="#program"  class="hu-nav-link">Program</a>
                <a href="#acara"    class="hu-nav-link">Acara</a>
                <a href="#donasi"   class="hu-nav-link">Donasi</a>
                <a href="#kontak"   class="hu-nav-link">Hubungi</a>
            </nav>

            <!-- Ganti Masjid -->
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

            {{-- Hamburger --}}
            <button class="hu-hamburger" id="huHamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

        </div>
    </header>

 <!-- INFO TICKER  -->
    <div class="hu-ticker">
        <span class="hu-ticker-label">INFO</span>
        <div class="hu-ticker-wrap">
            <div class="hu-ticker-track" id="huTickerTrack">
                <span class="hu-ticker-text">Santunan anak yatim setiap Jumat pertama dalam bulan</span>
                <span class="hu-ticker-dot">●</span>
                <span class="hu-ticker-text">Kajian Fiqih setiap Senin ba'da Isya</span>
                <span class="hu-ticker-dot">●</span>
                <span class="hu-ticker-text">Pendaftaran TPA/TPQ dibuka sampai akhir bulan</span>
                <span class="hu-ticker-dot">●</span>
            </div>
        </div>
    </div>

  
    <section class="hu-hero" id="beranda">
        <div class="hu-hero-overlay"></div>
        <div class="hu-hero-content">
            <div class="hu-hero-arabic">{{ $mosque->arabic_name ?? 'مسجد الرحمن' }}</div>
            <h1 class="hu-hero-title">{{ $mosque->mosque_name ?? 'Masjid Ar-Rahman' }}</h1>
            <p class="hu-hero-tagline">
                {{ $mosque->tagline ?? 'Rahmat untuk Semua' }}
                @if(!empty($mosque->city)) — {{ $mosque->city }} @endif
            </p>
            <div class="hu-hero-btns">
                <a href="#donasi" class="hu-btn-primary">Donasi Sekarang</a>
                <a href="#profil" class="hu-btn-outline">Tentang Kami</a>
            </div>
        </div>
        <div class="hu-hero-scroll">
            <span>SCROLL</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </div>
    </section>


    <section class="hu-section hu-profil-section" id="profil">
        <div class="hu-container">
            <div class="hu-profil-v2-grid">

                <div class="hu-profil-v2-left">
                    <div class="hu-profil-v2-tag">§ 01 — Profil Masjid</div>
                    <h2 class="hu-profil-v2-title">
                        Rumah Ibadah<br>
                        <em>untuk Semua</em>
                    </h2>
                    <p class="hu-profil-v2-desc">
                        {{ $mosque->description ?? 'Masjid ini hadir membawa misi rahmat Islam yang menjangkau seluruh lapisan masyarakat. Berfokus pada pendidikan, sosial, dan pemberdayaan ekonomi umat.' }}
                    </p>

                    <div class="hu-profil-v2-divider">
                        <span>✦</span>
                    </div>

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
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7skeyX4a7nPzMZhaMJEkntS_hwmhPHlRzoBvUGvTIzj9RsfMwGy1T5240&s=10"
                                 alt="Al-Quran" loading="lazy">
                        </div>
                        <div class="hu-profil-v2-img hu-img-short">
                            <img src="https://images.unsplash.com/photo-1564769625905-50e93615e769?w=500&q=80"
                                 alt="Masjid" loading="lazy">
                        </div>
                    </div>
                    <div class="hu-profil-v2-ayat">
                        <div class="hu-profil-v2-arabic">إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ</div>
                        <div class="hu-profil-v2-trans">
                            "Sesungguhnya yang memakmurkan masjid Allah hanyalah orang-orang yang beriman..."
                            — QS. At-Taubah: 18
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="hu-section hu-section-dark" id="shalat">
        <div class="hu-container">
            <div class="hu-section-head hu-section-head-light">
                <div class="hu-section-tag hu-tag-light">Hari Ini</div>
                <h2 class="hu-section-title hu-title-light">Jadwal Waktu Shalat</h2>
            </div>
        
            @php
                $prayers = [
                    ['name' => 'Subuh',   'time' => '04:32', 'active' => false],
                    ['name' => 'Dzuhur',  'time' => '12:05', 'active' => false],
                    ['name' => 'Ashar',   'time' => '15:21', 'active' => false],
                    ['name' => 'Maghrib', 'time' => '18:02', 'active' => false],
                    ['name' => 'Isya',    'time' => '19:14', 'active' => true],
                ];
            @endphp
            @foreach($prayers as $p)
             @endforeach
        
            <div class="hu-shalat-grid">
                @foreach($prayers as $p)
                <div class="hu-shalat-card {{ $p['active'] ? 'active' : '' }}">
                    <div class="hu-shalat-name">{{ $p['name'] }}</div>
                    <div class="hu-shalat-time">{{ $p['time'] }}</div>
                    @if($p['active'])
                    <div class="hu-shalat-now">Waktu Sekarang</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="hu-section hu-program-section" id="program">
        <div class="hu-container">
            <div class="hu-section-head">
                <div class="hu-section-tag hu-tag-amber">§ 02 — Kegiatan & Program</div>
                <h2 class="hu-section-title hu-title-dark">Program Unggulan</h2>
            </div>
            <div class="hu-program-v2-list">
                @php
                    $programList = !empty($mosque->programs) ? $mosque->programs : [
                        'Hafalan Quran 30 Juz',
                        'Ekonomi Syariah',
                        'Koperasi Masjid',
                        'Kajian Tafsir',
                        'Program Yatim',
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
    </section>

    <section class="hu-acara-section" id="acara">
        <div class="hu-container">
            <div class="hu-acara-v2-head">
                <div>
                    <div class="hu-section-tag hu-tag-amber">§ 03 — Agenda</div>
                    <h2 class="hu-section-title hu-title-dark">Acara Mendatang</h2>
                </div>
                <a href="#" class="hu-acara-lihat">Lihat semua →</a>
            </div>
            <div class="hu-acara-v2-grid">
                @php
                    $acaraList = [
                        ['bulan' => 'Agu', 'tanggal' => '17', 'judul' => 'Halaqah Quran Bersama',      'waktu' => '16:00 WIB', 'oleh' => 'Ustadz Yusuf Mansur',  'terbaru' => true],
                        ['bulan' => 'Agu', 'tanggal' => '24', 'judul' => 'Bazar Produk UMKM Muslim',   'waktu' => '08:00 WIB', 'oleh' => 'Panitia Masjid',        'terbaru' => false],
                        ['bulan' => 'Agu', 'tanggal' => '31', 'judul' => 'Khataman Quran & Doa Bersama','waktu' => '09:00 WIB', 'oleh' => 'Seluruh Santri',        'terbaru' => false],
                    ];
                @endphp
                @foreach($acaraList as $a)
                <div class="hu-acara-v2-card">
                    <div class="hu-acara-v2-top">
                        <div class="hu-acara-v2-date">
                            <div class="hu-acara-v2-month">{{ $a['bulan'] }}</div>
                            <div class="hu-acara-v2-day">{{ $a['tanggal'] }}</div>
                        </div>
                        @if($a['terbaru'])
                        <span class="hu-acara-v2-badge">Terbaru</span>
                        @endif
                    </div>
                    <div class="hu-acara-v2-judul">{{ $a['judul'] }}</div>
                    <div class="hu-acara-v2-meta">{{ $a['waktu'] }} · {{ $a['oleh'] }}</div>
                    <a href="#" class="hu-acara-v2-link">Detail Acara →</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="hu-donasi-v2-section" id="donasi">
        <div class="hu-donasi-v2-inner">

            {{-- Kiri: info donasi --}}
            <div class="hu-donasi-v2-left">
                <div class="hu-section-tag hu-tag-amber-light">§ 04 — Donasi & Sedekah</div>
                <h2 class="hu-donasi-v2-title">
                    Investasi<br>
                    <em>Terbaik Akhirat</em>
                </h2>
                <p class="hu-donasi-v2-desc">
                    Setiap rupiah yang Anda donasikan akan digunakan untuk
                    <strong>Pembangunan Aula Serbaguna</strong>. Mari bersama-sama memakmurkan masjid Allah.
                </p>
                <div class="hu-donasi-v2-progress-wrap">
                    <div class="hu-donasi-v2-progress-label">
                        <span>Terkumpul</span>
                        <span class="hu-donasi-v2-pct">77%</span>
                    </div>
                    <div class="hu-donasi-v2-track">
                        <div class="hu-donasi-v2-fill" style="width:77%"></div>
                    </div>
                    <div class="hu-donasi-v2-amounts">
                        <span>Rp 387.000.000</span>
                        <span>Rp 500.000.000</span>
                    </div>
                </div>
            </div>

            <div class="hu-donasi-v2-right">
                <div class="hu-donasi-v2-card">
                    <div class="hu-donasi-v2-card-title">Pilih Nominal Donasi</div>
                    <div class="hu-donasi-v2-nominals">
                        <button class="hu-nominal-btn" data-val="50000">Rp 50.000</button>
                        <button class="hu-nominal-btn" data-val="100000">Rp 100.000</button>
                        <button class="hu-nominal-btn" data-val="250000">Rp 250.000</button>
                        <button class="hu-nominal-btn" data-val="500000">Rp 500.000</button>
                    </div>
                    <div class="hu-donasi-v2-or">Atau masukkan nominal lain</div>
                    <div class="hu-donasi-v2-input-wrap">
                        <span class="hu-donasi-v2-prefix">Rp</span>
                        <input type="number" id="donasiNominal" class="hu-donasi-v2-input"
                               placeholder="0" min="1000">
                    </div>
                    <div class="hu-donasi-v2-label-field">Nama (opsional)</div>
                    <input type="text" class="hu-donasi-v2-input-name" placeholder="Hamba Allah">
                    <button class="hu-donasi-v2-submit">Donasi Sekarang</button>
                    <div class="hu-donasi-v2-note">
                        Pembayaran aman &amp; terpercaya · QRIS / Transfer Bank / Dompet Digital
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="hu-hubungi-section" id="kontak">
        <div class="hu-container">
            <div class="hu-section-head">
                <div class="hu-section-tag hu-tag-amber">§ 05 — Kontak & Lokasi</div>
                <h2 class="hu-section-title hu-title-dark">Hubungi Kami</h2>
            </div>

            <div class="hu-hubungi-grid">
                <!-- Alamat  -->
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
                            {{ $mosque->address ?? 'Jl. Sudirman No. 45, Cicendo,' }}<br>
                            {{ $mosque->city ?? 'Bandung' }} {{ $mosque->postal_code ?? '40172' }}
                        </div>
                    </div>
                </div>

                 <!-- Telepon  -->
                <div class="hu-hubungi-card">
                    <div class="hu-hubungi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hu-hubungi-label">Telepon</div>
                        <div class="hu-hubungi-val">{{ $mosque->phone ?? '+62 22 4210 5678' }}</div>
                    </div>
                </div>

               <!-- Email  -->
                <div class="hu-hubungi-card">
                    <div class="hu-hubungi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hu-hubungi-label">Email</div>
                        <div class="hu-hubungi-val">{{ $mosque->email ?? 'info@ar-rahman-bdg.id' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="hu-footer-v2">
        <div class="hu-footer-v2-inner">
            <div class="hu-footer-v2-grid">

                <div class="hu-footer-v2-brand">
                    <div class="hu-footer-v2-name">{{ $mosque->mosque_name ?? 'Masjid Ar-Rahman' }}</div>
                    <div class="hu-footer-v2-tagline">
                        {{ $mosque->tagline ?? 'Melayani umat dengan sepenuh hati.' }}<br>
                        Bersama kita makmurkan masjid Allah.
                    </div>
                </div>

                <div class="hu-footer-v2-arabic-col">
                    <div class="hu-footer-v2-arabic">{{ $mosque->arabic_name ?? 'مسجد الرحمن' }}</div>
                </div>

                <div class="hu-footer-v2-col">
                    <div class="hu-footer-v2-col-title">Masjid Lainnya</div>
                    <a href="#" class="hu-footer-v2-link">Baitul Digital</a>
                    <a href="#" class="hu-footer-v2-link hu-footer-v2-link-active">{{ $mosque->mosque_name ?? 'Masjid Ar-Rahman' }}</a>
                    <a href="#" class="hu-footer-v2-link">Masjid Al-Aqsa</a>
                </div>

                <div class="hu-footer-v2-col">
                    <div class="hu-footer-v2-col-title">Tautan</div>
                    <a href="#profil"   class="hu-footer-v2-link">Profil Masjid</a>
                    <a href="#shalat"   class="hu-footer-v2-link">Jadwal Shalat</a>
                    <a href="#program"  class="hu-footer-v2-link">Program</a>
                    <a href="#donasi"   class="hu-footer-v2-link">Donasi</a>
                    <a href="#kontak"   class="hu-footer-v2-link">Hubungi Kami</a>
                </div>

            </div>

            <div class="hu-footer-v2-bottom">
                <span>© {{ date('Y') }} {{ $mosque->mosque_name ?? 'Masjid Ar-Rahman' }} · Semua Hak Dilindungi</span>
                <span>Platform Masjid Digital · Multitenant</span>
            </div>
        </div>

        <button class="hu-kembali-btn" onclick="window.history.back()">
            ← Kembali
        </button>
    </footer>

    <button class="help-fab" aria-label="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nb = document.getElementById('huNavbar');
            nb.classList.toggle('scrolled', window.scrollY > 60);
        });

        // Hamburger
        document.getElementById('huHamburger').addEventListener('click', function () {
            document.querySelector('.hu-nav').classList.toggle('open');
            this.classList.toggle('active');
        });

        // Active nav link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.hu-nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(s => {
                if (window.scrollY >= s.offsetTop - 100) current = s.id;
            });
            navLinks.forEach(l => {
                l.classList.remove('active');
                if (l.getAttribute('href') === '#' + current) l.classList.add('active');
            });
        });

        // Nominal donasi
        document.querySelectorAll('.hu-nominal-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.hu-nominal-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('donasiNominal').value = this.dataset.val;
            });
        });
    </script>
</body>
</html>
