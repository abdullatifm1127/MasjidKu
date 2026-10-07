<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasjidKu - Platform Masjid Digital Indonesia</title>
    <meta name="description" content="Kelola keuangan, jadwal shalat, program dakwah, donasi, dan jamaah masjid dalam satu platform.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/halaman-utama.css') }}">
</head>
<body>

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Schema;

    $safe = function (callable $fn, $default = null) { try { return $fn(); } catch (\Throwable $e) { return $default; } };
    $has  = fn ($model, $table) => class_exists($model) && Schema::hasTable($table);
    $approved = fn () => \App\Models\Mosque::where('status', 'approved');

    $stats = [
        'masjid'   => $safe(fn () => $approved()->count(), 0),
        'provinsi' => $safe(fn () => Schema::hasColumn('mosques', 'provinsi') ? $approved()->whereNotNull('provinsi')->where('provinsi', '!=', '')->distinct()->count('provinsi') : 0, 0),
        'jamaah'   => $safe(fn () => class_exists(\App\Models\Jamaah::class) ? \App\Models\Jamaah::count() : 0, 0),
    ];
    $programs      = $safe(fn () => Schema::hasTable('programs') ? \App\Models\Program::where('is_published', true)->latest()->take(3)->get() : collect(), collect());
    $articles      = $safe(fn () => Schema::hasTable('articles') ? \App\Models\Article::where('is_published', true)->latest()->take(3)->get() : collect(), collect());
    $announcements = $safe(fn () => $has(\App\Models\Announcement::class, 'announcements') ? \App\Models\Announcement::latest()->take(5)->get() : collect(), collect());
    $events        = $safe(fn () => $has(\App\Models\Event::class, 'events') ? \App\Models\Event::latest()->take(4)->get() : collect(), collect());

    $keyword = trim(request('lokasi', ''));
    $query = $approved();
    if ($keyword !== '') {
        $cols = collect(Schema::getColumnListing('mosques'))->filter(fn ($c) => preg_match('/^(nama|name)|alamat|address|city|kota|kabupaten|kecamatan|kelurahan|desa|provinsi|province/i', $c));
        $query->where(function ($w) use ($cols, $keyword) { foreach ($cols as $c) { $w->orWhere($c, 'like', "%{$keyword}%"); } });
    }
    $registeredMosques = $query->latest()->take($keyword !== '' ? 30 : 6)->get();
    $mosque = auth()->check() ? $safe(fn () => \App\Models\Mosque::where('user_id', auth()->id())->first()) : null;

    // Rapikan data tiap masjid (deteksi kolom otomatis)
    $view = function ($m) {
        $attrs = collect($m->getAttributes());
        $skip  = '/(^id$|_id$|status|slug|password|token|created|updated|deleted)/i';
        $pickAll = fn ($p) => $attrs->filter(fn ($v, $k) => preg_match($p, $k) && !preg_match($skip, $k) && is_string($v) && trim($v) !== '' && !is_numeric($v))->values();
        $pick = fn ($p) => $pickAll($p)->first();
        $nama = Str::title(trim($pick('/^(nama|name)/i') ?? $pick('/nama|name|masjid|judul|title/i') ?? 'Masjid'));
        if (!preg_match('/masjid|musholla|mushola|langgar|surau/i', $nama)) $nama = 'Masjid ' . $nama;
        $alamat = $pickAll('/alamat|address|jalan/i')->first();
        $alamat = $alamat ? preg_replace('/\bJln\b\.?/i', 'Jl.', Str::title($alamat)) : null;
        $foto = $pick('/foto|photo|image|gambar|logo|banner|cover|thumbnail/i');
        return (object) [
            'nama' => $nama, 'alamat' => $alamat,
            'wilayah' => $pickAll('/kelurahan|desa|kecamatan|kota|city|kabupaten|provinsi|province/i')->map(fn ($v) => Str::title(trim($v)))->unique()->implode(', '),
            'telp' => $attrs->first(fn ($v, $k) => preg_match('/telp|telepon|phone|hp|wa|whatsapp/i', $k) && !empty($v)),
            'foto' => $foto ? (Str::startsWith($foto, ['http://', 'https://']) ? $foto : asset('storage/' . ltrim($foto, '/'))) : null,
            'link' => url('/masjid/' . ($m->slug ?? $m->id)),
        ];
    };
    $latest = $registeredMosques->take(3)->map($view);
    $email  = config('mail.from.address');
@endphp

{{-- ===== TOPBAR ===== --}}
<div class="topbar">
    <div class="topbar-inner">
        <span>📅 <b id="tglMasehi"></b> &nbsp;|&nbsp; <span id="tglHijri"></span></span>
        <span><a href="#jadwal-shalat">Jadwal Shalat</a> &nbsp;·&nbsp; <a href="#donasi">Donasi</a> &nbsp;·&nbsp; <a href="#kontak">Kontak</a></span>
    </div>
</div>
@if($announcements->count())
    <div class="ticker"><div class="ticker-inner">
        <span class="ticker-label">PENGUMUMAN</span>
        <div class="ticker-track"><span>{{ $announcements->map(fn ($a) => $a->judul ?? $a->title ?? '')->filter()->implode('   ✦   ') }}</span></div>
    </div></div>
@endif

{{-- ===== NAVBAR ===== --}}
<nav class="navbar" id="navbar">
    <div class="navbar-inner">
        <a href="{{ url('/') }}" class="navbar-brand"><span class="brand-icon">🕌</span><span class="brand-text">Masjid<strong>Ku</strong></span></a>
        <ul class="navbar-menu" id="navMenu">
            <li><a href="#beranda" class="nav-link active">Beranda</a></li>
            <li><a href="#tentang" class="nav-link">Fitur</a></li>
            <li><a href="#cara-kerja" class="nav-link">Cara Kerja</a></li>
            <li><a href="#program" class="nav-link">Program</a></li>
            <li><a href="#masjid" class="nav-link">Masjid</a></li>
            <li><a href="#donasi" class="nav-link">Donasi</a></li>
            <li><a href="#artikel" class="nav-link">Artikel</a></li>
            <li><a href="#faq" class="nav-link">FAQ</a></li>
            <li><a href="#kontak" class="nav-link">Kontak</a></li>
        </ul>
        <div class="navbar-actions">
            @auth
                @if(!$mosque)
                    <a href="{{ route('daftar.masjid') }}" class="btn-nav-primary">Daftarkan Masjid</a>
                @elseif($mosque->status === 'pending')
                    <a href="{{ route('waiting') }}" class="btn-nav-primary is-pending">Menunggu Verifikasi</a>
                @elseif($mosque->status === 'approved')
                    <a href="{{ route('dashboard') }}" class="btn-nav-primary is-approved">Dashboard Masjid</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn-nav-outline">Logout</button></form>
            @else
                <a href="{{ route('login') }}" class="btn-nav-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-nav-primary">Daftar Akun</a>
            @endauth
        </div>
        <button class="navbar-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false"><span></span><span></span><span></span></button>
    </div>
</nav>

<main>

{{-- ===== HERO ===== --}}
<section class="hero" id="beranda">
    <div class="hero-inner">
        <div class="hero-content">
            <p class="bismillah" lang="ar">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
            <span class="hero-badge">🌙 Platform Masjid Digital</span>
            <h1 class="hero-title">Kelola Masjid Anda<br>dengan <span class="text-green">Lebih Mudah</span></h1>
            <p class="hero-subtitle">Satu platform lengkap untuk mengelola keuangan, jadwal shalat, program dakwah, dan komunitas masjid Anda.</p>
            <div class="hero-actions">
                <a href="{{ route('daftar.masjid') }}" class="btn-primary">Daftarkan Masjid
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="#tentang" class="btn-secondary">Pelajari Lebih Lanjut</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item"><strong>{{ number_format($stats['masjid'], 0, ',', '.') }}</strong><span>Masjid Terdaftar</span></div>
                <div class="stat-divider"></div>
                <div class="stat-item"><strong>{{ $stats['provinsi'] }}</strong><span>Provinsi</span></div>
                <div class="stat-divider"></div>
                <div class="stat-item"><strong>{{ number_format($stats['jamaah'], 0, ',', '.') }}</strong><span>Jamaah Terdata</span></div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="arch"><div class="arch-in">
                <small>Baru bergabung</small>
                <h2>Masjid Terverifikasi</h2>
                @forelse($latest as $l)
                    <a href="{{ $l->link }}" class="join-item">
                        <span class="join-avatar">@if($l->foto)<img src="{{ $l->foto }}" alt="" loading="lazy">@else🕌@endif</span>
                        <span class="join-text"><strong>{{ $l->nama }}</strong><small>{{ $l->wilayah ?: 'Indonesia' }}</small></span>
                    </a>
                @empty
                    <p class="join-empty">Belum ada masjid terverifikasi. Daftarkan masjid Anda dan jadi yang pertama.</p>
                @endforelse
            </div></div>
        </div>
    </div>
</section>

{{-- ===== JADWAL SHALAT (Aladhan API, metode Kemenag RI) ===== --}}
<div class="prayer-band" id="jadwal-shalat">
    <div class="prayer-card">
        <div class="prayer-head">
            <div><h2>Jadwal Shalat Hari Ini</h2><p id="tglShalat">Memuat jadwal...</p></div>
            <div class="countdown" id="countdown" hidden></div>
            <label class="sr-only" for="kotaShalat">Pilih kota</label>
            <select id="kotaShalat">
                @foreach(['Jakarta','Surabaya','Malang','Bandung','Yogyakarta','Semarang','Medan','Palembang','Makassar','Denpasar','Banjarmasin','Pontianak'] as $k)<option>{{ $k }}</option>@endforeach
            </select>
        </div>
        <div class="prayer-times" id="prayerList"></div>
    </div>
</div>

{{-- ===== STRIP ===== --}}
<div class="strip"><div class="strip-inner">
    <div class="strip-item"><i>✔</i>Setiap masjid diverifikasi tim kami</div>
    <div class="strip-item"><i>📊</i>Laporan kas terbuka untuk jamaah</div>
    <div class="strip-item"><i>📱</i>Bisa diakses dari ponsel</div>
    <div class="strip-item"><i>🇮🇩</i>Dibuat untuk masjid di Indonesia</div>
</div></div>

{{-- ===== FITUR ===== --}}
<section class="features" id="tentang">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">Fitur Unggulan</span>
            <h2>Semua yang Anda Butuhkan dalam Satu Platform</h2>
            <p>Dirancang khusus untuk kebutuhan masjid modern di Indonesia.</p>
        </div>
        <div class="features-grid">
            @foreach([
                ['💰','Manajemen Keuangan','Catat pemasukan, pengeluaran, dan laporan keuangan masjid secara transparan dan mudah dipahami.'],
                ['📅','Jadwal & Agenda','Kelola jadwal imam, khatib, kajian, dan acara masjid dalam satu kalender terintegrasi.'],
                ['📢','Pengumuman Digital','Kirim informasi dan pengumuman kepada jamaah melalui notifikasi dan papan pengumuman digital.'],
                ['🤲','Pengelolaan Donasi','Terima donasi online dan offline, kelola zakat, infaq, dan sedekah dengan laporan yang transparan.'],
                ['📖','Program Dakwah','Daftarkan dan pantau program TPA, tahfidz, majelis taklim, dan kegiatan dakwah lainnya.'],
                ['👥','Data Jamaah','Kelola data anggota jamaah, pantau kehadiran, dan bangun komunitas masjid yang solid.'],
            ] as [$ic, $ti, $de])
                <div class="feature-card"><div class="feature-icon">{{ $ic }}</div><h3>{{ $ti }}</h3><p>{{ $de }}</p></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CARA KERJA ===== --}}
<section class="steps" id="cara-kerja">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">Cara Kerja</span>
            <h2>Mulai dalam Tiga Langkah</h2>
            <p>Dari membuat akun sampai masjid Anda tampil di MasjidKu.</p>
        </div>
        <div class="steps-grid">
            <div class="step"><h3>Buat akun</h3><p>Daftar dengan akun pribadi Anda sebagai pengurus atau takmir masjid.</p></div>
            <div class="step"><h3>Daftarkan masjid</h3><p>Isi data masjid: nama, alamat, kontak, dan foto agar mudah ditemukan jamaah.</p></div>
            <div class="step"><h3>Tunggu verifikasi</h3><p>Setelah data kami periksa dan disetujui, dashboard masjid langsung terbuka.</p></div>
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
        @if($programs->count())
            <div class="programs-grid">
                @foreach($programs as $p)
                    <a href="{{ url('/program/' . $p->id) }}" class="program-card">
                        <div class="program-img {{ ['green','gold','teal'][$loop->index % 3] }}"><span>{{ $p->ikon ?? '📖' }}</span></div>
                        <div class="program-body">
                            <span class="program-tag">{{ $p->kategori }}</span>
                            <h3>{{ $p->judul }}</h3>
                            <p>{{ Str::limit($p->deskripsi, 120) }}</p>
                            <span class="program-link">Selengkapnya →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-box">Belum ada program yang dipublikasikan. Pengurus masjid dapat menambahkannya dari dashboard.</div>
        @endif
    </div>
</section>

{{-- ===== AGENDA (tampil bila ada data) ===== --}}
@if($events->count())
<section class="agenda" id="agenda">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">Agenda</span><h2>Kegiatan Terdekat</h2><p>Kajian dan acara yang akan berlangsung di masjid.</p></div>
        <div class="agenda-list">
            @foreach($events as $e)
                @php $tgl = $safe(fn () => \Carbon\Carbon::parse($e->tanggal ?? $e->tanggal_mulai ?? $e->created_at)); @endphp
                <div class="agenda-item">
                    <div class="agenda-date"><b>{{ $tgl?->format('d') }}</b><small>{{ $tgl?->translatedFormat('M Y') }}</small></div>
                    <div><h3>{{ $e->judul ?? $e->nama ?? 'Kegiatan' }}</h3><p>{{ $e->lokasi ?? $e->tempat ?? '' }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== MASJID TERDAFTAR ===== --}}
<section class="mosques" id="masjid">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">Masjid Terdaftar</span>
            <h2>Masjid yang Sudah Bergabung</h2>
            <p>Daftar masjid yang telah terverifikasi dan menggunakan platform MasjidKu.</p>
        </div>
        <form method="GET" action="{{ url('/') }}#masjid" class="mosque-search" role="search">
            <div class="mosque-search-box">
                <span aria-hidden="true">📍</span>
                <input type="search" name="lokasi" value="{{ $keyword }}" placeholder="Cari masjid berdasarkan lokasi, mis. Malang, Kendalsari, Lowokwaru..." aria-label="Cari lokasi masjid">
                <button type="submit">Cari</button>
            </div>
            @if($keyword !== '')
                <p class="mosque-search-info">Menampilkan <strong>{{ $registeredMosques->count() }}</strong> masjid untuk "<strong>{{ $keyword }}</strong>" &middot; <a href="{{ url('/') }}#masjid">Reset</a></p>
            @endif
        </form>

        @if($registeredMosques->count())
            <div class="mosques-grid">
                @foreach($registeredMosques as $m)
                    @php $v = $view($m); @endphp
                    <a href="{{ $v->link }}" class="mosque-card">
                        <div class="mosque-photo">
                            @if($v->foto)<img src="{{ $v->foto }}" alt="Foto {{ $v->nama }}" loading="lazy">@else<span class="mosque-photo-placeholder">🕌</span>@endif
                            <span class="mosque-badge">✔ Terverifikasi</span>
                        </div>
                        <div class="mosque-info">
                            <h3>{{ $v->nama }}</h3>
                            <ul class="mosque-meta">
                                @if($v->alamat || $v->wilayah)
                                    <li><span class="meta-icon">📍</span><span>
                                        @if($v->alamat)<span class="meta-main">{{ $v->alamat }}</span>@endif
                                        @if($v->wilayah)<span class="meta-sub">{{ $v->wilayah }}</span>@endif
                                    </span></li>
                                @endif
                                @if($v->telp)<li><span class="meta-icon">📞</span><span class="meta-main">{{ $v->telp }}</span></li>@endif
                            </ul>
                            <span class="mosque-visit">Kunjungi Website →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="mosques-empty">
                @if($keyword !== '') Tidak ada masjid ditemukan untuk "{{ $keyword }}". Coba kata kunci lain.
                @else Belum ada masjid yang terdaftar. Jadilah yang pertama! @endif
            </p>
        @endif
    </div>
</section>

{{-- ===== DONASI ===== --}}
<section class="donasi" id="donasi">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">Donasi</span>
            <h2>Berbagi Kebaikan Lewat Masjid</h2>
            <p>Pilih masjid terverifikasi, lalu salurkan zakat, infaq, sedekah, dan wakaf Anda.</p>
        </div>
        <div class="donasi-grid">
            @foreach([['💎','Zakat','Tunaikan kewajiban zakat mal dan fitrah melalui masjid.'],['🤲','Infaq','Dukung operasional dan kegiatan masjid sehari-hari.'],['🌱','Sedekah','Berbagi untuk jamaah dan warga yang membutuhkan.'],['🏗️','Wakaf','Bantu pembangunan dan perawatan sarana masjid.']] as [$ic,$ti,$de])
                <a href="#masjid" class="donasi-card"><i>{{ $ic }}</i><h3>{{ $ti }}</h3><p>{{ $de }}</p></a>
            @endforeach
        </div>
        <div class="ayat">
            <p class="ar" lang="ar">مَنْ ذَا الَّذِيْ يُقْرِضُ اللّٰهَ قَرْضًا حَسَنًا فَيُضٰعِفَهٗ لَهٗٓ اَضْعَافًا كَثِيْرَةً</p>
            <p>"Siapakah yang mau memberi pinjaman kepada Allah, pinjaman yang baik, maka Allah melipatgandakannya."</p>
            <small>QS. Al-Baqarah: 245</small>
        </div>
    </div>
</section>

{{-- ===== ARTIKEL ===== --}}
<section class="programs articles" id="artikel">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">Artikel</span>
            <h2>Menambah Ilmu, Mencerahkan Iman</h2>
            <p>Selamat datang di ruang literasi masjid untuk menambah ilmu dan mencerahkan iman.</p>
        </div>
        @if($articles->count())
            <div class="programs-grid">
                @foreach($articles as $a)
                    <a href="{{ url('/artikel/' . $a->slug) }}" class="program-card">
                        <div class="program-body">
                            <span class="program-tag">{{ $a->created_at->translatedFormat('d F Y') }}</span>
                            <h3>{{ $a->judul }}</h3>
                            <p>{{ Str::limit($a->ringkasan ?? strip_tags($a->isi), 120) }}</p>
                            <span class="program-link">Baca artikel →</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="center"><a href="{{ url('/artikel') }}" class="btn-primary btn-dark">Baca Artikel Sekarang</a></div>
        @else
            <div class="empty-box">Artikel pertama akan segera hadir.</div>
        @endif
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="faq" id="faq">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">FAQ</span><h2>Pertanyaan yang Sering Diajukan</h2></div>
        <div class="faq-list">
            @foreach([
                ['Bagaimana cara mendaftarkan masjid?','Buat akun lebih dulu, lalu pilih "Daftarkan Masjid" dan isi data masjid Anda. Setelah dikirim, data akan diperiksa oleh tim MasjidKu.'],
                ['Apa arti status "Menunggu Verifikasi"?','Data masjid Anda sudah kami terima dan sedang diperiksa. Statusnya bisa dipantau dari tombol di pojok kanan atas setelah Anda masuk.'],
                ['Apa yang bisa dilakukan setelah masjid disetujui?','Tombol berubah menjadi "Dashboard Masjid". Dari sana Anda bisa mengelola keuangan, jadwal, pengumuman, donasi, program, dan data jamaah.'],
                ['Bagaimana jamaah menemukan masjid saya?','Masjid yang sudah terverifikasi tampil pada daftar di halaman ini dan bisa dicari berdasarkan nama, kelurahan, atau kota.'],
                ['Dari mana jadwal shalat berasal?','Jadwal dihitung berdasarkan kota yang dipilih dengan metode Kementerian Agama RI, lewat layanan Aladhan.'],
                ['Bagaimana cara menyalurkan donasi?','Pilih masjid terverifikasi pada daftar, buka profilnya, lalu ikuti petunjuk donasi yang disediakan pengurus masjid tersebut.'],
            ] as [$q, $a])
                <details><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== KONTAK ===== --}}
<section id="kontak-info">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">Kontak</span><h2>Hubungi Kami</h2><p>Ada pertanyaan seputar pendaftaran atau penggunaan MasjidKu?</p></div>
        <div class="kontak-grid">
            <div class="kontak-card"><i>✉️</i><div><h3>Email</h3>@if($email)<a href="mailto:{{ $email }}">{{ $email }}</a>@else<p>Segera tersedia</p>@endif</div></div>
            <div class="kontak-card"><i>📍</i><div><h3>Pusat Layanan</h3><p>Baitul Digital, Indonesia</p></div></div>
            <div class="kontak-card"><i>🕒</i><div><h3>Jam Layanan</h3><p>Senin sampai Jumat, 08.00 sampai 17.00 WIB</p></div></div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="cta-donasi">
    <div class="section-container"><div class="cta-box"><div class="cta-text">
        <h2>Siap Merapikan Pengelolaan Masjid Anda?</h2>
        <p>Daftarkan masjid Anda dan mulai kelola keuangan, jadwal, dan jamaah dari satu tempat.</p>
        <div class="cta-actions">
            <a href="{{ route('daftar.masjid') }}" class="btn-primary">Daftarkan Masjid</a>
            <a href="#faq" class="btn-secondary">Baca FAQ</a>
        </div>
    </div></div></div>
</section>

</main>

{{-- ===== FOOTER ===== --}}
<footer class="footer" id="kontak">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="navbar-brand"><span class="brand-icon">🕌</span><span class="brand-text">Masjid<strong>Ku</strong></span></a>
            <p>Platform digital untuk kemakmuran masjid Indonesia.</p>
            <div class="footer-social">
                <a href="#" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                <a href="#" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                <a href="#" aria-label="YouTube"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
            </div>
        </div>
        <div class="footer-links">
            <div class="footer-col"><h4>Platform</h4><ul>
                <li><a href="#tentang">Fitur</a></li><li><a href="#">Harga</a></li><li><a href="#">Panduan</a></li><li><a href="#">API</a></li>
            </ul></div>
            <div class="footer-col"><h4>Masjid</h4><ul>
                <li><a href="{{ route('daftar.masjid') }}">Daftarkan Masjid</a></li><li><a href="#masjid">Cari Masjid</a></li><li><a href="#jadwal-shalat">Jadwal Shalat</a></li><li><a href="#donasi">Donasi</a></li>
            </ul></div>
            <div class="footer-col"><h4>Perusahaan</h4><ul>
                <li><a href="#">Tentang Kami</a></li><li><a href="#artikel">Blog</a></li><li><a href="#">Karir</a></li><li><a href="#kontak-info">Kontak</a></li>
            </ul></div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Baitul Digital. Semua hak dilindungi.</p>
        <div class="footer-bottom-links"><a href="#">Privasi</a><a href="#">Syarat</a><a href="#">Cookie</a></div>
    </div>
</footer>

<button class="to-top" id="toTop" aria-label="Kembali ke atas">↑</button>

<script>
    const $ = id => document.getElementById(id);

    // Tanggal Masehi & Hijriah
    const now0 = new Date();
    $('tglMasehi').textContent = now0.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    try { $('tglHijri').textContent = new Intl.DateTimeFormat('id-u-ca-islamic-umalqura', { day: 'numeric', month: 'long', year: 'numeric' }).format(now0); } catch (e) {}

    // Menu mobile + menu aktif
    const navToggle = $('navToggle'), navMenu = $('navMenu');
    navToggle.addEventListener('click', () => {
        const open = navMenu.classList.toggle('open');
        navToggle.classList.toggle('active', open);
        navToggle.setAttribute('aria-expanded', open);
    });
    const navLinks = [...document.querySelectorAll('.nav-link')];
    navLinks.forEach(l => l.addEventListener('click', () => navMenu.classList.remove('open')));
    const io = new IntersectionObserver(es => es.forEach(e => {
        if (e.isIntersecting) navLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + e.target.id));
    }), { rootMargin: '-45% 0px -50% 0px' });
    navLinks.forEach(l => { const s = document.querySelector(l.getAttribute('href')); if (s) io.observe(s); });

    // Bayangan navbar + tombol ke atas
    addEventListener('scroll', () => {
        $('navbar').classList.toggle('scrolled', scrollY > 20);
        $('toTop').classList.toggle('show', scrollY > 600);
    }, { passive: true });
    $('toTop').addEventListener('click', () => scrollTo({ top: 0, behavior: 'smooth' }));

    // Jadwal shalat + hitung mundur
    const names = [['Fajr','Subuh'],['Dhuhr','Dzuhur'],['Asr','Ashar'],['Maghrib','Maghrib'],['Isha','Isya']];
    let timer, times = null;

    function tick() {
        if (!times) return;
        const n = new Date();
        let idx = times.findIndex(t => t > n), target;
        if (idx < 0) { idx = 0; target = new Date(times[0].getTime() + 864e5); } else target = times[idx];
        document.querySelectorAll('.prayer-item').forEach((el, i) => el.classList.toggle('active', i === idx));
        const s = Math.max(0, Math.floor((target - n) / 1000)), p = v => String(v).padStart(2, '0');
        $('countdown').hidden = false;
        $('countdown').innerHTML = names[idx][1] + ' dalam <strong>' + p(Math.floor(s / 3600)) + ':' + p(Math.floor(s % 3600 / 60)) + ':' + p(s % 60) + '</strong>';
    }

    async function loadPrayer() {
        const list = $('prayerList'); list.innerHTML = ''; times = null; clearInterval(timer);
        $('tglShalat').textContent = 'Memuat jadwal...'; $('countdown').hidden = true;
        try {
            const r = await fetch('https://api.aladhan.com/v1/timingsByCity?city=' + encodeURIComponent($('kotaShalat').value) + '&country=Indonesia&method=20');
            const t = (await r.json()).data.timings, d = new Date();
            times = names.map(([k]) => { const [h, m] = t[k].split(':'); return new Date(d.getFullYear(), d.getMonth(), d.getDate(), +h, +m); });
            names.forEach(([k, label]) => {
                const el = document.createElement('div'); el.className = 'prayer-item';
                el.innerHTML = '<span>' + label + '</span><strong>' + t[k].slice(0, 5) + '</strong>';
                list.appendChild(el);
            });
            $('tglShalat').textContent = $('kotaShalat').value + ' · metode Kemenag RI';
            tick(); timer = setInterval(tick, 1000);
        } catch (e) { $('tglShalat').textContent = 'Jadwal belum bisa dimuat. Periksa koneksi internet Anda.'; }
    }
    $('kotaShalat').addEventListener('change', loadPrayer);
    loadPrayer();
</script>
</body>
</html>