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
        </div>

        {{-- Nav --}}
        <nav class="ba2-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="ba2-nav-item {{ request()->routeIs('admin.landing-page*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="ba2-nav-item {{ request()->routeIs('admin.profil-masjid*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>

            <a href="{{ route('admin.jadwal-sholat') }}" class="ba2-nav-item {{ request()->routeIs('admin.jadwal-sholat*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
            </a>

            {{-- MENU PENGUMUMAN DINAMIS --}}
            @php
                $pengumumanAktif = isset($mosque)
                    ? \App\Models\Pengumuman::where('mosque_id', $mosque->id)->active()->count()
                    : 0;
            @endphp
            <a href="{{ route('admin.pengumuman') }}" class="ba2-nav-item {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                @if($pengumumanAktif > 0)
                    <span class="ba2-nav-badge" style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px; font-weight: bold;">{{ $pengumumanAktif }}</span>
                @endif
            </a>

            <a href="{{ route('admin.acara') }}" class="ba2-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
            </a>

            {{-- MENU DONASI DINAMIS BERDASARKAN PAKET MASJID --}}
            @if(isset($mosque) && $mosque->package_type === 'free')
                <a href="{{ route('masjid.perpanjangan.create') }}" class="ba2-nav-item {{ request()->routeIs('masjid.perpanjangan*') ? 'active' : '' }}" style="opacity: 0.8;" title="Upgrade paket untuk membuka fitur donasi">
                    <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="ba2-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #e74c3c; color: white;">Locked</span>
                </a>
            @else
                <a href="{{ route('admin.donasi') }}" class="ba2-nav-item {{ request()->routeIs('admin.donasi*') ? 'active' : '' }}">
                    <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="ba2-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #27ae60; color: white;">Aktif</span>
                </a>
            @endif

            <a href="{{ route('admin.jamaah') }}" class="ba2-nav-item {{ request()->routeIs('admin.jamaah*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
            </a>

            <a href="{{ route('admin.tema') }}" class="ba2-nav-item {{ request()->routeIs('admin.tema*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-palette"></i></span>
                <span class="ba2-nav-label">Tema Tampilan</span>
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

            @php
                // Hitung kelengkapan profil (untuk progress di hero)
                $cekField = ['mosque_name','tagline','founded','capacity','description','about_vision',
                             'imam_name','chairman_name','address','kelurahan','kecamatan','city','province','phone','email'];
                $terisi = 0;
                foreach ($cekField as $f) {
                    if (!empty($mosque->$f ?? null)) $terisi++;
                }
                if (!empty($mosque->photo ?? null)) { $terisi++; }
                $totalCek   = count($cekField) + 1;
                $kelengkapan = (int) round($terisi / $totalCek * 100);

                // Helper URL foto
                $fotoUrl = function ($path) {
                    if (empty($path)) return null;
                    return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])
                        ? $path
                        : asset('storage/' . ltrim($path, '/'));
                };
                $fotoUtama = $fotoUrl($mosque->photo ?? null);
                $fotoKedua = $fotoUrl($mosque->photo_secondary ?? null);
            @endphp

            {{-- Alert sukses / error --}}
            @if(session('success'))
                <div class="pm-alert pm-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="pm-alert pm-alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Terdapat kesalahan. Periksa kembali isian di bawah.
                </div>
            @endif

            {{-- Hero ringkas --}}
            <div class="pm-hero">
                <div class="pm-hero-icon">
                    <i class="fa-solid fa-mosque" style="font-size:24px;"></i>
                </div>
                <div class="pm-hero-text">
                    <div class="pm-hero-name">{{ $mosque->mosque_name ?? 'Nama Masjid' }}</div>
                    <div class="pm-hero-sub">
                        {{ collect([$mosque->city ?? null, $mosque->province ?? null])->filter()->implode(', ') ?: 'Lengkapi profil masjid Anda' }}
                    </div>
                </div>
                <div class="pm-hero-progress">
                    <div class="pm-hero-progress-top">
                        <span>Kelengkapan profil</span>
                        <strong>{{ $kelengkapan }}%</strong>
                    </div>
                    <div class="pm-hero-bar"><span style="width: {{ $kelengkapan }}%"></span></div>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="pm-tabs" role="tablist">
                <button class="pm-tab active" data-tab="identitas" type="button" role="tab"><i class="fa-solid fa-id-card"></i> Identitas</button>
                <button class="pm-tab" data-tab="pengurus" type="button" role="tab"><i class="fa-solid fa-user-tie"></i> Pengurus</button>
                <button class="pm-tab" data-tab="lokasi" type="button" role="tab"><i class="fa-solid fa-location-dot"></i> Lokasi &amp; Kontak</button>
                <button class="pm-tab" data-tab="program" type="button" role="tab"><i class="fa-solid fa-list-check"></i> Program</button>
                <button class="pm-tab pm-tab-preview" data-tab="preview" type="button" role="tab"><i class="fa-solid fa-eye"></i> Preview</button>
            </div>

            <form id="pmForm" method="POST" action="{{ route('admin.profil-masjid.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ===== TAB: IDENTITAS ===== --}}
                <div class="pm-tab-content active" id="tab-identitas">

                    {{-- Foto --}}
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-title-icon"><i class="fa-solid fa-image"></i></span>
                            Foto masjid
                        </div>
                        <p class="pm-section-desc">Foto ini tampil di halaman publik. Gunakan foto landscape yang terang dan jelas.</p>

                        <div class="pm-photo-grid">
                            @php
                                $photoFields = [
                                    ['name' => 'photo',           'title' => 'Foto utama',     'tag' => 'Section Profil',   'alt' => false, 'url' => $fotoUtama, 'hint' => 'Ditampilkan di section Profil pada Landing Page.'],
                                    ['name' => 'photo_secondary', 'title' => 'Foto pendukung', 'tag' => 'Kotak kanan',      'alt' => true,  'url' => $fotoKedua, 'hint' => 'Ditampilkan di kotak sebelah kanan Landing Page.'],
                                ];
                            @endphp

                            @foreach($photoFields as $pf)
                                <div class="pm-photo-item">
                                    <div class="pm-photo-label">
                                        {{ $pf['title'] }}
                                        <span class="pm-photo-tag {{ $pf['alt'] ? 'alt' : '' }}">{{ $pf['tag'] }}</span>
                                    </div>

                                    <label class="pm-dropzone {{ $pf['url'] ? 'has-image' : '' }}"
                                           data-dropzone data-original="{{ $pf['url'] }}">
                                        <input type="file" id="{{ $pf['name'] }}" name="{{ $pf['name'] }}"
                                               accept="image/png,image/jpeg,image/webp,image/avif">

                                        <div class="pm-dropzone-empty">
                                            <div class="pm-dropzone-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                            <div class="pm-dropzone-title">Tarik foto ke sini atau <u>pilih dari perangkat</u></div>
                                            <div class="pm-dropzone-note">PNG, JPG, WebP, AVIF · maksimal 2 MB</div>
                                        </div>

                                        <div class="pm-dropzone-preview">
                                            <img src="{{ $pf['url'] }}" alt="Pratinjau {{ strtolower($pf['title']) }}" data-preview>
                                            <span class="pm-photo-badge">Foto baru</span>
                                            <div class="pm-dropzone-meta">
                                                <div style="min-width:0;">
                                                    <div class="pm-dropzone-filename" data-filename>{{ $pf['url'] ? 'Foto saat ini' : '' }}</div>
                                                    <div class="pm-dropzone-filesize" data-filesize></div>
                                                </div>
                                                <div class="pm-dropzone-actions">
                                                    <button type="button" class="pm-photo-btn" data-change><i class="fa-solid fa-arrows-rotate"></i> Ganti</button>
                                                    <button type="button" class="pm-photo-btn danger" data-clear hidden><i class="fa-solid fa-xmark"></i> Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <div class="pm-photo-error" data-error></div>
                                    <span class="pm-hint" style="display:block;margin-top:8px;">{{ $pf['hint'] }}</span>
                                    @error($pf['name'])
                                        <span class="pm-field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Identitas --}}
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-title-icon"><i class="fa-solid fa-mosque"></i></span>
                            Identitas masjid
                        </div>
                        <p class="pm-section-desc">Nama, slogan, dan cerita singkat tentang masjid.</p>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="mosque_name">Nama masjid <span class="pm-required">*</span></label>
                                <input type="text" id="mosque_name" name="mosque_name"
                                       class="pm-input @error('mosque_name') pm-input-error @enderror"
                                       value="{{ old('mosque_name', $mosque->mosque_name ?? '') }}"
                                       placeholder="Nama masjid" required>
                                @error('mosque_name')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="arabic_name">Nama Arab</label>
                                <input type="text" id="arabic_name" name="arabic_name"
                                       class="pm-input pm-input-rtl"
                                       value="{{ old('arabic_name', $mosque->arabic_name ?? '') }}"
                                       placeholder="الاسم بالعربية" dir="rtl">
                                <span class="pm-hint">Nama ini juga otomatis tampil di Hero Landing Page.</span>
                            </div>
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="tagline">Tagline / slogan</label>
                            <input type="text" id="tagline" name="tagline" class="pm-input"
                                   value="{{ old('tagline', $mosque->tagline ?? '') }}"
                                   placeholder="Slogan atau tagline masjid">
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="founded">Tahun berdiri</label>
                                <input type="number" id="founded" name="founded" class="pm-input"
                                       value="{{ old('founded', $mosque->founded ?? '') }}"
                                       placeholder="Contoh: 1987" min="1000" max="{{ date('Y') }}">
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="capacity">Kapasitas jamaah</label>
                                <input type="text" id="capacity" name="capacity" class="pm-input"
                                       value="{{ old('capacity', $mosque->capacity ?? '') }}"
                                       placeholder="Contoh: 2.500 jamaah">
                            </div>
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="description">Deskripsi / sejarah singkat</label>
                            <textarea id="description" name="description" class="pm-textarea" rows="4"
                                      placeholder="Ceritakan sedikit tentang masjid ini...">{{ old('description', $mosque->description ?? '') }}</textarea>
                        </div>

                        <div class="pm-field">
                            <label class="pm-label" for="vision">Visi &amp; misi</label>
                            <textarea id="vision" name="vision" class="pm-textarea" rows="3"
                                      placeholder="Visi dan misi masjid Anda...">{{ old('vision', $mosque->about_vision ?? '') }}</textarea>
                        </div>
                    </div>
                </div><!-- /#tab-identitas -->

                {{-- ===== TAB: PENGURUS ===== --}}
                <div class="pm-tab-content" id="tab-pengurus">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-title-icon"><i class="fa-solid fa-user-tie"></i></span>
                            Struktur pengurus
                        </div>
                        <p class="pm-section-desc">Data imam, ketua DKM, dan pengurus inti.</p>

                        <div class="pm-field">
                            <label class="pm-label" for="organization_name">Nama organisasi / yayasan</label>
                            <input type="text" id="organization_name" name="organization_name" class="pm-input"
                                   value="{{ old('organization_name', $mosque->organization_name ?? '') }}"
                                   placeholder="Nama DKM / Yayasan">
                        </div>

                        <div class="pm-divider-label">Imam / Khatib</div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="imam_name">Nama imam <span class="pm-required">*</span></label>
                                <input type="text" id="imam_name" name="imam_name"
                                       class="pm-input @error('imam_name') pm-input-error @enderror"
                                       value="{{ old('imam_name', $mosque->imam_name ?? '') }}"
                                       placeholder="Nama imam masjid">
                                @error('imam_name')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="imam_phone">No. telepon imam</label>
                                <input type="text" id="imam_phone" name="imam_phone" class="pm-input"
                                       value="{{ old('imam_phone', $mosque->imam_phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="pm-divider-label">Ketua DKM</div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="chairman_name">Nama ketua <span class="pm-required">*</span></label>
                                <input type="text" id="chairman_name" name="chairman_name"
                                       class="pm-input @error('chairman_name') pm-input-error @enderror"
                                       value="{{ old('chairman_name', $mosque->chairman_name ?? '') }}"
                                       placeholder="Nama ketua DKM">
                                @error('chairman_name')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="chairman_phone">No. telepon ketua</label>
                                <input type="text" id="chairman_phone" name="chairman_phone" class="pm-input"
                                       value="{{ old('chairman_phone', $mosque->chairman_phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="secretary_name">Nama sekretaris</label>
                                <input type="text" id="secretary_name" name="secretary_name" class="pm-input"
                                       value="{{ old('secretary_name', $mosque->secretary_name ?? '') }}"
                                       placeholder="Nama sekretaris">
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="treasurer_name">Nama bendahara</label>
                                <input type="text" id="treasurer_name" name="treasurer_name" class="pm-input"
                                       value="{{ old('treasurer_name', $mosque->treasurer_name ?? '') }}"
                                       placeholder="Nama bendahara">
                            </div>
                        </div>
                    </div>
                </div><!-- /#tab-pengurus -->

                {{-- ===== TAB: LOKASI & KONTAK ===== --}}
                <div class="pm-tab-content" id="tab-lokasi">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-title-icon"><i class="fa-solid fa-location-dot"></i></span>
                            Lokasi
                        </div>
                        <p class="pm-section-desc">Alamat lengkap supaya jamaah mudah menemukan masjid.</p>

                        <div class="pm-field">
                            <label class="pm-label" for="address">Alamat lengkap <span class="pm-required">*</span></label>
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
                                <label class="pm-label" for="kelurahan">Kelurahan <span class="pm-required">*</span></label>
                                <input type="text" id="kelurahan" name="kelurahan"
                                       class="pm-input @error('kelurahan') pm-input-error @enderror"
                                       value="{{ old('kelurahan', $mosque->kelurahan ?? '') }}"
                                       placeholder="Nama kelurahan">
                                @error('kelurahan')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="kecamatan">Kecamatan <span class="pm-required">*</span></label>
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
                                <label class="pm-label" for="city">Kota / kabupaten <span class="pm-required">*</span></label>
                                <input type="text" id="city" name="city"
                                       class="pm-input @error('city') pm-input-error @enderror"
                                       value="{{ old('city', $mosque->city ?? '') }}"
                                       placeholder="Nama kota">
                                @error('city')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="province">Provinsi <span class="pm-required">*</span></label>
                                <input type="text" id="province" name="province"
                                       class="pm-input @error('province') pm-input-error @enderror"
                                       value="{{ old('province', $mosque->province ?? '') }}"
                                       placeholder="Nama provinsi">
                                @error('province')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="postal_code">Kode pos</label>
                                <input type="text" id="postal_code" name="postal_code" class="pm-input"
                                       value="{{ old('postal_code', $mosque->postal_code ?? '') }}"
                                       placeholder="12345">
                            </div>
                        </div>
                    </div>

                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-title-icon"><i class="fa-solid fa-phone"></i></span>
                            Kontak
                        </div>
                        <p class="pm-section-desc">Nomor dan email yang bisa dihubungi jamaah.</p>

                        <div class="pm-grid-2">
                            <div class="pm-field">
                                <label class="pm-label" for="phone">No. telepon / WA <span class="pm-required">*</span></label>
                                <input type="text" id="phone" name="phone"
                                       class="pm-input @error('phone') pm-input-error @enderror"
                                       value="{{ old('phone', $mosque->phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pm-field">
                                <label class="pm-label" for="email">Email <span class="pm-required">*</span></label>
                                <input type="email" id="email" name="email"
                                       class="pm-input @error('email') pm-input-error @enderror"
                                       value="{{ old('email', $mosque->email ?? '') }}"
                                       placeholder="masjid@email.com">
                                @error('email')
                                    <span class="pm-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div><!-- /#tab-lokasi -->

                {{-- ===== TAB: PROGRAM ===== --}}
                <div class="pm-tab-content" id="tab-program">
                    <div class="pm-section">
                        <div class="pm-section-title">
                            <span class="pm-section-title-icon"><i class="fa-solid fa-building-columns"></i></span>
                            Fasilitas
                        </div>
                        <p class="pm-section-desc">Pilih fasilitas yang tersedia di masjid.</p>

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
                            <span class="pm-section-title-icon"><i class="fa-solid fa-book-quran"></i></span>
                            Program kegiatan
                        </div>
                        <p class="pm-section-desc">Pilih kegiatan rutin yang diadakan masjid.</p>

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
                </div><!-- /#tab-program -->

                {{-- ===== TAB: PREVIEW ===== --}}
                <div class="pm-tab-content" id="tab-preview">
                    <div class="pm-preview-card">
                        @if($fotoUtama)
                            <div class="pm-preview-cover"><img src="{{ $fotoUtama }}" alt="Foto {{ $mosque->mosque_name ?? 'masjid' }}"></div>
                        @endif

                        <div class="pm-preview-body">
                            <div class="pm-preview-header">
                                <div class="pm-preview-mosque-icon">
                                    <i class="fa-solid fa-mosque" style="font-size:26px;"></i>
                                </div>
                                <div>
                                    <div class="pm-preview-mosque-name" id="prev-name">{{ $mosque->mosque_name ?? 'Nama Masjid' }}</div>
                                    @if(!empty($mosque->arabic_name))
                                        <div class="pm-preview-arabic" id="prev-arabic">{{ $mosque->arabic_name }}</div>
                                    @endif
                                    @if(!empty($mosque->tagline))
                                        <div class="pm-preview-tagline" id="prev-tagline">{{ $mosque->tagline }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="pm-preview-grid">
                                @if(!empty($mosque->founded))
                                    <div class="pm-preview-info-item">
                                        <span class="pm-preview-info-label">Tahun berdiri</span>
                                        <span class="pm-preview-info-val">{{ $mosque->founded }}</span>
                                    </div>
                                @endif
                                @if(!empty($mosque->capacity))
                                    <div class="pm-preview-info-item">
                                        <span class="pm-preview-info-label">Kapasitas jamaah</span>
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
                                <div class="pm-preview-section-title">Program kegiatan</div>
                                <div class="pm-preview-tags">
                                    @foreach($mosque->programs as $p)
                                        <span class="pm-preview-tag green">{{ $p }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div><!-- /.pm-preview-card -->
                </div><!-- /#tab-preview -->

                {{-- ===== FOOTER ACTIONS (satu saja) ===== --}}
                <div class="pm-footer" id="pmFooter">
                    <span class="pm-footer-status" id="pmStatus">Belum ada perubahan</span>
                    <div class="pm-footer-actions">
                        <button type="button" class="pm-btn-reset" id="pmReset">Reset</button>
                        <button type="submit" class="pm-btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan perubahan</button>
                    </div>
                </div>

            </form>

        </main>
    </div>
    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
    (function () {
        const MAX_SIZE = 2 * 1024 * 1024; // 2 MB
        const ALLOWED  = ['image/png', 'image/jpeg', 'image/webp', 'image/avif'];

        const tabs   = document.querySelectorAll('.pm-tab');
        const panels = document.querySelectorAll('.pm-tab-content');
        const footer = document.getElementById('pmFooter');
        const form   = document.getElementById('pmForm');
        const status = document.getElementById('pmStatus');

        /* ---------- Tabs ---------- */
        function showTab(name, pushHash) {
            const target = document.getElementById('tab-' + name);
            if (!target) return;
            tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
            panels.forEach(p => p.classList.toggle('active', p === target));
            footer.classList.toggle('preview-hidden', name === 'preview');
            if (pushHash) history.replaceState(null, '', '#' + name);
        }
        tabs.forEach(t => t.addEventListener('click', () => showTab(t.dataset.tab, true)));
        const initial = location.hash.replace('#', '');
        if (initial && document.getElementById('tab-' + initial)) showTab(initial, false);

        /* ---------- Dirty state ---------- */
        function markDirty() {
            status.textContent = 'Ada perubahan yang belum disimpan';
            status.classList.add('pm-footer-status--dirty');
        }
        function markClean() {
            status.textContent = 'Belum ada perubahan';
            status.classList.remove('pm-footer-status--dirty');
        }
        form.addEventListener('input',  markDirty);
        form.addEventListener('change', markDirty);

        /* ---------- Dropzone foto ---------- */
        function formatSize(bytes) {
            return bytes < 1024 * 1024
                ? Math.round(bytes / 1024) + ' KB'
                : (bytes / 1024 / 1024).toFixed(1) + ' MB';
        }

        document.querySelectorAll('[data-dropzone]').forEach(zone => {
            const input    = zone.querySelector('input[type="file"]');
            const img      = zone.querySelector('[data-preview]');
            const fname    = zone.querySelector('[data-filename]');
            const fsize    = zone.querySelector('[data-filesize]');
            const btnClear = zone.querySelector('[data-clear]');
            const btnChg   = zone.querySelector('[data-change]');
            const errBox   = zone.parentElement.querySelector('[data-error]');
            const original = zone.dataset.original || '';

            function showError(msg) {
                errBox.textContent = msg;
                errBox.classList.toggle('show', !!msg);
            }

            function restoreOriginal() {
                input.value = '';
                zone.classList.remove('has-new');
                btnClear.hidden = true;
                fsize.textContent = '';
                if (original) {
                    img.src = original;
                    fname.textContent = 'Foto saat ini';
                    zone.classList.add('has-image');
                } else {
                    img.removeAttribute('src');
                    fname.textContent = '';
                    zone.classList.remove('has-image');
                }
            }

            function handleFile(file) {
                showError('');
                if (!file) return;
                if (!ALLOWED.includes(file.type)) {
                    input.value = '';
                    return showError('Format tidak didukung. Gunakan PNG, JPG, WebP, atau AVIF.');
                }
                if (file.size > MAX_SIZE) {
                    input.value = '';
                    return showError('Ukuran ' + formatSize(file.size) + ' melebihi batas 2 MB. Kompres fotonya lalu coba lagi.');
                }
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    fname.textContent = file.name;
                    fsize.textContent = formatSize(file.size);
                    zone.classList.add('has-image', 'has-new');
                    btnClear.hidden = false;
                };
                reader.readAsDataURL(file);
                markDirty();
            }

            input.addEventListener('change', () => handleFile(input.files[0]));

            // Tombol "Ganti" membuka dialog pilih file
            btnChg.addEventListener('click', e => {
                e.preventDefault(); e.stopPropagation();
                input.style.pointerEvents = 'auto';
                input.click();
                input.style.pointerEvents = '';
            });
            // Tombol "Batal" mengembalikan ke foto sebelumnya
            btnClear.addEventListener('click', e => {
                e.preventDefault(); e.stopPropagation();
                restoreOriginal();
                showError('');
            });

            // Drag & drop
            ['dragenter', 'dragover'].forEach(ev =>
                zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('is-drag'); }));
            ['dragleave', 'drop'].forEach(ev =>
                zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('is-drag'); }));
            zone.addEventListener('drop', e => {
                const file = e.dataTransfer.files && e.dataTransfer.files[0];
                if (!file) return;
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                handleFile(file);
            });

            zone._restore = () => { restoreOriginal(); showError(''); };
        });

        /* ---------- Reset ---------- */
        document.getElementById('pmReset').addEventListener('click', () => {
            if (!confirm('Reset semua perubahan yang belum disimpan?')) return;
            form.reset();
            document.querySelectorAll('[data-dropzone]').forEach(z => z._restore && z._restore());
            markClean();
        });

        /* ---------- Peringatan keluar halaman ---------- */
        let submitting = false;
        form.addEventListener('submit', () => { submitting = true; });
        window.addEventListener('beforeunload', e => {
            if (!submitting && status.classList.contains('pm-footer-status--dirty')) {
                e.preventDefault(); e.returnValue = '';
            }
        });
    })();
    </script>
</body>
</html>