<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tema - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/penggunaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/manajemenTemaSuperAdmin.css') }}">
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
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item sa-nav-has-badge {{ request()->routeIs('superadmin.verifikasi*') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                <span class="sa-nav-badge-dot amber"></span>
            </a>

            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item {{ request()->routeIs('superadmin.manajemen-masjid*') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Manajemen Masjid</span>
            </a>

            <a href="{{ route('superadmin.pembayaran.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.pembayaran*') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Pembayaran</span>
            </a>

            <a href="{{ route('superadmin.pengguna') }}" class="sa-nav-item {{ request()->routeIs('superadmin.pengguna*') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Pengguna</span>
            </a>

            <a href="{{ route('superadmin.halaman-utama.edit') }}" class="sa-nav-item {{ request()->routeIs('superadmin.halaman-utama*') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Halaman Utama</span>
            </a>

            <a href="{{ route('superadmin.themes.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.themes*') ? 'active' : '' }}">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.928a3.75 3.75 0 004.12 1.054c2.812-.99 6.22-3.178 8.44-5.398s4.408-5.628 5.398-8.44a3.75 3.75 0 00-1.054-4.12l-1.536-1.536a3.75 3.75 0 00-4.12-1.054c-2.812.99-6.22 3.178-8.44 5.398s-4.408 5.628-5.398 8.44a3.75 3.75 0 001.054 4.12l1.536 1.536z" />
                    </svg>
                </span>
                <span class="sa-nav-label">Manajemen Tema</span>
            </a>

            <a href="{{ route('superadmin.pengaturan') }}" class="sa-nav-item {{ request()->routeIs('superadmin.pengaturan*') ? 'active' : '' }}">
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
            <div class="sa-topbar-title">Manajemen Tema</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                    Lihat Website
                </a>
            </div>
        </header>

        <main class="sa-content pg-content">
            @if(session('success'))
            <div class="pg-alert pg-alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="th-errors">
                <ul>
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @php
                // Preset tampilan per key tema (sama dengan yang tampil di admin masjid).
                // Tema baru yang belum ada di sini otomatis memakai warna netral.
                $presets = [
                    'default' => [
                        'tag' => 'Bawaan', 'bg' => '#f0fdfa', 'dark' => false,
                        'colors' => ['#0f766e', '#14b8a6', '#f59e0b'],
                        'hero' => 'linear-gradient(135deg,#0f766e,#14b8a6)',
                    ],
                    'emerald' => [
                        'tag' => 'Minimalis', 'bg' => '#ecfdf5', 'dark' => false,
                        'colors' => ['#064e3b', '#10b981', '#fde047'],
                        'hero' => 'linear-gradient(135deg,#064e3b,#10b981)',
                    ],
                    'terracotta' => [
                        'tag' => 'Nusantara', 'bg' => '#fff7ed', 'dark' => false,
                        'colors' => ['#9a3412', '#ea580c', '#fde68a'],
                        'hero' => 'linear-gradient(135deg,#9a3412,#ea580c)',
                    ],
                    'corporate' => [
                        'tag' => 'Dark Mode', 'bg' => '#0b1220', 'dark' => true,
                        'colors' => ['#0b1120', '#1e3a8a', '#60a5fa'],
                        'hero' => 'linear-gradient(135deg,#0b1120,#1e3a8a)',
                    ],
                ];
                $fallback = [
                    'tag' => 'Kustom', 'bg' => '#f8fafc', 'dark' => false,
                    'colors' => ['#334155', '#64748b', '#94a3b8'],
                    'hero' => 'linear-gradient(135deg,#334155,#64748b)',
                ];
                $aktif = $themes->where('is_active', true)->count();
            @endphp

            {{-- ===== SUBHEADER ===== --}}
            <div class="pg-subheader">
                <div>
                    <span class="pg-count">{{ $aktif }} dari {{ $themes->count() }} tema aktif</span>
                    <p class="th-intro">Tema yang aktif akan muncul sebagai pilihan di halaman Tema Tampilan admin masjid.</p>
                </div>
                <button class="pg-btn-tambah" id="pgTambahBtn" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Tambah Tema Baru
                </button>
            </div>

            {{-- ===== KARTU GRID TEMA ===== --}}
            @if($themes->isEmpty())
                <div class="th-empty">Belum ada tema. Klik "Tambah Tema Baru" untuk membuat yang pertama.</div>
            @else
            <div class="th-grid">
                @foreach($themes as $theme)
                @php
                    $p = $presets[$theme->key] ?? $fallback;
                    $c = $p['colors'];
                @endphp
                <div class="th-card {{ $theme->is_active ? '' : 'is-off' }}">
                    <div class="th-preview" style="background: linear-gradient(180deg, {{ $p['bg'] }}, #fff);">
                        <div class="th-mock" style="background: {{ $p['bg'] }};">
                            <div class="th-mock-bar"><i></i><i></i><i></i></div>
                            <div class="th-mock-nav">
                                <div class="th-mock-logo" style="background: {{ $c[0] }};"></div>
                                <div class="th-mock-links">
                                    <span></span><span></span>
                                    <b style="background: {{ $c[1] }};"></b>
                                </div>
                            </div>
                            <div class="th-mock-hero" style="background: {{ $p['hero'] }};">
                                <div class="l1"></div>
                                <div class="l2"></div>
                                <div class="btn" style="background: {{ $c[2] }};"></div>
                            </div>
                            <div class="th-mock-cards">
                                <div style="border-color: {{ $c[1] }}; {{ $p['dark'] ? 'background:#1e293b;' : '' }}"></div>
                                <div style="border-color: {{ $c[1] }}; {{ $p['dark'] ? 'background:#1e293b;' : '' }}"></div>
                                <div style="border-color: {{ $c[1] }}; {{ $p['dark'] ? 'background:#1e293b;' : '' }}"></div>
                            </div>
                        </div>
                        <span class="th-pill {{ $theme->is_active ? 'on' : 'off' }}">
                            {{ $theme->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <div class="th-body">
                        <div class="th-title-row">
                            <h3 class="th-title">{{ $theme->name }}</h3>
                            <span class="th-tag">{{ $p['tag'] }}</span>
                        </div>
                        <p class="th-desc">{{ $theme->description ?: 'Tidak ada deskripsi tema.' }}</p>
                        <div class="th-swatches">
                            @foreach($c as $warna)
                                <span style="background: {{ $warna }};"></span>
                            @endforeach
                        </div>
                        <span class="th-key">{{ $theme->key }}</span>
                    </div>

                    <div class="th-actions">
                        <button type="button" class="th-btn th-btn-edit js-edit-theme"
                                data-theme="{{ json_encode([
                                    'id' => $theme->id,
                                    'key' => $theme->key,
                                    'name' => $theme->name,
                                    'description' => $theme->description,
                                ]) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                            Edit
                        </button>
                        @if($theme->key !== 'default')
                        <form action="{{ route('superadmin.themes.destroy', $theme->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus tema ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="th-btn th-btn-del">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </main>
    </div>

    {{-- ===== MODAL TAMBAH/EDIT TEMA ===== --}}
    <div class="pg-modal-overlay" id="pgModalOverlay">
        <div class="pg-modal">
            <div class="pg-modal-head">
                <span class="pg-modal-title" id="pgModalTitle">Tambah Tema Baru</span>
                <button type="button" class="pg-modal-close" id="pgModalClose" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form class="pg-modal-body" id="pgModalForm" method="POST" action="{{ route('superadmin.themes.store') }}">
                @csrf
                <div id="pgMethodContainer"></div>

                <div class="pg-modal-grid">
                    <div class="pg-modal-field">
                        <label class="pg-modal-label" for="themeKey">Key Sistem <span class="pg-req">*</span></label>
                        <input type="text" name="key" id="themeKey" class="pg-modal-input" placeholder="Contoh: dark, modern" required>
                    </div>
                    <div class="pg-modal-field">
                        <label class="pg-modal-label" for="themeName">Nama Tema <span class="pg-req">*</span></label>
                        <input type="text" name="name" id="themeName" class="pg-modal-input" placeholder="Contoh: Modern Dark Mode" required>
                    </div>
                    <div class="pg-modal-field" style="grid-column: span 2;">
                        <label class="pg-modal-label" for="themeDesc">Deskripsi</label>
                        <textarea name="description" id="themeDesc" class="pg-modal-input" placeholder="Deskripsi singkat mengenai tema..." style="height: 80px; resize: vertical;"></textarea>
                    </div>
                </div>

                <div class="pg-modal-footer">
                    <button type="button" class="pg-btn-cancel" id="pgCancelBtn">Batal</button>
                    <button type="submit" class="pg-btn-simpan" id="pgSimpanBtn">Simpan Tema</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar toggle
        document.getElementById('saSidebarToggle').addEventListener('click', () => {
            document.getElementById('saSidebar').classList.toggle('collapsed');
            document.getElementById('saMain').classList.toggle('expanded');
        });

        const modalOverlay = document.getElementById('pgModalOverlay');
        const modalForm = document.getElementById('pgModalForm');
        const modalTitle = document.getElementById('pgModalTitle');
        const methodContainer = document.getElementById('pgMethodContainer');
        const simpanBtn = document.getElementById('pgSimpanBtn');
        const keyInput = document.getElementById('themeKey');

        function openModal() { modalOverlay.classList.add('active'); }
        function closeModal() { modalOverlay.classList.remove('active'); }

        // Tambah
        document.getElementById('pgTambahBtn').addEventListener('click', () => {
            modalForm.reset();
            modalForm.action = "{{ route('superadmin.themes.store') }}";
            modalTitle.textContent = 'Tambah Tema Baru';
            methodContainer.innerHTML = '';
            simpanBtn.textContent = 'Simpan Tema';
            keyInput.readOnly = false;
            openModal();
        });

        // Edit: data dibaca dari atribut data-theme (JSON) sehingga aman dari tanda kutip / karakter khusus
        document.querySelectorAll('.js-edit-theme').forEach(btn => {
            btn.addEventListener('click', () => {
                const t = JSON.parse(btn.dataset.theme);
                modalForm.reset();
                modalForm.action = "{{ url('/superadmin/themes') }}/" + t.id;
                modalTitle.textContent = 'Edit Tema';
                methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                simpanBtn.textContent = 'Simpan Perubahan';

                keyInput.value = t.key;
                keyInput.readOnly = true; // key unik tidak boleh diubah
                document.getElementById('themeName').value = t.name ?? '';
                document.getElementById('themeDesc').value = t.description ?? '';
                openModal();
            });
        });

        document.getElementById('pgModalClose').addEventListener('click', closeModal);
        document.getElementById('pgCancelBtn').addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>
</body>
</html>