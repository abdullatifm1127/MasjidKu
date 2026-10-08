<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="lp-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item {{ request()->routeIs('admin.landing-page') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="lp-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item {{ request()->routeIs('admin.profil-masjid') ? 'active' : '' }}">
                <span class="lp-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>
            <a href="{{ route('admin.jadwal-sholat') }}" class="lp-nav-item {{ request()->routeIs('admin.jadwal-sholat') ? 'active' : '' }}">
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
                <a href="{{ route('masjid.perpanjangan.create') }}" class="lp-nav-item" style="opacity: 0.8;" title="Upgrade paket untuk membuka fitur donasi">
                    <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="lp-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #e74c3c; color: white;">Locked</span>
                </a>
            @else
                <a href="{{ route('admin.donasi') }}" class="lp-nav-item">
                    <span class="lp-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="lp-nav-label">Donasi</span>
                    <span class="lp-nav-soon" style="background: #27ae60; color: white;">Aktif</span>
                </a>
            @endif

            <a href="{{ route('admin.jamaah') }}" class="lp-nav-item">
                <span class="lp-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="lp-nav-label">Data Jamaah</span>
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Preview
                </a>
                @endif
            </div>
        </header>

        <main class="lp-content">
            <div class="pg-wrap">

                <div class="pg-header">
                    <form method="GET" class="pg-filter">
                        <select name="category" onchange="this.form.submit()" aria-label="Filter kategori" class="pg-select">
                            <option value="">Semua kategori</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="status" onchange="this.form.submit()" aria-label="Filter status" class="pg-select">
                            <option value="">Semua status</option>
                            <option value="terbit" @selected(request('status') === 'terbit')>Terbit</option>
                            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        </select>
                    </form>
                    <button type="button" id="btnCreate" class="pg-btn-add">+ Tambah Pengumuman</button>
                </div>

                @if(session('success'))
                    <div class="pg-alert pg-alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="pg-alert pg-alert-error">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="pg-alert pg-alert-error">
                        <ul style="margin:0; padding-left:18px;">
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
                        <article class="pg-card {{ $item->is_pinned ? 'is-pinned' : '' }}">
                            <div class="pg-thumb">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="Poster {{ $item->title }}" loading="lazy">
                                @else
                                    <i class="fa-regular fa-image" aria-hidden="true"></i>
                                @endif
                                @if($item->is_pinned)
                                    <span class="pg-pin"><i class="fa-solid fa-thumbtack"></i> Disematkan</span>
                                @endif
                            </div>
                            <div class="pg-body">
                                <div class="pg-tags">
                                    <span class="pg-tag">{{ $categories[$item->category] ?? ucfirst($item->category) }}</span>
                                    <span class="pg-tag {{ $item->status === 'draft' ? 'is-draft' : 'is-live' }}">{{ $item->status === 'draft' ? 'Draft' : 'Terbit' }}</span>
                                    @if($item->is_expired)<span class="pg-tag is-expired">Sudah berakhir</span>@endif
                                </div>
                                <div class="pg-title">{{ $item->title }}</div>
                                <p class="pg-excerpt">{{ $item->content }}</p>
                                <div class="pg-meta">
                                    Dibuat {{ $item->created_at->translatedFormat('d F Y') }}
                                    @if($item->expires_at) · berakhir {{ $item->expires_at->translatedFormat('d F Y') }} @endif
                                </div>
                            </div>
                            <div class="pg-actions">
                                <button type="button" class="pg-btn-edit btn-edit" data-item="{{ json_encode($editData) }}">Edit</button>
                                <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus pengumuman ini? Foto yang terpasang juga ikut terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pg-btn-delete">Hapus</button>
                                </form>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <div style="margin-top:22px">{{ $items->links() }}</div>
                @else
                    <div class="pg-empty">Belum ada pengumuman. Klik "Tambah Pengumuman" untuk membuat yang pertama, misalnya jadwal kajian atau info sholat Jumat.</div>
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
                <div class="pg-form-title" id="pgTitle">Tambah Pengumuman</div>
                <button type="button" class="pg-x" id="btnClose" aria-label="Tutup">&times;</button>
            </div>

            <div class="pg-fields">
                <label class="pg-label" for="fTitle">Judul</label>
                <input type="text" id="fTitle" name="title" maxlength="150" class="pg-input" value="{{ old('title') }}" placeholder="mis. Kajian Ahad Pagi Ditiadakan" required>

                <label class="pg-label">Foto / Poster (opsional)</label>
                <div class="pg-drop" id="drop" tabindex="0" role="button" aria-label="Pilih foto pengumuman">
                    <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                    <p>Klik atau seret foto ke sini</p>
                    <span class="pg-hint">JPG, PNG, WebP · Maks. 3MB</span>
                </div>
                <input type="file" id="fImage" name="image" accept="image/jpeg,image/png,image/webp" hidden>
                <div class="pg-preview" id="preview">
                    <img src="" alt="Pratinjau foto" id="previewImg">
                    <button type="button" id="btnRemoveImg">Hapus foto</button>
                </div>

                <label class="pg-label" for="fContent">Isi Pengumuman</label>
                <textarea id="fContent" name="content" maxlength="3000" rows="5" class="pg-input pg-textarea" required>{{ old('content') }}</textarea>

                <div class="pg-row">
                    <div>
                        <label class="pg-label" for="fCategory">Kategori</label>
                        <select id="fCategory" name="category" class="pg-input">
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @selected(old('category', 'umum') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="pg-label" for="fStatus">Status</label>
                        <select id="fStatus" name="status" class="pg-input">
                            <option value="terbit" @selected(old('status', 'terbit') === 'terbit')>Terbit</option>
                            <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                        </select>
                    </div>
                </div>

                <label class="pg-label" for="fExpires">Tanggal Berakhir (opsional)</label>
                <input type="date" id="fExpires" name="expires_at" class="pg-input" value="{{ old('expires_at') }}">
                <span class="pg-hint">Setelah tanggal ini, pengumuman tidak lagi tampil di halaman publik.</span>

                <label class="pg-checkbox">
                    <input type="checkbox" id="fPinned" name="is_pinned" value="1" @checked(old('is_pinned'))>
                    Sematkan di paling atas
                </label>
            </div>

            <div class="pg-form-actions">
                <button type="button" class="pg-btn-cancel" id="btnCancel">Batal</button>
                <button type="submit" class="pg-btn-save">Simpan Pengumuman</button>
            </div>
        </form>
    </dialog>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <style>
    .pg-wrap { max-width: 980px; }
    .pg-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
    .pg-filter { display: flex; gap: 10px; flex-wrap: wrap; }
    .pg-select {
        border: 1px solid #e5e2d8; border-radius: 999px; padding: 9px 14px; background: #fff;
        font-size: 0.84rem; font-family: inherit; color: #1c2620;
    }

    .pg-alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 0.88rem; }
    .pg-alert-success { background: #e4eee6; color: #0f4a38; }
    .pg-alert-error { background: #fbe4e4; color: #8a2c2c; }

    .pg-btn-add {
        background: #0f4a38; color: #fff; border: none; padding: 11px 22px;
        border-radius: 999px; font-weight: 600; cursor: pointer; font-size: 0.88rem; white-space: nowrap;
    }
    .pg-btn-add:hover { background: #15583f; }

    .pg-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
    .pg-card { background: #fff; border: 1px solid #e5e2d8; border-radius: 14px; overflow: hidden; display: flex; flex-direction: column; }
    .pg-card.is-pinned { border-color: #c0923d; }
    .pg-thumb {
        aspect-ratio: 16 / 9; background: #faf8f3; position: relative;
        display: flex; align-items: center; justify-content: center; color: #b9b39f; font-size: 2rem;
    }
    .pg-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pg-pin {
        position: absolute; top: 10px; left: 10px; background: #e7cd8e; color: #93672a;
        font-size: 0.68rem; font-weight: 700; padding: 4px 10px; border-radius: 999px;
    }
    .pg-body { padding: 14px 16px 8px; flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .pg-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .pg-tag { font-size: 0.68rem; font-weight: 700; padding: 3px 10px; border-radius: 999px; background: #faf8f3; color: #6b7a71; border: 1px solid #e5e2d8; }
    .pg-tag.is-live { background: #e4eee6; color: #0f4a38; border-color: #e4eee6; }
    .pg-tag.is-draft { background: #eceff1; color: #55646c; border-color: #eceff1; }
    .pg-tag.is-expired { background: #fbe4e4; color: #8a2c2c; border-color: #fbe4e4; }
    .pg-title { font-weight: 700; font-size: 1.02rem; line-height: 1.3; }
    .pg-excerpt {
        margin: 0; font-size: 0.86rem; color: #6b7a71; line-height: 1.55;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .pg-meta { margin-top: auto; font-size: 0.76rem; color: #8a9891; }
    .pg-actions { display: flex; gap: 8px; padding: 10px 16px 14px; }
    .pg-btn-edit, .pg-btn-delete {
        border: 1px solid #e5e2d8; background: #faf8f3; border-radius: 8px;
        padding: 8px 14px; font-size: 0.82rem; font-weight: 600; cursor: pointer; color: #1c2620; font-family: inherit;
    }
    .pg-btn-edit:hover { border-color: #1e6e4c; background: #e4eee6; }
    .pg-btn-delete:hover { border-color: #c0392b; background: #fbe4e4; color: #c0392b; }

    .pg-empty {
        text-align: center; padding: 48px 20px; color: #6b7a71;
        border: 1.5px dashed #e5e2d8; border-radius: 14px; font-size: 0.92rem;
    }

    /* Modal */
    .pg-dialog { border: 0; border-radius: 16px; padding: 0; width: min(560px, 94vw); max-height: 92vh; font-family: inherit; }
    .pg-dialog::backdrop { background: rgba(15, 40, 30, 0.55); }
    .pg-form { display: flex; flex-direction: column; max-height: 92vh; background: #fff; }
    .pg-form-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e5e2d8; }
    .pg-form-title { font-weight: 700; font-size: 1.05rem; }
    .pg-x { background: none; border: 0; font-size: 1.4rem; line-height: 1; cursor: pointer; color: #6b7a71; }
    .pg-fields { padding: 8px 20px 18px; overflow-y: auto; display: flex; flex-direction: column; }
    .pg-label { font-size: 0.8rem; font-weight: 600; color: #4b5a50; margin: 14px 0 6px; }
    .pg-hint { font-size: 0.72rem; color: #8a9891; margin-top: 4px; }
    .pg-input {
        border: 1px solid #e5e2d8; border-radius: 10px; padding: 10px 12px;
        font-size: 0.92rem; font-family: inherit; width: 100%; background: #fff;
    }
    .pg-input:focus { outline: none; border-color: #c0923d; }
    .pg-textarea { resize: vertical; }
    .pg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .pg-checkbox { display: flex; align-items: center; gap: 8px; margin-top: 16px; font-size: 0.84rem; color: #4b5a50; }

    .pg-drop {
        border: 1.5px dashed #d8d3c3; border-radius: 12px; padding: 18px; text-align: center;
        cursor: pointer; background: #faf8f3; color: #0f4a38; transition: border-color .15s, background .15s;
    }
    .pg-drop:hover, .pg-drop.over { border-color: #0f4a38; background: #e4eee6; }
    .pg-drop:focus-visible { outline: 2px solid #c0923d; outline-offset: 2px; }
    .pg-drop i { font-size: 1.5rem; }
    .pg-drop p { margin: 6px 0 2px; font-size: 0.86rem; color: #4b5a50; font-weight: 600; }
    .pg-drop .pg-hint { display: block; margin: 0; }
    .pg-preview { display: none; position: relative; }
    .pg-preview.show { display: block; }
    .pg-preview img { width: 100%; max-height: 220px; object-fit: cover; border-radius: 10px; display: block; border: 1px solid #e5e2d8; }
    .pg-preview button {
        position: absolute; top: 8px; right: 8px; border: 0; border-radius: 999px; padding: 6px 12px; cursor: pointer;
        font-family: inherit; font-size: 0.76rem; font-weight: 600; background: rgba(255,255,255,.95); color: #c0392b;
    }

    .pg-form-actions { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid #e5e2d8; }
    .pg-btn-cancel {
        padding: 10px 18px; border-radius: 999px; border: 1px solid #e5e2d8; background: transparent;
        color: #4b5a50; font-size: 0.86rem; font-weight: 600; cursor: pointer; font-family: inherit;
    }
    .pg-btn-save {
        background: #0f4a38; color: #fff; border: none; padding: 10px 24px; border-radius: 999px;
        font-weight: 600; cursor: pointer; font-size: 0.86rem; font-family: inherit;
    }
    .pg-btn-save:hover { background: #15583f; }

    @media (max-width: 520px) { .pg-row { grid-template-columns: 1fr; } }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- Sidebar toggle (sama seperti halaman lain) ---
        const lpSidebar = document.getElementById('lpSidebar');
        const lpBody = document.getElementById('lpBody');
        document.getElementById('lpToggle')?.addEventListener('click', function () {
            lpSidebar?.classList.toggle('collapsed');
            lpBody?.classList.toggle('lp-sidebar-collapsed');
        });

        // --- Modal tambah / edit ---
        const dlg = document.getElementById('pgDialog');
        const form = document.getElementById('pgForm');
        const fImage = document.getElementById('fImage');
        const drop = document.getElementById('drop');
        const preview = document.getElementById('preview');
        const previewImg = document.getElementById('previewImg');
        const removeImage = document.getElementById('removeImage');
        const storeUrl = @json(route('admin.pengumuman.store'));
        const updateUrl = @json(route('admin.pengumuman.update', ['id' => '__ID__']));
        const MAX = 3 * 1024 * 1024;

        function showPreview(src) {
            previewImg.src = src;
            preview.classList.add('show');
            drop.style.display = 'none';
        }
        function clearPreview() {
            fImage.value = '';
            previewImg.src = '';
            preview.classList.remove('show');
            drop.style.display = '';
        }

        function openCreate() {
            form.reset();
            form.action = storeUrl;
            document.getElementById('methodSlot').innerHTML = '';
            document.getElementById('editId').value = '';
            removeImage.value = '0';
            document.getElementById('pgTitle').textContent = 'Tambah Pengumuman';
            clearPreview();
            dlg.showModal();
        }

        function openEdit(d) {
            form.reset();
            form.action = updateUrl.replace('__ID__', d.id);
            document.getElementById('methodSlot').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('editId').value = d.id;
            removeImage.value = '0';
            document.getElementById('pgTitle').textContent = 'Edit Pengumuman';
            document.getElementById('fTitle').value = d.title;
            document.getElementById('fContent').value = d.content;
            document.getElementById('fCategory').value = d.category;
            document.getElementById('fStatus').value = d.status;
            document.getElementById('fExpires').value = d.expires_at || '';
            document.getElementById('fPinned').checked = !!d.is_pinned;
            clearPreview();
            if (d.image_url) showPreview(d.image_url);
            dlg.showModal();
        }

        document.getElementById('btnCreate').addEventListener('click', openCreate);
        document.querySelectorAll('.btn-edit').forEach(function (b) {
            b.addEventListener('click', function () { openEdit(JSON.parse(b.dataset.item)); });
        });
        document.getElementById('btnClose').addEventListener('click', function () { dlg.close(); });
        document.getElementById('btnCancel').addEventListener('click', function () { dlg.close(); });

        // Pilih / seret foto
        drop.addEventListener('click', function () { fImage.click(); });
        drop.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fImage.click(); }
        });
        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('over'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('over'); });
        });
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer.files.length) {
                fImage.files = e.dataTransfer.files;
                fImage.dispatchEvent(new Event('change'));
            }
        });

        fImage.addEventListener('change', function () {
            const f = fImage.files[0];
            if (!f) return;
            if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { alert('Format gambar harus JPG, PNG, atau WebP.'); clearPreview(); return; }
            if (f.size > MAX) { alert('Ukuran gambar maksimal 3 MB.'); clearPreview(); return; }
            removeImage.value = '0';
            showPreview(URL.createObjectURL(f));
        });

        document.getElementById('btnRemoveImg').addEventListener('click', function () {
            clearPreview();
            removeImage.value = '1'; // saat edit: hapus foto lama di server
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
                dlg.showModal();
            })();
        @endif
    });
    </script>
</body>
</html>