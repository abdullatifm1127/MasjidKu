<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Masjid - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/profilMasjid.css') }}">
</head>
<body class="admin-page" id="adminBody">
    
    <aside class="ba-sidebar" id="baSidebar">

        <a href="{{ url('/') }}" class="ba-brand">
            <div class="ba-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="ba-brand-text">
                <strong>SIM Masjid</strong>
                <span>Baitul Digital</span>
            </div>
        </a>

        <nav class="ba-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="ba-nav-item active">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Profil Masjid</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Jadwal Shalat</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Pengumuman</span>
                <span class="ba-nav-badge">3</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Kegiatan &amp; Acara</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Donasi</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Data Jamaah</span>
                <span class="ba-nav-soon">dev</span>
            </a>
        </nav>

        <div class="ba-user">
            <div class="ba-user-avatar">AM</div>
            <div class="ba-user-info">
                <div class="ba-user-name">Admin Masjid</div>
                <div class="ba-user-email">admin@baituldigital.id</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba-main">

        {{-- Topbar --}}
        <header class="ba-topbar">
            <div class="ba-topbar-left">
                <button class="ba-toggle-btn" id="baToggle" aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <div class="ba-page-title">Profil Masjid</div>
                    <div class="ba-page-sub">Kelola modul Profil Masjid</div>
                </div>
            </div>
            <div class="ba-topbar-right">
                <a href="{{ url('/') }}" class="ba-btn-public">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Kembali ke Publik
                </a>
                <button class="ba-notif-btn" aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <span class="ba-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="ba-content pm-content">

            {{-- Alert sukses / error --}}
            @if(session('success'))
                <div class="pm-alert pm-alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="pm-alert pm-alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    Terdapat kesalahan. Periksa kembali isian di bawah.
                </div>
            @endif

            {{-- Tab Navigation --}}
            <div class="pm-tabs">
                <button class="pm-tab active" data-tab="identitas">Identitas</button>
                <button class="pm-tab" data-tab="pengurus">Pengurus</button>
                <button class="pm-tab" data-tab="lokasi">Lokasi &amp; Kontak</button>
                <button class="pm-tab" data-tab="program">Program</button>
                <button class="pm-tab pm-tab-preview" data-tab="preview">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Preview
                </button>
            </div>

            <form id="pmForm" method="POST" action="{{ route('admin.profil-masjid.update') }}">
                @csrf
                @method('PUT')

                {{-- ===== TAB: IDENTITAS ===== --}}
                <div class="pm-tab-content active" id="tab-identitas">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Identitas Masjid
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="mosque_name">
                                    NAMA MASJID <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="mosque_name" name="mosque_name"
                                       class="pm-input @error('mosque_name') pm-input-error @enderror"
                                       value="{{ old('mosque_name', $mosque->mosque_name ?? '') }}"
                                       placeholder="Nama masjid" required>
                                @error('mosque_name')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="arabic_name">NAMA ARAB</label>
                                <input type="text" id="arabic_name" name="arabic_name"
                                       class="pm-input pm-input-rtl"
                                       value="{{ old('arabic_name', $mosque->arabic_name ?? '') }}"
                                       placeholder="الاسم بالعربية"
                                       dir="rtl">
                            </div>
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="tagline">TAGLINE / SLOGAN</label>
                            <input type="text" id="tagline" name="tagline"
                                   class="pm-input"
                                   value="{{ old('tagline', $mosque->tagline ?? '') }}"
                                   placeholder="Slogan atau tagline masjid">
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="founded">TAHUN BERDIRI</label>
                                <input type="number" id="founded" name="founded"
                                       class="pm-input"
                                       value="{{ old('founded', $mosque->founded ?? '') }}"
                                       placeholder="Contoh: 1987"
                                       min="1000" max="{{ date('Y') }}">
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="capacity">KAPASITAS JAMAAH</label>
                                <input type="text" id="capacity" name="capacity"
                                       class="pm-input"
                                       value="{{ old('capacity', $mosque->capacity ?? '') }}"
                                       placeholder="Contoh: 2.500 jamaah">
                            </div>
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="description">DESKRIPSI / TENTANG MASJID</label>
                            <textarea id="description" name="description"
                                      class="pm-textarea"
                                      rows="4"
                                      placeholder="Ceritakan sedikit tentang masjid ini...">{{ old('description', $mosque->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ===== TAB: PENGURUS ===== --}}
                <div class="pm-tab-content" id="tab-pengurus">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Struktur Pengurus
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="organization_name">NAMA ORGANISASI / YAYASAN</label>
                            <input type="text" id="organization_name" name="organization_name"
                                   class="pm-input"
                                   value="{{ old('organization_name', $mosque->organization_name ?? '') }}"
                                   placeholder="Nama DKM / Yayasan">
                        </div>

                        <div class="pm-divider-label">Imam / Khatib</div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="imam_name">
                                    NAMA IMAM <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="imam_name" name="imam_name"
                                       class="pm-input @error('imam_name') pm-input-error @enderror"
                                       value="{{ old('imam_name', $mosque->imam_name ?? '') }}"
                                       placeholder="Nama imam masjid">
                                @error('imam_name')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="imam_phone">NO. TELEPON IMAM</label>
                                <input type="text" id="imam_phone" name="imam_phone"
                                       class="pm-input"
                                       value="{{ old('imam_phone', $mosque->imam_phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="pm-divider-label">Ketua DKM</div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="chairman_name">
                                    NAMA KETUA <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="chairman_name" name="chairman_name"
                                       class="pm-input @error('chairman_name') pm-input-error @enderror"
                                       value="{{ old('chairman_name', $mosque->chairman_name ?? '') }}"
                                       placeholder="Nama ketua DKM">
                                @error('chairman_name')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="chairman_phone">NO. TELEPON KETUA</label>
                                <input type="text" id="chairman_phone" name="chairman_phone"
                                       class="pm-input"
                                       value="{{ old('chairman_phone', $mosque->chairman_phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="secretary_name">NAMA SEKRETARIS</label>
                                <input type="text" id="secretary_name" name="secretary_name"
                                       class="pm-input"
                                       value="{{ old('secretary_name', $mosque->secretary_name ?? '') }}"
                                       placeholder="Nama sekretaris">
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="treasurer_name">NAMA BENDAHARA</label>
                                <input type="text" id="treasurer_name" name="treasurer_name"
                                       class="pm-input"
                                       value="{{ old('treasurer_name', $mosque->treasurer_name ?? '') }}"
                                       placeholder="Nama bendahara">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== TAB: LOKASI & KONTAK ===== --}}
                <div class="pm-tab-content" id="tab-lokasi">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Lokasi
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="address">
                                ALAMAT LENGKAP <span class="pm-required">*</span>
                            </label>
                            <textarea id="address" name="address"
                                      class="pm-textarea @error('address') pm-input-error @enderror"
                                      rows="2"
                                      placeholder="Jl. Nama Jalan No. X, RT/RW">{{ old('address', $mosque->address ?? '') }}</textarea>
                            @error('address')
                                <span class="pm-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="kelurahan">
                                    KELURAHAN <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="kelurahan" name="kelurahan"
                                       class="pm-input @error('kelurahan') pm-input-error @enderror"
                                       value="{{ old('kelurahan', $mosque->kelurahan ?? '') }}"
                                       placeholder="Nama kelurahan">
                                @error('kelurahan')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="kecamatan">
                                    KECAMATAN <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="kecamatan" name="kecamatan"
                                       class="pm-input @error('kecamatan') pm-input-error @enderror"
                                       value="{{ old('kecamatan', $mosque->kecamatan ?? '') }}"
                                       placeholder="Nama kecamatan">
                                @error('kecamatan')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pm-grid-3">
                            <div class="pm-field">
                                <label class="pm-label" for="city">
                                    KOTA / KABUPATEN <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="city" name="city"
                                       class="pm-input @error('city') pm-input-error @enderror"
                                       value="{{ old('city', $mosque->city ?? '') }}"
                                       placeholder="Nama kota">
                                @error('city')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="province">
                                    PROVINSI <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="province" name="province"
                                       class="pm-input @error('province') pm-input-error @enderror"
                                       value="{{ old('province', $mosque->province ?? '') }}"
                                       placeholder="Nama provinsi">
                                @error('province')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="postal_code">KODE POS</label>
                                <input type="text" id="postal_code" name="postal_code"
                                       class="pm-input"
                                       value="{{ old('postal_code', $mosque->postal_code ?? '') }}"
                                       placeholder="12345">
                            </div>
                        </div>
                    </div>

                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Kontak
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="phone">
                                    NO. TELEPON / WA <span class="pm-required">*</span>
                                </label>
                                <input type="text" id="phone" name="phone"
                                       class="pm-input @error('phone') pm-input-error @enderror"
                                       value="{{ old('phone', $mosque->phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="email">
                                    EMAIL <span class="pm-required">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                       class="pm-input @error('email') pm-input-error @enderror"
                                       value="{{ old('email', $mosque->email ?? '') }}"
                                       placeholder="masjid@email.com">
                                @error('email')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="website">WEBSITE</label>
                            <input type="text" id="website" name="website"
                                   class="pm-input"
                                   value="{{ old('website', $mosque->website ?? '') }}"
                                   placeholder="https://masjid.id">
                        </div>
                    </div>
                </div>

                {{-- ===== TAB: PROGRAM ===== --}}
                <div class="pm-tab-content" id="tab-program">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Fasilitas
                        </div>

                        <div class="pm-checkbox-grid">
                            @php
                                $fasilitasList = ['Tempat Wudhu Pria','Tempat Wudhu Wanita','Parkir Kendaraan','Toilet','Perpustakaan','Aula Serbaguna','Kantin / Warung','Klinik / Poliklinik','CCTV','Pendingin Ruangan (AC)','Sound System','Generator / Genset'];
                                $selectedFasilitas = old('facilities', $mosque->facilities ?? []);
                            @endphp
                            @foreach($fasilitasList as $fasilitas)
                                <label class="pm-checkbox-item">
                                    <input type="checkbox" name="facilities[]"
                                           value="{{ $fasilitas }}"
                                           {{ in_array($fasilitas, $selectedFasilitas) ? 'checked' : '' }}>
                                    <span class="pm-checkbox-box"></span>
                                    <span class="pm-checkbox-label">{{ $fasilitas }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Program Kegiatan
                        </div>

                        <div class="pm-checkbox-grid">
                            @php
                                $programList = ['Kajian Rutin','Tahfidz Al-Qur\'an','TPA / TPQ','Pengajian Ibu-ibu','Pengajian Bapak-bapak','Remaja Masjid','Zakat & Infaq','Bantuan Sosial','Kelas Bahasa Arab','Shalat Berjamaah','Pesantren Kilat','Qurban / Hari Raya'];
                                $selectedProgram = old('programs', $mosque->programs ?? []);
                            @endphp
                            @foreach($programList as $program)
                                <label class="pm-checkbox-item">
                                    <input type="checkbox" name="programs[]"
                                           value="{{ $program }}"
                                           {{ in_array($program, $selectedProgram) ? 'checked' : '' }}>
                                    <span class="pm-checkbox-box"></span>
                                    <span class="pm-checkbox-label">{{ $program }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-bar"></span>
                            Fitur Digital
                        </div>

                        <div class="pm-toggle-list">
                            <div class="pm-toggle-item">
                                <div>
                                    <div class="pm-toggle-name">Donasi Online</div>
                                    <div class="pm-toggle-desc">Aktifkan modul penerimaan donasi secara online</div>
                                </div>
                                <label class="pm-switch">
                                    <input type="checkbox" name="has_online_donation" value="1"
                                           {{ old('has_online_donation', $mosque->has_online_donation ?? false) ? 'checked' : '' }}>
                                    <span class="pm-switch-track"></span>
                                </label>
                            </div>
                            <div class="pm-toggle-item">
                                <div>
                                    <div class="pm-toggle-name">Jadwal Shalat</div>
                                    <div class="pm-toggle-desc">Tampilkan jadwal shalat otomatis di halaman publik</div>
                                </div>
                                <label class="pm-switch">
                                    <input type="checkbox" name="has_prayer_schedule" value="1"
                                           {{ old('has_prayer_schedule', $mosque->has_prayer_schedule ?? false) ? 'checked' : '' }}>
                                    <span class="pm-switch-track"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== TAB: PREVIEW ===== --}}
                <div class="pm-tab-content" id="tab-preview">
                    <div class="pm-preview-card">
                        <div class="pm-preview-header">
                            <div class="pm-preview-mosque-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="pm-preview-mosque-name" id="prev-name">
                                    {{ $mosque->mosque_name ?? 'Nama Masjid' }}
                                </div>
                                @if(!empty($mosque->arabic_name))
                                <div class="pm-preview-arabic" id="prev-arabic">
                                    {{ $mosque->arabic_name }}
                                </div>
                                @endif
                                @if(!empty($mosque->tagline))
                                <div class="pm-preview-tagline" id="prev-tagline">
                                    {{ $mosque->tagline }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="pm-preview-grid">
                            @if(!empty($mosque->founded))
                            <div class="pm-preview-info-item">
                                <span class="pm-preview-info-label">Tahun Berdiri</span>
                                <span class="pm-preview-info-val">{{ $mosque->founded }}</span>
                            </div>
                            @endif
                            @if(!empty($mosque->capacity))
                            <div class="pm-preview-info-item">
                                <span class="pm-preview-info-label">Kapasitas Jamaah</span>
                                <span class="pm-preview-info-val">{{ $mosque->capacity }}</span>
                            </div>
                            @endif
                            @if(!empty($mosque->city))
                            <div class="pm-preview-info-item">
                                <span class="pm-preview-info-label">Kota</span>
                                <span class="pm-preview-info-val">{{ $mosque->city }}</span>
                            </div>
                            @endif
                            @if(!empty($mosque->imam_name))
                            <div class="pm-preview-info-item">
                                <span class="pm-preview-info-label">Imam</span>
                                <span class="pm-preview-info-val">{{ $mosque->imam_name }}</span>
                            </div>
                            @endif
                        </div>

                        @if(!empty($mosque->description))
                        <div class="pm-preview-desc">{{ $mosque->description }}</div>
                        @endif

                        @if(!empty($mosque->facilities))
                        <div class="pm-preview-section-title">Fasilitas</div>
                        <div class="pm-preview-tags">
                            @foreach($mosque->facilities as $f)
                                <span class="pm-preview-tag">{{ $f }}</span>
                            @endforeach
                        </div>
                        @endif

                        @if(!empty($mosque->programs))
                        <div class="pm-preview-section-title">Program Kegiatan</div>
                        <div class="pm-preview-tags">
                            @foreach($mosque->programs as $p)
                                <span class="pm-preview-tag green">{{ $p }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ===== FOOTER ACTIONS ===== --}}
                <div class="pm-footer" id="pmFooter">
                    <span class="pm-footer-status" id="pmStatus">Perubahan belum disimpan</span>
                    <div class="pm-footer-actions">
                        <button type="button" class="pm-btn-reset" id="pmReset">Reset</button>
                        <button type="submit" class="pm-btn-save">Simpan Perubahan</button>
                    </div>
                </div>

            </form>

        </main>
    </div>

    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

    <script>
        // ---- Sidebar toggle ----
        document.getElementById('baToggle').addEventListener('click', () => {
            document.getElementById('baSidebar').classList.toggle('collapsed');
            document.getElementById('adminBody').classList.toggle('sidebar-collapsed');
        });

        // ---- Tab switching ----
        const tabs    = document.querySelectorAll('.pm-tab');
        const panels  = document.querySelectorAll('.pm-tab-content');
        const footer  = document.getElementById('pmFooter');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));
                tab.classList.add('active');

                const target = document.getElementById('tab-' + tab.dataset.tab);
                if (target) target.classList.add('active');

                // Hide footer on preview tab
                if (tab.dataset.tab === 'preview') {
                    footer.style.display = 'none';
                } else {
                    footer.style.display = 'flex';
                }
            });
        });

        // ---- Dirty state tracking ----
        const form   = document.getElementById('pmForm');
        const status = document.getElementById('pmStatus');
        let isDirty  = false;

        form.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('change', () => {
                isDirty = true;
                status.textContent = 'Ada perubahan yang belum disimpan';
                status.classList.add('pm-footer-status--dirty');
            });
        });

        // ---- Reset button ----
        document.getElementById('pmReset').addEventListener('click', () => {
            if (confirm('Reset semua perubahan yang belum disimpan?')) {
                form.reset();
                isDirty = false;
                status.textContent = 'Perubahan belum disimpan';
                status.classList.remove('pm-footer-status--dirty');
            }
        });
    </script>
</body>
</html>
