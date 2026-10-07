<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Donasi - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/donasiAdmin.css') }}?v={{ time() }}">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .dn-grid-dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .dn-card-modern { background: #ffffff; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); }
        .dn-card-modern h3 { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .dn-card-modern p { font-size: 0.875rem; color: #64748b; margin-bottom: 1.25rem; }
        .form-control-modern { width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.9rem; transition: all 0.2s; box-sizing: border-box; }
        .form-control-modern:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1); }
        .btn-primary-modern { background-color: #0f766e; color: #ffffff; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .btn-primary-modern:hover { background-color: #115e59; }

        /* ===== Submenu Donasi di sidebar ===== */
        .ba2-nav-group .ba2-sub { display: none; padding: 2px 0 6px; }
        .ba2-nav-group.open .ba2-sub { display: block; }
        .ba2-sub a { display: block; padding: 8px 12px 8px 46px; margin: 1px 0; font-size: .82rem; border-radius: 8px;
                     color: rgba(255,255,255,.65); text-decoration: none; }
        .ba2-sub a:hover { background: rgba(255,255,255,.06); color: #fff; }
        .ba2-sub a.active { background: rgba(255,255,255,.12); color: #fff; font-weight: 600; }
        .ba2-caret { margin-left: auto; font-size: .7rem; transition: transform .2s; }
        .ba2-nav-group.open .ba2-caret { transform: rotate(180deg); }

        /* ===== Tab strip di atas konten ===== */
        .dn-tabs { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .dn-tabs a { display: inline-flex; align-items: center; gap: .45rem; padding: .55rem 1rem; border-radius: 999px; font-size: .85rem;
                     font-weight: 600; text-decoration: none; color: #475569; background: #fff; border: 1px solid #e2e8f0; }
        .dn-tabs a:hover { border-color: #0f766e; color: #0f766e; }
        .dn-tabs a.active { background: #0f766e; border-color: #0f766e; color: #fff; }

        .dn-stat { font-size: 1.6rem; font-weight: 800; margin: 0; }
        .dn-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .dn-table th { text-align: left; padding: .6rem .5rem; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
        .dn-table td { padding: .6rem .5rem; border-bottom: 1px solid #f1f5f9; }
        .dn-table td.dn-note { max-width: 240px; word-break: break-word; color: #475569; }

        @media print {
            .ba2-sidebar, .ba2-topbar, .dn-tabs, .dn-noprint, .ba2-fab { display: none !important; }
            .ba2-main { margin: 0 !important; }
        }
    </style>
</head>
<body class="ba2-body" id="ba2Body">

@php
    // Judul & subjudul per tab
    $tabMeta = [
        'kategori'   => ['Kategori Donasi',   'Atur jenis & kategori donasi yang tampil di halaman publik',      'fa-layer-group'],
        'pengaturan' => ['Pengaturan Donasi', 'Zakat fitrah, nisab zakat mal, rekening bank, dan QRIS',          'fa-sliders'],
        'penyaluran' => ['Penyaluran Donasi', 'Dokumentasi & transparansi penyaluran dana ke jamaah',            'fa-images'],
        'rekap'      => ['Rekap & Pelaporan', 'Ringkasan, verifikasi, dan laporan donasi yang masuk',            'fa-chart-column'],
    ];
    $subLabels = [
        'kategori'   => 'Kategori',
        'pengaturan' => 'Pengaturan',
        'penyaluran' => 'Penyaluran',
        'rekap'      => 'Rekap Data',
    ];
    $meta = $tabMeta[$tab];

    $calcTypeOptions = [
        'nominal' => 'Nominal bebas (donatur pilih/isi sendiri jumlahnya)',
        'zakat'   => 'Zakat (kalkulator otomatis Fitrah & Mal)',
    ];
@endphp

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">
        <div class="ba2-brand">
            <div class="ba2-brand-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</div>
                <div class="ba2-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</div>
            </div>
        </div>

        <nav class="ba2-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="ba2-nav-item {{ request()->routeIs('admin.landing-page') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="ba2-nav-item {{ request()->routeIs('admin.profil-masjid') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="{{ route('admin.jadwal-sholat') }}" class="ba2-nav-item {{ request()->routeIs('admin.jadwal-sholat') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                <span class="ba2-nav-badge">3</span>
            </a>
            <a href="{{ route('admin.acara') }}" class="ba2-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
            </a>

            {{-- Donasi + submenu --}}
            <div class="ba2-nav-group open">
                <a href="#" class="ba2-nav-item active"
                   onclick="this.closest('.ba2-nav-group').classList.toggle('open'); return false;">
                    <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="ba2-nav-label">Donasi</span>
                    <i class="fa-solid fa-chevron-down ba2-caret"></i>
                </a>
                <div class="ba2-sub">
                    @foreach ($subLabels as $key => $label)
                        <a href="{{ route('admin.donasi.tab', $key) }}" class="{{ $tab === $key ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('admin.jamaah') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
            </a>
        </nav>

        <div class="ba2-user">
            <div class="ba2-user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 2) }}</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">{{ auth()->user()->name ?? 'Admin Masjid' }}</div>
                <div class="ba2-user-email">{{ auth()->user()->email ?? 'admin@baituldigital.id' }}</div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="ba2-main" id="ba2Main">

        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">{{ $meta[0] }}</div>
                <div class="ba2-page-sub">{{ $meta[1] }}</div>
            </div>
            <div class="ba2-topbar-right" style="display: flex; align-items: center; gap: 12px;">
                <form action="{{ route('masjid.unsubscribe') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin berhenti berlangganan? Paket akan kembali ke Free dan fitur donasi akan dinonaktifkan.');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ba2-btn-back" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; cursor: pointer; padding: 0.5rem 1rem; border-radius: 0.5rem;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Berhenti Berlangganan
                    </button>
                </form>

                <a href="{{ route('masjid.donasi.publik', $mosque->slug) }}" class="ba2-btn-back" target="_blank" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: #f1f5f9; text-decoration: none; color: #334155; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman Publik
                </a>

                <button class="ba2-notif-btn" aria-label="Notifikasi" style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; position: relative;">
                    <i class="fa-solid fa-bell"></i>
                    <span class="ba2-notif-dot"></span>
                </button>
            </div>
        </header>

        <main class="dn-content">

            {{-- Tab strip (memudahkan di layar kecil) --}}
            <nav class="dn-tabs">
                @foreach ($tabMeta as $key => $m)
                    <a href="{{ route('admin.donasi.tab', $key) }}" class="{{ $tab === $key ? 'active' : '' }}">
                        <i class="fa-solid {{ $m[2] }}"></i> {{ $subLabels[$key] }}
                    </a>
                @endforeach
            </nav>

            @if (session('success'))
                <div class="dn-alert dn-alert-success" style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="dn-alert dn-alert-error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- =====================================================
                TAB 1: KATEGORI
                ===================================================== --}}
            @if ($tab === 'kategori')
                <section class="dn-card-modern">
                    <h3><i class="fa-solid fa-layer-group text-teal-600"></i> Kelola Jenis &amp; Kategori Donasi</h3>
                    <p>Atur jenis donasi beserta foto banner program yang akan tampil pada halaman publik.</p>

                    {{-- Form Tambah Kategori --}}
                    <form action="{{ route('admin.donasi.kategori.store') }}" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; background: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem;">
                        @csrf
                        <div class="dn-field" style="margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nama Jenis Donasi</label>
                            <input type="text" name="title" class="form-control-modern" placeholder="Mis. Bantuan Bencana Alam" required maxlength="100">
                        </div>
                        <div class="dn-field" style="margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Tipe Kalkulasi / Kategori</label>
                            <select name="calc_type" class="form-control-modern" required>
                                @foreach ($calcTypeOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dn-field" style="margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Ikon</label>
                            <select name="icon_key" class="form-control-modern">
                                @foreach (\App\Models\DonationCategory::iconOptions() as $iconKey => $iconLabel)
                                    <option value="{{ $iconKey }}">{{ $iconLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dn-field" style="margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Urutan Tampil</label>
                            <input type="number" name="sort_order" class="form-control-modern" min="0" value="{{ ($donationCategories->max('sort_order') ?? -1) + 1 }}">
                        </div>
                        <div class="dn-field" style="grid-column: 1 / -1; margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Foto Program / Banner (Opsional)</label>
                            <input type="file" name="image" class="form-control-modern" accept="image/*">
                        </div>
                        <div style="grid-column: 1 / -1; margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control-modern" rows="2" placeholder="Penjelasan singkat mengenai program donasi ini..." maxlength="300"></textarea>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <button type="submit" class="btn-primary-modern"><i class="fa-solid fa-plus"></i> Tambah Kategori Donasi</button>
                        </div>
                    </form>

                    {{-- List Kategori --}}
                    @if ($donationCategories->isEmpty())
                        <div class="dn-empty" style="text-align: center; padding: 2rem; color: #64748b;">Belum ada jenis donasi yang ditambahkan.</div>
                    @else
                        <div class="dn-kategori-list" style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach ($donationCategories as $kategori)
                                <details style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; background: #ffffff;">
                                    <summary style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; font-weight: 600;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            @if(!empty($kategori->image))
                                                <img src="{{ asset('storage/' . $kategori->image) }}" alt="" style="width: 40px; height: 30px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                                                    {!! $kategori->iconPath() !!}
                                                </span>
                                            @endif
                                            <span>{{ $kategori->title }}</span>
                                        </div>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            <span style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 999px; background: #e0f2fe; color: #0369a1;">{{ ucfirst($kategori->calc_type) }}</span>
                                            <span style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 999px; {{ $kategori->is_active ? 'background: #dcfce7; color: #15803d;' : 'background: #f1f5f9; color: #64748b;' }}">
                                                {{ $kategori->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </summary>

                                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                                        {{-- Form Update Kategori --}}
                                        <form action="{{ route('admin.donasi.kategori.update', $kategori->id) }}" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Nama</label>
                                                <input type="text" name="title" class="form-control-modern" value="{{ $kategori->title }}" required maxlength="100">
                                            </div>
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Tipe</label>
                                                <select name="calc_type" class="form-control-modern" required>
                                                    @foreach ($calcTypeOptions as $val => $label)
                                                        <option value="{{ $val }}" {{ $kategori->calc_type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Ikon</label>
                                                <select name="icon_key" class="form-control-modern">
                                                    @foreach (\App\Models\DonationCategory::iconOptions() as $iconKey => $iconLabel)
                                                        <option value="{{ $iconKey }}" {{ $kategori->icon_key === $iconKey ? 'selected' : '' }}>{{ $iconLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Urutan</label>
                                                <input type="number" name="sort_order" class="form-control-modern" value="{{ $kategori->sort_order }}">
                                            </div>
                                            <div style="grid-column: 1 / -1;">
                                                <label style="font-size: 0.8rem; font-weight: 600;">Ganti Foto Program / Banner</label>
                                                @if(!empty($kategori->image))
                                                    <div style="margin-bottom: 0.5rem;">
                                                        <img src="{{ asset('storage/' . $kategori->image) }}" alt="Preview" style="max-height: 80px; border-radius: 6px;">
                                                    </div>
                                                @endif
                                                <input type="file" name="image" class="form-control-modern" accept="image/*">
                                            </div>
                                            <div style="grid-column: 1 / -1;">
                                                <label style="font-size: 0.8rem; font-weight: 600;">Deskripsi</label>
                                                <textarea name="description" class="form-control-modern" rows="2" maxlength="300">{{ $kategori->description }}</textarea>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn-primary-modern" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Simpan Perubahan</button>
                                            </div>
                                        </form>

                                        <div style="display: flex; gap: 0.5rem;">
                                            <form action="{{ route('admin.donasi.kategori.toggle', $kategori->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; font-size: 0.85rem;">
                                                    {{ $kategori->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.donasi.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Hapus jenis donasi ini? Riwayat donasi pada kategori ini akan tetap tersimpan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; font-size: 0.85rem;">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif

            {{-- =====================================================
                 TAB 2: PENGATURAN (zakat, rekening bank, QRIS)
                 ===================================================== --}}
            @if ($tab === 'pengaturan')
                <div class="dn-grid-dashboard">

                    {{-- Zakat Fitrah & Nisab --}}
                    <section class="dn-card-modern">
                        <h3><i class="fa-solid fa-calculator text-teal-600"></i> Zakat Fitrah &amp; Nisab</h3>
                        <p>Atur nominal standar per jiwa untuk kalkulator zakat publik.</p>
                        <form action="{{ route('admin.donasi.pengaturan') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nominal per Jiwa (Rp)</label>
                                <input type="number" step="1000" min="1000" name="zakat_fitrah_default" class="form-control-modern" value="{{ $mosque->zakat_fitrah_default ?? 45000 }}" required>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nisab Zakat Mal (Rp)</label>
                                <input type="number" step="1000" min="0" name="zakat_nisab" class="form-control-modern" value="{{ $mosque->zakat_nisab ?? 85000000 }}" required>
                                <small style="color: #94a3b8; font-size: 0.75rem;">Dipakai kalkulator zakat mal (2,5%) di halaman publik.</small>
                            </div>
                            <button type="submit" class="btn-primary-modern" style="width: 100%; justify-content: center;">Simpan Pengaturan</button>
                        </form>
                    </section>

                    {{-- Rekening Bank --}}
                    <section class="dn-card-modern">
                        <h3><i class="fa-solid fa-building-columns text-teal-600"></i> Rekening Bank</h3>
                        <p>Tambahkan rekening bank untuk transfer manual.</p>

                        <form action="{{ route('admin.donasi.rekening.store') }}" method="POST" style="display: grid; gap: 0.75rem; margin-bottom: 1.25rem;">
                            @csrf
                            <input type="text" name="bank_name" class="form-control-modern" placeholder="Nama Bank (mis. BCA, BSI)" required maxlength="100">
                            <input type="text" name="account_number" class="form-control-modern" placeholder="Nomor Rekening" required maxlength="50">
                            <input type="text" name="account_holder" class="form-control-modern" placeholder="Atas Nama" required maxlength="100">
                            <button type="submit" class="btn-primary-modern" style="justify-content: center;"><i class="fa-solid fa-plus"></i> Tambah Rekening</button>
                        </form>

                        @if ($bankAccounts->isEmpty())
                            <div class="dn-empty" style="text-align:center; padding:1rem; color:#64748b; font-size: 0.85rem;">Belum ada rekening bank.</div>
                        @else
                            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                                @foreach ($bankAccounts as $rek)
                                    <details style="border:1px solid #e2e8f0; border-radius:0.6rem; padding:0.75rem;">
                                        <summary style="cursor:pointer; display:flex; justify-content:space-between; align-items:center; font-weight:600; font-size:0.85rem;">
                                            <span>{{ $rek->bank_name }} — {{ $rek->account_number }}</span>
                                            <span style="font-size:0.65rem; padding:0.15rem 0.4rem; border-radius:999px; {{ $rek->is_active ? 'background:#dcfce7;color:#15803d;' : 'background:#f1f5f9;color:#64748b;' }}">
                                                {{ $rek->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </summary>
                                        <div style="margin-top:0.75rem; display:flex; flex-direction:column; gap:0.5rem;">
                                            <form action="{{ route('admin.donasi.rekening.update', $rek->id) }}" method="POST" style="display:grid; gap:0.5rem;">
                                                @csrf @method('PUT')
                                                <input type="text" name="bank_name" class="form-control-modern" value="{{ $rek->bank_name }}" required maxlength="100">
                                                <input type="text" name="account_number" class="form-control-modern" value="{{ $rek->account_number }}" required maxlength="50">
                                                <input type="text" name="account_holder" class="form-control-modern" value="{{ $rek->account_holder }}" required maxlength="100">
                                                <button type="submit" class="btn-primary-modern" style="padding:0.35rem 0.7rem; font-size:0.8rem;">Simpan</button>
                                            </form>
                                            <div style="display:flex; gap:0.5rem;">
                                                <form action="{{ route('admin.donasi.rekening.toggle', $rek->id) }}" method="POST" style="flex:1;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" style="width:100%; background:#f1f5f9; border:1px solid #cbd5e1; padding:0.3rem; border-radius:0.4rem; cursor:pointer; font-size:0.75rem;">
                                                        {{ $rek->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.donasi.rekening.destroy', $rek->id) }}" method="POST" onsubmit="return confirm('Hapus rekening ini?');" style="flex:1;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="width:100%; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:0.3rem; border-radius:0.4rem; cursor:pointer; font-size:0.75rem;">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    {{-- QRIS --}}
                    <section class="dn-card-modern">
                        <h3><i class="fa-solid fa-qrcode text-teal-600"></i> QRIS Masjid</h3>
                        <p>Unggah gambar QRIS untuk halaman publik.</p>

                        @if ($mosque->qris_image)
                            <div style="text-align:center; margin-bottom:1rem;">
                                <img src="{{ $mosque->qris_url }}" alt="QRIS" style="max-width:150px; border:1px solid #e2e8f0; border-radius:0.5rem; padding:0.4rem; background: #fff;">
                                <form action="{{ route('admin.donasi.qris.destroy') }}" method="POST" onsubmit="return confirm('Hapus gambar QRIS?');" style="margin-top:0.5rem;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:0.3rem 0.6rem; border-radius:0.4rem; cursor:pointer; font-size:0.75rem;">Hapus QRIS</button>
                                </form>
                            </div>
                        @endif

                        <form action="{{ route('admin.donasi.qris.update') }}" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:0.75rem;">
                            @csrf
                            <input type="file" name="qris_image" accept="image/png, image/jpeg" class="form-control-modern" required style="padding: 0.4rem; font-size: 0.85rem;">
                            <button type="submit" class="btn-primary-modern" style="justify-content:center; font-size: 0.85rem;">{{ $mosque->qris_image ? 'Ganti QRIS' : 'Unggah QRIS' }}</button>
                        </form>
                    </section>
                </div>
            @endif

            {{-- =====================================================
                 TAB 3: PENYALURAN (dokumentasi realisasi)
                 ===================================================== --}}
            @if ($tab === 'penyaluran')
                <div class="dn-grid-dashboard" style="margin-bottom: 1.5rem;">
                    <div class="dn-card-modern">
                        <h3><i class="fa-solid fa-sack-dollar text-teal-600"></i> Total Diterima</h3>
                        <p class="dn-stat" style="color: #0f766e;">Rp {{ number_format($summary['total_diterima'], 0, ',', '.') }}</p>
                    </div>
                    <div class="dn-card-modern">
                        <h3><i class="fa-solid fa-hand-holding-heart text-teal-600"></i> Total Tersalurkan</h3>
                        <p class="dn-stat" style="color: #0369a1;">Rp {{ number_format($summary['total_tersalurkan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="dn-card-modern">
                        <h3><i class="fa-solid fa-wallet text-teal-600"></i> Sisa Dana</h3>
                        <p class="dn-stat" style="color: #b45309;">Rp {{ number_format($summary['total_diterima'] - $summary['total_tersalurkan'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <section class="dn-card-modern" style="margin-bottom: 1.5rem;">
                    <h3><i class="fa-solid fa-images text-teal-600"></i> Dokumentasi Penyaluran</h3>
                    <p>Unggah foto bukti nyata penyaluran dana ke jamaah.</p>
                    <form action="{{ route('admin.donasi.galeri.store') }}" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Judul Kegiatan</label>
                            <input type="text" name="judul" class="form-control-modern" placeholder="Mis. Sembako Ramadhan" value="{{ old('judul') }}" required maxlength="120">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Kategori</label>
                            <select name="kategori" class="form-control-modern">
                                <option value="">Umum</option>
                                @foreach ($donationCategories as $cat)
                                    <option value="{{ $cat->key }}" {{ old('kategori') === $cat->key ? 'selected' : '' }}>{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control-modern" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nominal Terpakai (Rp)</label>
                            <input type="number" min="0" step="1000" name="nominal_terpakai" class="form-control-modern" placeholder="0" value="{{ old('nominal_terpakai') }}">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control-modern" rows="2" placeholder="Ceritakan singkat..." maxlength="300">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Pilih Foto (JPG/PNG, maks. 2MB)</label>
                            <input type="file" name="foto" accept="image/png, image/jpeg" class="form-control-modern" onchange="dnPreviewFoto(this)" required>
                            <img id="dn-foto-preview" class="dn-preview hidden" alt="Pratinjau foto">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <button type="submit" class="btn-primary-modern"><i class="fa-solid fa-upload"></i> Unggah Dokumentasi</button>
                        </div>
                    </form>
                </section>

                <section class="dn-card-modern">
                    <h3><i class="fa-solid fa-photo-film text-teal-600"></i> Galeri Dokumentasi Tersimpan</h3>
                    <p>Total {{ $items->count() }} foto telah dipublikasikan ke halaman donasi.</p>

                    @if ($items->isEmpty())
                        <div class="dn-empty" style="text-align: center; padding: 2rem; color: #64748b;">Belum ada dokumentasi foto yang diunggah.</div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
                            @foreach ($items as $item)
                                <div style="border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; position: relative;">
                                    <div style="height: 160px; background-size: cover; background-position: center; background-image:url('{{ $item->foto_url }}')"></div>
                                    <div style="padding: 1rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div>
                                            @php
                                                $catTitle = 'Umum';
                                                $matched = $donationCategories->firstWhere('key', $item->kategori);
                                                if ($matched) { $catTitle = $matched->title; }
                                            @endphp
                                            <span style="font-size: 0.7rem; background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.5rem; border-radius: 999px; font-weight: 600;">{{ $catTitle }}</span>
                                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0.5rem 0 0.25rem 0;">{{ $item->judul }}</h4>
                                            <p style="font-size: 0.8rem; color: #64748b; margin: 0;">{{ optional($item->tanggal)->translatedFormat('d F Y') ?? $item->tanggal }}</p>
                                            @if ($item->nominal_terpakai)
                                                <p style="font-size: 0.8rem; color: #0f766e; font-weight: 600; margin: 0.25rem 0 0;">Rp {{ number_format($item->nominal_terpakai, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('admin.donasi.galeri.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?');" style="margin-top: 1rem; display: flex; justify-content: flex-end;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #fee2e2; color: #991b1b; border: none; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; cursor: pointer;" aria-label="Hapus">
                                                <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif

            {{-- =====================================================
                 TAB 4: REKAP / PELAPORAN
                 ===================================================== --}}
            @if ($tab === 'rekap')
                <div class="dn-grid-dashboard" style="margin-bottom: 1.5rem;">
                    <div class="dn-card-modern">
                        <h3><i class="fa-solid fa-sack-dollar text-teal-600"></i> Total Diterima (Terverifikasi)</h3>
                        <p class="dn-stat" style="color: #0f766e;">Rp {{ number_format($summary['total_diterima'], 0, ',', '.') }}</p>
                    </div>
                    <div class="dn-card-modern">
                        <h3><i class="fa-solid fa-hourglass-half text-teal-600"></i> Menunggu Verifikasi</h3>
                        <p class="dn-stat" style="color: #b45309;">Rp {{ number_format($summary['total_menunggu'], 0, ',', '.') }}</p>
                    </div>
                    <div class="dn-card-modern">
                        <h3><i class="fa-solid fa-users text-teal-600"></i> Donatur Terverifikasi</h3>
                        <p class="dn-stat" style="color: #0f766e;">{{ $summary['jumlah_donatur'] }}</p>
                    </div>
                </div>

                {{-- Filter --}}
                <section class="dn-card-modern dn-noprint" style="margin-bottom: 1.5rem;">
                    <form method="GET" action="{{ route('admin.donasi.tab', 'rekap') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; align-items: end;">
                        <div>
                            <label style="display:block; font-size:.8rem; font-weight:600; margin-bottom:.4rem;">Status</label>
                            <select name="status" class="form-control-modern">
                                <option value="">Semua</option>
                                @foreach (['pending' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $v => $l)
                                    <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:.8rem; font-weight:600; margin-bottom:.4rem;">Kategori</label>
                            <select name="kategori" class="form-control-modern">
                                <option value="">Semua</option>
                                @foreach ($donationCategories as $cat)
                                    <option value="{{ $cat->key }}" {{ request('kategori') === $cat->key ? 'selected' : '' }}>{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:.8rem; font-weight:600; margin-bottom:.4rem;">Dari Tanggal</label>
                            <input type="date" name="dari" class="form-control-modern" value="{{ request('dari') }}">
                        </div>
                        <div>
                            <label style="display:block; font-size:.8rem; font-weight:600; margin-bottom:.4rem;">Sampai Tanggal</label>
                            <input type="date" name="sampai" class="form-control-modern" value="{{ request('sampai') }}">
                        </div>
                        <div style="display:flex; gap:.5rem;">
                            <button type="submit" class="btn-primary-modern"><i class="fa-solid fa-filter"></i> Terapkan</button>
                            <a href="{{ route('admin.donasi.tab', 'rekap') }}" class="btn-primary-modern" style="background:#f1f5f9; color:#334155;">Reset</a>
                        </div>
                    </form>
                </section>

                {{-- Rekap per kategori --}}
                <section class="dn-card-modern" style="margin-bottom: 1.5rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
                        <div>
                            <h3><i class="fa-solid fa-chart-pie text-teal-600"></i> Rekap per Kategori</h3>
                            <p>Hanya donasi berstatus <b>Diterima</b>{{ request()->hasAny(['kategori','dari','sampai']) ? ', sesuai filter yang dipilih' : '' }}.</p>
                        </div>
                        <button type="button" onclick="window.print()" class="btn-primary-modern dn-noprint" style="background:#f1f5f9; color:#334155;">
                            <i class="fa-solid fa-print"></i> Cetak Laporan
                        </button>
                    </div>

                    @if ($rekapKategori->isEmpty())
                        <div class="dn-empty" style="text-align:center; padding:1.5rem; color:#64748b;">Belum ada donasi diterima pada rentang ini.</div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="dn-table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah Donasi</th>
                                        <th>Total Diterima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekapKategori as $r)
                                        <tr>
                                            <td style="font-weight:600;">{{ $r['title'] }}</td>
                                            <td>{{ $r['jumlah'] }}</td>
                                            <td>Rp {{ number_format($r['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr style="font-weight:700; background:#f8fafc;">
                                        <td>Total</td>
                                        <td>{{ $rekapKategori->sum('jumlah') }}</td>
                                        <td>Rp {{ number_format($rekapKategori->sum('total'), 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                {{-- Daftar donasi masuk + verifikasi --}}
                <section class="dn-card-modern">
                    <h3><i class="fa-solid fa-list-check text-teal-600"></i> Daftar Donasi Masuk</h3>
                    <p class="dn-noprint">Cek mutasi rekening/e-wallet masjid, lalu tandai donasi sebagai <b>Diterima</b> atau <b>Ditolak</b>.</p>

                    @if ($donations->isEmpty())
                        <div class="dn-empty" style="text-align: center; padding: 2rem; color: #64748b;">Tidak ada donasi yang cocok dengan filter.</div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="dn-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Referensi</th>
                                        <th>Donatur</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Nominal</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th class="dn-noprint">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donations as $don)
                                        @php
                                            $statusStyle = match($don->status) {
                                                'diterima' => 'background:#dcfce7;color:#15803d;',
                                                'ditolak'  => 'background:#fee2e2;color:#991b1b;',
                                                default    => 'background:#fef3c7;color:#92400e;',
                                            };
                                        @endphp
                                        <tr>
                                            <td style="white-space:nowrap;">{{ optional($don->created_at)->translatedFormat('d M Y H:i') }}</td>
                                            <td style="font-weight: 600;">{{ $don->no_referensi }}</td>
                                            <td>{{ $don->nama_donatur }}</td>
                                            <td>{{ $don->category_title }}</td>
                                            <td class="dn-note">{{ $don->keterangan ?: '-' }}</td>
                                            <td style="white-space:nowrap;">Rp {{ number_format($don->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $don->metode_pembayaran }}</td>
                                            <td><span style="font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 999px; {{ $statusStyle }}">{{ ucfirst($don->status) }}</span></td>
                                            <td class="dn-noprint" style="white-space:nowrap;">
                                                @if ($don->status !== 'diterima')
                                                    <form action="{{ route('admin.donasi.status', $don->id) }}" method="POST" style="display:inline;">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="diterima">
                                                        <button type="submit" style="background:#dcfce7;color:#15803d;border:1px solid #a7f3d0;padding:0.3rem 0.6rem;border-radius:0.4rem;cursor:pointer;font-size:0.75rem;">Terima</button>
                                                    </form>
                                                @endif
                                                @if ($don->status !== 'ditolak')
                                                    <form action="{{ route('admin.donasi.status', $don->id) }}" method="POST" style="display:inline;">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="ditolak">
                                                        <button type="submit" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:0.3rem 0.6rem;border-radius:0.4rem;cursor:pointer;font-size:0.75rem;">Tolak</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination sederhana --}}
                        @if ($donations->hasPages())
                            <div class="dn-noprint" style="display:flex; justify-content:space-between; align-items:center; margin-top:1rem; font-size:.85rem; color:#64748b;">
                                <span>Halaman {{ $donations->currentPage() }} dari {{ $donations->lastPage() }} ({{ $donations->total() }} donasi)</span>
                                <div style="display:flex; gap:.5rem;">
                                    @if ($donations->onFirstPage())
                                        <span class="btn-primary-modern" style="background:#f1f5f9; color:#94a3b8; cursor:not-allowed;">&larr; Sebelumnya</span>
                                    @else
                                        <a href="{{ $donations->previousPageUrl() }}" class="btn-primary-modern" style="background:#f1f5f9; color:#334155;">&larr; Sebelumnya</a>
                                    @endif
                                    @if ($donations->hasMorePages())
                                        <a href="{{ $donations->nextPageUrl() }}" class="btn-primary-modern">Berikutnya &rarr;</a>
                                    @else
                                        <span class="btn-primary-modern" style="background:#f1f5f9; color:#94a3b8; cursor:not-allowed;">Berikutnya &rarr;</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                </section>
            @endif

        </main>
    </div>

    <button class="ba2-fab" aria-label="Bantuan" style="position: fixed; bottom: 2rem; right: 2rem; width: 48px; height: 48px; border-radius: 50%; background: #0f766e; color: white; border: none; font-size: 1.2rem; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">?</button>

    <script src="{{ asset('js/adminmasjid/donasi.js') }}"></script>
    <script>
        function dnPreviewFoto(input) {
            const preview = document.getElementById('dn-foto-preview');
            if (!preview || !input.files || !input.files[0]) return;
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2MB.');
                input.value = '';
                preview.classList.add('hidden');
                return;
            }
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    </script>
</body>
</html>