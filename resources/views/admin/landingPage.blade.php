<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Landing Page - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
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
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="lp-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item {{ request()->routeIs('admin.landing-page') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="lp-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item {{ request()->routeIs('admin.profil-masjid') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>

            <a href="{{ route('admin.jadwal-sholat') }}" class="lp-nav-item {{ request()->routeIs('admin.jadwal-sholat') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="lp-nav-label">Jadwal Shalat</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="lp-nav-label">Pengumuman</span>
                <span class="lp-nav-badge">3</span>
            </a>

           <a href="{{ route('admin.acara') }}" class="lp-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="lp-nav-label">Kegiatan &amp; Acara</span>
            </a>

            {{-- MENU DONASI DINAMIS BERDASARKAN PAKET MASJID --}}
        @if(isset($mosque) && $mosque->package_type === 'free')
            {{-- Jika Paket Free: Diarahkan langsung ke halaman perpanjangan/pilihan paket --}}
            <a href="{{ route('masjid.perpanjangan.create') }}" class="lp-nav-item" style="opacity: 0.8;" title="Upgrade paket untuk membuka fitur donasi">
                <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="lp-nav-label">Donasi</span>
                <span class="lp-nav-soon" style="background: #e74c3c; color: white;">Locked</span>
            </a>
        @else
            {{-- Jika Paket Berbayar: Aktif dan mengarah ke menu donasi admin --}}
            <a href="{{ route('admin.donasi') }}" class="lp-nav-item">
                <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="lp-nav-label">Donasi</span>
                <span class="lp-nav-soon" style="background: #27ae60; color: white;">Aktif</span>
            </a>
        @endif

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="lp-nav-label">Data Jamaah</span>
                <span class="lp-nav-soon">dev</span>
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
                <button type="button" class="lp-tab" data-tab="modul">Modul Aktif</button>
                <button type="button" class="lp-tab lp-tab-preview" data-tab="preview">Pratinjau</button>
            </div>

          {{-- ===== TAB: HERO ===== --}}
<div class="lp-panel active" id="lpTab-hero">
    {{-- Card 1: Konten Teks Utama --}}
    <div class="lp-card">
        <div class="lp-card-title"><span class="lp-card-bar"></span>Konten Hero / Banner Utama</div>

        <div class="lp-field">
            <label class="lp-label">Judul Utama</label>
            <input class="lp-input" type="text" name="hero_title"
                   placeholder="cth. Selamat Datang di Masjid Al-Ikhlas"
                   value="{{ old('hero_title', $landingPage->hero_title ?? '') }}"
                   style="width: 100% !important; max-width: 100% !important; display: block !important; box-sizing: border-box;">
        </div>

        <div class="lp-field">
            <label class="lp-label">Nama Masjid (Arab)</label>
            <input class="lp-input" type="text" value="{{ $mosque->arabic_name ?? '(belum diisi)' }}" dir="rtl" disabled
                   style="width: 100% !important; max-width: 100% !important; display: block !important; background: #f3f4f6; color: var(--text-mid); text-align: right; box-sizing: border-box;">
            <span style="font-size: 0.72rem; color: var(--text-light); display: block; margin-top: 4px;">
                Diambil dari <a href="{{ route('admin.profil-masjid') }}">Profil Masjid</a>. Ubah di sana untuk memperbarui.
            </span>
        </div>

        <div class="lp-field">
            <label class="lp-label">Sub-judul / Tagline</label>
            <input class="lp-input" type="text" name="hero_subtitle"
                   placeholder="cth. Masjid Rahmatan Lil Alamin"
                   value="{{ old('hero_subtitle', $landingPage->hero_subtitle ?? $mosque->tagline ?? '') }}"
                   style="width: 100% !important; max-width: 100% !important; display: block !important; box-sizing: border-box;">
        </div>

        <div class="lp-field">
            <label class="lp-label">Deskripsi Singkat</label>
            <textarea class="lp-textarea" name="hero_desc" rows="3"
                      placeholder="Ceritakan tentang masjid Anda dalam 1–2 kalimat..."
                      style="width: 100% !important; max-width: 100% !important; display: block !important; box-sizing: border-box;">{{ old('hero_desc', $landingPage->hero_desc ?? '') }}</textarea>
        </div>
    </div>

    {{-- Card 2: Gambar Latar --}}
    <div class="lp-card">
        <div class="lp-card-title"><span class="lp-card-bar"></span>Gambar</div>
        <div class="lp-field">
            <label class="lp-label">Gambar Latar — opsional</label>
            @if(!empty($landingPage->hero_image))
                <img src="{{ asset('storage/'.$landingPage->hero_image) }}" alt="Hero saat ini" style="max-height:100px;border-radius:8px;margin-bottom:8px;display:block;">
            @endif
            <input type="file" name="hero_image" accept="image/*" class="lp-input"
                   style="width: 100% !important; max-width: 100% !important; display: block !important; box-sizing: border-box;">
            <span style="font-size: 0.72rem; color: var(--text-light); display: block; margin-top: 4px;">PNG, JPG, AVIF, WebP · Maks. 2MB · Rekomendasi 1920×600</span>
        </div>
    </div>
</div>

           {{-- ===== TAB: TENTANG ===== --}}
<div class="lp-panel" id="lpTab-tentang">
    <div class="lp-card">
        <div class="lp-card-title"><span class="lp-card-bar"></span>Tentang Masjid</div>

        <div style="background:#f3f4f6;border-radius:10px;padding:14px 16px;font-size:0.85rem;color:var(--text-mid);margin-bottom:16px;">
            Konten section "Tentang Masjid" di halaman publik sekarang diambil langsung dari
            <strong>Profil Masjid</strong>, supaya tidak ada dua tempat mengedit data yang sama.
            <a href="{{ route('admin.profil-masjid') }}" style="font-weight:600;">Edit di Profil Masjid →</a>
        </div>

        <div class="lp-grid-2">
            <div class="lp-field">
                <label class="lp-label">Nama Masjid</label>
                <input class="lp-input" type="text" value="{{ $mosque->mosque_name ?? '(belum diisi)' }}" disabled 
                       style="width: 100% !important; max-width: 100% !important; display: block !important; background: #f3f4f6; color: var(--text-mid); box-sizing: border-box;">
            </div>
            <div class="lp-field">
                <label class="lp-label">Tahun Berdiri</label>
                <input class="lp-input" type="text" value="{{ $mosque->founded ?? '(belum diisi)' }}" disabled 
                       style="width: 100% !important; max-width: 100% !important; display: block !important; background: #f3f4f6; color: var(--text-mid); box-sizing: border-box;">
            </div>
        </div>
        <div class="lp-field">
            <label class="lp-label">Kapasitas Jamaah</label>
            <input class="lp-input" type="text" value="{{ $mosque->capacity ?? '(belum diisi)' }}" disabled 
                   style="width: 100% !important; max-width: 100% !important; display: block !important; background: #f3f4f6; color: var(--text-mid); box-sizing: border-box;">
        </div>
        <div class="lp-field">
            <label class="lp-label">Deskripsi / Sejarah Singkat</label>
            <textarea class="lp-textarea" rows="4" disabled 
                      style="width: 100% !important; max-width: 100% !important; display: block !important; background: #f3f4f6; color: var(--text-mid); box-sizing: border-box;">{{ $mosque->description ?? '(belum diisi)' }}</textarea>
        </div>
        <div class="lp-field">
            <label class="lp-label">Visi &amp; Misi</label>
            <textarea class="lp-textarea" rows="3" disabled 
                      style="width: 100% !important; max-width: 100% !important; display: block !important; background: #f3f4f6; color: var(--text-mid); box-sizing: border-box;">{{ $mosque->about_vision ?? '(belum diisi)' }}</textarea>
        </div>
    </div>
</div>

{{-- ===== TAB: MODUL ===== --}}
<div class="lp-panel" id="lpTab-modul">
    <div class="lp-card">
        <div class="lp-card-title"><span class="lp-card-bar"></span>Modul yang Ditampilkan</div>

        @php
            // Ambil data active_modules dari tabel landing_pages, bukan dari mosqeus
            $activeModules = $landingPage->active_modules ?? [];
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
            @php 
                // Jika data di database bernilai null (pertama kali), default-nya true (tercentang)
                $isChecked = isset($activeModules[$mod['key']]) ? $activeModules[$mod['key']] : true; 
            @endphp
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
        const lpSidebar = document.getElementById('lpSidebar');
        const lpBody = document.getElementById('lpBody');
        const lpToggle = document.getElementById('lpToggle');
        const lpSidebarToggle = document.getElementById('lpSidebarToggle');

        // 1. Cek status tersimpan di localStorage saat halaman dimuat
        const isCollapsed = localStorage.getItem("lp_sidebar_collapsed") === "true";
        if (isCollapsed) {
            lpSidebar?.classList.add('collapsed');
            lpBody?.classList.add('lp-sidebar-collapsed');
        }

        // 2. Fungsi Toggle Sidebar (Top button & Brand button)
        function toggleSidebarFunc() {
            lpSidebar?.classList.toggle('collapsed');
            lpBody?.classList.toggle('lp-sidebar-collapsed');

            // Simpan state ke localStorage
            const collapsedStatus = lpSidebar?.classList.contains('collapsed');
            localStorage.setItem("lp_sidebar_collapsed", collapsedStatus);
        }

        if (lpToggle) {
            lpToggle.addEventListener('click', toggleSidebarFunc);
        }
        if (lpSidebarToggle) {
            lpSidebarToggle.addEventListener('click', toggleSidebarFunc);
        }

        // Tab Navigation Logic
        document.querySelectorAll('.lp-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.lp-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lp-panel').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById('lpTab-' + tab.dataset.tab)?.classList.add('active');
            });
        });

        // Color Picker Sync
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