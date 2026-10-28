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

        /* ===== Edit penyaluran (rincian) ===== */
        .rincian-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: .4rem; margin-bottom: .5rem; }
        .rincian-row .rincian-label { grid-column: 1 / -1; }
        .rincian-hapus { background: #fee2e2; border: 1px solid #fecaca; border-radius: .4rem; padding: 0 .7rem; cursor: pointer; }
        .rincian-tambah { background: #f1f5f9; border: 1px solid #cbd5e1; padding: .35rem .7rem; border-radius: .4rem; cursor: pointer; font-size: .78rem; }

        /* ===== Catat uang masuk / keluar per kategori ===== */
        .dn-flow-box { display: grid; gap: .5rem; padding: 1rem; border-radius: .75rem; }
        .dn-flow-box .form-control-modern { padding: .55rem .8rem; font-size: .85rem; }
        .dn-flow-in  { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .dn-flow-out { background: #fef2f2; border: 1px solid #fecaca; }

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

            {{-- Donasi + submenu --}}
            <div class="ba2-nav-group {{ request()->routeIs('admin.donasi*') ? 'open' : '' }}">
                <a href="#" class="ba2-nav-item {{ request()->routeIs('admin.donasi*') ? 'active' : '' }}"
                    onclick="this.closest('.ba2-nav-group').classList.toggle('open'); return false;">
                    <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="ba2-nav-label">Donasi</span>
                    <i class="fa-solid fa-chevron-down ba2-caret"></i>
                </a>
                <div class="ba2-sub">
                    @foreach ($subLabels as $key => $label)
                        <a href="{{ route('admin.donasi.tab', $key) }}" class="{{ isset($tab) && $tab === $key ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('admin.jamaah') }}" class="ba2-nav-item {{ request()->routeIs('admin.jamaah*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
            </a>

            {{-- MENU TEMA TAMPILAN --}}
            <a href="{{ route('admin.tema') }}" class="ba2-nav-item {{ request()->routeIs('admin.tema*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-palette"></i></span>
                <span class="ba2-nav-label">Tema Tampilan</span>
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
                    <p>Atur jenis donasi beserta foto banner program yang akan tampil pada halaman publik. Uang masuk dan uang keluar tiap program bisa dicatat langsung di sini.</p>

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
                                @php
                                    $kMasuk  = (float) ($masukPerJenis[$kategori->key] ?? 0);
                                    $kKeluar = (float) ($keluarPerKategori[$kategori->key] ?? 0);
                                    $kSisa   = $kMasuk - $kKeluar;
                                @endphp
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

                                        {{-- ===== Ringkasan + catat uang masuk / keluar ===== --}}
                                        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed #cbd5e1;">
                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: .75rem; margin-bottom: 1rem;">
                                                <div style="background: #f0fdf4; border-radius: .6rem; padding: .7rem;">
                                                    <div style="font-size: .7rem; color: #64748b;">Uang Masuk</div>
                                                    <b style="color: #15803d;">Rp {{ number_format($kMasuk, 0, ',', '.') }}</b>
                                                </div>
                                                <div style="background: #fef2f2; border-radius: .6rem; padding: .7rem;">
                                                    <div style="font-size: .7rem; color: #64748b;">Uang Keluar</div>
                                                    <b style="color: #dc2626;">Rp {{ number_format($kKeluar, 0, ',', '.') }}</b>
                                                </div>
                                                <div style="background: #f8fafc; border-radius: .6rem; padding: .7rem;">
                                                    <div style="font-size: .7rem; color: #64748b;">Sisa Dana</div>
                                                    <b>Rp {{ number_format($kSisa, 0, ',', '.') }}</b>
                                                </div>
                                            </div>

                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                                                {{-- Catat Uang Masuk --}}
                                                <form action="{{ route('admin.donasi.kategori.uangmasuk', $kategori->id) }}" method="POST" class="dn-flow-box dn-flow-in">
                                                    @csrf
                                                    <b style="font-size: .85rem; color: #15803d;"><i class="fa-solid fa-arrow-down"></i> Catat Uang Masuk</b>
                                                    <input type="text" name="nama_donatur" class="form-control-modern" placeholder="Nama donatur (kosong = Hamba Allah)" maxlength="100">
                                                    <input type="number" name="nominal" class="form-control-modern" placeholder="Nominal (Rp)" min="1000" required>
                                                    <input type="date" name="tanggal" class="form-control-modern" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                    <select name="metode_pembayaran" class="form-control-modern" required>
                                                        <option>Tunai</option>
                                                        <option>Transfer Bank</option>
                                                        <option>QRIS</option>
                                                    </select>
                                                    <input type="text" name="keterangan" class="form-control-modern" placeholder="Keterangan (opsional)" maxlength="500">
                                                    <button type="submit" class="btn-primary-modern" style="justify-content: center; padding: .55rem 1rem; font-size: .85rem;">Simpan Uang Masuk</button>
                                                </form>

                                                {{-- Catat Uang Keluar --}}
                                                <form action="{{ route('admin.donasi.kategori.penyaluran', $kategori->id) }}" method="POST" enctype="multipart/form-data" class="dn-flow-box dn-flow-out">
                                                    @csrf
                                                    <b style="font-size: .85rem; color: #dc2626;"><i class="fa-solid fa-arrow-up"></i> Catat Uang Keluar (Penyaluran)</b>
                                                    <input type="text" name="judul" class="form-control-modern" placeholder="Judul (kosong = Penyaluran {{ $kategori->title }})" maxlength="150">
                                                    <input type="number" name="nominal_terpakai" class="form-control-modern" placeholder="Nominal keluar (Rp)" min="1" required>
                                                    <input type="date" name="tanggal" class="form-control-modern" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                    <input type="text" name="tujuan" class="form-control-modern" placeholder="Dikirim ke / tujuan (mis. BRI 1234xxx)" maxlength="150">
                                                    <textarea name="deskripsi" rows="2" class="form-control-modern" placeholder="Keterangan penggunaan dana" maxlength="1000"></textarea>
                                                    <input type="file" name="foto" accept="image/png, image/jpeg" class="form-control-modern" style="padding: .4rem; font-size: .8rem;">
                                                    <button type="submit" class="btn-primary-modern" style="justify-content: center; padding: .55rem 1rem; font-size: .85rem; background: #dc2626;">Simpan Uang Keluar</button>
                                                </form>
                                            </div>

                                            {{-- ===== Riwayat uang masuk & keluar (bisa diedit) ===== --}}
                                            @php
                                                $rk = $riwayatKeluar[strtolower(trim((string) $kategori->key))] ?? collect();
                                                $rm = $riwayatMasuk[$kategori->key] ?? collect();
                                            @endphp

                                            <div style="margin-top: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; align-items: start;">

                                                {{-- Riwayat Uang Masuk (manual) --}}
                                                <div>
                                                    <b style="font-size: .85rem; color: #15803d;">Riwayat Uang Masuk (catatan manual)</b>
                                                    @forelse ($rm as $m)
                                                        <details style="margin-top: .5rem; border: 1px solid #bbf7d0; border-radius: .6rem; padding: .6rem; background: #fff;">
                                                            <summary style="cursor: pointer; font-size: .8rem; font-weight: 600; display: flex; justify-content: space-between; gap: .5rem;">
                                                                <span>{{ optional($m->created_at)->format('d/m/Y') }} · {{ $m->nama_donatur }}</span>
                                                                <span style="color: #15803d;">Rp {{ number_format($m->nominal, 0, ',', '.') }}</span>
                                                            </summary>
                                                            <form action="{{ route('admin.donasi.uangmasuk.update', $m->id) }}" method="POST" style="display: grid; gap: .5rem; margin-top: .6rem;">
                                                                @csrf @method('PUT')
                                                                <input type="text" name="nama_donatur" class="form-control-modern" value="{{ $m->nama_donatur }}" placeholder="Nama donatur" maxlength="100">
                                                                <input type="number" name="nominal" class="form-control-modern" value="{{ (int) $m->nominal }}" min="1000" required>
                                                                <input type="date" name="tanggal" class="form-control-modern" value="{{ optional($m->created_at)->format('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                                <select name="metode_pembayaran" class="form-control-modern" required>
                                                                    @foreach (['Tunai', 'Transfer Bank', 'QRIS'] as $mt)
                                                                        <option {{ $m->metode_pembayaran === $mt ? 'selected' : '' }}>{{ $mt }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="text" name="keterangan" class="form-control-modern" value="{{ $m->keterangan }}" placeholder="Keterangan" maxlength="500">
                                                                <button type="submit" class="btn-primary-modern" style="justify-content: center; padding: .45rem 1rem; font-size: .8rem;">Simpan Perubahan</button>
                                                            </form>
                                                            <form action="{{ route('admin.donasi.uangmasuk.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus catatan uang masuk ini?');" style="margin-top: .4rem;">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" style="width: 100%; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: .35rem; border-radius: .4rem; cursor: pointer; font-size: .75rem;">Hapus</button>
                                                            </form>
                                                        </details>
                                                    @empty
                                                        <div style="margin-top: .5rem; font-size: .78rem; color: #94a3b8;">Belum ada catatan manual. Donasi online diverifikasi di tab Rekap Data.</div>
                                                    @endforelse
                                                </div>

                                                {{-- Riwayat Uang Keluar --}}
                                                <div>
                                                    <b style="font-size: .85rem; color: #dc2626;">Riwayat Uang Keluar (Penyaluran)</b>
                                                    @forelse ($rk as $g)
                                                        <details style="margin-top: .5rem; border: 1px solid #fecaca; border-radius: .6rem; padding: .6rem; background: #fff;">
                                                            <summary style="cursor: pointer; font-size: .8rem; font-weight: 600; display: flex; justify-content: space-between; gap: .5rem;">
                                                                <span>{{ optional($g->tanggal)->format('d/m/Y') }} · {{ \Illuminate\Support\Str::limit($g->judul, 28) }}</span>
                                                                <span style="color: #dc2626;">Rp {{ number_format((float) $g->nominal_terpakai, 0, ',', '.') }}</span>
                                                            </summary>
                                                            <form action="{{ route('admin.donasi.galeri.update', $g->id) }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: .5rem; margin-top: .6rem;">
                                                                @csrf @method('PUT')
                                                                <input type="hidden" name="kategori" value="{{ $kategori->key }}">
                                                                <input type="text" name="judul" class="form-control-modern" value="{{ $g->judul }}" placeholder="Judul" required maxlength="150">
                                                                <input type="date" name="tanggal" class="form-control-modern" value="{{ optional($g->tanggal)->format('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                                <input type="number" name="nominal_terpakai" class="form-control-modern" value="{{ $g->nominal_terpakai !== null ? (int) $g->nominal_terpakai : '' }}" placeholder="Uang keluar (Rp)" min="0">
                                                                <small style="color: #94a3b8; font-size: .7rem;">Kalau rincian di bawah diisi, angka ini otomatis diganti jumlah rincian.</small>
                                                                <input type="text" name="tujuan" class="form-control-modern" value="{{ $g->tujuan }}" placeholder="Dikirim ke rekening / tujuan" maxlength="150">
                                                                <textarea name="deskripsi" rows="2" class="form-control-modern" placeholder="Keterangan" maxlength="1000">{{ $g->deskripsi }}</textarea>

                                                                <div>
                                                                    <label style="font-size: .75rem; font-weight: 600;">Dipakai untuk apa saja</label>
                                                                    <div class="rincian-wrap" data-next="{{ count($g->rincian ?? []) }}" style="margin: .4rem 0;">
                                                                        @foreach ($g->rincian ?? [] as $i => $r)
                                                                            <div class="rincian-row">
                                                                                <input type="text" name="rincian[{{ $i }}][label]" value="{{ $r['label'] ?? '' }}" class="form-control-modern rincian-label" placeholder="Untuk apa (mis. Beli semen)">
                                                                                <input type="number" name="rincian[{{ $i }}][nominal]" value="{{ (int) ($r['nominal'] ?? 0) }}" class="form-control-modern" placeholder="Nominal" min="0">
                                                                                <input type="text" name="rincian[{{ $i }}][keterangan]" value="{{ $r['keterangan'] ?? '' }}" class="form-control-modern" placeholder="Keterangan">
                                                                                <button type="button" class="rincian-hapus">✕</button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" class="rincian-tambah">+ Tambah Rincian</button>
                                                                </div>

                                                                @if ($g->foto_url)
                                                                    <img src="{{ $g->foto_url }}" alt="Foto" style="max-height: 70px; border-radius: 6px;">
                                                                @endif
                                                                <input type="file" name="foto" accept="image/png, image/jpeg" class="form-control-modern" style="padding: .4rem; font-size: .8rem;">
                                                                <button type="submit" class="btn-primary-modern" style="justify-content: center; padding: .45rem 1rem; font-size: .8rem;">Simpan Perubahan</button>
                                                            </form>
                                                            <form action="{{ route('admin.donasi.galeri.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus catatan penyaluran ini?');" style="margin-top: .4rem;">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" style="width: 100%; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: .35rem; border-radius: .4rem; cursor: pointer; font-size: .75rem;">Hapus</button>
                                                            </form>
                                                        </details>
                                                    @empty
                                                        <div style="margin-top: .5rem; font-size: .78rem; color: #94a3b8;">Belum ada penyaluran untuk program ini.</div>
                                                    @endforelse
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @endif
                </section>

                <script>
                    // Tambah / hapus baris rincian penggunaan dana (tab Kategori)
                    document.addEventListener('click', function (e) {
                        if (e.target.classList.contains('rincian-tambah')) {
                            var wrap = e.target.previousElementSibling;
                            var i = parseInt(wrap.dataset.next, 10);
                            wrap.dataset.next = i + 1;
                            wrap.insertAdjacentHTML('beforeend',
                                '<div class="rincian-row">' +
                                '<input type="text" name="rincian[' + i + '][label]" class="form-control-modern rincian-label" placeholder="Untuk apa (mis. Beli semen)">' +
                                '<input type="number" name="rincian[' + i + '][nominal]" class="form-control-modern" placeholder="Nominal" min="0">' +
                                '<input type="text" name="rincian[' + i + '][keterangan]" class="form-control-modern" placeholder="Keterangan">' +
                                '<button type="button" class="rincian-hapus">✕</button></div>');
                        }
                        if (e.target.classList.contains('rincian-hapus')) {
                            e.target.closest('.rincian-row').remove();
                        }
                    });
                </script>
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
                 TAB 3: PENYALURAN (satu kartu per program, riwayat di dalam kartu)
                 ===================================================== --}}
            @if ($tab === 'penyaluran')
                @php
                    // Satu fungsi pencocokan (key / id / title) supaya konsisten di semua tempat
                    $matchProgram = function ($item, $c) {
                        $k = strtolower(trim((string) $item->kategori));
                        if ($k === '') {
                            return false;
                        }
                        return in_array($k, [
                            strtolower(trim((string) $c->key)),
                            strtolower(trim((string) $c->id)),
                            strtolower(trim((string) $c->title)),
                        ], true);
                    };

                    // Rekap tiap program: dana masuk, tersalurkan, sisa + daftar catatan penyalurannya
                    $grupProgram = $donationCategories->map(function ($c) use ($mosque, $items, $matchProgram) {
                        $masuk  = (float) \App\Models\Donasi::where('mosque_id', $mosque->id)
                                    ->where('jenis', $c->key)->where('status', 'diterima')->sum('nominal');
                        $docs   = $items->filter(fn ($i) => $matchProgram($i, $c))->values();
                        $keluar = (float) $docs->sum('nominal_terpakai');
                        return (object) [
                            'key'    => $c->key,
                            'title'  => $c->title,
                            'image'  => $c->image,
                            'umum'   => false,
                            'masuk'  => $masuk,
                            'keluar' => $keluar,
                            'sisa'   => $masuk - $keluar,
                            'pct'    => $masuk > 0 ? min(round($keluar / $masuk * 100), 100) : 0,
                            'dok'    => $docs->filter(fn ($i) => !empty($i->foto))->count(),
                            'items'  => $docs,
                        ];
                    });

                    // Catatan yang tidak cocok dengan program mana pun -> dikelompokkan di kartu "Umum"
                    $itemUmum = $items->filter(
                        fn ($i) => !$donationCategories->contains(fn ($c) => $matchProgram($i, $c))
                    )->values();

                    $grupAll = $grupProgram->values();
                    if ($itemUmum->isNotEmpty()) {
                        $grupAll->push((object) [
                            'key'    => '',
                            'title'  => 'Umum',
                            'image'  => null,
                            'umum'   => true,
                            'masuk'  => 0,
                            'keluar' => (float) $itemUmum->sum('nominal_terpakai'),
                            'sisa'   => 0,
                            'pct'    => 0,
                            'dok'    => $itemUmum->filter(fn ($i) => !empty($i->foto))->count(),
                            'items'  => $itemUmum,
                        ]);
                    }
                @endphp

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

                <section class="dn-card-modern" id="dn-form-galeri" style="margin-bottom: 1.5rem;">
                    <h3><i class="fa-solid fa-images text-teal-600"></i> Dokumentasi Penyaluran</h3>
                    <p>Unggah foto bukti nyata penyaluran dana ke jamaah. Pilih program agar otomatis masuk ke penyaluran program tersebut. Untuk mencatat uang keluar tanpa foto, gunakan menu Kategori.</p>
                    <form action="{{ route('admin.donasi.galeri.store') }}" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                        @csrf
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Judul Kegiatan</label>
                            <input type="text" name="judul" id="dn-judul" class="form-control-modern" placeholder="Mis. Sembako Ramadhan" value="{{ old('judul') }}" required maxlength="120">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Kategori / Program</label>
                            <select name="kategori" id="dn-kategori-select" class="form-control-modern">
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
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Dikirim ke Rekening / Tujuan</label>
                            <input type="text" name="tujuan" class="form-control-modern" placeholder="Mis. Panitia Pembangunan / BRI 1234xxx" value="{{ old('tujuan') }}" maxlength="150">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control-modern" rows="2" placeholder="Ceritakan singkat..." maxlength="1000">{{ old('deskripsi') }}</textarea>
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
                    <p>Setiap program donasi punya satu kartu. Catatan penyaluran ({{ $items->count() }} tersimpan) berada di dalam kartu programnya, di bagian <b>Riwayat penyaluran</b>. Klik sebuah catatan untuk mengubah uang keluar, tujuan, keterangan, dan rincian penggunaan dana.</p>

                    @if ($grupAll->isEmpty())
                        <div class="dn-empty" style="text-align: center; padding: 2rem; color: #64748b;">Belum ada dokumentasi penyaluran.</div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-top: 1rem; align-items: start;">

                            @foreach ($grupAll as $rp)
                                @php
                                    // Sampul: banner program -> kalau tidak ada, pakai foto catatan penyaluran pertama
                                    $cover = !empty($rp->image)
                                        ? asset('storage/' . $rp->image)
                                        : optional($rp->items->first(fn ($i) => !empty($i->foto_url)))->foto_url;
                                @endphp
                                <div style="border: 1px solid {{ $rp->umum ? '#e2e8f0' : '#99f6e4' }}; border-radius: 0.75rem; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; position: relative;">
                                    <div style="position: relative; height: 160px; background-size: cover; background-position: center; background-color: #0f766e; {{ $cover ? "background-image:url('" . $cover . "');" : 'background-image: linear-gradient(150deg,#0f766e,#115e59);' }}">
                                        <span style="position:absolute; top:10px; left:10px; background:rgba(13,148,136,.95); color:#fff; padding:3px 10px; border-radius:999px; font-size:.65rem; font-weight:700; text-transform:uppercase;">
                                            {{ $rp->umum ? 'Tanpa Program' : 'Program · Otomatis' }}
                                        </span>
                                    </div>

                                    <div style="padding: 1rem; display: flex; flex-direction: column; gap: .75rem;">
                                        <div>
                                            <span style="font-size: 0.7rem; background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.5rem; border-radius: 999px; font-weight: 600;">{{ $rp->title }}</span>
                                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0.5rem 0 0.5rem 0;">
                                                {{ $rp->umum ? 'Penyaluran Umum' : 'Penyaluran ' . $rp->title }}
                                            </h4>

                                            <div style="display:grid; gap:3px; font-size:.78rem;">
                                                @unless ($rp->umum)
                                                    <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Dana masuk</span><b style="color:#10b981;">Rp {{ number_format($rp->masuk, 0, ',', '.') }}</b></div>
                                                @endunless
                                                <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Tersalurkan</span><b style="color:#ef4444;">Rp {{ number_format($rp->keluar, 0, ',', '.') }}</b></div>
                                                @unless ($rp->umum)
                                                    <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Sisa dana</span><b style="color:#0f172a;">Rp {{ number_format($rp->sisa, 0, ',', '.') }}</b></div>
                                                @endunless
                                            </div>

                                            @unless ($rp->umum)
                                                <div style="margin-top:.5rem; width:100%; height:6px; background:#e2e8f0; border-radius:999px; overflow:hidden;">
                                                    <div style="width: {{ $rp->pct }}%; height:100%; background: linear-gradient(90deg,#0d9488,#14b8a6);"></div>
                                                </div>
                                                <div style="margin-top:4px; font-size:.7rem; color:#94a3b8;">{{ $rp->pct }}% tersalurkan &middot; {{ $rp->dok }} dokumentasi foto</div>
                                            @else
                                                <div style="margin-top:4px; font-size:.7rem; color:#94a3b8;">{{ $rp->dok }} dokumentasi foto</div>
                                            @endunless
                                        </div>

                                        <button type="button" class="btn-primary-modern dn-pick-program" data-kategori="{{ $rp->key }}" style="justify-content:center; padding:.45rem .8rem; font-size:.78rem;">
                                            <i class="fa-solid fa-plus"></i> Tambah Dokumentasi
                                        </button>

                                        {{-- ===== RIWAYAT PENYALURAN (di dalam kartu program) ===== --}}
                                        <div style="border-top: 1px dashed #cbd5e1; padding-top: .75rem;">
                                            <b style="font-size: .82rem; color: #dc2626;">Riwayat penyaluran ({{ $rp->items->count() }})</b>

                                            @forelse ($rp->items as $item)
                                                <details style="margin-top: .5rem; border: 1px solid #fecaca; border-radius: .6rem; padding: .6rem; background: #fff;">
                                                    <summary style="cursor: pointer; font-size: .8rem; font-weight: 600; display: flex; justify-content: space-between; gap: .5rem;">
                                                        <span>{{ optional($item->tanggal)->format('d/m/Y') }} · {{ \Illuminate\Support\Str::limit($item->judul, 26) }}</span>
                                                        <span style="color: #dc2626; white-space: nowrap;">Rp {{ number_format((float) $item->nominal_terpakai, 0, ',', '.') }}</span>
                                                    </summary>

                                                    @if ($item->foto_url)
                                                        <img src="{{ $item->foto_url }}" alt="{{ $item->judul }}" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 8px; margin-top: .6rem;">
                                                    @endif
                                                    @if ($item->tujuan)
                                                        <p style="font-size: .75rem; color: #64748b; margin: .5rem 0 0;">Tujuan: {{ $item->tujuan }}</p>
                                                    @endif

                                                    {{-- ===== EDIT PENYALURAN ===== --}}
                                                    <form action="{{ route('admin.donasi.galeri.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                                                          style="display:flex; flex-direction:column; gap:.7rem; margin-top:.75rem;">
                                                        @csrf
                                                        @method('PUT')

                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Judul</label>
                                                            <input type="text" name="judul" class="form-control-modern" value="{{ $item->judul }}" required maxlength="150">
                                                        </div>
                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Program</label>
                                                            <select name="kategori" class="form-control-modern">
                                                                <option value="">Umum</option>
                                                                @foreach ($donationCategories as $cat)
                                                                    <option value="{{ $cat->key }}" {{ $matchProgram($item, $cat) ? 'selected' : '' }}>{{ $cat->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Dikirim ke Rekening / Tujuan</label>
                                                            <input type="text" name="tujuan" class="form-control-modern" value="{{ $item->tujuan }}" maxlength="150">
                                                        </div>
                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Tanggal</label>
                                                            <input type="date" name="tanggal" class="form-control-modern" value="{{ optional($item->tanggal)->format('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                        </div>
                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Uang Keluar (Rp)</label>
                                                            <input type="number" name="nominal_terpakai" class="form-control-modern" min="0" step="1" value="{{ $item->nominal_terpakai !== null ? (int) $item->nominal_terpakai : '' }}">
                                                            <small style="color:#94a3b8; font-size:.7rem;">Kalau rincian di bawah diisi, angka ini otomatis diganti jumlah rincian.</small>
                                                        </div>
                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Keterangan</label>
                                                            <textarea name="deskripsi" rows="3" class="form-control-modern" maxlength="1000">{{ $item->deskripsi }}</textarea>
                                                        </div>

                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">Dipakai untuk apa saja</label>
                                                            <div class="rincian-wrap" data-next="{{ count($item->rincian ?? []) }}" style="margin:.4rem 0;">
                                                                @foreach ($item->rincian ?? [] as $i => $r)
                                                                    <div class="rincian-row">
                                                                        <input type="text" name="rincian[{{ $i }}][label]" value="{{ $r['label'] ?? '' }}" class="form-control-modern rincian-label" placeholder="Untuk apa (mis. Beli semen)">
                                                                        <input type="number" name="rincian[{{ $i }}][nominal]" value="{{ (int) ($r['nominal'] ?? 0) }}" class="form-control-modern" placeholder="Nominal" min="0">
                                                                        <input type="text" name="rincian[{{ $i }}][keterangan]" value="{{ $r['keterangan'] ?? '' }}" class="form-control-modern" placeholder="Keterangan">
                                                                        <button type="button" class="rincian-hapus">✕</button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="rincian-tambah">+ Tambah Rincian</button>
                                                        </div>

                                                        <div>
                                                            <label style="font-size:.75rem; font-weight:600;">{{ $item->foto ? 'Ganti Foto (opsional)' : 'Tambah Foto (opsional)' }}</label>
                                                            <input type="file" name="foto" accept="image/png, image/jpeg" class="form-control-modern" style="padding:.4rem; font-size:.8rem;">
                                                        </div>

                                                        <button type="submit" class="btn-primary-modern" style="justify-content:center; padding:.5rem 1rem; font-size:.82rem;">Simpan Perubahan</button>
                                                    </form>

                                                    <form action="{{ route('admin.donasi.galeri.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus catatan penyaluran ini?');" style="margin-top: .5rem;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="width: 100%; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: .35rem; border-radius: .4rem; cursor: pointer; font-size: .75rem;">
                                                            <i class="fa-solid fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </details>
                                            @empty
                                                <div style="margin-top: .5rem; font-size: .78rem; color: #94a3b8;">Belum ada penyaluran untuk program ini.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <script>
                    // Tombol "Tambah Dokumentasi" di kartu program: pilih kategori otomatis lalu geser ke formulir
                    document.querySelectorAll('.dn-pick-program').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            var sel = document.getElementById('dn-kategori-select');
                            if (sel) sel.value = this.dataset.kategori;
                            var form = document.getElementById('dn-form-galeri');
                            if (form) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            var judul = document.getElementById('dn-judul');
                            if (judul) setTimeout(function () { judul.focus({ preventScroll: true }); }, 400);
                        });
                    });

                    // Tambah / hapus baris rincian penggunaan dana
                    document.addEventListener('click', function (e) {
                        if (e.target.classList.contains('rincian-tambah')) {
                            var wrap = e.target.previousElementSibling;
                            var i = parseInt(wrap.dataset.next, 10);
                            wrap.dataset.next = i + 1;
                            wrap.insertAdjacentHTML('beforeend',
                                '<div class="rincian-row">' +
                                '<input type="text" name="rincian[' + i + '][label]" class="form-control-modern rincian-label" placeholder="Untuk apa (mis. Beli semen)">' +
                                '<input type="number" name="rincian[' + i + '][nominal]" class="form-control-modern" placeholder="Nominal" min="0">' +
                                '<input type="text" name="rincian[' + i + '][keterangan]" class="form-control-modern" placeholder="Keterangan">' +
                                '<button type="button" class="rincian-hapus">✕</button></div>');
                        }
                        if (e.target.classList.contains('rincian-hapus')) {
                            e.target.closest('.rincian-row').remove();
                        }
                    });
                </script>
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