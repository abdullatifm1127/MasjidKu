<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Tema - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/temaAdmin.css') }}?v={{ time() }}">
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

        @php
            // Daftar tema. Key harus sama dengan nilai yang diterima controller (admin.tema.update).
            $themes = [
                'default'    => ['name' => 'Default Asli',        'tag' => 'Bawaan',     'locked' => false, 'desc' => 'Tampilan asli bawaan sistem tanpa modifikasi warna khusus.'],
                'emerald'    => ['name' => 'Emerald Minimalist',  'tag' => 'Minimalis',  'locked' => true,  'desc' => 'Nuansa hijau zamrud yang bersih dan segar.'],
                'terracotta' => ['name' => 'Warm Terracotta',     'tag' => 'Nusantara',  'locked' => true,  'desc' => 'Nuansa terakota hangat khas masjid tradisional.'],
                'corporate'  => ['name' => 'Corporate Navy',      'tag' => 'Dark Mode',  'locked' => true,  'desc' => 'Gaya korporat dark mode yang elegan dan profesional.'],
            ];

            $currentTheme = strtolower(trim(optional($mosque)->theme ?? 'default'));
            $activeKey    = in_array($currentTheme, ['', 'default'], true) ? 'default' : $currentTheme;
            if (!isset($themes[$activeKey])) {
                $activeKey = 'default';
            }

            $isBerbayar = (optional($mosque)->package_type ?? 'free') !== 'free'
                && strtolower(trim(optional($mosque)->payment_status ?? '')) === 'approved';

            // Kerangka mini landing page, dipakai di kartu & banner
            $mockHtml = '<div class="tm-mock"><div class="tm-mock-bar"><i></i><i></i><i></i></div>'
                . '<div class="tm-mock-nav"><b></b><span><i></i><i></i><i></i></span></div>'
                . '<div class="tm-mock-hero"><b></b><span></span><em></em></div>'
                . '<div class="tm-mock-cards"><i></i><i></i><i></i></div></div>';
        @endphp

        <main class="ba2-content">
            <div class="tm-page">

                {{-- ===== Banner tema aktif ===== --}}
                <section class="tm-active tm-theme-{{ $activeKey }}" id="tmActive" aria-live="polite">
                    <div class="tm-active-info">
                        <span class="tm-active-label"><i class="fa-solid fa-circle-check"></i> Tema aktif</span>
                        <div class="tm-active-name" id="tmActiveName">{{ $themes[$activeKey]['name'] }}</div>
                        <div class="tm-active-tag" id="tmActiveDesc">{{ $themes[$activeKey]['desc'] }}</div>

                        <div class="tm-active-actions">
                            @if(!empty($mosque->slug))
                                <a href="{{ url('masjid/' . $mosque->slug) }}" target="_blank" rel="noopener" class="tm-btn tm-btn-light">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Landing Page
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="tm-active-thumb" aria-hidden="true">{!! $mockHtml !!}</div>
                </section>

                {{-- ===== Info paket Free ===== --}}
                @if(!$isBerbayar)
                    <div class="tm-upgrade">
                        <span class="tm-upgrade-icon"><i class="fa-solid fa-lock"></i></span>
                        <div class="tm-upgrade-text">
                            Anda menggunakan <b>Paket Free</b>. Tema kustom (Emerald, Terracotta, Corporate) masih terkunci.
                            Upgrade ke paket berbayar untuk membuka semua tema.
                        </div>
                        <a href="{{ route('masjid.perpanjangan.create') }}" class="tm-btn tm-btn-amber">
                            <i class="fa-solid fa-rocket"></i> Upgrade Paket
                        </a>
                    </div>
                @endif

                {{-- ===== Pilihan tema ===== --}}
                <section>
                    <div class="tm-section-head" style="margin-bottom: 18px;">
                        <h2>Pilih Tema Landing Page</h2>
                        <p>Klik salah satu tema untuk langsung menerapkannya pada halaman utama masjid.</p>
                    </div>

                    <div class="tm-grid" id="tmGrid">
                        @foreach($themes as $key => $t)
                            @php
                                $locked   = $t['locked'] && !$isBerbayar;
                                $selected = $key === $activeKey;
                            @endphp
                            <button type="button"
                                    class="tm-card tm-theme-{{ $key }} {{ $selected ? 'is-selected' : '' }} {{ $locked ? 'is-locked' : '' }}"
                                    data-theme="{{ $key }}"
                                    data-name="{{ $t['name'] }}"
                                    data-desc="{{ $t['desc'] }}"
                                    data-locked="{{ $locked ? '1' : '0' }}"
                                    aria-pressed="{{ $selected ? 'true' : 'false' }}">

                                <span class="tm-check"><i class="fa-solid fa-check"></i> Dipakai</span>
                                @if($locked)
                                    <span class="tm-lock"><i class="fa-solid fa-lock"></i> Terkunci</span>
                                @endif

                                <span class="tm-preview" aria-hidden="true">{!! $mockHtml !!}</span>

                                <span class="tm-body">
                                    <span class="tm-title-row">
                                        <span class="tm-name">{{ $t['name'] }}</span>
                                        <span class="tm-tag">{{ $t['tag'] }}</span>
                                    </span>
                                    <span class="tm-desc">{{ $t['desc'] }}</span>
                                    <span class="tm-swatches" aria-hidden="true"><i></i><i></i><i></i></span>

                                    <span class="tm-cta">
                                        <span class="tm-cta-text">
                                            @if($selected) Sedang dipakai
                                            @elseif($locked) Upgrade untuk membuka
                                            @else Terapkan tema
                                            @endif
                                        </span>
                                        <span class="tm-cta-icon">
                                            <i class="fa-solid {{ $locked ? 'fa-lock' : ($selected ? 'fa-check' : 'fa-arrow-right') }}"></i>
                                        </span>
                                    </span>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </section>

            </div>
        </main>
    </div>

    <div class="tm-toast-wrap" id="tmToasts" aria-live="polite"></div>

    <script>
        (function () {
            'use strict';

            const updateUrl  = @json(route('admin.tema.update'));
            const upgradeUrl = @json(route('masjid.perpanjangan.create'));
            const csrfToken  = @json(csrf_token());

            const grid     = document.getElementById('tmGrid');
            const banner   = document.getElementById('tmActive');
            const toasts   = document.getElementById('tmToasts');

            // ---------- toast ----------
            function toast(message, type, action) {
                const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
                const el = document.createElement('div');
                el.className = 'tm-toast' + (type === 'error' ? ' is-error' : type === 'info' ? ' is-info' : '');

                const icon = document.createElement('i');
                icon.className = 'fa-solid ' + (icons[type] || icons.success);

                const body = document.createElement('div');
                const text = document.createElement('div');
                text.textContent = message;
                body.appendChild(text);

                if (action) {
                    const a = document.createElement('a');
                    a.href = action.href;
                    a.textContent = action.label;
                    if (action.blank) { a.target = '_blank'; a.rel = 'noopener'; }
                    body.appendChild(a);
                }

                el.appendChild(icon);
                el.appendChild(body);
                toasts.appendChild(el);

                setTimeout(() => {
                    el.classList.add('is-leaving');
                    setTimeout(() => el.remove(), 260);
                }, 5000);
            }

            // ---------- tandai kartu terpilih + perbarui banner ----------
            function markSelected(card) {
                grid.querySelectorAll('.tm-card').forEach(c => {
                    const on = c === card;
                    c.classList.toggle('is-selected', on);
                    c.setAttribute('aria-pressed', on ? 'true' : 'false');

                    const locked = c.dataset.locked === '1';
                    const txt  = c.querySelector('.tm-cta-text');
                    const icon = c.querySelector('.tm-cta-icon i');
                    if (txt)  txt.textContent = on ? 'Sedang dipakai' : (locked ? 'Upgrade untuk membuka' : 'Terapkan tema');
                    if (icon) icon.className = 'fa-solid ' + (on ? 'fa-check' : (locked ? 'fa-lock' : 'fa-arrow-right'));
                });

                banner.className = 'tm-active tm-theme-' + card.dataset.theme;
                document.getElementById('tmActiveName').textContent = card.dataset.name;
                document.getElementById('tmActiveDesc').textContent = card.dataset.desc;
            }

            // ---------- ganti tema ----------
            async function applyTheme(card) {
                if (card.classList.contains('is-loading') || card.classList.contains('is-selected')) return;

                if (card.dataset.locked === '1') {
                    toast('Tema ini terkunci. Fitur ganti tema hanya tersedia untuk masjid berbayar.', 'info',
                          { href: upgradeUrl, label: 'Upgrade paket sekarang' });
                    return;
                }

                card.classList.add('is-loading');
                try {
                    const res = await fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ theme: card.dataset.theme })
                    });

                    const data = await res.json().catch(() => null);

                    if (!res.ok || !data || !data.success) {
                        throw new Error((data && data.message) ? data.message : 'Gagal mengubah tema (kode ' + res.status + ').');
                    }

                    markSelected(card);
                    toast('Tema "' + card.dataset.name + '" berhasil diterapkan. Muat ulang halaman masjid Anda untuk melihatnya.', 'success');
                } catch (err) {
                    toast(err.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
                } finally {
                    card.classList.remove('is-loading');
                }
            }

            grid.addEventListener('click', function (e) {
                const card = e.target.closest('.tm-card');
                if (card) applyTheme(card);
            });
        })();
    </script>
</body>
</html>