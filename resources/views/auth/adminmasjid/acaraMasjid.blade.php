<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan & Acara - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/acara.css') }}">
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

            {{-- MENU PENGUMUMAN DINAMIS --}}
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
                    <div class="lp-page-title">Kegiatan &amp; Acara</div>
                    <div class="lp-page-sub">Kelola acara/kegiatan yang tampil di halaman utama masjid Anda</div>
                </div>
            </div>
            <div class="lp-topbar-right">
                <a href="{{ route('masjid.publik', $mosque->slug) }}" class="lp-btn-back" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Preview</span>
                </a>
            </div>
        </header>

        <main class="lp-content">
            @php
                $today = \Illuminate\Support\Carbon::today();

                // Tambahkan info tanggal ke tiap acara (tanpa mengubah model)
                $daftar = $acaras->map(function ($a) use ($today) {
                    $tgl = \Illuminate\Support\Carbon::parse($a->event_date)->startOfDay();
                    $a->_tgl     = $tgl;
                    $a->_selisih = (int) round($today->diffInDays($tgl, false)); // + = akan datang, 0 = hari ini, - = lewat
                    $a->_selesai = $a->_selisih < 0;
                    return $a;
                });

                $totalAcara     = $daftar->count();
                $totalMendatang = $daftar->where('_selesai', false)->count();
                $totalSelesai   = $daftar->where('_selesai', true)->count();
                $berikutnya     = $daftar->where('_selesai', false)->sortBy('_tgl')->first();

                $labelSelisih = function (int $n) {
                    if ($n === 0) return 'Hari ini';
                    if ($n === 1) return 'Besok';
                    return $n . ' hari lagi';
                };

                $editIdError  = old('_edit_id');
                $urlUpdateErr = $editIdError ? route('admin.acara.update', $editIdError) : '';
                $bukaModal    = $errors->any() && old('_form') === 'acara';
            @endphp

            <div class="ev-page">

                @if(session('success'))
                    <div class="ev-alert ev-alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="ev-alert ev-alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="ev-alert ev-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Acara berikutnya --}}
                @if($berikutnya)
                    <section class="ev-next" aria-label="Acara berikutnya">
                        <div class="ev-next-date">
                            <small>{{ $berikutnya->_tgl->translatedFormat('M') }}</small>
                            <b>{{ $berikutnya->_tgl->format('d') }}</b>
                            <span>{{ $berikutnya->_tgl->translatedFormat('l') }}</span>
                        </div>
                        <div>
                            <div class="ev-next-label">Acara berikutnya</div>
                            <div class="ev-next-title">{{ $berikutnya->title }}</div>
                            <div class="ev-next-meta">
                                @if($berikutnya->event_time)<span><i class="fa-regular fa-clock"></i>{{ $berikutnya->event_time }}</span>@endif
                                @if($berikutnya->organizer)<span><i class="fa-regular fa-user"></i>{{ $berikutnya->organizer }}</span>@endif
                                <span><i class="fa-regular fa-calendar"></i>{{ $berikutnya->_tgl->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="ev-countdown">
                            @if($berikutnya->_selisih <= 1)
                                <b>{{ $labelSelisih($berikutnya->_selisih) }}</b>
                            @else
                                <b>{{ $berikutnya->_selisih }}</b><span>hari lagi</span>
                            @endif
                        </div>
                    </section>
                @endif

                {{-- Toolbar --}}
                <div class="ev-toolbar">
                    <div class="ev-tabs" role="tablist" aria-label="Filter acara">
                        <button type="button" class="ev-tab active" data-filter="all" role="tab">Semua <em>{{ $totalAcara }}</em></button>
                        <button type="button" class="ev-tab" data-filter="upcoming" role="tab">Akan datang <em>{{ $totalMendatang }}</em></button>
                        <button type="button" class="ev-tab" data-filter="past" role="tab">Selesai <em>{{ $totalSelesai }}</em></button>
                    </div>

                    <label class="ev-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" id="evSearch" placeholder="Cari judul atau pengisi..." aria-label="Cari acara">
                    </label>

                    <button type="button" class="ev-btn-add" id="evCreate">
                        <i class="fa-solid fa-plus"></i> Tambah acara
                    </button>
                </div>

                {{-- Daftar acara --}}
                @if($totalAcara > 0)
                    <div class="ev-grid" id="evGrid">
                        @foreach($daftar as $acara)
                            @php
                                $fotoUrl = $acara->photo ? asset('storage/' . $acara->photo) : null;
                                $editData = [
                                    'id'          => $acara->id,
                                    'title'       => $acara->title,
                                    'event_date'  => $acara->_tgl->format('Y-m-d'),
                                    'event_time'  => $acara->event_time,
                                    'organizer'   => $acara->organizer,
                                    'description' => $acara->description,
                                    'is_featured' => (bool) $acara->is_featured,
                                    'photo_url'   => $fotoUrl,
                                    'update_url'  => route('admin.acara.update', $acara->id),
                                ];
                            @endphp

                            <article class="ev-card {{ $acara->is_featured ? 'is-featured' : '' }} {{ $acara->_selesai ? 'is-past' : '' }}"
                                     data-state="{{ $acara->_selesai ? 'past' : 'upcoming' }}"
                                     data-search="{{ \Illuminate\Support\Str::lower($acara->title . ' ' . $acara->organizer) }}">

                                <div class="ev-media">
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="{{ $acara->title }}" loading="lazy">
                                    @else
                                        <div class="ev-media-date">
                                            <b>{{ $acara->_tgl->format('d') }}</b>
                                            <span>{{ $acara->_tgl->translatedFormat('F Y') }}</span>
                                        </div>
                                    @endif

                                    <div class="ev-media-top">
                                        @if($fotoUrl)
                                            <span class="ev-date-chip"><b>{{ $acara->_tgl->format('d') }}</b>{{ $acara->_tgl->translatedFormat('M') }}</span>
                                        @endif
                                        @if($acara->is_featured)
                                            <span class="ev-star"><i class="fa-solid fa-star"></i> Terbaru</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ev-body">
                                    @if($acara->_selesai)
                                        <span class="ev-pill is-past">Selesai</span>
                                    @elseif($acara->_selisih === 0)
                                        <span class="ev-pill is-today"><i></i>Hari ini</span>
                                    @else
                                        <span class="ev-pill"><i></i>{{ $labelSelisih($acara->_selisih) }}</span>
                                    @endif

                                    <div class="ev-title">{{ $acara->title }}</div>

                                    <div class="ev-meta">
                                        <span><i class="fa-regular fa-calendar"></i>{{ $acara->_tgl->translatedFormat('l, d F Y') }}</span>
                                        @if($acara->event_time)<span><i class="fa-regular fa-clock"></i>{{ $acara->event_time }}</span>@endif
                                        @if($acara->organizer)<span><i class="fa-regular fa-user"></i>{{ $acara->organizer }}</span>@endif
                                    </div>

                                    @if($acara->description)
                                        <p class="ev-desc">{{ $acara->description }}</p>
                                    @endif
                                </div>

                                <div class="ev-foot">
                                    <button type="button" class="ev-btn-edit btn-edit" data-item="{{ json_encode($editData) }}">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.acara.destroy', $acara->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus acara ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ev-btn-del" aria-label="Hapus acara {{ $acara->title }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="ev-nomatch" id="evNoMatch">
                        <div class="ev-empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <div class="ev-empty-title">Tidak ada acara yang cocok</div>
                        <p>Coba kata kunci lain atau pilih filter yang berbeda.</p>
                    </div>
                @else
                    <div class="ev-empty">
                        <div class="ev-empty-icon"><i class="fa-solid fa-calendar-plus"></i></div>
                        <div class="ev-empty-title">Belum ada acara</div>
                        <p>Tambahkan kajian, pengajian, atau kegiatan lain supaya jamaah tahu jadwalnya.</p>
                        <button type="button" class="ev-btn-add" style="margin:0 auto;" data-create>
                            <i class="fa-solid fa-plus"></i> Tambah acara pertama
                        </button>
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- ===== MODAL TAMBAH / EDIT ===== --}}
    <dialog class="ev-dialog" id="evDialog">
        <form method="POST" action="{{ route('admin.acara.store') }}" enctype="multipart/form-data" class="ev-form" id="evForm">
            @csrf
            <span id="evMethodSlot"></span>
            <input type="hidden" name="_form" value="acara">
            <input type="hidden" name="_edit_id" id="evEditId" value="{{ $editIdError }}">

            <div class="ev-form-head">
                <div class="ev-form-title">
                    <span class="ev-form-icon"><i class="fa-solid fa-calendar-plus" id="evFormIcon"></i></span>
                    <span id="evFormTitle">Acara baru</span>
                </div>
                <button type="button" class="ev-x" id="evClose" aria-label="Tutup">&times;</button>
            </div>

            <div class="ev-fields">

                <div class="ev-field">
                    <label class="ev-label" for="fTitle">Judul acara</label>
                    <input type="text" id="fTitle" name="title" class="ev-input"
                           value="{{ old('title') }}" placeholder="mis. Halaqah Quran Bersama" required>
                </div>

                <div class="ev-row">
                    <div class="ev-field">
                        <label class="ev-label" for="fDate">Tanggal</label>
                        <input type="date" id="fDate" name="event_date" class="ev-input" value="{{ old('event_date') }}" required>
                    </div>
                    <div class="ev-field">
                        <label class="ev-label" for="fTime">Jam <span class="ev-opt">opsional</span></label>
                        <input type="text" id="fTime" name="event_time" class="ev-input" value="{{ old('event_time') }}" placeholder="mis. 16:00 WIB">
                    </div>
                </div>

                <div class="ev-field">
                    <label class="ev-label" for="fOrganizer">Pengisi / penyelenggara <span class="ev-opt">opsional</span></label>
                    <input type="text" id="fOrganizer" name="organizer" class="ev-input" value="{{ old('organizer') }}" placeholder="mis. Ustadz Yusuf Mansur">
                </div>

                <div class="ev-field">
                    <label class="ev-label" for="fDesc">Deskripsi <span class="ev-opt">opsional</span></label>
                    <textarea id="fDesc" name="description" class="ev-input ev-textarea" rows="3"
                              placeholder="Ceritakan singkat tentang acara ini...">{{ old('description') }}</textarea>
                </div>

                {{-- FOTO ACARA --}}
                <div class="ev-field">
                    <label class="ev-label">Foto acara <span class="ev-opt">opsional</span></label>

                    <div class="ev-drop" id="evDrop" tabindex="0" role="button" aria-label="Pilih foto acara">
                        <div class="ev-drop-icon"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i></div>
                        <div class="ev-drop-title">Tarik foto ke sini atau <u>pilih dari perangkat</u></div>
                        <div class="ev-drop-note">PNG, JPG, WebP, AVIF · maksimal 2 MB</div>
                    </div>
                    <input type="file" id="fPhoto" name="photo" accept="image/png,image/jpeg,image/webp,image/avif" hidden>

                    <div class="ev-preview" id="evPreview">
                        <img src="" alt="Pratinjau foto acara" id="evPreviewImg">
                        <span class="ev-preview-badge">Foto baru</span>
                        <div class="ev-preview-meta">
                            <div class="ev-preview-info">
                                <div class="ev-preview-name" id="evPreviewName"></div>
                                <div class="ev-preview-size" id="evPreviewSize"></div>
                            </div>
                            <div class="ev-preview-actions">
                                <button type="button" class="ev-photo-btn" id="evChange"><i class="fa-solid fa-arrows-rotate"></i> Ganti</button>
                                <button type="button" class="ev-photo-btn danger" id="evUndo"><i class="fa-solid fa-xmark"></i> <span id="evUndoText">Hapus</span></button>
                            </div>
                        </div>
                    </div>
                    <div class="ev-photo-err" id="evPhotoErr"></div>
                    <span class="ev-hint">Ditampilkan di kartu acara pada halaman publik. Rekomendasi rasio 16:10.</span>
                </div>

                <label class="ev-feature">
                    <input type="checkbox" id="fFeatured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span class="ev-switch"></span>
                    <span class="ev-feature-text">
                        <strong>Tandai sebagai acara "Terbaru"</strong>
                        <small>Acara akan diberi lencana khusus di halaman publik.</small>
                    </span>
                </label>
            </div>

            <div class="ev-form-actions">
                <button type="button" class="ev-btn-cancel" id="evCancel">Batal</button>
                <button type="submit" class="ev-btn-save" id="evSave"><i class="fa-solid fa-floppy-disk"></i> Simpan acara</button>
            </div>
        </form>
    </dialog>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const $  = (s, r = document) => r.querySelector(s);
        const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

        /* ---------- Sidebar ---------- */
        $('#lpToggle')?.addEventListener('click', () => {
            $('#lpSidebar')?.classList.toggle('collapsed');
            $('#lpBody')?.classList.toggle('lp-sidebar-collapsed');
        });

        /* ---------- Filter + pencarian ---------- */
        const cards   = $$('.ev-card');
        const noMatch = $('#evNoMatch');
        let filter = 'all';
        let query  = '';

        function applyFilter() {
            let shown = 0;
            cards.forEach(card => {
                const okState = filter === 'all' || card.dataset.state === filter;
                const okText  = !query || card.dataset.search.includes(query);
                card.hidden = !(okState && okText);
                if (!card.hidden) shown++;
            });
            noMatch?.classList.toggle('show', cards.length > 0 && shown === 0);
        }
        $$('.ev-tab').forEach(tab => tab.addEventListener('click', () => {
            $$('.ev-tab').forEach(t => t.classList.toggle('active', t === tab));
            filter = tab.dataset.filter;
            applyFilter();
        }));
        $('#evSearch')?.addEventListener('input', e => {
            query = e.target.value.trim().toLowerCase();
            applyFilter();
        });

        /* ---------- Modal ---------- */
        const dlg      = $('#evDialog');
        const form     = $('#evForm');
        const f = {
            title: $('#fTitle'), date: $('#fDate'), time: $('#fTime'),
            org: $('#fOrganizer'), desc: $('#fDesc'), featured: $('#fFeatured'),
        };
        const storeUrl = @json(route('admin.acara.store'));
        const errUpdateUrl = @json($urlUpdateErr);

        function setFields(d) {
            f.title.value    = d.title || '';
            f.date.value     = d.event_date || '';
            f.time.value     = d.event_time || '';
            f.org.value      = d.organizer || '';
            f.desc.value     = d.description || '';
            f.featured.checked = !!d.is_featured;
        }

        function setMode(edit, id, updateUrl) {
            form.action = edit ? updateUrl : storeUrl;
            $('#evMethodSlot').innerHTML = edit ? '<input type="hidden" name="_method" value="PUT">' : '';
            $('#evEditId').value = edit ? id : '';
            $('#evFormTitle').textContent = edit ? 'Edit acara' : 'Acara baru';
            $('#evFormIcon').className = 'fa-solid ' + (edit ? 'fa-pen-to-square' : 'fa-calendar-plus');
            $('#evSave').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> ' + (edit ? 'Simpan perubahan' : 'Simpan acara');
            $('#evSave').disabled = false;
        }

        function openCreate() {
            setFields({});
            setMode(false);
            photo.reset('');
            dlg.showModal();
            f.title.focus();
        }
        function openEdit(d) {
            setFields(d);
            setMode(true, d.id, d.update_url);
            photo.reset(d.photo_url || '');
            dlg.showModal();
        }

        $('#evCreate')?.addEventListener('click', openCreate);
        $$('[data-create]').forEach(b => b.addEventListener('click', openCreate));
        $$('.btn-edit').forEach(b => b.addEventListener('click', () => openEdit(JSON.parse(b.dataset.item))));
        $('#evClose').addEventListener('click', () => dlg.close());
        $('#evCancel').addEventListener('click', () => dlg.close());
        dlg.addEventListener('click', e => { if (e.target === dlg) dlg.close(); });

        form.addEventListener('submit', () => {
            const btn = $('#evSave');
            btn.innerHTML = '<span class="lp-spin"></span> Menyimpan...';
            setTimeout(() => { btn.disabled = true; }, 0);
        });

        /* ---------- Foto acara ---------- */
        const photo = (function () {
            const MAX = 2 * 1024 * 1024;
            const ALLOWED = ['image/png', 'image/jpeg', 'image/webp', 'image/avif'];
            const input = $('#fPhoto'), drop = $('#evDrop'), preview = $('#evPreview');
            const img = $('#evPreviewImg'), pName = $('#evPreviewName'), pSize = $('#evPreviewSize');
            const err = $('#evPhotoErr'), undoText = $('#evUndoText');
            let original = '', objectUrl = null;

            const fmt = b => b < 1048576 ? Math.round(b / 1024) + ' KB' : (b / 1048576).toFixed(1) + ' MB';
            const showErr = m => { err.textContent = m; err.classList.toggle('show', !!m); };
            const revoke = () => { if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; } };

            function show(src, name, size, isNew) {
                img.src = src;
                pName.textContent = name || '';
                pSize.textContent = size || '';
                preview.classList.add('show');
                preview.classList.toggle('is-new', !!isNew);
                drop.style.display = 'none';
                undoText.textContent = original ? 'Batal' : 'Hapus';
            }
            function empty() {
                revoke();
                input.value = '';
                img.removeAttribute('src');
                preview.classList.remove('show', 'is-new');
                drop.style.display = '';
            }
            function reset(orig) {
                original = orig || '';
                showErr('');
                empty();
                if (original) show(original, 'Foto saat ini', '', false);
            }
            function handle(file) {
                showErr('');
                if (!file) return;
                if (!ALLOWED.includes(file.type)) { input.value = ''; return showErr('Format tidak didukung. Gunakan PNG, JPG, WebP, atau AVIF.'); }
                if (file.size > MAX) { input.value = ''; return showErr('Ukuran ' + fmt(file.size) + ' melebihi batas 2 MB. Kompres fotonya lalu coba lagi.'); }
                revoke();
                objectUrl = URL.createObjectURL(file);
                show(objectUrl, file.name, fmt(file.size), true);
            }

            drop.addEventListener('click', () => input.click());
            drop.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });
            ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('over'); }));
            ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('over'); }));
            drop.addEventListener('drop', e => {
                const file = e.dataTransfer.files && e.dataTransfer.files[0];
                if (!file) return;
                const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
                handle(file);
            });
            input.addEventListener('change', () => handle(input.files[0]));
            $('#evChange').addEventListener('click', () => input.click());
            $('#evUndo').addEventListener('click', () => {
                // Batal: kembali ke foto lama. Hapus (saat tambah): kosongkan.
                const o = original;
                reset(o);
            });

            return { reset };
        })();

        /* ---------- Buka ulang modal jika validasi server gagal ---------- */
        @if($bukaModal)
            (function () {
                const editId = @json($editIdError);
                setMode(!!editId, editId, errUpdateUrl);
                photo.reset('');
                dlg.showModal();
            })();
        @endif
    });
    </script>
</body>
</html>