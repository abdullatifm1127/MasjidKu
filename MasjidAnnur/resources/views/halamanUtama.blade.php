<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasjidKu - Beranda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/halaman-utama.css">
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ url('/') }}" class="navbar-brand">
                <span class="brand-icon">🕌</span>
                <span class="brand-text">Masjid<strong>Ku</strong></span>
            </a>
            <ul class="navbar-menu" id="navMenu">
                <li><a href="#beranda" class="nav-link active">Beranda</a></li>
                <li><a href="#tentang" class="nav-link">Fitur</a></li>
                <li><a href="#program" class="nav-link">Program</a></li>
                <li><a href="#donasi" class="nav-link">Artikel</a></li>
                <li><a href="#kontak" class="nav-link">Kontak</a></li>
            </ul>
            <div class="navbar-actions">
        @auth
        <a href="{{ route('daftar.masjid') }}" class="btn-nav-primary">
            Daftarkan Masjid
        </a>

         <form method="POST" action="{{ route('logout') }}" style="display: inline;">
             @csrf
                <button type="submit" class="btn-nav-outline">
                Logout
                </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-nav-outline">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-nav-primary">
                    Daftar Akun
                </a>
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
                <span class="hero-badge">🌙 Platform Masjid Digital</span>
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
                    <div class="feature-icon">💰</div>
                    <h3>Manajemen Keuangan</h3>
                    <p>Catat pemasukan, pengeluaran, dan laporan keuangan masjid secara transparan dan mudah dipahami.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Jadwal & Agenda</h3>
                    <p>Kelola jadwal imam, khatib, kajian, dan acara masjid dalam satu kalender terintegrasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📢</div>
                    <h3>Pengumuman Digital</h3>
                    <p>Kirim informasi dan pengumuman kepada jamaah melalui notifikasi dan papan pengumuman digital.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🤲</div>
                    <h3>Pengelolaan Donasi</h3>
                    <p>Terima donasi online dan offline, kelola zakat, infaq, dan sedekah dengan laporan yang transparan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📖</div>
                    <h3>Program Dakwah</h3>
                    <p>Daftarkan dan pantau program TPA, tahfidz, majelis taklim, dan kegiatan dakwah lainnya.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Data Jamaah</h3>
                    <p>Kelola data anggota jamaah, pantau kehadiran, dan bangun komunitas masjid yang solid.</p>
                </div>
            </div>
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
                        <span>📖</span>
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
                        <span>☪</span>
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
                        <span>🤝</span>
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
                    <span class="brand-icon">🕌</span>
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
