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
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="13" height="13" style="vertical-align:-2px;margin-right:4px;opacity:.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
            </svg>
            <b id="tglMasehi"></b> &nbsp;|&nbsp; <span id="tglHijri"></span>
        </span>
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
        <a href="{{ url('/') }}" class="navbar-brand">            <span class="brand-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M3.75 21V9.75M20.25 21V9.75M12 2.25c0 0-5.25 2.25-5.25 7.5h10.5c0-5.25-5.25-7.5-5.25-7.5ZM8.25 9.75V6.75M15.75 9.75V6.75M1.5 9.75h21M9.75 21v-3.75a2.25 2.25 0 0 1 4.5 0V21"/></svg></span><span class="brand-text">Masjid<strong>Ku</strong></span></a>
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
                        <span class="join-avatar">@if($l->foto)<img src="{{ $l->foto }}" alt="" loading="lazy">@else<span class="join-initial">{{ mb_strtoupper(mb_substr($l->nama, 0, 2)) }}</span>@endif</span>
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
    @php
        $stripSvg = [
            '✓' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>',
            '≡' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5h18M3 10.5h18M3 7.5h18"/></svg>',
            '◫' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>',
            '◈' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>',
        ];
    @endphp
    @foreach($c['strip'] as $s)
        <div class="strip-item">
            {!! $stripSvg[$s['icon']] ?? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>' !!}
            {{ $s['text'] }}
        </div>
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
            @php
                $featureSvg = [
                    'keuangan' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                    'jadwal'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>',
                    'info'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"/>',
                    'donasi'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>',
                    'program'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>',
                    'jamaah'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
                ];
            @endphp
            @foreach($c['features'] as $f)
                <div class="feature-card">
                    <div class="feature-icon">
                        @if(isset($featureSvg[$f['icon']]))
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28">{!! $featureSvg[$f['icon']] !!}</svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
                        @endif
                    </div>
                    <h3>{{ $f['title'] }}</h3>
                    <p>{{ $f['desc'] }}</p>
                </div>
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16" aria-hidden="true" style="flex-shrink:0;color:#9ca3af">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM19.5 19.5l-4.35-4.35"/>
                </svg>
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
                            @if($v->foto)<img src="{{ $v->foto }}" alt="Foto {{ $v->nama }}" loading="lazy">@else<span class="mosque-photo-placeholder">{{ mb_strtoupper(mb_substr($v->nama, 0, 2)) }}</span>@endif
                            <span class="mosque-badge">✔ Terverifikasi</span>
                        </div>
                        <div class="mosque-info">
                            <h3>{{ $v->nama }}</h3>
                            <ul class="mosque-meta">
                                @if($v->alamat || $v->wilayah)
                                    <li>
                                        <span class="meta-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                        </span>
                                        <span>
                                            @if($v->alamat)<span class="meta-main">{{ $v->alamat }}</span>@endif
                                            @if($v->wilayah)<span class="meta-sub">{{ $v->wilayah }}</span>@endif
                                        </span>
                                    </li>
                                @endif
                                @if($v->telp)
                                    <li>
                                        <span class="meta-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                        </span>
                                        <span class="meta-main">{{ $v->telp }}</span>
                                    </li>
                                @endif
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
            @php
                $donasiSvg = [
                    'zakat'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                    'infaq'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>',
                    'sedekah' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>',
                    'wakaf'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/>',
                ];
                $fallbackSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/>';
            @endphp
            @foreach($c['donasi'] as $d)
                <a href="#masjid" class="donasi-card">
                    <i><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="30" height="30">{!! $donasiSvg[$d['icon']] ?? $fallbackSvg !!}</svg></i>
                    <h3>{{ $d['title'] }}</h3>
                    <p>{{ $d['desc'] }}</p>
                </a>
            @endforeach
        </div>
        <div class="ayat">
            <p class="ar" lang="ar">{{ $c['ayat']['arab'] }}</p>
            <p>"{{ $c['ayat']['arti'] }}"</p>
            <small>{{ $c['ayat']['sumber'] }}</small>
        </div>
    </div>
</section>

@php
    $warnaArt = ['g1','g2','g3'];
@endphp
<section class="articles" id="artikel">
    <div class="section-container">
        <div class="section-header">
            <span class="section-badge">{{ $c['artikel_head']['badge'] }}</span>
            <h2>{{ $c['artikel_head']['title'] }}</h2>
            <p>{{ $c['artikel_head']['desc'] }}</p>
        </div>

        @if($articles->count())
            <div class="art-grid">
                @foreach($articles as $a)
                    @php $menit = max(1, (int) ceil(str_word_count(strip_tags($a->isi ?? '')) / 200)); @endphp
                    <a href="{{ url('/artikel/' . $a->slug) }}" class="art-card">
                        <div class="art-cover {{ $warnaArt[$loop->index % 3] }}">
                            <span class="art-date">{{ $a->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="art-body">
                            <h3>{{ $a->title }}</h3>
                            <p>{{ Str::limit(strip_tags($a->isi), 130) }}</p>

                            @if($a->file || $a->link)
                                <div class="art-chips">
                                    @if($a->file)
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="13" height="13" style="vertical-align:-2px"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"/></svg>
                                            {{ strtoupper($a->file_ext) }}
                                        </span>
                                    @endif
                                    @if($a->link)
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="13" height="13" style="vertical-align:-2px"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                                            {{ $a->link_host }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="art-foot">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="13" height="13" style="vertical-align:-2px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    {{ $menit }} menit baca
                                </span>
                                <b>Baca artikel →</b>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
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
            <div class="kontak-card">
                <i><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg></i>
                <div><h3>Email</h3>@if($email)<a href="mailto:{{ $email }}">{{ $email }}</a>@else<p>Segera tersedia</p>@endif</div>
            </div>
            <div class="kontak-card">
                <i><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg></i>
                <div><h3>Pusat Layanan</h3><p>{{ $c['kontak']['alamat'] }}</p></div>
            </div>
            <div class="kontak-card">
                <i><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg></i>
                <div><h3>Jam Layanan</h3><p>{{ $c['kontak']['jam'] }}</p></div>
            </div>
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
            <a href="{{ url('/') }}" class="navbar-brand">            <span class="brand-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M3.75 21V9.75M20.25 21V9.75M12 2.25c0 0-5.25 2.25-5.25 7.5h10.5c0-5.25-5.25-7.5-5.25-7.5ZM8.25 9.75V6.75M15.75 9.75V6.75M1.5 9.75h21M9.75 21v-3.75a2.25 2.25 0 0 1 4.5 0V21"/></svg></span><span class="brand-text">Masjid<strong>Ku</strong></span></a>
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