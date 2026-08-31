<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/donasi.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <button class="ba2-collapse-btn" id="ba2CollapseBtn" aria-label="Collapse">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <nav class="ba2-nav">
            <a href="{{ route('admin.dashboard') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="{{ route('admin.pengumuman') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
            </a>
            <a href="{{ route('admin.acara') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
            </a>
            <a href="{{ route('admin.donasi') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                @if($stats['pending'] > 0)
                    <span class="ba2-nav-badge">{{ $stats['pending'] }}</span>
                @endif
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
        </nav>

        <div class="ba2-user">
            <div class="ba2-user-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 2) }}</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">{{ Auth::user()->name ?? 'Admin Masjid' }}</div>
                <div class="ba2-user-email">{{ Auth::user()->email ?? 'admin@masjid.id' }}</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Donasi</div>
                <div class="ba2-page-sub">Kelola data donasi {{ $mosque->mosque_name ?? '' }}</div>
            </div>
            <div class="ba2-topbar-right">
                @if(isset($mosque->slug))
                    <a href="{{ route('masjid.publik', $mosque->slug) }}" class="ba2-btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Publik
                    </a>
                @endif
                <button class="ba2-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="ba2-notif-dot"></span>
                </button>
            </div>
        </header>

        <main class="ba2-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="dn-alert dn-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                    <button class="dn-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="dn-alert dn-alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                    <button class="dn-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            {{-- ===== STATS RINGKASAN ===== --}}
            <div class="dn-stats">
                <div class="dn-stat-card green">
                    <div class="dn-stat-icon"><i class="fa-solid fa-coins"></i></div>
                    <div class="dn-stat-body">
                        <div class="dn-stat-label">Total Donasi</div>
                        <div class="dn-stat-value">Rp {{ number_format($stats['total_semua'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="dn-stat-card blue">
                    <div class="dn-stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <div class="dn-stat-body">
                        <div class="dn-stat-label">Bulan Ini</div>
                        <div class="dn-stat-value">Rp {{ number_format($stats['total_bulan'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="dn-stat-card amber">
                    <div class="dn-stat-icon"><i class="fa-solid fa-list-check"></i></div>
                    <div class="dn-stat-body">
                        <div class="dn-stat-label">Jumlah Transaksi</div>
                        <div class="dn-stat-value">{{ $stats['jumlah_donasi'] }}</div>
                    </div>
                </div>
                <div class="dn-stat-card red">
                    <div class="dn-stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                    <div class="dn-stat-body">
                        <div class="dn-stat-label">Menunggu Konfirmasi</div>
                        <div class="dn-stat-value">{{ $stats['pending'] }}</div>
                    </div>
                </div>
            </div>

            {{-- ===== LAYOUT DUA KOLOM ===== --}}
            <div class="dn-layout">

                {{-- ===== FORM TAMBAH / EDIT ===== --}}
                <div class="dn-form-col">
                    <div class="dn-card">
                        <div class="dn-card-head">
                            <span class="dn-card-title">
                                <i class="fa-solid fa-{{ $edit ? 'pen-to-square' : 'plus' }}"></i>
                                {{ $edit ? 'Edit Data Donasi' : 'Catat Donasi Baru' }}
                            </span>
                            @if($edit)
                                <a href="{{ route('admin.donasi') }}" class="dn-btn-cancel">
                                    <i class="fa-solid fa-xmark"></i> Batal
                                </a>
                            @endif
                        </div>

                        <div class="dn-card-body">
                            @if($edit)
                                <form method="POST"
                                      action="{{ route('admin.donasi.update', $edit->id) }}"
                                      enctype="multipart/form-data"
                                      id="dnForm">
                                    @csrf
                                    @method('PUT')
                            @else
                                <form method="POST"
                                      action="{{ route('admin.donasi.store') }}"
                                      enctype="multipart/form-data"
                                      id="dnForm">
                                    @csrf
                            @endif

                                {{-- Nama Donatur --}}
                                <div class="dn-field">
                                    <label class="dn-label" for="nama_donatur">
                                        Nama Donatur <span class="dn-required">*</span>
                                    </label>
                                    <input type="text" id="nama_donatur" name="nama_donatur"
                                           class="dn-input @error('nama_donatur') is-error @enderror"
                                           placeholder="Nama atau Hamba Allah"
                                           value="{{ old('nama_donatur', $edit->nama_donatur ?? 'Hamba Allah') }}"
                                           required>
                                    @error('nama_donatur')
                                        <span class="dn-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- No HP --}}
                                <div class="dn-field">
                                    <label class="dn-label" for="no_hp">No. HP / WhatsApp</label>
                                    <input type="text" id="no_hp" name="no_hp"
                                           class="dn-input @error('no_hp') is-error @enderror"
                                           placeholder="Opsional"
                                           value="{{ old('no_hp', $edit->no_hp ?? '') }}">
                                    @error('no_hp')
                                        <span class="dn-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Jumlah & Tanggal --}}
                                <div class="dn-row-2">
                                    <div class="dn-field">
                                        <label class="dn-label" for="jumlah">
                                            Jumlah (Rp) <span class="dn-required">*</span>
                                        </label>
                                        <input type="number" id="jumlah" name="jumlah" min="0" step="1000"
                                               class="dn-input @error('jumlah') is-error @enderror"
                                               placeholder="0"
                                               value="{{ old('jumlah', $edit->jumlah ?? '') }}"
                                               required>
                                        @error('jumlah')
                                            <span class="dn-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dn-field">
                                        <label class="dn-label" for="tanggal_donasi">
                                            Tanggal <span class="dn-required">*</span>
                                        </label>
                                        <input type="date" id="tanggal_donasi" name="tanggal_donasi"
                                               class="dn-input @error('tanggal_donasi') is-error @enderror"
                                               value="{{ old('tanggal_donasi', isset($edit->tanggal_donasi) ? $edit->tanggal_donasi->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                               required>
                                        @error('tanggal_donasi')
                                            <span class="dn-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Kategori & Metode --}}
                                <div class="dn-row-2">
                                    <div class="dn-field">
                                        <label class="dn-label" for="kategori">Kategori</label>
                                        <select id="kategori" name="kategori"
                                                class="dn-input @error('kategori') is-error @enderror">
                                            @php
                                                $kategoriList = [
                                                    'infaq'   => 'Infaq',
                                                    'sedekah' => 'Sedekah',
                                                    'zakat'   => 'Zakat',
                                                    'wakaf'   => 'Wakaf',
                                                    'lainnya' => 'Lainnya',
                                                ];
                                                $selKategori = old('kategori', $edit->kategori ?? 'infaq');
                                            @endphp
                                            @foreach($kategoriList as $val => $lbl)
                                                <option value="{{ $val }}" {{ $selKategori === $val ? 'selected' : '' }}>
                                                    {{ $lbl }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori')
                                            <span class="dn-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dn-field">
                                        <label class="dn-label" for="metode">Metode Bayar</label>
                                        <select id="metode" name="metode"
                                                class="dn-input @error('metode') is-error @enderror">
                                            @php
                                                $metodeList = [
                                                    'tunai'    => 'Tunai',
                                                    'transfer' => 'Transfer Bank',
                                                    'qris'     => 'QRIS',
                                                    'lainnya'  => 'Lainnya',
                                                ];
                                                $selMetode = old('metode', $edit->metode ?? 'tunai');
                                            @endphp
                                            @foreach($metodeList as $val => $lbl)
                                                <option value="{{ $val }}" {{ $selMetode === $val ? 'selected' : '' }}>
                                                    {{ $lbl }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('metode')
                                            <span class="dn-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="dn-field">
                                    <label class="dn-label" for="status">Status Pembayaran</label>
                                    <select id="status" name="status"
                                            class="dn-input @error('status') is-error @enderror">
                                        @php $selStatus = old('status', $edit->status ?? 'lunas'); @endphp
                                        <option value="lunas"   {{ $selStatus === 'lunas'   ? 'selected' : '' }}>Lunas</option>
                                        <option value="pending" {{ $selStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="batal"   {{ $selStatus === 'batal'   ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    @error('status')
                                        <span class="dn-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Keterangan --}}
                                <div class="dn-field">
                                    <label class="dn-label" for="keterangan">Keterangan</label>
                                    <textarea id="keterangan" name="keterangan" rows="3"
                                              class="dn-input dn-textarea @error('keterangan') is-error @enderror"
                                              placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $edit->keterangan ?? '') }}</textarea>
                                    @error('keterangan')
                                        <span class="dn-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Bukti Transfer --}}
                                <div class="dn-field">
                                    <label class="dn-label" for="bukti_transfer">
                                        Bukti Transfer
                                        <span class="dn-label-hint">JPG/PNG/PDF, maks 2 MB</span>
                                    </label>
                                    @if($edit && $edit->bukti_transfer)
                                        <div class="dn-bukti-preview">
                                            <i class="fa-solid fa-file-image"></i>
                                            <span>File tersimpan</span>
                                            <a href="{{ Storage::url($edit->bukti_transfer) }}"
                                               target="_blank" class="dn-bukti-link">Lihat</a>
                                        </div>
                                    @endif
                                    <input type="file" id="bukti_transfer" name="bukti_transfer"
                                           class="dn-input-file @error('bukti_transfer') is-error @enderror"
                                           accept=".jpg,.jpeg,.png,.webp,.pdf">
                                    @error('bukti_transfer')
                                        <span class="dn-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Submit --}}
                                <div class="dn-form-actions">
                                    <button type="submit" class="dn-btn-submit">
                                        <i class="fa-solid fa-{{ $edit ? 'floppy-disk' : 'plus' }}"></i>
                                        {{ $edit ? 'Simpan Perubahan' : 'Catat Donasi' }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===== DAFTAR DONASI ===== --}}
                <div class="dn-list-col">
                    <div class="dn-card">
                        <div class="dn-card-head">
                            <span class="dn-card-title">
                                <i class="fa-solid fa-list"></i>
                                Riwayat Donasi
                            </span>
                            <span class="dn-count-badge">{{ $donasis->count() }} transaksi</span>
                        </div>

                        {{-- Filter tabs --}}
                        <div class="dn-tabs" id="dnTabs">
                            <button class="dn-tab active" data-filter="semua">Semua</button>
                            <button class="dn-tab" data-filter="lunas">Lunas</button>
                            <button class="dn-tab" data-filter="pending">Pending</button>
                            <button class="dn-tab" data-filter="batal">Batal</button>
                        </div>

                        <div class="dn-list" id="dnList">
                            @forelse($donasis as $item)
                                <div class="dn-item" data-status="{{ $item->status }}">

                                    {{-- Baris atas --}}
                                    <div class="dn-item-head">
                                        <div class="dn-item-donatur">
                                            <div class="dn-avatar">
                                                {{ strtoupper(substr($item->nama_donatur, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="dn-item-nama">{{ $item->nama_donatur }}</div>
                                                @if($item->no_hp)
                                                    <div class="dn-item-hp">{{ $item->no_hp }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="dn-item-jumlah">
                                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    {{-- Badges --}}
                                    <div class="dn-item-badges">
                                        <span class="dn-badge kategori-{{ $item->kategori }}">
                                            {{ ucfirst($item->kategori) }}
                                        </span>
                                        <span class="dn-badge metode-{{ $item->metode }}">
                                            <i class="fa-solid fa-{{ $item->metode === 'tunai' ? 'money-bill' : ($item->metode === 'transfer' ? 'building-columns' : ($item->metode === 'qris' ? 'qrcode' : 'circle-dot')) }}"></i>
                                            {{ ucfirst($item->metode) }}
                                        </span>
                                        <span class="dn-badge status-{{ $item->status }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>

                                    {{-- Keterangan --}}
                                    @if($item->keterangan)
                                        <p class="dn-item-ket">{{ Str::limit($item->keterangan, 100) }}</p>
                                    @endif

                                    {{-- Meta --}}
                                    <div class="dn-item-meta">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ $item->tanggal_donasi->format('d M Y') }}
                                        @if($item->bukti_transfer)
                                            <span class="dn-meta-sep">·</span>
                                            <a href="{{ Storage::url($item->bukti_transfer) }}"
                                               target="_blank" class="dn-bukti-link">
                                                <i class="fa-solid fa-paperclip"></i> Bukti
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="dn-item-actions">
                                        <a href="{{ route('admin.donasi.edit', $item->id) }}"
                                           class="dn-action-btn edit">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        @if($item->status !== 'batal')
                                            <form method="POST"
                                                  action="{{ route('admin.donasi.toggle-status', $item->id) }}"
                                                  style="display:inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dn-action-btn toggle">
                                                    <i class="fa-solid fa-{{ $item->status === 'lunas' ? 'rotate-left' : 'check' }}"></i>
                                                    {{ $item->status === 'lunas' ? 'Set Pending' : 'Konfirmasi' }}
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST"
                                              action="{{ route('admin.donasi.destroy', $item->id) }}"
                                              style="display:inline"
                                              onsubmit="return confirm('Hapus data donasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dn-action-btn delete">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="dn-empty">
                                    <div class="dn-empty-icon">
                                        <i class="fa-solid fa-hand-holding-dollar"></i>
                                    </div>
                                    <div class="dn-empty-title">Belum ada data donasi</div>
                                    <div class="dn-empty-sub">Catat donasi pertama menggunakan form di sebelah kiri.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>{{-- end dn-layout --}}

        </main>
    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
        // Collapse sidebar
        const collapseBtn = document.getElementById('ba2CollapseBtn');
        if (collapseBtn) {
            collapseBtn.addEventListener('click', () => {
                document.getElementById('ba2Sidebar').classList.toggle('collapsed');
                document.getElementById('ba2Main').classList.toggle('expanded');
            });
        }

        // Filter tabs
        document.querySelectorAll('.dn-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.dn-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const filter = tab.dataset.filter;
                document.querySelectorAll('.dn-item').forEach(item => {
                    item.style.display = (filter === 'semua' || item.dataset.status === filter) ? '' : 'none';
                });
            });
        });

        // Preview nama file yang dipilih
        const fileInput = document.getElementById('bukti_transfer');
        if (fileInput) {
            fileInput.addEventListener('change', () => {
                const label = fileInput.nextElementSibling;
                if (label && fileInput.files.length > 0) {
                    label.textContent = fileInput.files[0].name;
                }
            });
        }

        // Auto-hide alert setelah 4 detik
        document.querySelectorAll('.dn-alert').forEach(el => {
            setTimeout(() => el.remove(), 4000);
        });
    </script>
</body>
</html>
