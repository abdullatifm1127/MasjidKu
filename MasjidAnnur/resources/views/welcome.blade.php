<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Baitul Digital — Platform Masjid' }}</title>
    <link rel="stylesheet" href="{{ asset('css/masjid.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand-mark">🕌</span>
            <span><strong>Baitul</strong> Digital</span>
        </a>
        <nav class="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="#cara-kerja">Cara Kerja</a>
            <a href="#tentang">Tentang</a>
            <button type="button" class="nav-login" onclick="openAuthModal()">Masuk</button>
            <a class="nav-register" href="{{ route('register') }}">Daftarkan Masjid</a>
        </nav>
        <button class="menu-toggle" type="button" aria-label="Buka menu" onclick="document.body.classList.toggle('menu-open')">☰</button>
    </div>
</header>

<main>
<section class="hero">
    <div class="hero-pattern"></div>
    <div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div>
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow"><span class="pulse-dot"></span> Platform Masjid No.1 Indonesia</div>
            <h1>Masjid <span class="accent">Digital</span><br><span class="green">untuk Umat</span></h1>
            <p>Kelola masjid Anda secara digital. Jadwal shalat, donasi online, agenda kegiatan, dan informasi jamaah — semua dalam satu platform terpadu.</p>
            <div class="hero-actions">
                <a class="btn btn-gold" href="{{ route('register') }}">Daftarkan Masjid</a>
                <a class="btn btn-outline" href="#fitur">Pelajari Fitur</a>
            </div>
            <div class="stats">
                <div><strong>120+</strong><span>Masjid Terdaftar</span></div>
                <div><strong>2.5 Jt</strong><span>Jamaah Aktif</span></div>
                <div><strong>Rp 12M</strong><span>Total Donasi</span></div>
            </div>
        </div>
</section>

<section id="fitur" class="section geometric-bg">
    <div class="container">
        <div class="section-heading"><span class="section-kicker">FITUR LENGKAP</span><h2>Semua yang Masjid Butuhkan</h2><p>Satu platform untuk menghubungkan pengurus, jamaah, dan kegiatan masjid.</p></div>
        <div class="feature-grid">
            @foreach([
                ['🕐','Jadwal Shalat Otomatis','Perhitungan waktu shalat akurat berdasarkan lokasi masjid dan dapat diperbarui setiap hari.'],
                ['💳','Donasi Digital','Terima donasi via QRIS, transfer bank, dan dompet digital dengan laporan transparan.'],
                ['📅','Manajemen Acara','Buat dan publikasikan kajian, pengajian, halaqah, dan kegiatan masjid.'],
                ['📢','Pengumuman Jamaah','Sampaikan informasi penting kepada jamaah melalui halaman masjid.'],
                ['📊','Laporan Keuangan','Catat pemasukan dan pengeluaran serta tampilkan laporan secara transparan.'],
                ['📸','Galeri & Dokumentasi','Simpan dan tampilkan dokumentasi kegiatan masjid dengan rapi.']
            ] as $f)
            <article class="feature-card"><div class="feature-icon">{{ $f[0] }}</div><h3>{{ $f[1] }}</h3><p>{{ $f[2] }}</p></article>
            @endforeach
        </div>
    </div>
</section>

<section id="cara-kerja" class="section dark-section">
    <div class="container two-col">
        <div><span class="section-kicker gold">CARA KERJA</span><h2>Mulai Digitalisasi Masjid dalam Hitungan Menit</h2><p>Daftarkan masjid, lengkapi data, lalu kelola seluruh informasi dari dashboard.</p></div>
        <div class="steps">
            @foreach([['01','Daftarkan Masjid','Isi informasi dasar masjid dan data pengurus.'],['02','Lengkapi Profil','Tambahkan fasilitas, program, kontak, dan informasi lainnya.'],['03','Kelola & Publikasikan','Kelola kegiatan, berita, donasi, dan laporan melalui dashboard.']] as $s)
            <div class="step"><b>{{ $s[0] }}</b><div><h3>{{ $s[1] }}</h3><p>{{ $s[2] }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>

<section id="tentang" class="section">
    <div class="container cta-card">
        <div><span class="section-kicker">UNTUK PENGURUS MASJID</span><h2>Siap Membawa Masjid ke Era Digital?</h2><p>Bangun pusat informasi masjid yang mudah diakses jamaah kapan saja.</p></div>
        <a class="btn btn-gold" href="{{ route('register') }}">Daftarkan Masjid →</a>
    </div>
</section>
</main>

<footer class="footer"><div class="container footer-wrap"><div><div class="brand light"><span class="brand-mark">🕌</span><span><strong>Baitul</strong> Digital</span></div><p>Platform digital untuk masjid dan umat.</p></div><div><small>© {{ date('Y') }} Baitul Digital. Semua hak dilindungi.</small></div></div></footer>
<script src="{{ asset('js/masjid.js') }}"></script>
</body>
</html>