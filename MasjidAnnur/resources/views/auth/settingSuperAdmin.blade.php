<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/settingSuperAdmin.css') }}">
</head>
<body class="sa-page" id="saBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sa-sidebar" id="saSidebar">

        <div class="sa-brand">
            <div class="sa-brand-avatar">SA</div>
            <div class="sa-brand-info">
                <strong>MasjidKu Admin</strong>
                <span>Super Administrator</span>
            </div>
            <button class="sa-sidebar-toggle" id="saSidebarToggle" aria-label="Collapse sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </button>
        </div>

        <nav class="sa-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item sa-nav-has-badge">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                <span class="sa-nav-badge-dot amber"></span>
            </a>

            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Manajemen Masjid</span>
            </a>

            <a href="{{ route('superadmin.pengguna') }}" class="sa-nav-item">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Pengguna</span>
            </a>

            <a href="{{ route('superadmin.pengaturan') }}" class="sa-nav-item active">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Pengaturan</span>
            </a>
        </nav>

        <div class="sa-user-footer">
            <div class="sa-user-avatar-sm">SA</div>
            <div class="sa-user-info">
                <div class="sa-user-name">Super Admin</div>
                <div class="sa-user-email">admin@masjidku.id</div>
            </div>
            <a href="{{ route('logout') }}" class="sa-logout-btn"
               onclick="event.preventDefault(); document.getElementById('sa-logout-form').submit();"
               aria-label="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
            </a>
            <form id="sa-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="sa-main" id="saMain">

        <header class="sa-topbar">
            <div class="sa-topbar-title">Pengaturan</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                    Lihat Website
                </a>
                <button class="sa-notif-btn" aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <span class="sa-notif-dot"></span>
                </button>
            </div>
        </header>

        <main class="sa-content st-content">

            @if(session('success'))
            <div class="st-alert st-alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('superadmin.pengaturan.update') }}" id="stForm">
                @csrf
                @method('PUT')

                <div class="st-layout">

                    {{-- Kolom kiri: form --}}
                    <div class="st-col-form">

                        {{-- Section: Informasi Platform --}}
                        <div class="st-section">
                            <div class="st-section-title">Informasi Platform</div>

                            <div class="st-field">
                                <label class="st-label" for="platform_name">NAMA PLATFORM</label>
                                <input type="text" id="platform_name" name="platform_name"
                                       class="st-input"
                                       value="{{ old('platform_name', 'MasjidKu') }}"
                                       placeholder="Nama platform">
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="support_email">EMAIL SUPPORT</label>
                                <input type="email" id="support_email" name="support_email"
                                       class="st-input"
                                       value="{{ old('support_email', 'support@masjidku.id') }}"
                                       placeholder="support@domain.id">
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="whatsapp">NOMOR WHATSAPP</label>
                                <input type="text" id="whatsapp" name="whatsapp"
                                       class="st-input"
                                       value="{{ old('whatsapp', '+62 812 0000 0000') }}"
                                       placeholder="+62 8xx xxxx xxxx">
                            </div>
                        </div>

                        {{-- Section: Batas Tenant --}}
                        <div class="st-section">
                            <div class="st-section-title">Batas Tenant</div>

                            <div class="st-field">
                                <label class="st-label" for="max_mosque_free">MAKS. MASJID PER PAKET GRATIS</label>
                                <input type="number" id="max_mosque_free" name="max_mosque_free"
                                       class="st-input"
                                       value="{{ old('max_mosque_free', 1) }}"
                                       min="1" max="100">
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="max_announcement">MAKS. PENGUMUMAN PER BULAN</label>
                                <input type="number" id="max_announcement" name="max_announcement"
                                       class="st-input"
                                       value="{{ old('max_announcement', 10) }}"
                                       min="1" max="1000">
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="max_event">MAKS. ACARA PER BULAN</label>
                                <input type="number" id="max_event" name="max_event"
                                       class="st-input"
                                       value="{{ old('max_event', 5) }}"
                                       min="1" max="1000">
                            </div>
                        </div>

                        {{-- Section: Notifikasi --}}
                        <div class="st-section">
                            <div class="st-section-title">Notifikasi Sistem</div>

                            <div class="st-toggle-list">
                                <div class="st-toggle-item">
                                    <div>
                                        <div class="st-toggle-name">Notifikasi Pendaftaran Baru</div>
                                        <div class="st-toggle-desc">Kirim email ke super admin saat ada masjid baru mendaftar</div>
                                    </div>
                                    <label class="st-switch">
                                        <input type="checkbox" name="notif_registration" value="1" checked>
                                        <span class="st-switch-track"></span>
                                    </label>
                                </div>
                                <div class="st-toggle-item">
                                    <div>
                                        <div class="st-toggle-name">Notifikasi Donasi Masuk</div>
                                        <div class="st-toggle-desc">Kirim ringkasan donasi harian ke email super admin</div>
                                    </div>
                                    <label class="st-switch">
                                        <input type="checkbox" name="notif_donation" value="1" checked>
                                        <span class="st-switch-track"></span>
                                    </label>
                                </div>
                                <div class="st-toggle-item">
                                    <div>
                                        <div class="st-toggle-name">Mode Maintenance</div>
                                        <div class="st-toggle-desc">Nonaktifkan akses publik sementara untuk pemeliharaan sistem</div>
                                    </div>
                                    <label class="st-switch">
                                        <input type="checkbox" name="maintenance_mode" value="1">
                                        <span class="st-switch-track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Keamanan Akun --}}
                        <div class="st-section">
                            <div class="st-section-title">Keamanan Akun</div>

                            <div class="st-field">
                                <label class="st-label" for="current_password">PASSWORD SAAT INI</label>
                                <input type="password" id="current_password" name="current_password"
                                       class="st-input"
                                       placeholder="Password saat ini">
                            </div>

                            <div class="st-grid-2">
                                <div class="st-field">
                                    <label class="st-label" for="new_password">PASSWORD BARU</label>
                                    <input type="password" id="new_password" name="new_password"
                                           class="st-input"
                                           placeholder="Min. 8 karakter">
                                </div>
                                <div class="st-field">
                                    <label class="st-label" for="new_password_confirmation">KONFIRMASI PASSWORD</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                           class="st-input"
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <p class="st-hint">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>

                        {{-- Simpan --}}
                        <div class="st-footer">
                            <button type="submit" class="st-btn-simpan">Simpan Pengaturan</button>
                        </div>

                    </div>

                    {{-- Kolom kanan: info card --}}
                    <div class="st-col-info">

                        <div class="st-info-card">
                            <div class="st-info-title">Versi Sistem</div>
                            <div class="st-info-val">SIM Masjid v1.0.0</div>
                            <div class="st-info-sub">Rilis: Agustus 2026</div>
                        </div>

                        <div class="st-info-card">
                            <div class="st-info-title">Status Platform</div>
                            <div class="st-info-status-row">
                                <span class="st-status-dot green"></span>
                                <span class="st-info-val">Berjalan Normal</span>
                            </div>
                            <div class="st-info-sub">Uptime: 99.9%</div>
                        </div>

                        <div class="st-info-card">
                            <div class="st-info-title">Ringkasan Tenant</div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Total Masjid</span>
                                <span class="st-info-stat-val">3</span>
                            </div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Aktif</span>
                                <span class="st-info-stat-val green">2</span>
                            </div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Pending</span>
                                <span class="st-info-stat-val amber">1</span>
                            </div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Total Pengguna</span>
                                <span class="st-info-stat-val">4</span>
                            </div>
                        </div>

                        <div class="st-info-card st-danger-card">
                            <div class="st-info-title red">Zona Berbahaya</div>
                            <p class="st-danger-desc">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan.</p>
                            <button type="button" class="st-btn-danger" onclick="return confirm('Yakin ingin menghapus semua data? Tindakan ini tidak bisa dibatalkan.')">
                                Hapus Semua Data Tenant
                            </button>
                        </div>

                    </div>

                </div>
            </form>

        </main>
    </div>

    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

    <script>
        document.getElementById('saSidebarToggle').addEventListener('click', () => {
            document.getElementById('saSidebar').classList.toggle('collapsed');
            document.getElementById('saMain').classList.toggle('expanded');
        });
    </script>
</body>
</html>
