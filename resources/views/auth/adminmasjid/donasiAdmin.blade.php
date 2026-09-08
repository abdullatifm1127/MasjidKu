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
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/donasiAdmin.css') }}">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .dn-modern-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: #ffffff; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .dn-grid-dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .dn-card-modern { background: #ffffff; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); }
        .dn-card-modern h3 { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .dn-card-modern p { font-size: 0.875rem; color: #64748b; margin-bottom: 1.25rem; }
        .form-control-modern { width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.9rem; transition: all 0.2s; }
        .form-control-modern:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1); }
        .btn-primary-modern { background-color: #0f766e; color: #ffffff; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary-modern:hover { background-color: #115e59; }
    </style>
</head>
<body class="ba2-body" id="ba2Body">

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
            <a href="{{ route('admin.donasi') }}" class="ba2-nav-item {{ request()->routeIs('admin.donasi*') ? 'active' : '' }}">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
            </a>
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

        {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Kelola Donasi &amp; Zakat</div>
                <div class="ba2-page-sub">Pusat pengaturan kampanye donasi, kalkulator zakat, dan transparansi penyaluran</div>
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

        {{-- Main Area --}}
        <main class="dn-content">

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

            <div class="dn-grid-dashboard">
                {{-- ===== Kelola Jenis Donasi ===== --}}
                <section class="dn-card-modern" style="grid-column: span 2;">
                    <h3><i class="fa-solid fa-layer-group text-teal-600"></i> Kelola Jenis &amp; Kategori Donasi</h3>
                    <p>Atur jenis donasi yang akan otomatis tampil pada halaman publik. Anda dapat mengaktifkan atau menonaktifkan kategori sesuai kebutuhan.</p>

                    {{-- Form Tambah Kategori --}}
                    <form action="{{ route('admin.donasi.kategori.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; background: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem;">
                        @csrf
                        <div class="dn-field" style="margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nama Jenis Donasi</label>
                            <input type="text" name="title" class="form-control-modern" placeholder="Mis. Infaq Jumat" required>
                        </div>
                        <div class="dn-field" style="margin: 0;">
    <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Tipe Kalkulasi / Kategori</label>
    <select name="calc_type" class="form-control-modern">
        <option value="zakat">Zakat (Fitrah &amp; Mal)</option>
        <option value="infaq">Infaq (Kotak infaq operasional)</option>
        <option value="sedekah">Sedekah (Sedekah umum/harian)</option>
        <option value="pembangunan">Pembangunan (Renovasi &amp; pembangunan fisik masjid)</option>
        <option value="yatim">Yatim (Santunan anak yatim &amp; dhuafa)</option>
        <option value="bencana">Bencana (Dana darurat &amp; kemanusiaan)</option>
        <option value="wakaf">Wakaf (Wakaf produktif, tanah, atau sumur)</option>
        <option value="qurban">Qurban (Tabungan atau penyaluran hewan qurban)</option>
        <option value="pendidikan">Pendidikan (TPA, madrasah, atau beasiswa santri)</option>
        <option value="kesehatan">Kesehatan (Bantuan berobat jamaah / sosial kesehatan)</option>
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
                        <div style="grid-column: 1 / -1; margin: 0;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control-modern" rows="2" placeholder="Penjelasan singkat mengenai program donasi ini..."></textarea>
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
                                            <span style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                                                {!! $kategori->iconPath() !!}
                                            </span>
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
                                        <form action="{{ route('admin.donasi.kategori.update', $kategori->id) }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Nama</label>
                                                <input type="text" name="title" class="form-control-modern" value="{{ $kategori->title }}" required>
                                            </div>
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Tipe</label>
                                                <select name="calc_type" class="form-control-modern">
                                                    <option value="nominal" {{ $kategori->calc_type === 'nominal' ? 'selected' : '' }}>Nominal</option>
                                                    <option value="zakat" {{ $kategori->calc_type === 'zakat' ? 'selected' : '' }}>Zakat</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label style="font-size: 0.8rem; font-weight: 600;">Urutan</label>
                                                <input type="number" name="sort_order" class="form-control-modern" value="{{ $kategori->sort_order }}">
                                            </div>
                                            <div style="grid-column: 1 / -1;">
                                                <label style="font-size: 0.8rem; font-weight: 600;">Deskripsi</label>
                                                <textarea name="description" class="form-control-modern" rows="2">{{ $kategori->description }}</textarea>
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
                                            <form action="{{ route('admin.donasi.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Hapus jenis donasi ini?');">
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

                {{-- Kolom Pengaturan Zakat & Dokumentasi --}}
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    {{-- ===== Pengaturan Zakat Fitrah ===== --}}
                    <section class="dn-card-modern">
                        <h3><i class="fa-solid fa-calculator text-teal-600"></i> Zakat Fitrah Default</h3>
                        <p>Atur nominal standar per jiwa untuk kalkulator zakat fitrah publik.</p>
                        <form action="{{ route('admin.donasi.pengaturan') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nominal per Jiwa (Rp)</label>
                                <input type="number" step="1000" min="0" name="zakat_fitrah_default" class="form-control-modern" value="{{ old('zakat_fitrah_default', $mosque->zakat_fitrah_default ?? 45000) }}" required>
                            </div>
                            <button type="submit" class="btn-primary-modern" style="width: 100%; justify-content: center;">Simpan Pengaturan</button>
                        </form>
                    </section>

                    {{-- ===== Upload Foto Realisasi ===== --}}
                    <section class="dn-card-modern">
                        <h3><i class="fa-solid fa-images text-teal-600"></i> Dokumentasi Penyaluran</h3>
                        <p>Unggah foto bukti nyata penyaluran dana ke jamaah.</p>
                        <form action="{{ route('admin.donasi.galeri.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1rem;">
                            @csrf
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Judul Kegiatan</label>
                                <input type="text" name="judul" class="form-control-modern" placeholder="Mis. Sembako Ramadhan" value="{{ old('judul') }}" required>
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
                                <input type="date" name="tanggal" class="form-control-modern" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Nominal Terpakai (Rp)</label>
                                <input type="number" min="0" name="nominal_terpakai" class="form-control-modern" placeholder="0" value="{{ old('nominal_terpakai') }}">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control-modern" rows="2" placeholder="Ceritakan singkat...">{{ old('deskripsi') }}</textarea>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem;">Pilih Foto</label>
                                <input type="file" name="foto" accept="image/*" class="form-control-modern" onchange="dnPreviewFoto(this)" required>
                            </div>
                            <button type="submit" class="btn-primary-modern" style="width: 100%; justify-content: center;">Unggah Dokumentasi</button>
                        </form>
                    </section>
                </div>
            </div>

            {{-- ===== Daftar Galeri Tersimpan ===== --}}
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

        </main>
    </div>

    <button class="ba2-fab" aria-label="Bantuan" style="position: fixed; bottom: 2rem; right: 2rem; width: 48px; height: 48px; border-radius: 50%; background: #0f766e; color: white; border: none; font-size: 1.2rem; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">?</button>

    <script src="{{ asset('js/adminmasjid/donasi.js') }}"></script>
</body>
</html>