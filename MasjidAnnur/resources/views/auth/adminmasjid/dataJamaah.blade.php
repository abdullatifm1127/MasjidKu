<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jamaah - {{ $mosque->mosque_name ?? 'SIM Masjid' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/dataJamaah.css') }}">
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
            <a href="{{ route('admin.donasi') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
            </a>
            <a href="{{ route('admin.jamaah') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
                @if($stats['total'] > 0)
                    <span class="ba2-nav-badge">{{ $stats['total'] }}</span>
                @endif
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
                <div class="ba2-page-title">Data Jamaah</div>
                <div class="ba2-page-sub">Kelola data anggota jamaah {{ $mosque->mosque_name ?? '' }}</div>
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
                <div class="dj-alert dj-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                    <button class="dj-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="dj-alert dj-alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                    <button class="dj-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            {{-- ===== STATS ===== --}}
            <div class="dj-stats">
                <div class="dj-stat-card green">
                    <div class="dj-stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="dj-stat-body">
                        <div class="dj-stat-label">Total Jamaah</div>
                        <div class="dj-stat-value">{{ $stats['total'] }}</div>
                    </div>
                </div>
                <div class="dj-stat-card blue">
                    <div class="dj-stat-icon"><i class="fa-solid fa-user-check"></i></div>
                    <div class="dj-stat-body">
                        <div class="dj-stat-label">Jamaah Aktif</div>
                        <div class="dj-stat-value">{{ $stats['aktif'] }}</div>
                    </div>
                </div>
                <div class="dj-stat-card amber">
                    <div class="dj-stat-icon"><i class="fa-solid fa-star"></i></div>
                    <div class="dj-stat-body">
                        <div class="dj-stat-label">Pengurus</div>
                        <div class="dj-stat-value">{{ $stats['pengurus'] }}</div>
                    </div>
                </div>
                <div class="dj-stat-card teal">
                    <div class="dj-stat-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <div class="dj-stat-body">
                        <div class="dj-stat-label">Bergabung Bulan Ini</div>
                        <div class="dj-stat-value">{{ $stats['baru'] }}</div>
                    </div>
                </div>
            </div>

            {{-- ===== LAYOUT DUA KOLOM ===== --}}
            <div class="dj-layout">

                {{-- ===== FORM TAMBAH / EDIT ===== --}}
                <div class="dj-form-col">
                    <div class="dj-card">
                        <div class="dj-card-head">
                            <span class="dj-card-title">
                                <i class="fa-solid fa-{{ $edit ? 'pen-to-square' : 'user-plus' }}"></i>
                                {{ $edit ? 'Edit Data Jamaah' : 'Tambah Jamaah' }}
                            </span>
                            @if($edit)
                                <a href="{{ route('admin.jamaah') }}" class="dj-btn-cancel">
                                    <i class="fa-solid fa-xmark"></i> Batal
                                </a>
                            @endif
                        </div>

                        <div class="dj-card-body">
                            @if($edit)
                                <form method="POST"
                                      action="{{ route('admin.jamaah.update', $edit->id) }}"
                                      enctype="multipart/form-data" id="djForm">
                                    @csrf @method('PUT')
                            @else
                                <form method="POST"
                                      action="{{ route('admin.jamaah.store') }}"
                                      enctype="multipart/form-data" id="djForm">
                                    @csrf
                            @endif

                                {{-- Seksi: Identitas --}}
                                <div class="dj-section-title">
                                    <i class="fa-solid fa-id-card"></i> Identitas
                                </div>

                                {{-- Nama --}}
                                <div class="dj-field">
                                    <label class="dj-label" for="nama">
                                        Nama Lengkap <span class="dj-required">*</span>
                                    </label>
                                    <input type="text" id="nama" name="nama"
                                           class="dj-input @error('nama') is-error @enderror"
                                           placeholder="Nama lengkap jamaah"
                                           value="{{ old('nama', $edit->nama ?? '') }}" required>
                                    @error('nama')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                </div>

                                {{-- Jenis Kelamin & Peran --}}
                                <div class="dj-row-2">
                                    <div class="dj-field">
                                        <label class="dj-label" for="jenis_kelamin">
                                            Jenis Kelamin <span class="dj-required">*</span>
                                        </label>
                                        <select id="jenis_kelamin" name="jenis_kelamin"
                                                class="dj-input @error('jenis_kelamin') is-error @enderror">
                                            @php $selJk = old('jenis_kelamin', $edit->jenis_kelamin ?? ''); @endphp
                                            <option value="" disabled {{ $selJk === '' ? 'selected' : '' }}>Pilih</option>
                                            <option value="laki-laki"  {{ $selJk === 'laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="perempuan"  {{ $selJk === 'perempuan'  ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="dj-field">
                                        <label class="dj-label" for="peran">Peran</label>
                                        <select id="peran" name="peran"
                                                class="dj-input @error('peran') is-error @enderror">
                                            @php
                                                $peranList = ['jamaah'=>'Jamaah','pengurus'=>'Pengurus','remaja'=>'Remaja','anak'=>'Anak'];
                                                $selPeran  = old('peran', $edit->peran ?? 'jamaah');
                                            @endphp
                                            @foreach($peranList as $v => $l)
                                                <option value="{{ $v }}" {{ $selPeran === $v ? 'selected' : '' }}>{{ $l }}</option>
                                            @endforeach
                                        </select>
                                        @error('peran')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                {{-- Tempat & Tanggal Lahir --}}
                                <div class="dj-row-2">
                                    <div class="dj-field">
                                        <label class="dj-label" for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" id="tempat_lahir" name="tempat_lahir"
                                               class="dj-input @error('tempat_lahir') is-error @enderror"
                                               placeholder="Kota"
                                               value="{{ old('tempat_lahir', $edit->tempat_lahir ?? '') }}">
                                        @error('tempat_lahir')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="dj-field">
                                        <label class="dj-label" for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                               class="dj-input @error('tanggal_lahir') is-error @enderror"
                                               value="{{ old('tanggal_lahir', isset($edit->tanggal_lahir) ? $edit->tanggal_lahir->format('Y-m-d') : '') }}">
                                        @error('tanggal_lahir')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                {{-- NIK --}}
                                <div class="dj-field">
                                    <label class="dj-label" for="nik">NIK</label>
                                    <input type="text" id="nik" name="nik"
                                           class="dj-input @error('nik') is-error @enderror"
                                           placeholder="16 digit NIK (opsional)"
                                           maxlength="20"
                                           value="{{ old('nik', $edit->nik ?? '') }}">
                                    @error('nik')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                </div>

                                {{-- Seksi: Kontak --}}
                                <div class="dj-section-title">
                                    <i class="fa-solid fa-phone"></i> Kontak
                                </div>

                                <div class="dj-row-2">
                                    <div class="dj-field">
                                        <label class="dj-label" for="no_hp">No. HP / WA</label>
                                        <input type="text" id="no_hp" name="no_hp"
                                               class="dj-input @error('no_hp') is-error @enderror"
                                               placeholder="08xx-xxxx-xxxx"
                                               value="{{ old('no_hp', $edit->no_hp ?? '') }}">
                                        @error('no_hp')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="dj-field">
                                        <label class="dj-label" for="email">Email</label>
                                        <input type="email" id="email" name="email"
                                               class="dj-input @error('email') is-error @enderror"
                                               placeholder="email@domain.com"
                                               value="{{ old('email', $edit->email ?? '') }}">
                                        @error('email')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                {{-- Seksi: Alamat --}}
                                <div class="dj-section-title">
                                    <i class="fa-solid fa-location-dot"></i> Alamat
                                </div>

                                <div class="dj-field">
                                    <label class="dj-label" for="alamat">Alamat Lengkap</label>
                                    <textarea id="alamat" name="alamat" rows="2"
                                              class="dj-input dj-textarea @error('alamat') is-error @enderror"
                                              placeholder="Jalan, nomor rumah…">{{ old('alamat', $edit->alamat ?? '') }}</textarea>
                                    @error('alamat')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                </div>

                                <div class="dj-row-3">
                                    <div class="dj-field">
                                        <label class="dj-label" for="rt_rw">RT/RW</label>
                                        <input type="text" id="rt_rw" name="rt_rw"
                                               class="dj-input @error('rt_rw') is-error @enderror"
                                               placeholder="001/002"
                                               value="{{ old('rt_rw', $edit->rt_rw ?? '') }}">
                                    </div>
                                    <div class="dj-field">
                                        <label class="dj-label" for="kelurahan">Kelurahan</label>
                                        <input type="text" id="kelurahan" name="kelurahan"
                                               class="dj-input @error('kelurahan') is-error @enderror"
                                               placeholder="Kelurahan"
                                               value="{{ old('kelurahan', $edit->kelurahan ?? '') }}">
                                    </div>
                                    <div class="dj-field">
                                        <label class="dj-label" for="kecamatan">Kecamatan</label>
                                        <input type="text" id="kecamatan" name="kecamatan"
                                               class="dj-input @error('kecamatan') is-error @enderror"
                                               placeholder="Kecamatan"
                                               value="{{ old('kecamatan', $edit->kecamatan ?? '') }}">
                                    </div>
                                </div>

                                {{-- Seksi: Keanggotaan --}}
                                <div class="dj-section-title">
                                    <i class="fa-solid fa-mosque"></i> Keanggotaan
                                </div>

                                <div class="dj-row-2">
                                    <div class="dj-field">
                                        <label class="dj-label" for="status">Status</label>
                                        <select id="status" name="status"
                                                class="dj-input @error('status') is-error @enderror">
                                            @php
                                                $statusList = ['aktif'=>'Aktif','nonaktif'=>'Nonaktif','pindah'=>'Pindah','meninggal'=>'Meninggal'];
                                                $selStatus  = old('status', $edit->status ?? 'aktif');
                                            @endphp
                                            @foreach($statusList as $v => $l)
                                                <option value="{{ $v }}" {{ $selStatus === $v ? 'selected' : '' }}>{{ $l }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="dj-field">
                                        <label class="dj-label" for="tanggal_bergabung">Tanggal Bergabung</label>
                                        <input type="date" id="tanggal_bergabung" name="tanggal_bergabung"
                                               class="dj-input @error('tanggal_bergabung') is-error @enderror"
                                               value="{{ old('tanggal_bergabung', isset($edit->tanggal_bergabung) ? $edit->tanggal_bergabung->format('Y-m-d') : '') }}">
                                        @error('tanggal_bergabung')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div class="dj-field">
                                    <label class="dj-label" for="catatan">Catatan</label>
                                    <textarea id="catatan" name="catatan" rows="2"
                                              class="dj-input dj-textarea @error('catatan') is-error @enderror"
                                              placeholder="Catatan tambahan (opsional)">{{ old('catatan', $edit->catatan ?? '') }}</textarea>
                                    @error('catatan')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                </div>

                                {{-- Foto --}}
                                <div class="dj-field">
                                    <label class="dj-label" for="foto">
                                        Foto <span class="dj-label-hint">JPG/PNG, maks 2 MB</span>
                                    </label>
                                    @if($edit && $edit->foto)
                                        <div class="dj-foto-preview">
                                            <img src="{{ Storage::url($edit->foto) }}" alt="Foto {{ $edit->nama }}">
                                            <span>Foto tersimpan</span>
                                        </div>
                                    @endif
                                    <input type="file" id="foto" name="foto"
                                           class="dj-input-file @error('foto') is-error @enderror"
                                           accept=".jpg,.jpeg,.png,.webp">
                                    @error('foto')<span class="dj-error-msg">{{ $message }}</span>@enderror
                                </div>

                                {{-- Submit --}}
                                <div class="dj-form-actions">
                                    <button type="submit" class="dj-btn-submit">
                                        <i class="fa-solid fa-{{ $edit ? 'floppy-disk' : 'user-plus' }}"></i>
                                        {{ $edit ? 'Simpan Perubahan' : 'Tambah Jamaah' }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===== DAFTAR JAMAAH ===== --}}
                <div class="dj-list-col">
                    <div class="dj-card">
                        <div class="dj-card-head">
                            <span class="dj-card-title">
                                <i class="fa-solid fa-list-ul"></i> Daftar Jamaah
                            </span>
                            <span class="dj-count-badge">{{ $jamaahs->count() }} anggota</span>
                        </div>

                        {{-- Toolbar: search + filter --}}
                        <form method="GET" action="{{ route('admin.jamaah') }}" class="dj-toolbar" id="djSearch">
                            <div class="dj-search-wrap">
                                <i class="fa-solid fa-magnifying-glass dj-search-icon"></i>
                                <input type="text" name="q" class="dj-search-input"
                                       placeholder="Cari nama, NIK, no HP…"
                                       value="{{ request('q') }}">
                            </div>
                            <select name="peran" class="dj-filter-select" onchange="this.form.submit()">
                                <option value="">Semua Peran</option>
                                @foreach(['jamaah'=>'Jamaah','pengurus'=>'Pengurus','remaja'=>'Remaja','anak'=>'Anak'] as $v => $l)
                                    <option value="{{ $v }}" {{ request('peran') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            <select name="status_filter" class="dj-filter-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                @foreach(['aktif'=>'Aktif','nonaktif'=>'Nonaktif','pindah'=>'Pindah','meninggal'=>'Meninggal'] as $v => $l)
                                    <option value="{{ $v }}" {{ request('status_filter') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="dj-search-btn">
                                <i class="fa-solid fa-search"></i>
                            </button>
                            @if(request()->hasAny(['q','peran','status_filter']))
                                <a href="{{ route('admin.jamaah') }}" class="dj-reset-btn" title="Reset filter">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </form>

                        {{-- Tabel jamaah --}}
                        <div class="dj-table-wrap">
                            @forelse($jamaahs as $item)
                                <div class="dj-row" data-status="{{ $item->status }}">

                                    {{-- Avatar + Nama --}}
                                    <div class="dj-row-identity">
                                        <div class="dj-avatar {{ $item->jenis_kelamin === 'perempuan' ? 'female' : 'male' }}">
                                            @if($item->foto)
                                                <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}">
                                            @else
                                                {{ $item->inisial }}
                                            @endif
                                        </div>
                                        <div class="dj-row-info">
                                            <div class="dj-row-nama">{{ $item->nama }}</div>
                                            <div class="dj-row-sub">
                                                @if($item->no_hp)
                                                    <span><i class="fa-solid fa-phone"></i> {{ $item->no_hp }}</span>
                                                @endif
                                                @if($item->umur)
                                                    <span><i class="fa-solid fa-cake-candles"></i> {{ $item->umur }} thn</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Badges --}}
                                    <div class="dj-row-badges">
                                        <span class="dj-badge peran-{{ $item->peran }}">{{ ucfirst($item->peran) }}</span>
                                        <span class="dj-badge status-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                                        <span class="dj-badge jk-{{ $item->jenis_kelamin === 'laki-laki' ? 'l' : 'p' }}">
                                            <i class="fa-solid fa-{{ $item->jenis_kelamin === 'laki-laki' ? 'mars' : 'venus' }}"></i>
                                        </span>
                                    </div>

                                    {{-- Alamat ringkas --}}
                                    @if($item->kelurahan || $item->kecamatan)
                                        <div class="dj-row-alamat">
                                            <i class="fa-solid fa-location-dot"></i>
                                            {{ implode(', ', array_filter([$item->kelurahan, $item->kecamatan])) }}
                                        </div>
                                    @endif

                                    {{-- Tanggal bergabung --}}
                                    @if($item->tanggal_bergabung)
                                        <div class="dj-row-meta">
                                            <i class="fa-regular fa-calendar"></i>
                                            Bergabung {{ $item->tanggal_bergabung->format('d M Y') }}
                                        </div>
                                    @endif

                                    {{-- Aksi --}}
                                    <div class="dj-row-actions">
                                        <a href="{{ route('admin.jamaah.edit', $item->id) }}"
                                           class="dj-action-btn edit">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.jamaah.destroy', $item->id) }}"
                                              style="display:inline"
                                              onsubmit="return confirm('Hapus data jamaah {{ addslashes($item->nama) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dj-action-btn delete">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="dj-empty">
                                    <div class="dj-empty-icon"><i class="fa-solid fa-users"></i></div>
                                    <div class="dj-empty-title">
                                        @if(request()->hasAny(['q','peran','status_filter']))
                                            Tidak ada jamaah yang cocok
                                        @else
                                            Belum ada data jamaah
                                        @endif
                                    </div>
                                    <div class="dj-empty-sub">
                                        @if(request()->hasAny(['q','peran','status_filter']))
                                            Coba ubah filter pencarian.
                                        @else
                                            Tambahkan jamaah pertama menggunakan form di sebelah kiri.
                                        @endif
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>{{-- end dj-layout --}}

        </main>
    </div>


    <script>
        // Collapse sidebar
        const collapseBtn = document.getElementById('ba2CollapseBtn');
        if (collapseBtn) {
            collapseBtn.addEventListener('click', () => {
                document.getElementById('ba2Sidebar').classList.toggle('collapsed');
                document.getElementById('ba2Main').classList.toggle('expanded');
            });
        }

        // Submit pencarian saat Enter
        document.querySelector('.dj-search-input')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') e.target.closest('form').submit();
        });

        // Auto-hide alert 4 detik
        document.querySelectorAll('.dj-alert').forEach(el => {
            setTimeout(() => el.remove(), 4000);
        });
    </script>
</body>
</html>
