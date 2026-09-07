<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/donasiAdmin.css') }}">
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
           {{-- Menu Donasi Dinamis Berdasarkan Paket Masjid --}}
            <a href="{{ route('admin.donasi') }}" class="ba2-nav-item {{ request()->routeIs('admin.donasi*') ? 'active' : '' }}">
            <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
            <span class="ba2-nav-label">Donasi</span>
            </a>

            <a href="{{ route('admin.jamaah') }}" class="ba2-nav-item">
    <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
    <span class="ba2-nav-label">Data Jamaah</span>
    <!-- Jika sudah siap digunakan, badge "dev" ini bisa dihapus atau diubah -->
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

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

       {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Donasi</div>
                <div class="ba2-page-sub">Kelola pengaturan zakat & dokumentasi realisasi donasi</div>
            </div>
            <div class="ba2-topbar-right" style="display: flex; align-items: center; gap: 10px;">
                
                {{-- TOMBOL BERHENTI BERLANGGANAN --}}
                <form action="{{ route('masjid.unsubscribe') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin berhenti berlangganan? Paket akan kembali ke Free dan fitur donasi akan dinonaktifkan.');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ba2-btn-back" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; cursor: pointer;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Berhenti Berlangganan
                    </button>
                </form>

                <a href="{{ route('masjid.donasi.publik', $mosque->slug) }}" class="ba2-btn-back" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    Lihat Halaman Donasi
                </a>
                
                <button class="ba2-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="ba2-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="dn-content">

            @if (session('success'))
                <div class="dn-alert dn-alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="dn-alert dn-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ===== Pengaturan Zakat Fitrah ===== --}}
            <section class="dn-card">
                <div class="dn-card-head">
                    <h2>Pengaturan Zakat Fitrah</h2>
                    <p>Nominal per jiwa ini yang akan tampil sebagai default di kalkulator zakat fitrah pada halaman donasi publik.</p>
                </div>
                <form action="{{ route('admin.donasi.pengaturan') }}" method="POST" class="dn-form-inline">
                    @csrf
                    @method('PUT')
                    <div class="dn-field">
                        <label for="zakat_fitrah_default">Nominal per jiwa (Rp)</label>
                        <input type="number" step="1000" min="0" name="zakat_fitrah_default" id="zakat_fitrah_default"
                               value="{{ old('zakat_fitrah_default', $mosque->zakat_fitrah_default ?? 45000) }}">
                    </div>
                    <button type="submit" class="dn-btn-primary">Simpan Pengaturan</button>
                </form>
            </section>

            {{-- ===== Upload Foto Realisasi Donasi ===== --}}
            <section class="dn-card">
                <div class="dn-card-head">
                    <h2>Tambah Dokumentasi Realisasi Donasi</h2>
                    <p>Foto ini akan tampil di halaman transparansi donasi publik, sebagai bukti nyata penyaluran dana ke jamaah.</p>
                </div>
                <form action="{{ route('admin.donasi.galeri.store') }}" method="POST" enctype="multipart/form-data" class="dn-form-grid">
                    @csrf
                    <div class="dn-field">
                        <label for="judul">Judul</label>
                        <input type="text" name="judul" id="judul" placeholder="Mis. Pembagian sembako Ramadhan" value="{{ old('judul') }}" required>
                    </div>
                    <div class="dn-field">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori">
                            <option value="">Umum</option>
                            @if (!empty($categories))
                                @foreach ($categories as $key => $cat)
                                    <option value="{{ $key }}" {{ old('kategori') === $key ? 'selected' : '' }}>{{ is_array($cat) ? ($cat['title'] ?? $key) : $cat }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="dn-field">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="dn-field">
                        <label for="nominal_terpakai">Nominal terpakai (Rp) — opsional</label>
                        <input type="number" min="0" name="nominal_terpakai" id="nominal_terpakai" placeholder="0" value="{{ old('nominal_terpakai') }}">
                    </div>
                    <div class="dn-field dn-field-full">
                        <label for="deskripsi">Deskripsi — opsional</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Ceritakan singkat kegiatan/penyaluran ini">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="dn-field dn-field-full">
                        <label for="foto">Foto</label>
                        <input type="file" name="foto" id="foto" accept="image/*" onchange="dnPreviewFoto(this)" required>
                        <img id="dn-preview" class="dn-preview hidden" alt="Preview foto">
                    </div>
                    <div class="dn-field-full">
                        <button type="submit" class="dn-btn-primary">Unggah Foto</button>
                    </div>
                </form>
            </section>

            {{-- ===== Daftar Galeri ===== --}}
            <section class="dn-card">
                <div class="dn-card-head">
                    <h2>Dokumentasi Tersimpan</h2>
                    <p>{{ $items->count() }} foto sudah dipublikasikan ke halaman transparansi.</p>
                </div>

                @if ($items->isEmpty())
                    <div class="dn-empty">Belum ada foto. Tambahkan lewat form di atas.</div>
                @else
                    <div class="dn-gallery-grid">
                        @foreach ($items as $item)
                            <div class="dn-gallery-item">
                                <div class="dn-gallery-photo" style="background-image:url('{{ $item->foto_url }}')"></div>
                                <div class="dn-gallery-info">
                                    <span class="dn-tag">
                                        @php
                                            $catTitle = 'Umum';
                                            if (!empty($categories) && isset($categories[$item->kategori])) {
                                                $catTitle = is_array($categories[$item->kategori]) ? ($categories[$item->kategori]['title'] ?? $item->kategori) : $categories[$item->kategori];
                                            }
                                        @endphp
                                        {{ $catTitle }}
                                    </span>
                                    <h4>{{ $item->judul }}</h4>
                                    <p>{{ optional($item->tanggal)->translatedFormat('d F Y') ?? $item->tanggal }}</p>
                                </div>
                                <form action="{{ route('admin.donasi.galeri.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus foto ini?');" class="dn-gallery-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" aria-label="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </main>

    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script src="{{ asset('js/adminmasjid/donasi.js') }}"></script>
</body>
</html>