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

    // Isi teks halaman utama (bawaan + hasil edit Super Admin)
    $c = \App\Models\SiteSetting::content();

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
        $cols = collect(Schema::getColumnListing('mosques'))->filter(fn ($col) => preg_match('/^(nama|name)|alamat|address|city|kota|kabupaten|kecamatan|kelurahan|desa|provinsi|province/i', $col));
        $query->where(function ($w) use ($cols, $keyword) { foreach ($cols as $col) { $w->orWhere($col, 'like', "%{$keyword}%"); } });
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
    $email  = $c['kontak']['email'] ?: config('mail.from.address');
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
            @if($c['hero']['show_bismillah'])<p class="bismillah" lang="ar">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</p>@endif
            <span class="hero-badge">{{ $c['hero']['badge'] }}</span>
            <h1 class="hero-title">{{ $c['hero']['title_1'] }}<br>{{ $c['hero']['title_2'] }} <span class="text-green">{{ $c['hero']['title_highlight'] }}</span></h1>
            <p class="hero-subtitle">{{ $c['hero']['subtitle'] }}</p>
            <div class="hero-actions">
                <a href="{{ route('daftar.masjid') }}" class="btn-primary">{{ $c['hero']['btn_primary'] }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="#tentang" class="btn-secondary">{{ $c['hero']['btn_secondary'] }}</a>
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
                <small>{{ $c['hero']['card_small'] }}</small>
                <h2>{{ $c['hero']['card_title'] }}</h2>
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
    @foreach($c['strip'] as $s)
        <div class="strip-item"><i>{{ $s['icon'] }}</i>{{ $s['text'] }}</div>
    @endforeach
</div></div>

{{-- ===== FITUR ===== --}}
<section class="features" id="tentang">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">{{ $c['features_head']['badge'] }}</span>
            <h2>{{ $c['features_head']['title'] }}</h2>
            <p>{{ $c['features_head']['desc'] }}</p>
        </div>
        <div class="features-grid">
            @foreach($c['features'] as $f)
                <div class="feature-card"><div class="feature-icon">{{ $f['icon'] }}</div><h3>{{ $f['title'] }}</h3><p>{{ $f['desc'] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CARA KERJA ===== --}}
<section class="steps" id="cara-kerja">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">{{ $c['steps_head']['badge'] }}</span>
            <h2>{{ $c['steps_head']['title'] }}</h2>
            <p>{{ $c['steps_head']['desc'] }}</p>
        </div>
        <div class="steps-grid">
            @foreach($c['steps'] as $st)
                <div class="step"><h3>{{ $st['title'] }}</h3><p>{{ $st['desc'] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== PROGRAM ===== --}}
<section class="programs" id="program">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">{{ $c['program_head']['badge'] }}</span>
            <h2>{{ $c['program_head']['title'] }}</h2>
            <p>{{ $c['program_head']['desc'] }}</p>
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
            <span class="section-badge">{{ $c['masjid_head']['badge'] }}</span>
            <h2>{{ $c['masjid_head']['title'] }}</h2>
            <p>{{ $c['masjid_head']['desc'] }}</p>
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
            <span class="section-badge">{{ $c['donasi_head']['badge'] }}</span>
            <h2>{{ $c['donasi_head']['title'] }}</h2>
            <p>{{ $c['donasi_head']['desc'] }}</p>
        </div>
        <div class="donasi-grid">
            @foreach($c['donasi'] as $d)
                <a href="#masjid" class="donasi-card"><i>{{ $d['icon'] }}</i><h3>{{ $d['title'] }}</h3><p>{{ $d['desc'] }}</p></a>
            @endforeach
        </div>
        <div class="ayat">
            <p class="ar" lang="ar">{{ $c['ayat']['arab'] }}</p>
            <p>"{{ $c['ayat']['arti'] }}"</p>
            <small>{{ $c['ayat']['sumber'] }}</small>
        </div>
    </div>
</section>

{{-- ===== ARTIKEL ===== --}}
<section class="programs articles" id="artikel">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">{{ $c['artikel_head']['badge'] }}</span>
            <h2>{{ $c['artikel_head']['title'] }}</h2>
            <p>{{ $c['artikel_head']['desc'] }}</p>
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
        <div class="section-header"><span class="section-badge">{{ $c['faq_head']['badge'] }}</span><h2>{{ $c['faq_head']['title'] }}</h2></div>
        <div class="faq-list">
            @foreach($c['faq'] as $fq)
                @if(!empty($fq['q']))
                    <details><summary>{{ $fq['q'] }}</summary><p>{{ $fq['a'] }}</p></details>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- ===== KONTAK ===== --}}
<section id="kontak-info">
    <div class="section-container">
        <div class="section-header"><span class="section-badge">Kontak</span><h2>{{ $c['kontak']['title'] }}</h2><p>{{ $c['kontak']['desc'] }}</p></div>
        <div class="kontak-grid">
            <div class="kontak-card"><i>✉️</i><div><h3>Email</h3>@if($email)<a href="mailto:{{ $email }}">{{ $email }}</a>@else<p>Segera tersedia</p>@endif</div></div>
            <div class="kontak-card"><i>📍</i><div><h3>Pusat Layanan</h3><p>{{ $c['kontak']['alamat'] }}</p></div></div>
            <div class="kontak-card"><i>🕒</i><div><h3>Jam Layanan</h3><p>{{ $c['kontak']['jam'] }}</p></div></div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="cta-donasi">
    <div class="section-container"><div class="cta-box"><div class="cta-text">
        <h2>{{ $c['cta']['title'] }}</h2>
        <p>{{ $c['cta']['desc'] }}</p>
        <div class="cta-actions">
            <a href="{{ route('daftar.masjid') }}" class="btn-primary">{{ $c['cta']['btn_primary'] }}</a>
            <a href="#faq" class="btn-secondary">{{ $c['cta']['btn_secondary'] }}</a>
        </div>
    </div></div></div>
</section>

</main>

{{-- ===== FOOTER ===== --}}
<footer class="footer" id="kontak">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="navbar-brand"><span class="brand-icon">🕌</span><span class="brand-text">Masjid<strong>Ku</strong></span></a>
            <p>{{ $c['footer']['tagline'] }}</p>
            <div class="footer-social">
                <a href="{{ $c['footer']['instagram'] ?: '#' }}" target="_blank" rel="noopener" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                <a href="{{ $c['footer']['facebook'] ?: '#' }}" target="_blank" rel="noopener" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                <a href="{{ $c['footer']['youtube'] ?: '#' }}" target="_blank" rel="noopener" aria-label="YouTube"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
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
        <p>&copy; {{ date('Y') }} {{ $c['footer']['copyright'] }}</p>
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