<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="lp-body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="lp-sidebar" id="lpSidebar">

        {{-- Toggle button --}}
        <button class="lp-sidebar-toggle" id="lpSidebarToggle" aria-label="Toggle sidebar">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        {{-- Brand --}}
        <div class="lp-brand">
            <div class="lp-brand-avatar">A</div>
            <div class="lp-brand-info">
                <div class="lp-brand-name">SIM Masjid</div>
                <div class="lp-brand-sub">Baitul Digital</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="lp-nav">
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-dashboard">
                    <i class="fa-solid fa-table-cells-large"></i>
                </span>
                <span class="lp-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item active">
                <span class="lp-nav-icon lp-icon-landing">
                    <i class="fa-solid fa-globe"></i>
                </span>
                <span class="lp-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-profil">
                    <i class="fa-solid fa-mosque"></i>
                </span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-shalat">
                    <i class="fa-solid fa-clock"></i>
                </span>
                <span class="lp-nav-label">Jadwal Shalat</span>
                <span class="lp-nav-soon">dev</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-pengumuman">
                    <i class="fa-solid fa-bullhorn"></i>
                </span>
                <span class="lp-nav-label">Pengumuman</span>
                <span class="lp-nav-badge">3</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-acara">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>
                <span class="lp-nav-label">Kegiatan &amp; Acara</span>
                <span class="lp-nav-soon">dev</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-donasi">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </span>
                <span class="lp-nav-label">Donasi</span>
                <span class="lp-nav-soon">dev</span>
            </a>

            <a href="#" class="lp-nav-item">
                <span class="lp-nav-icon lp-icon-jamaah">
                    <i class="fa-solid fa-users"></i>
                </span>
                <span class="lp-nav-label">Data Jamaah</span>
                <span class="lp-nav-soon">dev</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="lp-user">
            <div class="lp-user-avatar">A</div>
            <div class="lp-user-info">
                <div class="lp-user-name">Admin Masjid</div>
                <div class="lp-user-email">admin@baituldigital.id</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="lp-main">

        {{-- Topbar --}}
        <header class="lp-topbar">
            <div class="lp-topbar-left">
                <div>
                    <div class="lp-page-title">Landing Page</div>
                    <div class="lp-page-sub">Kelola modul Landing Page</div>
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

            @if(session('success'))
            <div class="lp-alert">{{ session('success') }}</div>
            @endif

            {{-- Tab Navigation --}}
            <div class="lp-tabs">
                <button class="lp-tab active" data-tab="hero">Hero Section</button>
                <button class="lp-tab" data-tab="statistik">Statistik</button>
                <button class="lp-tab" data-tab="fitur">Fitur Unggulan</button>
                <button class="lp-tab lp-tab-preview" data-tab="preview">
                    <i class="fa-solid fa-eye"></i>
                    Preview
                </button>
            </div>

            <form id="lpForm" method="POST" action="{{ route('admin.landing-page.update') }}">
                @csrf
                @method('PUT')

                {{-- ===== TAB: HERO SECTION ===== --}}
                <div class="lp-panel active" id="lpTab-hero">
                    <div class="lp-card">
                        <div class="lp-card-title">
                            <span class="lp-card-bar"></span>
                            Konten Hero Section
                        </div>

                        <div class="lp-field">
                            <label class="lp-label">JUDUL UTAMA</label>
                            <input type="text" name="hero_title" class="lp-input"
                                   value="{{ old('hero_title', 'Baitul Digital') }}"
                                   placeholder="Judul utama hero section">
                        </div>

                        <div class="lp-field">
                            <label class="lp-label">SUB JUDUL / TAGLINE</label>
                            <input type="text" name="hero_tagline" class="lp-input"
                                   value="{{ old('hero_tagline', 'Pusat Ilmu dan Ibadah') }}"
                                   placeholder="Sub judul atau tagline">
                        </div>

                        <div class="lp-field">
                            <label class="lp-label">DESKRIPSI</label>
                            <textarea name="hero_desc" class="lp-textarea" rows="3"
                                      placeholder="Deskripsi singkat...">{{ old('hero_desc', 'Platform masjid digital terpadu untuk mengelola informasi, kegiatan, dan donasi masjid Anda.') }}</textarea>
                        </div>

                        <div class="lp-grid-2">
                            <div class="lp-field">
                                <label class="lp-label">TEKS TOMBOL UTAMA</label>
                                <input type="text" name="btn_primary" class="lp-input"
                                       value="{{ old('btn_primary', 'Jelajahi Masjid') }}">
                            </div>
                            <div class="lp-field">
                                <label class="lp-label">TEKS TOMBOL SEKUNDER</label>
                                <input type="text" name="btn_secondary" class="lp-input"
                                       value="{{ old('btn_secondary', 'Pelajari Fitur') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== TAB: STATISTIK ===== --}}
                <div class="lp-panel" id="lpTab-statistik">
                    <div class="lp-card">
                        <div class="lp-card-title">
                            <span class="lp-card-bar"></span>
                            Angka Statistik
                        </div>
                        <div class="lp-grid-2">
                            <div class="lp-field">
                                <label class="lp-label">JUMLAH MASJID TERDAFTAR</label>
                                <input type="number" name="stat_masjid" class="lp-input" value="{{ old('stat_masjid', 120) }}">
                            </div>
                            <div class="lp-field">
                                <label class="lp-label">TOTAL JAMAAH</label>
                                <input type="text" name="stat_jamaah" class="lp-input" value="{{ old('stat_jamaah', '50.000+') }}">
                            </div>
                            <div class="lp-field">
                                <label class="lp-label">TOTAL DONASI TERKUMPUL</label>
                                <input type="text" name="stat_donasi" class="lp-input" value="{{ old('stat_donasi', 'Rp 2.4 M') }}">
                            </div>
                            <div class="lp-field">
                                <label class="lp-label">KOTA TERJANGKAU</label>
                                <input type="number" name="stat_kota" class="lp-input" value="{{ old('stat_kota', 38) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== TAB: FITUR ===== --}}
                <div class="lp-panel" id="lpTab-fitur">
                    <div class="lp-card">
                        <div class="lp-card-title">
                            <span class="lp-card-bar"></span>
                            Fitur Unggulan Platform
                        </div>
                        @php
                            $fiturList = [
                                ['icon' => '🕌', 'judul' => 'Profil Masjid Digital',   'desc' => 'Kelola informasi masjid lengkap dalam satu platform'],
                                ['icon' => '📢', 'judul' => 'Pengumuman Realtime',      'desc' => 'Kirim pengumuman ke jamaah kapan saja'],
                                ['icon' => '🤲', 'judul' => 'Donasi Online',            'desc' => 'Terima donasi digital dengan mudah dan aman'],
                                ['icon' => '📅', 'judul' => 'Jadwal & Acara',           'desc' => 'Kelola jadwal shalat dan acara masjid'],
                            ];
                        @endphp
                        @foreach($fiturList as $idx => $f)
                        <div class="lp-fitur-row">
                            <div class="lp-fitur-emoji">{{ $f['icon'] }}</div>
                            <div class="lp-fitur-fields lp-grid-2">
                                <div class="lp-field">
                                    <label class="lp-label">JUDUL FITUR {{ $idx + 1 }}</label>
                                    <input type="text" name="fitur[{{ $idx }}][judul]" class="lp-input" value="{{ $f['judul'] }}">
                                </div>
                                <div class="lp-field">
                                    <label class="lp-label">DESKRIPSI</label>
                                    <input type="text" name="fitur[{{ $idx }}][desc]" class="lp-input" value="{{ $f['desc'] }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== TAB: PREVIEW ===== --}}
                <div class="lp-panel" id="lpTab-preview">
                    <div class="lp-card lp-preview-card">
                        <div class="lp-card-title">
                            <span class="lp-card-bar"></span>
                            Pratinjau
                        </div>
                        <div class="lp-preview-inner">
                            <div class="lp-preview-emoji">👁</div>
                            <div class="lp-preview-text">Simpan perubahan terlebih dahulu untuk melihat pratinjau</div>
                            <a href="{{ url('/') }}" target="_blank" class="lp-preview-link">Buka Halaman Publik →</a>
                        </div>
                    </div>
                </div>

                {{-- Footer aksi --}}
                <div class="lp-footer" id="lpFooter">
                    <span class="lp-footer-status" id="lpStatus">Perubahan belum disimpan</span>
                    <div class="lp-footer-btns">
                        <button type="button" class="lp-btn-reset" id="lpReset">Reset</button>
                        <button type="submit" class="lp-btn-save">Simpan Perubahan</button>
                    </div>
                </div>

            </form>

        </main>
    </div>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('lpSidebar');
        const toggleBtn = document.getElementById('lpSidebarToggle');
        const body = document.body;

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('lp-sidebar-collapsed');
        });

        // Tab switching
        document.querySelectorAll('.lp-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.lp-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lp-panel').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                const panel = document.getElementById('lpTab-' + tab.dataset.tab);
                if (panel) panel.classList.add('active');
                document.getElementById('lpFooter').style.display =
                    tab.dataset.tab === 'preview' ? 'none' : 'flex';
            });
        });

        // Dirty state
        const status = document.getElementById('lpStatus');
        document.getElementById('lpForm').querySelectorAll('input,textarea').forEach(el => {
            el.addEventListener('input', () => {
                status.textContent = 'Ada perubahan yang belum disimpan';
                status.style.color = '#d97706';
            });
        });

        // Reset
        document.getElementById('lpReset').addEventListener('click', () => {
            if (confirm('Reset semua perubahan?')) {
                document.getElementById('lpForm').reset();
                status.textContent = 'Perubahan belum disimpan';
                status.style.color = '';
            }
        });
    </script>
</body>
</html>
