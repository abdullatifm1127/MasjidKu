<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Landing Page - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
    <style>
        /* Tambahan kecil untuk dropzone foto (melengkapi .lp-drop di landingPage.css) */
        .lp-drop { position: relative; }
        .lp-drop input[type="file"] { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
        .lp-drop:focus-within { border-color: var(--green-2); box-shadow: 0 0 0 4px rgba(47, 122, 82, 0.13); }
        .lp-drop.has-new { border-style: solid; border-color: var(--green-2); background: #f0f8f3; }
        .lp-drop-text { flex: 1; min-width: 0; }
        .lp-drop-text b { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .lp-drop-tag {
            display: none; margin-bottom: 6px; padding: 2px 9px; width: fit-content;
            background: #fff3d6; color: #8a5a06; border-radius: 999px;
            font-size: 0.66rem; font-weight: 800;
        }
        .lp-drop.has-new .lp-drop-tag { display: block; }
        .lp-drop-actions { display: flex; gap: 8px; flex-shrink: 0; }
        .lp-drop-btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px;
            font-family: inherit; font-size: 0.76rem; font-weight: 800; color: var(--green);
            background: #fff; border: 1px solid #bfd1c6; border-radius: 10px; cursor: pointer;
            transition: background .15s, border-color .15s, color .15s;
        }
        .lp-drop-btn:hover { background: #f0f8f3; border-color: var(--green-2); }
        .lp-drop-btn.danger { color: #b91c1c; border-color: #f3c9c6; }
        .lp-drop-btn.danger:hover { background: #fef2f2; border-color: #ef4444; }
        .lp-drop-btn[hidden] { display: none; }
        .lp-drop-err { display: none; }
        .lp-drop-err.show { display: block; }
        .lp-drop.is-over .lp-drop-thumb { transform: scale(1.06); }
        .lp-drop-thumb { transition: transform .2s; }
        @media (max-width: 560px) {
            .lp-drop { flex-wrap: wrap; }
            .lp-drop-actions { width: 100%; }
        }
    </style>
</head>
<body class="lp-body" id="lpBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="lp-sidebar" id="lpSidebar">

        <div class="lp-brand">
            <div class="lp-brand-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="lp-brand-info">
                <span class="lp-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</span>
                <span class="lp-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</span>
            </div>
        </div>

        <nav class="lp-nav">
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="lp-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item {{ request()->routeIs('admin.landing-page*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="lp-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item {{ request()->routeIs('admin.profil-masjid*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>

            <a href="{{ route('admin.jadwal-sholat') }}" class="lp-nav-item {{ request()->routeIs('admin.jadwal-sholat*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="lp-nav-label">Jadwal Shalat</span>
            </a>

            {{-- MENU PENGUMUMAN DINAMIS --}}
            @php
                $pengumumanAktif = isset($mosque)
                    ? \App\Models\Pengumuman::where('mosque_id', $mosque->id)->active()->count()
                    : 0;
            @endphp
            <a href="{{ route('admin.pengumuman') }}" class="lp-nav-item {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="lp-nav-label">Pengumuman</span>
                @if($pengumumanAktif > 0)
                    <span class="lp-nav-badge">{{ $pengumumanAktif }}</span>
                @endif
            </a>

            <a href="{{ route('admin.acara') }}" class="lp-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="lp-nav-label">Kegiatan &amp; Acara</span>
            </a>

            {{-- MENU DONASI DINAMIS BERDASARKAN PAKET MASJID --}}
            @if(isset($mosque) && $mosque->package_type === 'free')
                <a href="{{ route('masjid.perpanjangan.create') }}" class="lp-nav-item {{ request()->routeIs('masjid.perpanjangan*') ? 'active' : '' }}" style="opacity: 0.8;" title="Upgrade paket untuk membuka fitur donasi">
                    <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="lp-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #e74c3c; color: white;">Locked</span>
                </a>
            @else
                <a href="{{ route('admin.donasi') }}" class="lp-nav-item {{ request()->routeIs('admin.donasi*') ? 'active' : '' }}">
                    <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="lp-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #27ae60; color: white;">Aktif</span>
                </a>
            @endif

            <a href="{{ route('admin.jamaah') }}" class="lp-nav-item {{ request()->routeIs('admin.jamaah*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="lp-nav-label">Data Jamaah</span>
            </a>

            {{-- MENU TEMA TAMPILAN --}}
            <a href="{{ route('admin.tema') }}" class="lp-nav-item {{ request()->routeIs('admin.tema*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-palette"></i></span>
                <span class="lp-nav-label">Tema Tampilan</span>
            </a>
        </nav>

        <div class="lp-user">
            <div class="lp-user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 2) }}</div>
            <div class="lp-user-info">
                <div class="lp-user-name">{{ auth()->user()->name ?? 'Admin Masjid' }}</div>
                <div class="lp-user-email">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="lp-main">

        <form id="lpForm" action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            $isPublished = $mosque->is_published ?? true;
            $heroImgUrl  = !empty($landingPage->hero_image) ? asset('storage/' . $landingPage->hero_image) : null;
            $heroTitle   = old('hero_title', $landingPage->hero_title ?? '');
            $heroSub     = old('hero_subtitle', $landingPage->hero_subtitle ?? $mosque->tagline ?? '');
            $heroDesc    = old('hero_desc', $landingPage->hero_desc ?? '');
            $publikUrl   = route('masjid.publik', $mosque->slug ?? '');
        @endphp

        {{-- Topbar --}}
        <header class="lp-topbar">
            <div class="lp-topbar-left" style="display:flex;align-items:center;gap:12px;">
                <button type="button" class="lp-toggle-btn" id="lpToggle" aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <div class="lp-page-title">Editor Landing Page</div>
                    <div class="lp-page-sub">Atur tampilan halaman publik masjid Anda</div>
                </div>
            </div>
            <div class="lp-topbar-right">
                <span class="lp-dirty" id="lpDirty">Belum disimpan</span>
                <a href="{{ $publikUrl }}" class="lp-btn-back" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Lihat Website</span>
                </a>
                <button type="submit" class="lp-btn-save" id="lpSaveBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan perubahan
                </button>
            </div>
        </header>

        @if(session('success'))
            <div class="lp-alert" id="lpAlert">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="lp-alert-close" aria-label="Tutup" onclick="document.getElementById('lpAlert').remove()">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="lp-alert is-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Content --}}
        <main class="lp-content">

            {{-- Status publikasi --}}
            <div class="lp-status">
                <div class="lp-status-left">
                    <div class="lp-status-ico {{ $isPublished ? '' : 'draft' }}" id="lpStatusIco">
                        <i class="fa-solid {{ $isPublished ? 'fa-globe' : 'fa-eye-slash' }}"></i>
                    </div>
                    <div>
                        <div class="lp-status-title" id="lpStatusTitle">{{ $isPublished ? 'Dipublikasikan' : 'Draft' }}</div>
                        <div class="lp-status-sub">
                            Terakhir disimpan {{ isset($mosque) && $mosque->updated_at ? $mosque->updated_at->diffForHumans() : '-' }}
                        </div>
                    </div>
                </div>
                <label class="lp-publish">
                    <span>Tampilkan ke publik</span>
                    <input type="hidden" name="is_published" value="0">
                    <span class="lp-switch">
                        <input type="checkbox" id="lpPublish" name="is_published" value="1" {{ $isPublished ? 'checked' : '' }}>
                        <span class="lp-slider"></span>
                    </span>
                </label>
            </div>

            {{-- Tabs --}}
            <div class="lp-tabs" id="lpTabs" role="tablist">
                <button type="button" class="lp-tab active" data-tab="hero"><i class="fa-solid fa-panorama"></i> Hero / Banner</button>
                <button type="button" class="lp-tab" data-tab="tentang"><i class="fa-solid fa-circle-info"></i> Tentang Masjid</button>
                <button type="button" class="lp-tab" data-tab="modul"><i class="fa-solid fa-table-cells"></i> Modul Aktif</button>
                <button type="button" class="lp-tab lp-tab-preview" data-tab="preview"><i class="fa-solid fa-eye"></i> Pratinjau</button>
            </div>

            {{-- ===== TAB: HERO ===== --}}
            <div class="lp-panel active" id="lpTab-hero">
                <div class="lp-hero-grid">

                    <div class="lp-hero-main">
                        {{-- Konten teks --}}
                        <div class="lp-card">
                            <div class="lp-card-title"><span class="lp-card-bar"></span>Konten hero / banner utama</div>

                            <div class="lp-field">
                                <label class="lp-label" for="hero_title">Judul utama</label>
                                <input class="lp-input" type="text" id="hero_title" name="hero_title"
                                       data-live="title"
                                       placeholder="cth. Selamat Datang di Masjid Al-Ikhlas"
                                       value="{{ $heroTitle }}">
                            </div>

                            <div class="lp-field">
                                <label class="lp-label">Nama masjid (Arab)</label>
                                @if(!empty($mosque->arabic_name))
                                    <div class="lp-readonly" dir="rtl">{{ $mosque->arabic_name }}</div>
                                @else
                                    <div class="lp-readonly empty">(belum diisi)</div>
                                @endif
                                <span class="lp-hint">Diambil dari <a href="{{ route('admin.profil-masjid') }}">Profil Masjid</a>. Ubah di sana untuk memperbarui.</span>
                            </div>

                            <div class="lp-field">
                                <label class="lp-label" for="hero_subtitle">Sub-judul / tagline</label>
                                <input class="lp-input" type="text" id="hero_subtitle" name="hero_subtitle"
                                       data-live="sub"
                                       placeholder="cth. Masjid Rahmatan Lil Alamin"
                                       value="{{ $heroSub }}">
                            </div>

                            <div class="lp-field">
                                <label class="lp-label" for="hero_desc">Deskripsi singkat</label>
                                <textarea class="lp-textarea" id="hero_desc" name="hero_desc" rows="3"
                                          data-live="desc"
                                          placeholder="Ceritakan tentang masjid Anda dalam 1–2 kalimat...">{{ $heroDesc }}</textarea>
                            </div>
                        </div>

                        {{-- Gambar latar --}}
                        <div class="lp-card">
                            <div class="lp-card-title"><span class="lp-card-bar"></span>Gambar latar</div>

                            <div class="lp-field">
                                <label class="lp-label" for="hero_image">Gambar latar <small>opsional</small></label>

                                <label class="lp-drop" id="lpDrop" data-original="{{ $heroImgUrl }}" for="hero_image">
                                    <input type="file" id="hero_image" name="hero_image" accept="image/png,image/jpeg,image/webp,image/avif">

                                    <div class="lp-drop-thumb" id="lpDropThumb">
                                        @if($heroImgUrl)
                                            <img src="{{ $heroImgUrl }}" alt="Gambar hero saat ini">
                                        @else
                                            <i class="fa-regular fa-image"></i>
                                        @endif
                                    </div>

                                    <div class="lp-drop-text">
                                        <span class="lp-drop-tag">Gambar baru</span>
                                        <b id="lpDropTitle">{{ $heroImgUrl ? 'Gambar saat ini' : 'Tarik gambar ke sini atau klik untuk memilih' }}</b>
                                        <span id="lpDropSub">{{ $heroImgUrl ? 'Klik untuk mengganti dengan gambar baru' : 'PNG, JPG, WebP, AVIF · maksimal 2 MB' }}</span>
                                    </div>

                                    <div class="lp-drop-actions">
                                        <button type="button" class="lp-drop-btn danger" id="lpDropClear" hidden>
                                            <i class="fa-solid fa-rotate-left"></i> Batal
                                        </button>
                                    </div>
                                </label>

                                <div class="lp-drop-err" id="lpDropErr"></div>
                                <span class="lp-hint">Rekomendasi 1920×600 piksel. Gambar akan tampil di belakang teks hero dan sedikit digelapkan agar teks tetap terbaca.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pratinjau langsung --}}
                    <aside class="lp-hero-side">
                        <div class="lp-live">
                            <div class="lp-live-head">Pratinjau langsung <span>LIVE</span></div>
                            <div class="lp-live-bar"><i></i><i></i><i></i><em>{{ $mosque->slug ?? 'masjid' }}</em></div>
                            <div class="lp-live-hero" id="pvHero" @if($heroImgUrl) style="--pv-img: url('{{ $heroImgUrl }}')" @endif>
                                <div class="lp-live-ar" dir="rtl">{{ $mosque->arabic_name ?? '' }}</div>
                                <div class="lp-live-title" id="pvTitle">{{ $heroTitle ?: ($mosque->mosque_name ?? 'Judul utama masjid') }}</div>
                                <div class="lp-live-sub" id="pvSub">{{ $heroSub ?: 'Sub-judul / tagline' }}</div>
                                <div class="lp-live-desc" id="pvDesc">{{ $heroDesc ?: 'Deskripsi singkat masjid akan tampil di sini.' }}</div>
                                <span class="lp-live-btn">Lihat jadwal shalat</span>
                            </div>
                            <div class="lp-live-note">Perubahan tampil langsung di sini. Klik <b>Simpan perubahan</b> untuk menerapkannya ke website.</div>
                        </div>
                    </aside>

                </div>
            </div>

            {{-- ===== TAB: TENTANG ===== --}}
            <div class="lp-panel" id="lpTab-tentang">
                <div class="lp-info-banner">
                    <div class="lp-info-banner-ico"><i class="fa-solid fa-link"></i></div>
                    <div class="lp-info-banner-text">
                        Konten section "Tentang Masjid" di halaman publik diambil langsung dari
                        <b>Profil Masjid</b>, jadi data yang sama tidak perlu diedit di dua tempat.
                    </div>
                    <a class="lp-btn-link" href="{{ route('admin.profil-masjid') }}">
                        Edit di Profil Masjid <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Tentang masjid</div>

                    <div class="lp-info-grid">
                        <div class="lp-info-item">
                            <div class="lp-info-label"><i class="fa-solid fa-mosque"></i> Nama masjid</div>
                            <div class="lp-info-value {{ empty($mosque->mosque_name) ? 'empty' : '' }}">{{ $mosque->mosque_name ?: '(belum diisi)' }}</div>
                        </div>
                        <div class="lp-info-item">
                            <div class="lp-info-label"><i class="fa-solid fa-calendar-check"></i> Tahun berdiri</div>
                            <div class="lp-info-value {{ empty($mosque->founded) ? 'empty' : '' }}">{{ $mosque->founded ?: '(belum diisi)' }}</div>
                        </div>
                        <div class="lp-info-item">
                            <div class="lp-info-label"><i class="fa-solid fa-people-roof"></i> Kapasitas jamaah</div>
                            <div class="lp-info-value {{ empty($mosque->capacity) ? 'empty' : '' }}">{{ $mosque->capacity ?: '(belum diisi)' }}</div>
                        </div>
                        <div class="lp-info-item wide">
                            <div class="lp-info-label"><i class="fa-solid fa-landmark"></i> Deskripsi / sejarah singkat</div>
                            <div class="lp-info-value {{ empty($mosque->description) ? 'empty' : '' }}">{{ $mosque->description ?: '(belum diisi)' }}</div>
                        </div>
                        <div class="lp-info-item wide">
                            <div class="lp-info-label"><i class="fa-solid fa-bullseye"></i> Visi &amp; misi</div>
                            <div class="lp-info-value {{ empty($mosque->about_vision) ? 'empty' : '' }}">{{ $mosque->about_vision ?: '(belum diisi)' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: MODUL ===== --}}
            <div class="lp-panel" id="lpTab-modul">
                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Modul yang ditampilkan</div>

                    @php
                        $activeModules = $landingPage->active_modules ?? [];
                        $moduls = [
                            ['key' => 'pengumuman',    'icon' => 'fa-bullhorn',            'name' => 'Pengumuman',       'desc' => 'Tampilkan pengumuman terbaru masjid (ticker info)'],
                            ['key' => 'jadwal_shalat', 'icon' => 'fa-clock',               'name' => 'Jadwal Shalat',    'desc' => 'Widget jadwal shalat hari ini'],
                            ['key' => 'kegiatan',      'icon' => 'fa-calendar-days',       'name' => 'Kegiatan & Acara', 'desc' => 'Daftar acara dan kegiatan mendatang'],
                            ['key' => 'donasi',        'icon' => 'fa-hand-holding-dollar', 'name' => 'Donasi Online',    'desc' => 'Tombol dan form donasi online'],
                            ['key' => 'data_jamaah',   'icon' => 'fa-users',               'name' => 'Data Jamaah',      'desc' => 'Statistik dan info jamaah terdaftar'],
                            ['key' => 'peta_lokasi',   'icon' => 'fa-location-dot',        'name' => 'Peta Lokasi',      'desc' => 'Tautan Google Maps di bagian Hubungi Kami'],
                        ];
                    @endphp

                    <div class="lp-mod-grid">
                        @foreach($moduls as $mod)
                            @php
                                // Data kosong (pertama kali) dianggap aktif
                                $isChecked = isset($activeModules[$mod['key']]) ? $activeModules[$mod['key']] : true;
                            @endphp
                            <label class="lp-mod">
                                <span class="lp-mod-icon"><i class="fa-solid {{ $mod['icon'] }}"></i></span>
                                <span class="lp-mod-text">
                                    <span class="lp-mod-name" style="display:block;">{{ $mod['name'] }}</span>
                                    <span class="lp-mod-desc" style="display:block;">{{ $mod['desc'] }}</span>
                                </span>
                                <input type="hidden" name="modul[{{ $mod['key'] }}]" value="0">
                                <span class="lp-switch">
                                    <input type="checkbox" name="modul[{{ $mod['key'] }}]" value="1" {{ $isChecked ? 'checked' : '' }}>
                                    <span class="lp-slider"></span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== TAB: PRATINJAU ===== --}}
            <div class="lp-panel" id="lpTab-preview">
                <div class="lp-card">
                    <div class="lp-frame-bar">
                        <div class="lp-devices" id="lpDevices">
                            <button type="button" class="lp-device active" data-device="desktop"><i class="fa-solid fa-desktop"></i> Desktop</button>
                            <button type="button" class="lp-device" data-device="tablet"><i class="fa-solid fa-tablet-screen-button"></i> Tablet</button>
                            <button type="button" class="lp-device" data-device="mobile"><i class="fa-solid fa-mobile-screen"></i> Ponsel</button>
                        </div>
                        <div class="lp-frame-actions">
                            <button type="button" class="lp-chip-btn" id="lpReload"><i class="fa-solid fa-rotate-right"></i> Muat ulang</button>
                            <a class="lp-chip-btn" href="{{ $publikUrl }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka di tab baru</a>
                        </div>
                    </div>

                    <div class="lp-frame-stage">
                        <div class="lp-frame-wrap" id="lpFrameWrap">
                            <iframe id="lpFrame" data-src="{{ $publikUrl }}" title="Pratinjau website masjid" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="lp-frame-note">Pratinjau menampilkan versi yang sudah disimpan. Simpan perubahan dulu, lalu klik "Muat ulang".</div>
                </div>
            </div>

        </main>
        </form>
    </div>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const $  = (s, r = document) => r.querySelector(s);
        const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

        /* ---------- Sidebar ---------- */
        const lpSidebar = $('#lpSidebar');
        const lpBody = $('#lpBody');
        try {
            if (localStorage.getItem('lp_sidebar_collapsed') === 'true') {
                lpSidebar.classList.add('collapsed');
                lpBody.classList.add('lp-sidebar-collapsed');
            }
        } catch (e) {}
        $('#lpToggle').addEventListener('click', () => {
            lpSidebar.classList.toggle('collapsed');
            lpBody.classList.toggle('lp-sidebar-collapsed');
            try { localStorage.setItem('lp_sidebar_collapsed', lpSidebar.classList.contains('collapsed')); } catch (e) {}
        });

        /* ---------- Tabs ---------- */
        const frame = $('#lpFrame');
        function showTab(name, setHash) {
            if (!$('#lpTab-' + name)) return;
            $$('.lp-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === name));
            $$('.lp-panel').forEach(p => p.classList.toggle('active', p.id === 'lpTab-' + name));
            if (setHash) history.replaceState(null, '', '#' + name);
            if (name === 'preview' && !frame.src) frame.src = frame.dataset.src;
        }
        $$('.lp-tab').forEach(t => t.addEventListener('click', () => showTab(t.dataset.tab, true)));
        const h = location.hash.replace('#', '');
        if (h) showTab(h, false);

        /* ---------- Pratinjau (iframe) ---------- */
        $$('.lp-device').forEach(btn => btn.addEventListener('click', () => {
            $$('.lp-device').forEach(b => b.classList.toggle('active', b === btn));
            const wrap = $('#lpFrameWrap');
            wrap.classList.remove('tablet', 'mobile');
            if (btn.dataset.device !== 'desktop') wrap.classList.add(btn.dataset.device);
        }));
        $('#lpReload').addEventListener('click', () => { frame.src = frame.dataset.src; });

        /* ---------- Status publikasi ---------- */
        const pub = $('#lpPublish');
        pub.addEventListener('change', () => {
            $('#lpStatusTitle').textContent = pub.checked ? 'Dipublikasikan' : 'Draft';
            $('#lpStatusIco').classList.toggle('draft', !pub.checked);
            $('#lpStatusIco i').className = 'fa-solid ' + (pub.checked ? 'fa-globe' : 'fa-eye-slash');
        });

        /* ---------- Pratinjau langsung (teks) ---------- */
        const live = {
            title: { out: $('#pvTitle'), ph: @json($mosque->mosque_name ?? 'Judul utama masjid') },
            sub:   { out: $('#pvSub'),   ph: 'Sub-judul / tagline' },
            desc:  { out: $('#pvDesc'),  ph: 'Deskripsi singkat masjid akan tampil di sini.' },
        };
        $$('[data-live]').forEach(el => {
            const cfg = live[el.dataset.live];
            const update = () => {
                const v = el.value.trim();
                cfg.out.textContent = v || cfg.ph;
                cfg.out.classList.toggle('is-ph', !v);
            };
            el.addEventListener('input', update);
            update();
        });

        /* ---------- Dirty state & simpan ---------- */
        const form = $('#lpForm');
        const dirty = $('#lpDirty');
        let submitting = false;
        const markDirty = () => dirty.classList.add('show');
        form.addEventListener('input', markDirty);
        form.addEventListener('change', markDirty);
        form.addEventListener('submit', () => {
            submitting = true;
            const btn = $('#lpSaveBtn');
            btn.innerHTML = '<span class="lp-spin"></span> Menyimpan...';
            setTimeout(() => { btn.disabled = true; }, 0);
        });
        window.addEventListener('beforeunload', e => {
            if (!submitting && dirty.classList.contains('show')) { e.preventDefault(); e.returnValue = ''; }
        });

        /* ---------- Upload gambar hero ---------- */
        const MAX_SIZE = 2 * 1024 * 1024;
        const ALLOWED  = ['image/png', 'image/jpeg', 'image/webp', 'image/avif'];
        const drop     = $('#lpDrop');
        const input    = $('#hero_image');
        const thumb    = $('#lpDropThumb');
        const dTitle   = $('#lpDropTitle');
        const dSub     = $('#lpDropSub');
        const dErr     = $('#lpDropErr');
        const btnClear = $('#lpDropClear');
        const pvHero   = $('#pvHero');
        const original = drop.dataset.original || '';

        const fmt = b => b < 1048576 ? Math.round(b / 1024) + ' KB' : (b / 1048576).toFixed(1) + ' MB';
        const showErr = msg => { dErr.textContent = msg; dErr.classList.toggle('show', !!msg); };
        const setBg = src => pvHero.style.setProperty('--pv-img', src ? 'url("' + src + '")' : 'none');

        function restore() {
            input.value = '';
            drop.classList.remove('has-new');
            btnClear.hidden = true;
            if (original) {
                thumb.innerHTML = '<img src="' + original + '" alt="Gambar hero saat ini">';
                dTitle.textContent = 'Gambar saat ini';
                dSub.textContent = 'Klik untuk mengganti dengan gambar baru';
                setBg(original);
            } else {
                thumb.innerHTML = '<i class="fa-regular fa-image"></i>';
                dTitle.textContent = 'Tarik gambar ke sini atau klik untuk memilih';
                dSub.textContent = 'PNG, JPG, WebP, AVIF · maksimal 2 MB';
                setBg('');
            }
        }

        function handle(file) {
            showErr('');
            if (!file) return;
            if (!ALLOWED.includes(file.type)) {
                input.value = '';
                return showErr('Format tidak didukung. Gunakan PNG, JPG, WebP, atau AVIF.');
            }
            if (file.size > MAX_SIZE) {
                input.value = '';
                return showErr('Ukuran ' + fmt(file.size) + ' melebihi batas 2 MB. Kompres gambarnya lalu coba lagi.');
            }
            const reader = new FileReader();
            reader.onload = e => {
                thumb.innerHTML = '<img src="' + e.target.result + '" alt="Pratinjau gambar baru">';
                dTitle.textContent = file.name;
                dSub.textContent = fmt(file.size) + ' · klik untuk mengganti';
                drop.classList.add('has-new');
                btnClear.hidden = false;
                setBg(e.target.result);
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', () => handle(input.files[0]));
        btnClear.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); restore(); showErr(''); });

        ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('is-over'); }));
        ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('is-over'); }));
        drop.addEventListener('drop', e => {
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if (!file) return;
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            handle(file);
            markDirty();
        });
    });
    </script>
</body>
</html>