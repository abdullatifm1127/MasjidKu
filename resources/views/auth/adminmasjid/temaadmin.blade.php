<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Tema - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .ba2-theme-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .ba2-theme-card {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
        }
        .ba2-theme-card:hover {
            border-color: #047857;
            transform: translateY(-3px);
        }
        .ba2-theme-preview {
            height: 110px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            text-align: center;
            padding: 0 10px;
        }
        /* Preview warna untuk setiap tema termasuk default */
        .theme-preview-default { background: linear-gradient(135deg, #0d9488, #0f766e); }
        .theme-preview-emerald { background: linear-gradient(135deg, #064e3b, #047857); }
        .theme-preview-terracotta { background: linear-gradient(135deg, #9A3412, #C2410C); }
        .theme-preview-corporate { background: linear-gradient(135deg, #020617, #1e3a8a); }

        .ba2-theme-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .ba2-theme-desc {
            font-size: 13px;
            color: #64748b;
        }
        .ba2-theme-card.selected {
            border-color: #047857;
            background-color: #f0fdf4;
            box-shadow: 0 0 0 2px #047857;
        }
    </style>
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">
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
                <span class="ba2-nav-soon">dev</span>
            </a>

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

            @if(isset($mosque) && $mosque->package_type === 'free')
                <a href="{{ route('masjid.perpanjangan.create') }}" class="ba2-nav-item {{ request()->routeIs('masjid.perpanjangan*') ? 'active' : '' }}" style="opacity: 0.8;" title="Upgrade paket">
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
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Pengaturan Tema</div>
                <div class="ba2-page-sub">Pilih gaya dan warna tema tampilan halaman utama (Landing Page) masjid Anda</div>
            </div>
            <div class="ba2-topbar-right">
                <a href="{{ route('admin.dashboard') }}" class="ba2-btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </header>

        <main class="ba2-content">
            <div class="ba2-card">
                <div class="ba2-card-head">
                    <span class="ba2-card-title">Pilihan Tema Landing Page</span>
                </div>
                <div class="ba2-card-body">
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 15px;">Klik salah satu tema di bawah ini untuk langsung menerapkan dan melihat tampilan halaman utama masjid.</p>
                    
                    @php
                        $currentTheme = strtolower(trim(optional($mosque)->theme ?? 'default'));
                    @endphp

                    <div class="ba2-theme-grid">
                        <!-- Tema 0: Default Original -->
                        <div class="ba2-theme-card {{ in_array($currentTheme, ['', 'default']) ? 'selected' : '' }}" onclick="changeTheme('default', this)">
                            <div class="ba2-theme-preview theme-preview-default">Default Bawaan</div>
                            <div class="ba2-theme-title">Default Asli</div>
                            <div class="ba2-theme-desc">Tampilan asli bawaan sistem tanpa modifikasi warna khusus.</div>
                        </div>

                        <!-- Tema 1: Emerald -->
                        <div class="ba2-theme-card {{ $currentTheme == 'emerald' ? 'selected' : '' }}" onclick="changeTheme('emerald', this)">
                            <div class="ba2-theme-preview theme-preview-emerald">Modern Minimalis</div>
                            <div class="ba2-theme-title">Emerald Minimalist</div>
                            <div class="ba2-theme-desc">Nuansa hijau zamrud yang bersih dan segar.</div>
                        </div>

                        <!-- Tema 2: Terracotta -->
                        <div class="ba2-theme-card {{ $currentTheme == 'terracotta' ? 'selected' : '' }}" onclick="changeTheme('terracotta', this)">
                            <div class="ba2-theme-preview theme-preview-terracotta">Klasik Nusantara</div>
                            <div class="ba2-theme-title">Warm Terracotta</div>
                            <div class="ba2-theme-desc">Nuansa terakota hangat khas masjid tradisional.</div>
                        </div>

                        <!-- Tema 3: Corporate -->
                        <div class="ba2-theme-card {{ $currentTheme == 'corporate' ? 'selected' : '' }}" onclick="changeTheme('corporate', this)">
                            <div class="ba2-theme-preview theme-preview-corporate">Executive Professional</div>
                            <div class="ba2-theme-title">Corporate Navy</div>
                            <div class="ba2-theme-desc">Gaya korporat dark mode elegan.</div>
                        </div>
                    </div>

                    <script>
                        function changeTheme(themeName, element) {
                            fetch("{{ route('admin.tema.update') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ theme: themeName })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelectorAll('.ba2-theme-card').forEach(card => card.classList.remove('selected'));
                                    element.classList.add('selected');
                                    
                                    alert("Tema berhasil diubah! Silakan refresh halaman web masjid Anda.");
                                } else {
                                    alert("Gagal mengubah tema.");
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                            });
                        }
                    </script>
                </div>
            </div>
        </main>
    </div>
</body>
</html>