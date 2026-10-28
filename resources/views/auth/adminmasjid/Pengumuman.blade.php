<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
</head>
<body class="lp-body" id="lpBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="lp-sidebar" id="lpSidebar">

        <div class="lp-brand">
            <div class="lp-brand-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div class="lp-brand-info">
                <span class="lp-brand-name">{{ $mosque->mosque_name ?? 'SIM Masjid' }}</span>
                <span class="lp-brand-sub">{{ $mosque->city ?? 'Baitul Digital' }}</span>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="lp-nav">
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="lp-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item {{ request()->routeIs('admin.landing-page*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="lp-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item {{ request()->routeIs('admin.profil-masjid*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>
            <a href="{{ route('admin.jadwal-sholat') }}" class="lp-nav-item {{ request()->routeIs('admin.jadwal-sholat*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="lp-nav-label">Jadwal Shalat</span>
            </a>

            {{-- PENGUMUMAN --}}
            @php
                $pengumumanAktif = isset($mosque)
                    ? \App\Models\Pengumuman::where('mosque_id', $mosque->id)->active()->count()
                    : 0;
            @endphp
            <a href="{{ route('admin.pengumuman') }}" class="lp-nav-item {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="lp-nav-label">Pengumuman</span>
                @if($pengumumanAktif > 0)
                    <span class="lp-nav-badge">{{ $pengumumanAktif }}</span>
                @endif
            </a>

            <a href="{{ route('admin.acara') }}" class="lp-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="lp-nav-label">Kegiatan &amp; Acara</span>
            </a>

            {{-- MENU DONASI DINAMIS BERDASARKAN PAKET MASJID --}}
            @if(isset($mosque) && $mosque->package_type === 'free')
                <a href="{{ route('masjid.perpanjangan.create') }}" class="lp-nav-item {{ request()->routeIs('masjid.perpanjangan*') ? 'active' : '' }}" style="opacity: 0.8;" title="Upgrade paket untuk membuka fitur donasi">
                    <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="lp-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #e74c3c; color: white;">Locked</span>
                </a>
            @else
                <a href="{{ route('admin.donasi') }}" class="lp-nav-item {{ request()->routeIs('admin.donasi*') ? 'active' : '' }}">
                    <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="lp-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #27ae60; color: white;">Aktif</span>
                </a>
            @endif

            <a href="{{ route('admin.jamaah') }}" class="lp-nav-item {{ request()->routeIs('admin.jamaah*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="lp-nav-label">Data Jamaah</span>
            </a>

            {{-- MENU TEMA TAMPILAN --}}
            <a href="{{ route('admin.tema') }}" class="lp-nav-item {{ request()->routeIs('admin.tema*') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-palette"></i></span>
                <span class="lp-nav-label">Tema Tampilan</span>
            </a>
        </nav>

        <div class="lp-user">
            <div class="lp-user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 2) }}</div>
            <div class="lp-user-info">
                <div class="lp-user-name">{{ auth()->user()->name ?? 'Admin Masjid' }}</div>
                <div class="lp-user-email">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="lp-main">

        <header class="lp-topbar">
            <div class="lp-topbar-left" style="display:flex;align-items:center;gap:12px;">
                <button type="button" class="lp-toggle-btn" id="lpToggle" aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <div class="lp-page-title">Pengumuman</div>
                    <div class="lp-page-sub">Kelola pengumuman untuk jamaah. Yang berstatus Terbit tampil di halaman utama masjid Anda</div>
                </div>
            </div>
            <div class="lp-topbar-right">
                @if(!empty($mosque->slug))
                    <a href="{{ route('masjid.publik', $mosque->slug) }}" class="lp-btn-back" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Preview</span>
                    </a>
                @endif
            </div>
        </header>

        <main class="lp-content">
            @php
                $filterAktif = request('category') || request('status');
            @endphp

            <div class="pg-wrap">

                {{-- Toolbar --}}
                <div class="pg-header">
                    <form method="GET" class="pg-filter">
                        <div class="pg-select-wrap">
                            <i class="fa-solid fa-tag"></i>
                            <select name="category" onchange="this.form.submit()" aria-label="Filter kategori" class="pg-select">
                                <option value="">Semua kategori</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pg-select-wrap">
                            <i class="fa-solid fa-signal"></i>
                            <select name="status" onchange="this.form.submit()" aria-label="Filter status" class="pg-select">
                                <option value="">Semua status</option>
                                <option value="terbit" @selected(request('status') === 'terbit')>Terbit</option>
                                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                            </select>
                        </div>
                        @if($filterAktif)
                            <a href="{{ route('admin.pengumuman') }}" class="pg-reset"><i class="fa-solid fa-xmark"></i> Reset filter</a>
                        @endif
                    </form>

                    <button type="button" id="btnCreate" class="pg-btn-add">
                        <i class="fa-solid fa-plus"></i> Tambah pengumuman
                    </button>
                </div>

                @if($items->count())
                    <div class="pg-count">
                        Menampilkan <b>{{ $items->count() }}</b>
                        @if(method_exists($items, 'total')) dari <b>{{ $items->total() }}</b> @endif
                        pengumuman
                    </div>
                @endif

                @if(session('success'))
                    <div class="pg-alert pg-alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="pg-alert pg-alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="pg-alert pg-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- DAFTAR PENGUMUMAN --}}
                @if($items->count())
                    <div class="pg-grid">
                        @foreach($items as $item)
                            @php
                                $editData = [
                                    'id'         => $item->id,
                                    'title'      => $item->title,
                                    'content'    => $item->content,
                                    'category'   => $item->category,
                                    'status'     => $item->status,
                                    'is_pinned'  => $item->is_pinned,
                                    'expires_at' => optional($item->expires_at)->format('Y-m-d'),
                                    'image_url'  => $item->image_url,
                                ];
                            @endphp
                            <article class="pg-card {{ $item->is_pinned ? 'is-pinned' : '' }} {{ $item->is_expired ? 'is-expired' : '' }}">
                                <div class="pg-thumb">
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="Poster {{ $item->title }}" loading="lazy">
                                    @else
                                        <div class="pg-thumb-empty">
                                            <i class="fa-solid fa-bullhorn" aria-hidden="true"></i>
                                            <span>Tanpa poster</span>
                                        </div>
                                    @endif

                                    <div class="pg-thumb-top">
                                        <span class="pg-status {{ $item->status === 'draft' ? 'is-draft' : 'is-live' }}">
                                            <i></i>{{ $item->status === 'draft' ? 'Draft' : 'Terbit' }}
                                        </span>
                                        @if($item->is_pinned)
                                            <span class="pg-pin"><i class="fa-solid fa-thumbtack"></i> Disematkan</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pg-body">
                                    <div class="pg-tags">
                                        <span class="pg-tag">{{ $categories[$item->category] ?? ucfirst($item->category) }}</span>
                                        @if($item->is_expired)<span class="pg-tag is-expired">Sudah berakhir</span>@endif
                                    </div>
                                    <div class="pg-title">{{ $item->title }}</div>
                                    <p class="pg-excerpt">{{ $item->content }}</p>
                                    <div class="pg-meta">
                                        <span><i class="fa-regular fa-calendar"></i> {{ $item->created_at->translatedFormat('d F Y') }}</span>
                                        @if($item->expires_at)
                                            <span><i class="fa-regular fa-hourglass-half"></i> Berakhir {{ $item->expires_at->translatedFormat('d F Y') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pg-actions">
                                    <button type="button" class="pg-btn-edit btn-edit" data-item="{{ json_encode($editData) }}">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus pengumuman ini? Foto yang terpasang juga ikut terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pg-btn-delete">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div style="margin-top:22px">{{ $items->links() }}</div>
                @else
                    <div class="pg-empty">
                        <div class="pg-empty-icon"><i class="fa-solid {{ $filterAktif ? 'fa-filter-circle-xmark' : 'fa-bullhorn' }}"></i></div>
                        @if($filterAktif)
                            <div class="pg-empty-title">Tidak ada pengumuman yang cocok</div>
                            <p>Coba ubah atau reset filter untuk melihat pengumuman lainnya.</p>
                            <a href="{{ route('admin.pengumuman') }}" class="pg-btn-add" style="text-decoration:none;">Reset filter</a>
                        @else
                            <div class="pg-empty-title">Belum ada pengumuman</div>
                            <p>Buat yang pertama, misalnya jadwal kajian, info shalat Jumat, atau libur kegiatan.</p>
                            <button type="button" class="pg-btn-add" data-create><i class="fa-solid fa-plus"></i> Tambah pengumuman pertama</button>
                        @endif
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- ===== MODAL TAMBAH / EDIT ===== --}}
    <dialog class="pg-dialog" id="pgDialog">
        <form method="POST" action="{{ route('admin.pengumuman.store') }}" enctype="multipart/form-data" class="pg-form" id="pgForm">
            @csrf
            <span id="methodSlot"></span>
            <input type="hidden" name="_edit_id" id="editId" value="{{ old('_edit_id') }}">
            <input type="hidden" name="remove_image" id="removeImage" value="0">

            <div class="pg-form-head">
                <div class="pg-form-title">
                    <span class="pg-form-icon"><i class="fa-solid fa-bullhorn"></i></span>
                    <span id="pgTitle">Tambah Pengumuman</span>
                </div>
                <button type="button" class="pg-x" id="btnClose" aria-label="Tutup">&times;</button>
            </div>

            <div class="pg-fields">

                <div class="pg-field">
                    <label class="pg-label" for="fTitle">
                        Judul <span class="pg-count-chip" id="cntTitle">0/150</span>
                    </label>
                    <input type="text" id="fTitle" name="title" maxlength="150" class="pg-input"
                           value="{{ old('title') }}" placeholder="mis. Kajian Ahad Pagi Ditiadakan" required>
                </div>

                {{-- FOTO / POSTER --}}
                <div class="pg-field">
                    <label class="pg-label">Foto / poster <span class="pg-opt">opsional</span></label>

                    <div class="pg-drop" id="drop" tabindex="0" role="button" aria-label="Pilih foto pengumuman">
                        <div class="pg-drop-icon"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i></div>
                        <div class="pg-drop-title">Tarik foto ke sini atau <u>pilih dari perangkat</u></div>
                        <div class="pg-drop-note">JPG, PNG, WebP · maksimal 3 MB</div>
                    </div>
                    <input type="file" id="fImage" name="image" accept="image/jpeg,image/png,image/webp" hidden>

                    <div class="pg-preview" id="preview">
                        <img src="" alt="Pratinjau foto" id="previewImg">
                        <span class="pg-preview-badge" id="previewBadge">Foto baru</span>
                        <div class="pg-preview-meta">
                            <div class="pg-preview-info">
                                <div class="pg-preview-name" id="previewName"></div>
                                <div class="pg-preview-size" id="previewSize"></div>
                            </div>
                            <div class="pg-preview-actions">
                                <button type="button" class="pg-photo-btn" id="btnChangeImg"><i class="fa-solid fa-arrows-rotate"></i> Ganti</button>
                                <button type="button" class="pg-photo-btn danger" id="btnRemoveImg"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                            </div>
                        </div>
                    </div>
                    <div class="pg-photo-err" id="photoErr"></div>
                </div>

                <div class="pg-field">
                    <label class="pg-label" for="fContent">
                        Isi pengumuman <span class="pg-count-chip" id="cntContent">0/3000</span>
                    </label>
                    <textarea id="fContent" name="content" maxlength="3000" rows="5" class="pg-input pg-textarea"
                              placeholder="Tulis isi pengumuman untuk jamaah..." required>{{ old('content') }}</textarea>
                </div>

                <div class="pg-row">
                    <div class="pg-field">
                        <label class="pg-label" for="fCategory">Kategori</label>
                        <select id="fCategory" name="category" class="pg-input">
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @selected(old('category', 'umum') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pg-field">
                        <label class="pg-label">Status</label>
                        <div class="pg-seg" role="radiogroup" aria-label="Status pengumuman">
                            <label class="pg-seg-item">
                                <input type="radio" name="status" value="terbit" @checked(old('status', 'terbit') === 'terbit')>
                                <span><i class="fa-solid fa-globe"></i> Terbit</span>
                            </label>
                            <label class="pg-seg-item">
                                <input type="radio" name="status" value="draft" @checked(old('status') === 'draft')>
                                <span><i class="fa-solid fa-pen-ruler"></i> Draft</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pg-field">
                    <label class="pg-label" for="fExpires">Tanggal berakhir <span class="pg-opt">opsional</span></label>
                    <input type="date" id="fExpires" name="expires_at" class="pg-input" value="{{ old('expires_at') }}">
                    <span class="pg-hint">Setelah tanggal ini, pengumuman tidak lagi tampil di halaman publik.</span>
                </div>

                <label class="pg-feature">
                    <input type="checkbox" id="fPinned" name="is_pinned" value="1" @checked(old('is_pinned'))>
                    <span class="pg-feature-switch"></span>
                    <span class="pg-feature-text">
                        <strong>Sematkan di paling atas</strong>
                        <small>Pengumuman penting akan selalu tampil pertama.</small>
                    </span>
                </label>
            </div>

            <div class="pg-form-actions">
                <button type="button" class="pg-btn-cancel" id="btnCancel">Batal</button>
                <button type="submit" class="pg-btn-save" id="btnSave"><i class="fa-solid fa-floppy-disk"></i> Simpan pengumuman</button>
            </div>
        </form>
    </dialog>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <style>
    .pg-wrap {
        --pg-line: #e7e5dc;
        max-width: 1000px;
        font-family: 'Plus Jakarta Sans', 'Poppins', 'Segoe UI', sans-serif;
    }

    /* ---- Toolbar ---- */
    .pg-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
    .pg-filter { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .pg-select-wrap { position: relative; }
    .pg-select-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: .78rem; color: var(--text-light); pointer-events: none; }
    .pg-select {
        appearance: none; -webkit-appearance: none;
        border: 1px solid var(--border); border-radius: 12px; padding: 10px 14px 10px 34px;
        background: #fff; font-size: 0.84rem; font-weight: 600; font-family: inherit; color: var(--text);
        cursor: pointer; transition: border-color .16s, box-shadow .16s;
    }
    .pg-select:hover { border-color: #b9d4c4; }
    .pg-select:focus { outline: none; border-color: var(--green-2); box-shadow: 0 0 0 4px rgba(47,122,82,.13); }
    .pg-reset {
        display: inline-flex; align-items: center; gap: 6px; padding: 9px 12px;
        font-size: .8rem; font-weight: 700; color: #b91c1c; text-decoration: none;
        border-radius: 10px; transition: background .15s;
    }
    .pg-reset:hover { background: #fef2f2; }

    .pg-btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--green), var(--green-2)); color: #fff; border: none;
        padding: 11px 22px; border-radius: 12px; font-weight: 800; font-size: 0.86rem; font-family: inherit;
        cursor: pointer; white-space: nowrap;
        box-shadow: 0 12px 20px -12px rgba(26,71,49,.95);
        transition: transform .15s, filter .15s;
    }
    .pg-btn-add:hover { transform: translateY(-1px); filter: brightness(1.08); }

    .pg-count { margin-bottom: 16px; font-size: .78rem; color: var(--text-light); }
    .pg-count b { color: var(--text-mid); }

    /* ---- Alert ---- */
    .pg-alert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: .86rem; font-weight: 600; }
    .pg-alert i { margin-top: 2px; }
    .pg-alert ul { margin: 0; padding-left: 18px; }
    .pg-alert-success { background: #ecfdf3; color: #166534; border: 1px solid #bbf7d0; }
    .pg-alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* ---- Kartu ---- */
    .pg-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 18px; }
    .pg-card {
        background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
        overflow: hidden; display: flex; flex-direction: column;
        box-shadow: var(--shadow-sm);
        transition: transform .18s, box-shadow .18s, border-color .18s;
    }
    .pg-card:hover { transform: translateY(-3px); border-color: #b9d4c4; box-shadow: 0 22px 32px -22px rgba(26,71,49,.55); }
    .pg-card.is-pinned { border-color: #e8c26f; }
    .pg-card.is-expired .pg-thumb img { filter: grayscale(.8); opacity: .85; }
    .pg-card.is-expired .pg-title { color: var(--text-mid); }

    .pg-thumb { position: relative; aspect-ratio: 16 / 9; background: #eef2ef; overflow: hidden; }
    .pg-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pg-thumb::after { content: ''; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(180deg, rgba(10,30,20,.35) 0%, rgba(10,30,20,0) 40%); }
    .pg-thumb-empty {
        height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;
        color: #9db3a6; font-size: 1.8rem;
        background: repeating-linear-gradient(135deg, #f1f5f2 0 12px, #eaf0ec 12px 24px);
    }
    .pg-thumb-empty span { font-size: .72rem; font-weight: 700; }
    .pg-thumb-top { position: absolute; top: 10px; left: 10px; right: 10px; z-index: 2; display: flex; justify-content: space-between; gap: 8px; }

    .pg-status {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px;
        font-size: .68rem; font-weight: 800; background: rgba(255,255,255,.95); color: var(--text-mid);
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }
    .pg-status i { width: 7px; height: 7px; border-radius: 50%; background: #94a3b8; }
    .pg-status.is-live { color: #166534; }
    .pg-status.is-live i { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.2); }
    .pg-pin {
        display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px;
        font-size: .68rem; font-weight: 800; background: #fbe3a4; color: #7a4f06; box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .pg-body { padding: 16px 18px 8px; flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .pg-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .pg-tag { font-size: .68rem; font-weight: 800; padding: 3px 10px; border-radius: 999px; background: #eef6f1; color: var(--green-2); }
    .pg-tag.is-expired { background: #fef2f2; color: #b91c1c; }
    .pg-title { font-weight: 800; font-size: 1.02rem; line-height: 1.35; letter-spacing: -.01em; color: var(--text); }
    .pg-excerpt {
        margin: 0; font-size: .84rem; color: var(--text-mid); line-height: 1.6;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .pg-meta { margin-top: auto; padding-top: 4px; display: flex; flex-wrap: wrap; gap: 4px 14px; font-size: .74rem; color: var(--text-light); }
    .pg-meta i { margin-right: 5px; }

    .pg-actions { display: flex; gap: 8px; padding: 12px 18px 16px; }
    .pg-actions form { margin: 0; }
    .pg-btn-edit, .pg-btn-delete {
        display: inline-flex; align-items: center; gap: 7px;
        border: 1px solid var(--border); background: #fbfcfb; border-radius: 10px;
        padding: 8px 14px; font-size: .8rem; font-weight: 700; cursor: pointer; color: var(--text-mid); font-family: inherit;
        transition: all .15s;
    }
    .pg-btn-edit { flex: 1; justify-content: center; }
    .pg-btn-edit:hover { border-color: var(--green-2); background: #f0f8f3; color: var(--green); }
    .pg-btn-delete:hover { border-color: #ef4444; background: #fef2f2; color: #b91c1c; }

    /* ---- Kosong ---- */
    .pg-empty { text-align: center; padding: 56px 24px; color: var(--text-light); border: 2px dashed #d5ddd8; border-radius: 20px; background: #fcfdfc; }
    .pg-empty-icon { width: 66px; height: 66px; margin: 0 auto 14px; border-radius: 50%; display: grid; place-items: center; background: #eef6f1; color: var(--green-2); font-size: 1.5rem; }
    .pg-empty-title { font-size: 1.05rem; font-weight: 800; color: var(--text); margin-bottom: 4px; }
    .pg-empty p { margin: 0 auto 20px; max-width: 380px; font-size: .86rem; line-height: 1.6; }

    /* ---- Modal ---- */
    .pg-dialog { border: 0; border-radius: 20px; padding: 0; width: min(580px, 94vw); max-height: 92vh; font-family: inherit; box-shadow: 0 40px 80px -30px rgba(0,0,0,.6); }
    .pg-dialog[open] { animation: pgIn .22s ease; }
    .pg-dialog::backdrop { background: rgba(10, 30, 20, .58); backdrop-filter: blur(3px); }
    @@keyframes pgIn { from { opacity: 0; transform: translateY(10px) scale(.98); } to { opacity: 1; transform: none; } }

    .pg-form { display: flex; flex-direction: column; max-height: 92vh; background: #fff; font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; color: var(--text); }
    .pg-form-head { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid var(--border); }
    .pg-form-title { display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 1.05rem; }
    .pg-form-icon { width: 36px; height: 36px; border-radius: 11px; display: grid; place-items: center; background: #eef6f1; color: var(--green-2); font-size: .95rem; }
    .pg-x { width: 34px; height: 34px; background: #f3f6f4; border: 0; border-radius: 10px; font-size: 1.3rem; line-height: 1; cursor: pointer; color: var(--text-mid); transition: background .15s; }
    .pg-x:hover { background: #e4ece7; }

    .pg-fields { padding: 20px 22px 8px; overflow-y: auto; display: flex; flex-direction: column; }
    .pg-field { display: flex; flex-direction: column; gap: 7px; margin-bottom: 18px; }
    .pg-label { display: flex; align-items: center; justify-content: space-between; font-size: .8rem; font-weight: 700; color: #38433d; margin: 0; }
    .pg-opt { font-weight: 500; font-size: .72rem; color: var(--text-light); }
    .pg-count-chip { font-weight: 600; font-size: .7rem; color: var(--text-light); }
    .pg-hint { font-size: .74rem; color: var(--text-light); }

    .pg-input {
        border: 1.5px solid #dbe4de; border-radius: 12px; padding: 11px 14px; width: 100%;
        font-size: .9rem; font-family: inherit; color: var(--text); background: #fbfcfb; box-sizing: border-box;
        transition: border-color .16s, box-shadow .16s, background .16s;
    }
    .pg-input:focus { outline: none; background: #fff; border-color: var(--green-2); box-shadow: 0 0 0 4px rgba(47,122,82,.13); }
    .pg-textarea { resize: vertical; min-height: 110px; line-height: 1.6; }
    .pg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    /* Segmented status */
    .pg-seg { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; padding: 4px; background: #eef2ef; border-radius: 12px; }
    .pg-seg-item { position: relative; cursor: pointer; }
    .pg-seg-item input { position: absolute; opacity: 0; pointer-events: none; }
    .pg-seg-item span {
        display: flex; align-items: center; justify-content: center; gap: 7px; padding: 8px 6px; border-radius: 9px;
        font-size: .8rem; font-weight: 700; color: var(--text-mid); transition: all .16s;
    }
    .pg-seg-item input:checked + span { background: #fff; color: var(--green); box-shadow: 0 2px 8px rgba(15,35,27,.12); }
    .pg-seg-item input:focus-visible + span { outline: 2px solid var(--green-2); outline-offset: 2px; }

    /* Switch sematkan */
    .pg-feature {
        position: relative; display: flex; align-items: center; gap: 14px; padding: 14px 16px; margin-bottom: 14px;
        border: 1.5px solid var(--border); border-radius: 14px; background: #fff; cursor: pointer; transition: border-color .16s, background .16s;
    }
    .pg-feature:hover { border-color: #b9d4c4; }
    .pg-feature:has(input:checked) { border-color: #e8c26f; background: #fff9ea; }
    .pg-feature input { position: absolute; opacity: 0; pointer-events: none; }
    .pg-feature-switch { position: relative; flex-shrink: 0; width: 44px; height: 25px; background: #d5dbd7; border-radius: 99px; transition: background .2s; }
    .pg-feature-switch::after { content: ''; position: absolute; top: 3px; left: 3px; width: 19px; height: 19px; background: #fff; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,.25); transition: transform .2s; }
    .pg-feature input:checked + .pg-feature-switch { background: var(--gold); }
    .pg-feature input:checked + .pg-feature-switch::after { transform: translateX(19px); }
    .pg-feature input:focus-visible + .pg-feature-switch { outline: 3px solid rgba(47,122,82,.4); outline-offset: 2px; }
    .pg-feature-text strong { display: block; font-size: .86rem; font-weight: 800; }
    .pg-feature-text small { font-size: .74rem; color: var(--text-light); }

    /* ---- Dropzone foto ---- */
    .pg-drop {
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;
        min-height: 170px; padding: 22px 16px; text-align: center; cursor: pointer;
        border: 2px dashed #bfd1c6; border-radius: 16px;
        background: linear-gradient(180deg, #fbfdfb, #f3f8f4);
        transition: border-color .2s, background .2s, transform .2s;
    }
    .pg-drop:hover { border-color: var(--green-2); background: #f0f8f3; }
    .pg-drop:focus-visible { outline: 3px solid rgba(47,122,82,.4); outline-offset: 2px; }
    .pg-drop.over { border-color: var(--gold); background: #fff9ea; transform: scale(1.01); }
    .pg-drop-icon {
        width: 52px; height: 52px; border-radius: 50%; display: grid; place-items: center; margin-bottom: 6px;
        background: #fff; border: 1px solid #cfe5d7; color: var(--green-2); font-size: 1.2rem;
        box-shadow: 0 8px 18px -8px rgba(26,71,49,.35); transition: transform .2s;
    }
    .pg-drop:hover .pg-drop-icon { transform: translateY(-3px); }
    .pg-drop-title { font-size: .88rem; font-weight: 700; color: var(--text); }
    .pg-drop-title u { text-decoration: none; color: var(--green-2); border-bottom: 1.5px solid currentColor; }
    .pg-drop-note { font-size: .74rem; color: var(--text-light); }

    .pg-preview { display: none; position: relative; border-radius: 16px; overflow: hidden; background: #000; border: 1px solid #cfe5d7; }
    .pg-preview.show { display: block; }
    .pg-preview img { display: block; width: 100%; aspect-ratio: 16 / 9; object-fit: cover; }
    .pg-preview::after { content: ''; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,.7) 100%); }
    .pg-preview-badge {
        position: absolute; top: 12px; left: 12px; z-index: 2; display: none; padding: 4px 11px;
        font-size: .7rem; font-weight: 800; color: #3a2704; background: #fbe3a4; border-radius: 99px;
    }
    .pg-preview.is-new .pg-preview-badge { display: inline-block; }
    .pg-preview-meta {
        position: absolute; left: 14px; right: 14px; bottom: 12px; z-index: 2; color: #fff;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .pg-preview-info { min-width: 0; }
    .pg-preview-name { font-size: .8rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pg-preview-size { font-size: .72rem; color: rgba(255,255,255,.8); }
    .pg-preview-actions { display: flex; gap: 8px; flex-shrink: 0; }
    .pg-photo-btn {
        display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px;
        font-size: .76rem; font-weight: 700; font-family: inherit; color: #fff; cursor: pointer;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35); border-radius: 9px;
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); transition: background .15s;
    }
    .pg-photo-btn:hover { background: rgba(255,255,255,.3); }
    .pg-photo-btn.danger:hover { background: rgba(224,71,61,.9); border-color: transparent; }
    .pg-photo-err { display: none; font-size: .78rem; font-weight: 700; color: #dc2626; }
    .pg-photo-err.show { display: block; }

    .pg-form-actions { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 22px; border-top: 1px solid var(--border); background: #fbfcfb; }
    .pg-btn-cancel {
        padding: 10px 20px; border-radius: 12px; border: 1px solid #d5dad5; background: #fff;
        color: var(--text-mid); font-size: .84rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: background .15s;
    }
    .pg-btn-cancel:hover { background: #f3f6f4; }
    .pg-btn-save {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; border: none; border-radius: 12px;
        background: linear-gradient(135deg, var(--green), var(--green-2)); color: #fff;
        font-weight: 800; font-size: .84rem; font-family: inherit; cursor: pointer;
        box-shadow: 0 12px 20px -12px rgba(26,71,49,.95); transition: transform .15s, filter .15s;
    }
    .pg-btn-save:hover { transform: translateY(-1px); filter: brightness(1.08); }
    .pg-btn-save:disabled { opacity: .7; cursor: progress; transform: none; }

    @@media (max-width: 560px) {
        .pg-row { grid-template-columns: 1fr; }
        .pg-preview-meta { flex-direction: column; align-items: flex-start; }
        .pg-header { flex-direction: column; align-items: stretch; }
        .pg-btn-add { justify-content: center; }
    }
    @@media (prefers-reduced-motion: reduce) {
        .pg-wrap *, .pg-dialog { transition: none !important; animation: none !important; }
    }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- Sidebar toggle ---
        const lpSidebar = document.getElementById('lpSidebar');
        const lpBody = document.getElementById('lpBody');
        document.getElementById('lpToggle')?.addEventListener('click', function () {
            lpSidebar?.classList.toggle('collapsed');
            lpBody?.classList.toggle('lp-sidebar-collapsed');
        });

        // --- Elemen modal ---
        const dlg = document.getElementById('pgDialog');
        const form = document.getElementById('pgForm');
        const fImage = document.getElementById('fImage');
        const drop = document.getElementById('drop');
        const preview = document.getElementById('preview');
        const previewImg = document.getElementById('previewImg');
        const previewName = document.getElementById('previewName');
        const previewSize = document.getElementById('previewSize');
        const photoErr = document.getElementById('photoErr');
        const removeImage = document.getElementById('removeImage');
        const fTitle = document.getElementById('fTitle');
        const fContent = document.getElementById('fContent');
        const btnSave = document.getElementById('btnSave');
        const storeUrl = @json(route('admin.pengumuman.store'));
        const updateUrl = @json(route('admin.pengumuman.update', ['id' => '__ID__']));
        const MAX = 3 * 1024 * 1024;
        let objectUrl = null;

        const fmt = b => b < 1048576 ? Math.round(b / 1024) + ' KB' : (b / 1048576).toFixed(1) + ' MB';
        const showErr = msg => { photoErr.textContent = msg; photoErr.classList.toggle('show', !!msg); };

        // --- Penghitung karakter ---
        function updateCounters() {
            document.getElementById('cntTitle').textContent = fTitle.value.length + '/150';
            document.getElementById('cntContent').textContent = fContent.value.length + '/3000';
        }
        fTitle.addEventListener('input', updateCounters);
        fContent.addEventListener('input', updateCounters);

        // --- Foto ---
        function showPreview(src, name, size, isNew) {
            previewImg.src = src;
            previewName.textContent = name || '';
            previewSize.textContent = size || '';
            preview.classList.add('show');
            preview.classList.toggle('is-new', !!isNew);
            drop.style.display = 'none';
        }
        function clearPreview() {
            if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
            fImage.value = '';
            previewImg.removeAttribute('src');
            preview.classList.remove('show', 'is-new');
            drop.style.display = '';
            showErr('');
        }

        function resetFormState() {
            form.reset();
            removeImage.value = '0';
            clearPreview();
        }

        function openCreate() {
            resetFormState();
            form.action = storeUrl;
            document.getElementById('methodSlot').innerHTML = '';
            document.getElementById('editId').value = '';
            document.getElementById('pgTitle').textContent = 'Tambah Pengumuman';
            updateCounters();
            dlg.showModal();
        }

        function openEdit(d) {
            resetFormState();
            form.action = updateUrl.replace('__ID__', d.id);
            document.getElementById('methodSlot').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('editId').value = d.id;
            document.getElementById('pgTitle').textContent = 'Edit Pengumuman';
            fTitle.value = d.title;
            fContent.value = d.content;
            document.getElementById('fCategory').value = d.category;
            const radio = form.querySelector('input[name="status"][value="' + d.status + '"]');
            if (radio) radio.checked = true;
            document.getElementById('fExpires').value = d.expires_at || '';
            document.getElementById('fPinned').checked = !!d.is_pinned;
            if (d.image_url) showPreview(d.image_url, 'Foto saat ini', '', false);
            updateCounters();
            dlg.showModal();
        }

        document.getElementById('btnCreate').addEventListener('click', openCreate);
        document.querySelectorAll('[data-create]').forEach(b => b.addEventListener('click', openCreate));
        document.querySelectorAll('.btn-edit').forEach(function (b) {
            b.addEventListener('click', function () { openEdit(JSON.parse(b.dataset.item)); });
        });
        document.getElementById('btnClose').addEventListener('click', () => dlg.close());
        document.getElementById('btnCancel').addEventListener('click', () => dlg.close());
        dlg.addEventListener('click', e => { if (e.target === dlg) dlg.close(); });

        // Pilih / seret foto
        drop.addEventListener('click', () => fImage.click());
        drop.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fImage.click(); }
        });
        ['dragenter', 'dragover'].forEach(ev =>
            drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('over'); }));
        ['dragleave', 'drop'].forEach(ev =>
            drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('over'); }));
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer.files.length) {
                fImage.files = e.dataTransfer.files;
                fImage.dispatchEvent(new Event('change'));
            }
        });

        fImage.addEventListener('change', function () {
            const f = fImage.files[0];
            if (!f) return;
            showErr('');
            if (!/^image\/(jpeg|png|webp)$/.test(f.type)) {
                fImage.value = '';
                return showErr('Format gambar harus JPG, PNG, atau WebP.');
            }
            if (f.size > MAX) {
                fImage.value = '';
                return showErr('Ukuran ' + fmt(f.size) + ' melebihi batas 3 MB. Kompres fotonya lalu coba lagi.');
            }
            removeImage.value = '0';
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(f);
            showPreview(objectUrl, f.name, fmt(f.size), true);
        });

        document.getElementById('btnChangeImg').addEventListener('click', () => fImage.click());
        document.getElementById('btnRemoveImg').addEventListener('click', function () {
            clearPreview();
            removeImage.value = '1'; // saat edit: hapus foto lama di server
        });

        // Tombol simpan: tampilkan status memproses
        form.addEventListener('submit', function () {
            btnSave.innerHTML = '<span class="lp-spin"></span> Menyimpan...';
            setTimeout(() => { btnSave.disabled = true; }, 0);
        });

        // Buka ulang modal jika validasi server gagal
        @if($errors->any())
            (function () {
                const editId = @json(old('_edit_id'));
                if (editId) {
                    form.action = updateUrl.replace('__ID__', editId);
                    document.getElementById('methodSlot').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    document.getElementById('pgTitle').textContent = 'Edit Pengumuman';
                }
                updateCounters();
                dlg.showModal();
            })();
        @endif

        updateCounters();
    });
    </script>
</body>
</html>