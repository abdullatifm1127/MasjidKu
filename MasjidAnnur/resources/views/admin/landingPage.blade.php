<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Landing Page - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landingPage.css') }}">
</head>
<body class="lp-page" id="lpBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba-sidebar" id="lpSidebar">
        <a href="{{ url('/') }}" class="ba-brand">
            <div class="ba-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
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

            <a href="{{ route('admin.landing-page') }}" class="ba-nav-item active">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Landing Page</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21"/>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Pengumuman</span>
                <span class="ba-nav-badge">3</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Kegiatan &amp; Acara</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75"/>
                    </svg>
                </span>
                <span class="ba-nav-label">Donasi</span>
                <span class="ba-nav-soon">dev</span>
            </a>

            <a href="#" class="ba-nav-item">
                <span class="ba-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"/>
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
    <div class="lp-main">

        {{-- Topbar --}}
        <header class="lp-topbar">
            <div class="lp-topbar-left">
                <button class="ba-toggle-btn" id="lpToggle" aria-label="Toggle sidebar">
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
                <a href="{{ url('/') }}" class="lp-btn-preview" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Preview
                </a>
                <button class="lp-btn-save" id="lpSaveBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="15" height="15">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="lp-content">

            {{-- Status Bar --}}
            <div class="lp-status-bar">
                <div class="lp-status-left">
                    <span class="lp-status-dot published"></span>
                    <span class="lp-status-label">Dipublikasikan</span>
                    <span class="lp-status-time">· Terakhir disimpan 5 menit lalu</span>
                </div>
                <div class="lp-status-right">
                    <label class="lp-toggle-published">
                        <span>Tampilkan ke publik</span>
                        <div class="lp-switch">
                            <input type="checkbox" id="lpPublish" checked>
                            <span class="lp-switch-track"></span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="lp-tabs" id="lpTabs">
                <button class="lp-tab active" data-tab="hero">Hero / Banner</button>
                <button class="lp-tab" data-tab="tentang">Tentang Masjid</button>
                <button class="lp-tab" data-tab="kontak">Kontak &amp; Sosial</button>
                <button class="lp-tab" data-tab="modul">Modul Aktif</button>
                <button class="lp-tab" data-tab="preview">Pratinjau</button>
            </div>

            {{-- ===== TAB: HERO ===== --}}
            <div class="lp-panel active" id="lpTab-hero">

                <div class="lp-card">
                    <div class="lp-card-head">
                        <div>
                            <div class="lp-card-title">Konten Hero / Banner Utama</div>
                            <div class="lp-card-desc">Bagian pertama yang dilihat pengunjung halaman masjid Anda</div>
                        </div>
                    </div>
                    <div class="lp-card-body">
                        <div class="lp-group">
                            <label>Judul Utama</label>
                            <input class="lp-input" type="text" name="hero_title"
                                   placeholder="cth. Selamat Datang di Masjid Al-Ikhlas"
                                   value="Selamat Datang di Masjid Annur">
                            <span class="lp-hint">Judul besar yang tampil di bagian atas halaman</span>
                        </div>
                        <div class="lp-group">
                            <label>Sub-judul / Tagline</label>
                            <input class="lp-input" type="text" name="hero_subtitle"
                                   placeholder="cth. Masjid Rahmatan Lil Alamin"
                                   value="Masjid Rahmatan Lil Alamin — Bekasi Selatan">
                        </div>
                        <div class="lp-group">
                            <label>Deskripsi Singkat</label>
                            <textarea class="lp-textarea" name="hero_desc" rows="3"
                                      placeholder="Ceritakan tentang masjid Anda dalam 1–2 kalimat...">Masjid Annur berdiri sejak 1985, melayani jamaah dengan program dakwah, pendidikan, dan sosial untuk kemakmuran umat.</textarea>
                        </div>
                        <div class="lp-grid-2">
                            <div class="lp-group">
                                <label>Teks Tombol Utama</label>
                                <input class="lp-input" type="text" name="btn_primary"
                                       placeholder="cth. Donasi Sekarang"
                                       value="Donasi Sekarang">
                            </div>
                            <div class="lp-group">
                                <label>Link Tombol Utama</label>
                                <input class="lp-input" type="url" name="btn_primary_url"
                                       placeholder="https://..." value="">
                            </div>
                        </div>
                        <div class="lp-grid-2">
                            <div class="lp-group">
                                <label>Teks Tombol Sekunder <span class="opt">(opsional)</span></label>
                                <input class="lp-input" type="text" name="btn_secondary"
                                       placeholder="cth. Program Kami"
                                       value="Program Kami">
                            </div>
                            <div class="lp-group">
                                <label>Link Tombol Sekunder</label>
                                <input class="lp-input" type="url" name="btn_secondary_url"
                                       placeholder="https://..." value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lp-card">
                    <div class="lp-card-head">
                        <div>
                            <div class="lp-card-title">Gambar &amp; Warna Hero</div>
                            <div class="lp-card-desc">Atur visual tampilan hero section</div>
                        </div>
                    </div>
                    <div class="lp-card-body">
                        <div class="lp-group">
                            <label>Gambar Latar <span class="opt">(opsional)</span></label>
                            <div class="lp-upload" id="lpUploadHero">
                                <div class="lp-upload-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" width="32" height="32">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>
                                </div>
                                <p>Seret gambar ke sini atau</p>
                                <span>klik untuk upload</span>
                                <p style="margin-top:4px;">PNG, JPG, WebP · Maks. 2MB · Rekomendasi 1920×600</p>
                                <input type="file" name="hero_image" accept="image/*" style="display:none" id="lpHeroFile">
                            </div>
                        </div>
                        <div class="lp-grid-2">
                            <div class="lp-group">
                                <label>Warna Latar Hero</label>
                                <div class="lp-color-row">
                                    <input type="color" class="lp-color-input" name="hero_bg_color" value="#0e3320">
                                    <input class="lp-input" type="text" name="hero_bg_hex" value="#0e3320" style="max-width:130px;">
                                </div>
                            </div>
                            <div class="lp-group">
                                <label>Warna Teks Hero</label>
                                <div class="lp-color-row">
                                    <input type="color" class="lp-color-input" name="hero_text_color" value="#ffffff">
                                    <input class="lp-input" type="text" name="hero_text_hex" value="#ffffff" style="max-width:130px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== TAB: TENTANG ===== --}}
            <div class="lp-panel" id="lpTab-tentang">
                <div class="lp-card">
                    <div class="lp-card-head">
                        <div>
                            <div class="lp-card-title">Tentang Masjid</div>
                            <div class="lp-card-desc">Informasi yang tampil di bagian "Tentang Kami"</div>
                        </div>
                    </div>
                    <div class="lp-card-body">
                        <div class="lp-group">
                            <label>Nama Masjid</label>
                            <input class="lp-input" type="text" name="about_name" value="Masjid Annur">
                        </div>
                        <div class="lp-grid-2">
                            <div class="lp-group">
                                <label>Tahun Berdiri</label>
                                <input class="lp-input" type="number" name="about_founded" value="1985">
                            </div>
                            <div class="lp-group">
                                <label>Kapasitas Jamaah</label>
                                <input class="lp-input" type="text" name="about_capacity" value="2.000 orang">
                            </div>
                        </div>
                        <div class="lp-group">
                            <label>Sejarah Singkat</label>
                            <textarea class="lp-textarea" name="about_history" rows="4"
                                      placeholder="Ceritakan sejarah singkat berdirinya masjid...">Masjid Annur didirikan pada tahun 1985 oleh warga Kelurahan Margahayu sebagai pusat ibadah dan kegiatan keagamaan masyarakat setempat...</textarea>
                        </div>
                        <div class="lp-group">
                            <label>Visi &amp; Misi</label>
                            <textarea class="lp-textarea" name="about_vision" rows="3"
                                      placeholder="Visi dan misi masjid Anda...">Menjadi pusat peradaban Islam yang rahmatan lil alamin, mewujudkan jamaah yang bertaqwa dan berdaya.</textarea>
                        </div>
                        <div class="lp-group">
                            <label>Foto Masjid <span class="opt">(opsional)</span></label>
                            <div class="lp-upload">
                                <div class="lp-upload-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" width="28" height="28">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                                    </svg>
                                </div>
                                <p>Upload foto eksterior atau interior masjid</p>
                                <span>klik untuk upload</span>
                                <input type="file" name="about_photo" accept="image/*" style="display:none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: KONTAK & SOSIAL ===== --}}
            <div class="lp-panel" id="lpTab-kontak">
                <div class="lp-card">
                    <div class="lp-card-head">
                        <div>
                            <div class="lp-card-title">Informasi Kontak</div>
                            <div class="lp-card-desc">Alamat, telepon, dan email yang tampil di halaman</div>
                        </div>
                    </div>
                    <div class="lp-card-body">
                        <div class="lp-group">
                            <label>Alamat Lengkap</label>
                            <textarea class="lp-textarea" name="contact_address" rows="2">Jl. Margahayu Raya No. 12, Bekasi Selatan, Jawa Barat 17141</textarea>
                        </div>
                        <div class="lp-grid-2">
                            <div class="lp-group">
                                <label>Nomor Telepon</label>
                                <input class="lp-input" type="tel" name="contact_phone" value="+62 21 1234567">
                            </div>
                            <div class="lp-group">
                                <label>Email</label>
                                <input class="lp-input" type="email" name="contact_email" value="info@masjidannur.id">
                            </div>
                        </div>
                        <div class="lp-group">
                            <label>Link Google Maps <span class="opt">(opsional)</span></label>
                            <input class="lp-input" type="url" name="contact_maps"
                                   placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>

                <div class="lp-card">
                    <div class="lp-card-head">
                        <div>
                            <div class="lp-card-title">Media Sosial</div>
                            <div class="lp-card-desc">Link akun sosial media masjid</div>
                        </div>
                    </div>
                    <div class="lp-card-body">
                        <div class="lp-social-item">
                            <div class="lp-social-icon ig">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                </svg>
                            </div>
                            <span class="lp-social-label">Instagram</span>
                            <input class="lp-input" type="url" name="social_ig"
                                   placeholder="https://instagram.com/masjidanda">
                        </div>
                        <div class="lp-social-item">
                            <div class="lp-social-icon fb">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <span class="lp-social-label">Facebook</span>
                            <input class="lp-input" type="url" name="social_fb"
                                   placeholder="https://facebook.com/masjidanda">
                        </div>
                        <div class="lp-social-item">
                            <div class="lp-social-icon yt">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </div>
                            <span class="lp-social-label">YouTube</span>
                            <input class="lp-input" type="url" name="social_yt"
                                   placeholder="https://youtube.com/@masjidanda">
                        </div>
                        <div class="lp-social-item">
                            <div class="lp-social-icon wa">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <span class="lp-social-label">WhatsApp</span>
                            <input class="lp-input" type="tel" name="social_wa"
                                   placeholder="+62 812 0000 0000">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: MODUL ===== --}}
            <div class="lp-panel" id="lpTab-modul">
                <div class="lp-card">
                    <div class="lp-card-head">
                        <div>
                            <div class="lp-card-title">Modul yang Ditampilkan</div>
                            <div class="lp-card-desc">Pilih bagian mana yang tampil di halaman publik masjid</div>
                        </div>
                    </div>
                    <div class="lp-card-body" style="padding:0;">
                        @php
                            $moduls = [
                                ['icon' => 'M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09', 'name' => 'Pengumuman', 'desc' => 'Tampilkan pengumuman terbaru masjid', 'checked' => true],
                                ['icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z', 'name' => 'Jadwal Shalat', 'desc' => 'Widget jadwal shalat hari ini', 'checked' => true],
                                ['icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25', 'name' => 'Kegiatan & Acara', 'desc' => 'Daftar acara dan kegiatan mendatang', 'checked' => false],
                                ['icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75', 'name' => 'Donasi Online', 'desc' => 'Tombol dan form donasi online', 'checked' => false],
                                ['icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z', 'name' => 'Data Jamaah', 'desc' => 'Statistik dan info jamaah terdaftar', 'checked' => false],
                                ['icon' => 'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z', 'name' => 'Peta Lokasi', 'desc' => 'Tampilkan peta Google Maps masjid', 'checked' => true],
                            ];
                        @endphp
                        @foreach($moduls as $mod)
                            <div class="lp-modul-item">
                                <div class="lp-modul-left">
                                    <div class="lp-modul-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.8" stroke="currentColor" width="18" height="18">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $mod['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="lp-modul-name">{{ $mod['name'] }}</div>
                                        <div class="lp-modul-desc">{{ $mod['desc'] }}</div>
                                    </div>
                                </div>
                                <div class="lp-switch" style="flex-shrink:0;">
                                    <input type="checkbox" name="modul_{{ $loop->index }}"
                                           {{ $mod['checked'] ? 'checked' : '' }}>
                                    <span class="lp-switch-track"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== TAB: PREVIEW ===== --}}
            <div class="lp-panel" id="lpTab-preview">
                <div class="lp-preview-wrap">
                    <div class="lp-preview-toolbar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" width="16" height="16" style="color:#9ca3af">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/>
                        </svg>
                        <span class="lp-preview-url">http://127.0.0.1:8000/masjid/annur</span>
                        <a href="{{ url('/') }}" target="_blank" style="font-size:0.78rem; color:#1a6640; font-weight:600; text-decoration:none; flex-shrink:0;">
                            Buka di tab baru ↗
                        </a>
                    </div>
                    <div class="lp-preview-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" width="48" height="48">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p style="font-size:0.9rem; font-weight:600;">Simpan perubahan untuk melihat pratinjau</p>
                        <p style="font-size:0.8rem;">Atau klik "Buka di tab baru" untuk melihat halaman publik</p>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <button class="help-fab" aria-label="Bantuan">?</button>

    <script>
        /* Sidebar toggle */
        document.getElementById('lpToggle').addEventListener('click', () => {
            document.getElementById('lpSidebar').classList.toggle('collapsed');
            document.getElementById('lpBody').classList.toggle('sidebar-collapsed');
        });

        /* Tab switching */
        document.querySelectorAll('.lp-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.lp-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lp-panel').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                const panelId = 'lpTab-' + tab.dataset.tab;
                document.getElementById(panelId).classList.add('active');
            });
        });

        /* Upload click */
        document.getElementById('lpUploadHero').addEventListener('click', () => {
            document.getElementById('lpHeroFile').click();
        });

        /* Save button feedback */
        document.getElementById('lpSaveBtn').addEventListener('click', function () {
            this.textContent = 'Menyimpan...';
            setTimeout(() => {
                this.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="15" height="15">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg> Tersimpan!`;
                setTimeout(() => {
                    this.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" width="15" height="15">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg> Simpan Perubahan`;
                }, 2000);
            }, 800);
        });

        /* Color input sync */
        document.querySelectorAll('input[type="color"]').forEach(colorInput => {
            const sibling = colorInput.nextElementSibling;
            if (sibling && sibling.type === 'text') {
                colorInput.addEventListener('input', () => { sibling.value = colorInput.value; });
                sibling.addEventListener('input', () => { colorInput.value = sibling.value; });
            }
        });
    </script>
</body>
</html>
