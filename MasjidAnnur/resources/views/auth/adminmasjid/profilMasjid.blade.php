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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    {{-- Struktur & class disamakan persis dengan berandaAdmin.blade.php (prefix ba2-*)
         supaya mewarisi style dari berandaAdmin.css dengan benar. --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">

        {{-- Brand --}}
        <div class="ba2-brand">
            <div class="ba2-brand-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</div>
                <div class="ba2-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</div>
            </div>
            <button class="ba2-collapse-btn" id="ba2CollapseBtn" aria-label="Collapse">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="ba2-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                <span class="ba2-nav-badge">3</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="ba2-user">
            <div class="ba2-user-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 2) }}</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">{{ Auth::user()->name ?? 'Admin Masjid' }}</div>
                <div class="ba2-user-email">{{ Auth::user()->email ?? 'admin@baituldigital.id' }}</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

        {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Profil Masjid</div>
                <div class="ba2-page-sub">Kelola identitas, pengurus, lokasi, dan program masjid</div>
            </div>
            <div class="ba2-topbar-right">
                @if(!empty($mosque->slug))
                    <a href="{{ route('masjid.publik', $mosque->slug) }}" class="ba2-btn-back" target="_blank">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Publik
                    </a>
                @else
                    <a href="{{ url('/') }}" class="ba2-btn-back">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Publik
                    </a>
                @endif
                <button class="ba2-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="ba2-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="ba2-content pm-content">

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
                <button class="pm-tab active" data-tab="identitas" type="button">Identitas</button>
                <button class="pm-tab" data-tab="pengurus" type="button">Pengurus</button>
                <button class="pm-tab" data-tab="lokasi" type="button">Lokasi &amp; Kontak</button>
                <button class="pm-tab" data-tab="program" type="button">Program</button>
                <button class="pm-tab pm-tab-preview" data-tab="preview" type="button">
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

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
        // ---- Sidebar toggle (disamakan dengan berandaAdmin.blade.php) ----
        document.getElementById('ba2CollapseBtn').addEventListener('click', () => {
            document.getElementById('ba2Sidebar').classList.toggle('collapsed');
            document.getElementById('ba2Main').classList.toggle('expanded');
        });

        // ---- Tab switching ----
        const tabs   = document.querySelectorAll('.pm-tab');
        const panels = document.querySelectorAll('.pm-tab-content');
        const footer = document.getElementById('pmFooter');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));
                tab.classList.add('active');

                const target = document.getElementById('tab-' + tab.dataset.tab);
                if (target) target.classList.add('active');

                // Sembunyikan footer di tab preview
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