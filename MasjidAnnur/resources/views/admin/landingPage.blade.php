<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Landing Page - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
</head>
<body class="lp-body" id="lpBody">

    {{-- ===== SIDEBAR ===== --}}
    {{-- PENTING: id harus "lpSidebar" (huruf S besar) karena dipakai persis
         seperti ini oleh JavaScript di bawah: getElementById('lpSidebar') --}}
    <aside class="lp-sidebar" id="lpSidebar">

        <div class="lp-brand">
            <div class="lp-brand-avatar">AM</div>
            <div class="lp-brand-info">
                <span class="lp-brand-name">SIM Masjid</span>
                <span class="lp-brand-sub">Baitul Digital</span>
            </div>
            <button class="lp-sidebar-toggle" id="lpSidebarToggle" aria-label="Collapse sidebar" type="button">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <nav class="lp-nav">
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item">
                <span class="lp-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="lp-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item active">
                <span class="lp-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                    </svg>
                </span>
                <span class="lp-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item">
                <span class="lp-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Jadwal Shalat</span>
                <span class="lp-nav-soon">dev</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Pengumuman</span>
                <span class="lp-nav-badge">3</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Kegiatan &amp; Acara</span>
                <span class="lp-nav-soon">dev</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Donasi</span>
                <span class="lp-nav-soon">dev</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Data Jamaah</span>
                <span class="lp-nav-soon">dev</span>
            </a>
        </nav>

        <div class="lp-user">
            <div class="lp-user-avatar">AM</div>
            <div class="lp-user-info">
                <div class="lp-user-name">{{ auth()->user()->name ?? 'Admin Masjid' }}</div>
                <div class="lp-user-email">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="lp-main">

        <form action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if(session('success'))
            <div class="lp-alert">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="lp-alert" style="background:#fdecea;color:#b3261e;border-color:#f5c2c0;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                <a href="{{ route('masjid.publik', $mosque->slug) }}" class="lp-btn-back" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Preview
                </a>
                <button type="submit" class="lp-btn-save" id="lpSaveBtn">
                    Simpan Perubahan
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="lp-content">

            {{-- Status Bar --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
                <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:var(--text-mid);">
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ ($mosque->is_published ?? true) ? '#22c55e' : '#d1d5db' }};display:inline-block;"></span>
                    <span style="font-weight:600;">{{ ($mosque->is_published ?? true) ? 'Dipublikasikan' : 'Draft' }}</span>
                    <span style="color:var(--text-light);">
                        · Terakhir disimpan
                        {{ isset($mosque) && $mosque->updated_at ? $mosque->updated_at->diffForHumans() : '-' }}
                    </span>
                </div>
                <label style="display:flex;align-items:center;gap:8px;font-size:0.82rem;">
                    <span>Tampilkan ke publik</span>
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" id="lpPublish" name="is_published" value="1"
                           {{ ($mosque->is_published ?? true) ? 'checked' : '' }}>
                </label>
            </div>

            {{-- Tabs --}}
            <div class="lp-tabs" id="lpTabs">
                <button type="button" class="lp-tab active" data-tab="hero">Hero / Banner</button>
                <button type="button" class="lp-tab" data-tab="tentang">Tentang Masjid</button>
                <button type="button" class="lp-tab" data-tab="kontak">Kontak &amp; Sosial</button>
                <button type="button" class="lp-tab" data-tab="modul">Modul Aktif</button>
                <button type="button" class="lp-tab lp-tab-preview" data-tab="preview">Pratinjau</button>
            </div>

            {{-- ===== TAB: HERO ===== --}}
            <div class="lp-panel active" id="lpTab-hero">
                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Konten Hero / Banner Utama</div>

                    <div class="lp-field">
                        <label class="lp-label">Judul Utama</label>
                        <input class="lp-input" type="text" name="hero_title"
                               placeholder="cth. Selamat Datang di Masjid Al-Ikhlas"
                               value="{{ old('hero_title', $mosque->hero_title ?? '') }}">
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Nama Masjid (Arab) — opsional</label>
                        <input class="lp-input" type="text" name="arabic_name" dir="rtl"
                               placeholder="مسجد الرحمن"
                               value="{{ old('arabic_name', $mosque->arabic_name ?? '') }}">
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Sub-judul / Tagline</label>
                        <input class="lp-input" type="text" name="hero_subtitle"
                               placeholder="cth. Masjid Rahmatan Lil Alamin"
                               value="{{ old('hero_subtitle', $mosque->hero_subtitle ?? '') }}">
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Deskripsi Singkat</label>
                        <textarea class="lp-textarea" name="hero_desc" rows="3"
                                  placeholder="Ceritakan tentang masjid Anda dalam 1–2 kalimat...">{{ old('hero_desc', $mosque->hero_desc ?? '') }}</textarea>
                    </div>

                    <div class="lp-grid-2">
                        <div class="lp-field">
                            <label class="lp-label">Teks Tombol Utama</label>
                            <input class="lp-input" type="text" name="btn_primary"
                                   placeholder="cth. Donasi Sekarang"
                                   value="{{ old('btn_primary', $mosque->btn_primary ?? '') }}">
                        </div>
                        <div class="lp-field">
                            <label class="lp-label">Link Tombol Utama</label>
                            <input class="lp-input" type="url" name="btn_primary_url"
                                   placeholder="https://... atau #donasi" value="{{ old('btn_primary_url', $mosque->btn_primary_url ?? '') }}">
                        </div>
                    </div>

                    <div class="lp-grid-2">
                        <div class="lp-field">
                            <label class="lp-label">Teks Tombol Sekunder — opsional</label>
                            <input class="lp-input" type="text" name="btn_secondary"
                                   placeholder="cth. Program Kami"
                                   value="{{ old('btn_secondary', $mosque->btn_secondary ?? '') }}">
                        </div>
                        <div class="lp-field">
                            <label class="lp-label">Link Tombol Sekunder</label>
                            <input class="lp-input" type="url" name="btn_secondary_url"
                                   placeholder="https://... atau #profil" value="{{ old('btn_secondary_url', $mosque->btn_secondary_url ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Gambar &amp; Warna Hero</div>

                    <div class="lp-field">
                        <label class="lp-label">Gambar Latar — opsional</label>
                        @if(!empty($mosque->hero_image))
                            <img src="{{ asset('storage/'.$mosque->hero_image) }}" alt="Hero saat ini" style="max-height:100px;border-radius:8px;margin-bottom:8px;display:block;">
                        @endif
                        <input type="file" name="hero_image" accept="image/*" class="lp-input">
                        <span style="font-size:0.72rem;color:var(--text-light);">PNG, JPG, WebP · Maks. 2MB · Rekomendasi 1920×600</span>
                    </div>

                    <div class="lp-grid-2">
                        <div class="lp-field">
                            <label class="lp-label">Warna Latar Hero</label>
                            <div style="display:flex;gap:8px;">
                                <input type="color" name="hero_bg_color_picker" value="{{ old('hero_bg_color', $mosque->hero_bg_color ?? '#0e3320') }}">
                                <input class="lp-input" type="text" name="hero_bg_color" value="{{ old('hero_bg_color', $mosque->hero_bg_color ?? '#0e3320') }}" style="max-width:130px;">
                            </div>
                        </div>
                        <div class="lp-field">
                            <label class="lp-label">Warna Teks Hero</label>
                            <div style="display:flex;gap:8px;">
                                <input type="color" name="hero_text_color_picker" value="{{ old('hero_text_color', $mosque->hero_text_color ?? '#ffffff') }}">
                                <input class="lp-input" type="text" name="hero_text_color" value="{{ old('hero_text_color', $mosque->hero_text_color ?? '#ffffff') }}" style="max-width:130px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: TENTANG ===== --}}
            <div class="lp-panel" id="lpTab-tentang">
                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Tentang Masjid</div>

                    <div class="lp-field">
                        <label class="lp-label">Nama Masjid</label>
                        <input class="lp-input" type="text" name="about_name" value="{{ old('about_name', $mosque->about_name ?? '') }}">
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Imam Besar — opsional</label>
                        <input class="lp-input" type="text" name="imam_name"
                               placeholder="cth. KH. Ahmad Syafi'i"
                               value="{{ old('imam_name', $mosque->imam_name ?? '') }}">
                    </div>

                    <div class="lp-grid-2">
                        <div class="lp-field">
                            <label class="lp-label">Tahun Berdiri</label>
                            <input class="lp-input" type="text" name="about_founded" value="{{ old('about_founded', $mosque->about_founded ?? '') }}">
                        </div>
                        <div class="lp-field">
                            <label class="lp-label">Kapasitas Jamaah</label>
                            <input class="lp-input" type="text" name="about_capacity" value="{{ old('about_capacity', $mosque->about_capacity ?? '') }}">
                        </div>
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Sejarah Singkat</label>
                        <textarea class="lp-textarea" name="about_history" rows="4"
                                  placeholder="Ceritakan sejarah singkat berdirinya masjid...">{{ old('about_history', $mosque->about_history ?? '') }}</textarea>
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Visi &amp; Misi</label>
                        <textarea class="lp-textarea" name="about_vision" rows="3"
                                  placeholder="Visi dan misi masjid Anda...">{{ old('about_vision', $mosque->about_vision ?? '') }}</textarea>
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Foto Masjid — opsional</label>
                        @if(!empty($mosque->about_photo))
                            <img src="{{ asset('storage/'.$mosque->about_photo) }}" alt="Foto masjid saat ini" style="max-height:100px;border-radius:8px;margin-bottom:8px;display:block;">
                        @endif
                        <input type="file" name="about_photo" accept="image/*" class="lp-input">
                    </div>
                </div>
            </div>

            {{-- ===== TAB: KONTAK & SOSIAL ===== --}}
            <div class="lp-panel" id="lpTab-kontak">
                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Informasi Kontak</div>

                    <div class="lp-field">
                        <label class="lp-label">Alamat Lengkap</label>
                        <textarea class="lp-textarea" name="contact_address" rows="2">{{ old('contact_address', $mosque->contact_address ?? '') }}</textarea>
                    </div>

                    <div class="lp-grid-2">
                        <div class="lp-field">
                            <label class="lp-label">Nomor Telepon</label>
                            <input class="lp-input" type="tel" name="contact_phone" value="{{ old('contact_phone', $mosque->contact_phone ?? '') }}">
                        </div>
                        <div class="lp-field">
                            <label class="lp-label">Email</label>
                            <input class="lp-input" type="email" name="contact_email" value="{{ old('contact_email', $mosque->contact_email ?? '') }}">
                        </div>
                    </div>

                    <div class="lp-field">
                        <label class="lp-label">Link Google Maps — opsional</label>
                        <input class="lp-input" type="url" name="contact_maps"
                               placeholder="https://maps.google.com/..." value="{{ old('contact_maps', $mosque->contact_maps ?? '') }}">
                    </div>
                </div>

                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Media Sosial</div>

                    <div class="lp-field">
                        <label class="lp-label">Instagram</label>
                        <input class="lp-input" type="url" name="social_ig"
                               placeholder="https://instagram.com/masjidanda" value="{{ old('social_ig', $mosque->social_ig ?? '') }}">
                    </div>
                    <div class="lp-field">
                        <label class="lp-label">Facebook</label>
                        <input class="lp-input" type="url" name="social_fb"
                               placeholder="https://facebook.com/masjidanda" value="{{ old('social_fb', $mosque->social_fb ?? '') }}">
                    </div>
                    <div class="lp-field">
                        <label class="lp-label">YouTube</label>
                        <input class="lp-input" type="url" name="social_yt"
                               placeholder="https://youtube.com/@masjidanda" value="{{ old('social_yt', $mosque->social_yt ?? '') }}">
                    </div>
                    <div class="lp-field">
                        <label class="lp-label">WhatsApp</label>
                        <input class="lp-input" type="tel" name="social_wa"
                               placeholder="+62 812 0000 0000" value="{{ old('social_wa', $mosque->social_wa ?? '') }}">
                    </div>
                </div>
            </div>

            {{-- ===== TAB: MODUL ===== --}}
            <div class="lp-panel" id="lpTab-modul">
                <div class="lp-card">
                    <div class="lp-card-title"><span class="lp-card-bar"></span>Modul yang Ditampilkan</div>

                    @php
                        $activeModules = $mosque->active_modules ?? [];
                        $moduls = [
                            ['key' => 'pengumuman',    'name' => 'Pengumuman',       'desc' => 'Tampilkan pengumuman terbaru masjid (ticker info)'],
                            ['key' => 'jadwal_shalat', 'name' => 'Jadwal Shalat',    'desc' => 'Widget jadwal shalat hari ini'],
                            ['key' => 'kegiatan',      'name' => 'Kegiatan & Acara', 'desc' => 'Daftar acara dan kegiatan mendatang'],
                            ['key' => 'donasi',        'name' => 'Donasi Online',    'desc' => 'Tombol dan form donasi online'],
                            ['key' => 'data_jamaah',   'name' => 'Data Jamaah',      'desc' => 'Statistik dan info jamaah terdaftar'],
                            ['key' => 'peta_lokasi',   'name' => 'Peta Lokasi',      'desc' => 'Tautan Google Maps di bagian Hubungi Kami'],
                        ];
                    @endphp
                    @foreach($moduls as $mod)
                        @php $isChecked = $activeModules[$mod['key']] ?? true; @endphp
                        <div class="lp-fitur-row">
                            <div class="lp-fitur-fields">
                                <div style="font-weight:600;font-size:0.86rem;">{{ $mod['name'] }}</div>
                                <div style="font-size:0.78rem;color:var(--text-light);">{{ $mod['desc'] }}</div>
                            </div>
                            <label style="margin-top:14px;">
                                <input type="hidden" name="modul[{{ $mod['key'] }}]" value="0">
                                <input type="checkbox" name="modul[{{ $mod['key'] }}]" value="1"
                                       {{ $isChecked ? 'checked' : '' }}>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== TAB: PREVIEW ===== --}}
            <div class="lp-panel" id="lpTab-preview">
                <div class="lp-card">
                    <div class="lp-preview-inner">
                        <span class="lp-preview-emoji">🕌</span>
                        <span class="lp-preview-text">Simpan perubahan untuk melihat pratinjau</span>
                        <a href="{{ route('masjid.publik', $mosque->slug ?? '') }}" target="_blank" class="lp-preview-link">
                            Buka di tab baru ↗
                        </a>
                    </div>
                </div>
            </div>

        </main>
        </form>
    </div>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const lpToggle = document.getElementById('lpToggle');
        if (lpToggle) {
            lpToggle.addEventListener('click', () => {
                document.getElementById('lpSidebar')?.classList.toggle('collapsed');
                document.getElementById('lpBody')?.classList.toggle('lp-sidebar-collapsed');
            });
        }

        document.querySelectorAll('.lp-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.lp-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lp-panel').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById('lpTab-' + tab.dataset.tab)?.classList.add('active');
            });
        });

        document.querySelectorAll('input[type="color"]').forEach(colorInput => {
            const sibling = colorInput.parentElement.querySelector('input[type="text"]');
            if (sibling) {
                colorInput.addEventListener('input', () => { sibling.value = colorInput.value; });
                sibling.addEventListener('input', () => { colorInput.value = sibling.value; });
            }
        });
    });
    </script>
</body>
</html>