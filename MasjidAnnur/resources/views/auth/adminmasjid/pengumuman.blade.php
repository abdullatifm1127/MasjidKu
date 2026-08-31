<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/pengumuman.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">

        {{-- Brand --}}
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

        {{-- Nav --}}
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
            <a href="{{ route('admin.pengumuman') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                @php $aktifCount = $pengumumans->where('status','aktif')->count(); @endphp
                @if($aktifCount > 0)
                    <span class="ba2-nav-badge">{{ $aktifCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.acara') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
        </nav>

        {{-- User footer --}}
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

        {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Pengumuman</div>
                <div class="ba2-page-sub">Kelola pengumuman masjid {{ $mosque->mosque_name ?? '' }}</div>
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

        {{-- Content --}}
        <main class="ba2-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="pg-alert pg-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                    <button class="pg-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="pg-alert pg-alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                    <button class="pg-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            {{-- Layout dua kolom: form kiri, daftar kanan --}}
            <div class="pg-layout">

                {{-- ===== FORM TAMBAH / EDIT ===== --}}
                <div class="pg-form-col">
                    <div class="pg-card">
                        <div class="pg-card-head">
                            <span class="pg-card-title">
                                <i class="fa-solid fa-{{ $edit ? 'pen-to-square' : 'plus' }}"></i>
                                {{ $edit ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
                            </span>
                            @if($edit)
                                <a href="{{ route('admin.pengumuman') }}" class="pg-btn-cancel">
                                    <i class="fa-solid fa-xmark"></i> Batal
                                </a>
                            @endif
                        </div>

                        <div class="pg-card-body">
                            @if($edit)
                                <form method="POST"
                                      action="{{ route('admin.pengumuman.update', $edit->id) }}"
                                      id="pgForm">
                                    @csrf
                                    @method('PUT')
                            @else
                                <form method="POST"
                                      action="{{ route('admin.pengumuman.store') }}"
                                      id="pgForm">
                                    @csrf
                            @endif

                                {{-- Judul --}}
                                <div class="pg-field">
                                    <label class="pg-label" for="judul">
                                        Judul Pengumuman <span class="pg-required">*</span>
                                    </label>
                                    <input type="text" id="judul" name="judul"
                                           class="pg-input @error('judul') is-error @enderror"
                                           placeholder="Contoh: Jadwal Shalat Tarawih Ramadan 1446H"
                                           value="{{ old('judul', $edit->judul ?? '') }}"
                                           required>
                                    @error('judul')
                                        <span class="pg-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Kategori & Status --}}
                                <div class="pg-row-2">
                                    <div class="pg-field">
                                        <label class="pg-label" for="kategori">Kategori</label>
                                        <select id="kategori" name="kategori"
                                                class="pg-input @error('kategori') is-error @enderror">
                                            @php
                                                $kategoriList = [
                                                    'umum'     => 'Umum',
                                                    'ibadah'   => 'Ibadah',
                                                    'kegiatan' => 'Kegiatan',
                                                    'sosial'   => 'Sosial',
                                                    'darurat'  => 'Darurat',
                                                ];
                                                $selectedKategori = old('kategori', $edit->kategori ?? 'umum');
                                            @endphp
                                            @foreach($kategoriList as $val => $label)
                                                <option value="{{ $val }}"
                                                    {{ $selectedKategori === $val ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori')
                                            <span class="pg-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="pg-field">
                                        <label class="pg-label" for="status">Status</label>
                                        <select id="status" name="status"
                                                class="pg-input @error('status') is-error @enderror">
                                            @php $selectedStatus = old('status', $edit->status ?? 'aktif'); @endphp
                                            <option value="aktif"    {{ $selectedStatus === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ $selectedStatus === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                        @error('status')
                                            <span class="pg-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Tanggal --}}
                                <div class="pg-row-2">
                                    <div class="pg-field">
                                        <label class="pg-label" for="tanggal_mulai">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                               class="pg-input @error('tanggal_mulai') is-error @enderror"
                                               value="{{ old('tanggal_mulai', isset($edit->tanggal_mulai) ? $edit->tanggal_mulai->format('Y-m-d') : '') }}">
                                        @error('tanggal_mulai')
                                            <span class="pg-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="pg-field">
                                        <label class="pg-label" for="tanggal_selesai">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                               class="pg-input @error('tanggal_selesai') is-error @enderror"
                                               value="{{ old('tanggal_selesai', isset($edit->tanggal_selesai) ? $edit->tanggal_selesai->format('Y-m-d') : '') }}">
                                        @error('tanggal_selesai')
                                            <span class="pg-error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Isi --}}
                                <div class="pg-field">
                                    <label class="pg-label" for="isi">
                                        Isi Pengumuman <span class="pg-required">*</span>
                                    </label>
                                    <textarea id="isi" name="isi" rows="6"
                                              class="pg-input pg-textarea @error('isi') is-error @enderror"
                                              placeholder="Tulis isi pengumuman di sini..."
                                              required>{{ old('isi', $edit->isi ?? '') }}</textarea>
                                    @error('isi')
                                        <span class="pg-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Is Penting --}}
                                <div class="pg-field">
                                    <label class="pg-toggle-wrap">
                                        <input type="hidden" name="is_penting" value="0">
                                        <input type="checkbox" id="is_penting" name="is_penting" value="1"
                                               class="pg-toggle-input"
                                               {{ old('is_penting', $edit->is_penting ?? false) ? 'checked' : '' }}>
                                        <span class="pg-toggle-track">
                                            <span class="pg-toggle-thumb"></span>
                                        </span>
                                        <span class="pg-toggle-label">Tandai sebagai pengumuman penting</span>
                                    </label>
                                </div>

                                {{-- Submit --}}
                                <div class="pg-form-actions">
                                    <button type="submit" class="pg-btn-submit">
                                        <i class="fa-solid fa-{{ $edit ? 'floppy-disk' : 'plus' }}"></i>
                                        {{ $edit ? 'Simpan Perubahan' : 'Tambah Pengumuman' }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===== DAFTAR PENGUMUMAN ===== --}}
                <div class="pg-list-col">
                    <div class="pg-card">
                        <div class="pg-card-head">
                            <span class="pg-card-title">
                                <i class="fa-solid fa-list"></i>
                                Daftar Pengumuman
                            </span>
                            <span class="pg-count-badge">{{ $pengumumans->count() }} total</span>
                        </div>

                        {{-- Filter tabs --}}
                        <div class="pg-tabs" id="pgTabs">
                            <button class="pg-tab active" data-filter="semua">Semua</button>
                            <button class="pg-tab" data-filter="aktif">Aktif</button>
                            <button class="pg-tab" data-filter="nonaktif">Nonaktif</button>
                            <button class="pg-tab" data-filter="penting">Penting</button>
                        </div>

                        <div class="pg-list" id="pgList">
                            @forelse($pengumumans as $item)
                                <div class="pg-item {{ $item->is_penting ? 'is-penting' : '' }}"
                                     data-status="{{ $item->status }}"
                                     data-penting="{{ $item->is_penting ? 'penting' : '' }}">

                                    {{-- Baris atas: judul + badge --}}
                                    <div class="pg-item-head">
                                        <div class="pg-item-title-wrap">
                                            @if($item->is_penting)
                                                <span class="pg-badge darurat" title="Pengumuman Penting">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                </span>
                                            @endif
                                            <span class="pg-item-title">{{ $item->judul }}</span>
                                        </div>
                                        <div class="pg-item-badges">
                                            <span class="pg-badge kategori-{{ $item->kategori }}">
                                                {{ ucfirst($item->kategori) }}
                                            </span>
                                            <span class="pg-badge status-{{ $item->status }}">
                                                {{ $item->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Isi preview --}}
                                    <p class="pg-item-isi">{{ Str::limit($item->isi, 120) }}</p>

                                    {{-- Meta: tanggal --}}
                                    <div class="pg-item-meta">
                                        <span class="pg-meta-icon">
                                            <i class="fa-regular fa-calendar"></i>
                                        </span>
                                        @if($item->tanggal_mulai)
                                            {{ $item->tanggal_mulai->format('d M Y') }}
                                            @if($item->tanggal_selesai)
                                                &ndash; {{ $item->tanggal_selesai->format('d M Y') }}
                                            @endif
                                        @else
                                            Dibuat {{ $item->created_at->diffForHumans() }}
                                        @endif
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="pg-item-actions">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.pengumuman.edit', $item->id) }}"
                                           class="pg-action-btn edit" title="Edit">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        {{-- Toggle status --}}
                                        <form method="POST"
                                              action="{{ route('admin.pengumuman.toggle-status', $item->id) }}"
                                              style="display:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="pg-action-btn toggle"
                                                    title="{{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fa-solid fa-{{ $item->status === 'aktif' ? 'eye-slash' : 'eye' }}"></i>
                                                {{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        {{-- Hapus --}}
                                        <form method="POST"
                                              action="{{ route('admin.pengumuman.destroy', $item->id) }}"
                                              style="display:inline"
                                              onsubmit="return confirm('Hapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pg-action-btn delete" title="Hapus">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="pg-empty">
                                    <div class="pg-empty-icon">
                                        <i class="fa-solid fa-bullhorn"></i>
                                    </div>
                                    <div class="pg-empty-title">Belum ada pengumuman</div>
                                    <div class="pg-empty-sub">Tambahkan pengumuman pertama menggunakan form di sebelah kiri.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>{{-- end pg-layout --}}

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
        document.querySelectorAll('.pg-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.pg-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const filter = tab.dataset.filter;
                document.querySelectorAll('.pg-item').forEach(item => {
                    if (filter === 'semua') {
                        item.style.display = '';
                    } else if (filter === 'penting') {
                        item.style.display = item.dataset.penting === 'penting' ? '' : 'none';
                    } else {
                        item.style.display = item.dataset.status === filter ? '' : 'none';
                    }
                });
            });
        });

        // Auto-hide alert setelah 4 detik
        document.querySelectorAll('.pg-alert').forEach(el => {
            setTimeout(() => el.remove(), 4000);
        });
    </script>
</body>
</html>
