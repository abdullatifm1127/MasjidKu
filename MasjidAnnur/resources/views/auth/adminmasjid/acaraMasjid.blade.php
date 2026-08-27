<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan & Acara - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/landingPage.css') }}">
</head>
<body class="lp-body" id="lpBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="lp-sidebar" id="lpSidebar">

        <div class="lp-brand">
            <div class="lp-brand-avatar">AM</div>
            <div class="lp-brand-info">
                <span class="lp-brand-name">SIM Masjid</span>
                <span class="lp-brand-sub">Baitul Digital</span>
            </div>
            <button class="lp-sidebar-toggle" id="lpSidebarToggle" aria-label="Collapse sidebar" type="button">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <nav class="lp-nav">
            <a href="{{ route('admin.dashboard') }}" class="lp-nav-item">
                <span class="lp-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="lp-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.landing-page') }}" class="lp-nav-item">
                <span class="lp-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                    </svg>
                </span>
                <span class="lp-nav-label">Landing Page</span>
            </a>

            <a href="{{ route('admin.profil-masjid') }}" class="lp-nav-item">
                <span class="lp-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="lp-nav-label">Profil Masjid</span>
            </a>

            <a href="{{ route('admin.program') }}" class="lp-nav-item">
                <span class="lp-nav-label">Program</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Jadwal Shalat</span>
                <span class="lp-nav-soon">dev</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Pengumuman</span>
                <span class="lp-nav-badge">3</span>
            </a>
            <a href="{{ route('admin.acara') }}" class="lp-nav-item active">
                <span class="lp-nav-label">Kegiatan &amp; Acara</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Donasi</span>
                <span class="lp-nav-soon">dev</span>
            </a>
            <a href="#" class="lp-nav-item">
                <span class="lp-nav-label">Data Jamaah</span>
                <span class="lp-nav-soon">dev</span>
            </a>
        </nav>

        <div class="lp-user">
            <div class="lp-user-avatar">AM</div>
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Preview
                </a>
            </div>
        </header>

        <main class="lp-content">
            <div class="ac-wrap">
                <div class="ac-header">
                    <div></div>
                    <button type="button" id="acToggleAdd" class="ac-btn-add">+ Tambah Acara</button>
                </div>

                @if(session('success'))
                    <div class="ac-alert ac-alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="ac-alert ac-alert-error">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                <div class="ac-alert ac-alert-error">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- FORM TAMBAH — tersembunyi, muncul saat tombol "+ Tambah Acara" diklik --}}
                <div id="acAddForm" class="ac-form-box" style="display:none;">
                    <form action="{{ route('admin.acara.store') }}" method="POST" class="ac-form" enctype="multipart/form-data">
                        @csrf

                        <label class="ac-label">Judul Acara</label>
                        <input type="text" name="title" class="ac-input" placeholder="mis. Halaqah Quran Bersama" required>

                        <div class="ac-row">
                            <div>
                                <label class="ac-label">Tanggal</label>
                                <input type="date" name="event_date" class="ac-input" required>
                            </div>
                            <div>
                                <label class="ac-label">Jam (opsional)</label>
                                <input type="text" name="event_time" class="ac-input" placeholder="mis. 16:00 WIB">
                            </div>
                        </div>

                        <label class="ac-label">Pengisi / Penyelenggara (opsional)</label>
                        <input type="text" name="organizer" class="ac-input" placeholder="mis. Ustadz Yusuf Mansur">

                        <label class="ac-label">Deskripsi (opsional)</label>
                        <textarea name="description" class="ac-input ac-textarea" rows="3"></textarea>

                        <label class="ac-label">Foto Acara (opsional)</label>
                        <input type="file" name="photo" accept="image/*" class="ac-input">
                        <span class="ac-hint">PNG, JPG, WebP · Maks. 2MB · Ditampilkan di kartu acara pada halaman publik.</span>

                        <label class="ac-checkbox">
                            <input type="checkbox" name="is_featured" value="1">
                            Tandai sebagai acara "Terbaru"
                        </label>

                        <div class="ac-form-actions">
                            <button type="button" class="ac-btn-cancel" onclick="document.getElementById('acAddForm').style.display='none';">Batal</button>
                            <button type="submit" class="ac-btn-save">Simpan Acara</button>
                        </div>
                    </form>
                </div>

                {{-- DAFTAR ACARA --}}
                @forelse($acaras as $acara)
                <div class="ac-card" id="acCard-{{ $acara->id }}">
                    <div class="ac-card-view">
                        @if($acara->photo)
                        <div class="ac-card-photo">
                            <img src="{{ asset('storage/'.$acara->photo) }}" alt="{{ $acara->title }}" loading="lazy">
                        </div>
                        @else
                        <div class="ac-card-date">
                            <div class="ac-card-month">{{ \Illuminate\Support\Carbon::parse($acara->event_date)->translatedFormat('M') }}</div>
                            <div class="ac-card-day">{{ \Illuminate\Support\Carbon::parse($acara->event_date)->format('d') }}</div>
                        </div>
                        @endif
                        <div class="ac-card-body">
                            <div class="ac-card-top">
                                <div class="ac-card-title">{{ $acara->title }}</div>
                                @if($acara->is_featured)<span class="ac-badge">Terbaru</span>@endif
                            </div>
                            <div class="ac-card-meta">
                                {{ $acara->event_time ?? '-' }}
                                @if($acara->organizer) · {{ $acara->organizer }} @endif
                                · {{ \Illuminate\Support\Carbon::parse($acara->event_date)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="ac-card-actions">
                            <button type="button" class="ac-btn-edit" onclick="acToggleEdit({{ $acara->id }})">Edit</button>
                            <form action="{{ route('admin.acara.destroy', $acara->id) }}" method="POST" onsubmit="return confirm('Hapus acara ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ac-btn-delete">Hapus</button>
                            </form>
                        </div>
                    </div>

                    {{-- FORM EDIT --}}
                    <div id="acEditForm-{{ $acara->id }}" class="ac-form-box" style="display:none;">
                        <form action="{{ route('admin.acara.update', $acara->id) }}" method="POST" class="ac-form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <label class="ac-label">Judul Acara</label>
                            <input type="text" name="title" value="{{ $acara->title }}" class="ac-input" required>

                            <div class="ac-row">
                                <div>
                                    <label class="ac-label">Tanggal</label>
                                    <input type="date" name="event_date" value="{{ \Illuminate\Support\Carbon::parse($acara->event_date)->format('Y-m-d') }}" class="ac-input" required>
                                </div>
                                <div>
                                    <label class="ac-label">Jam (opsional)</label>
                                    <input type="text" name="event_time" value="{{ $acara->event_time }}" class="ac-input">
                                </div>
                            </div>

                            <label class="ac-label">Pengisi / Penyelenggara (opsional)</label>
                            <input type="text" name="organizer" value="{{ $acara->organizer }}" class="ac-input">

                            <label class="ac-label">Deskripsi (opsional)</label>
                            <textarea name="description" class="ac-input ac-textarea" rows="3">{{ $acara->description }}</textarea>

                            <label class="ac-label">Foto Acara (opsional)</label>
                            @if($acara->photo)
                            <div class="ac-photo-preview">
                                <img src="{{ asset('storage/'.$acara->photo) }}" alt="Foto saat ini">
                                <span>Foto saat ini — pilih file baru untuk menggantinya.</span>
                            </div>
                            @endif
                            <input type="file" name="photo" accept="image/*" class="ac-input">

                            <label class="ac-checkbox">
                                <input type="checkbox" name="is_featured" value="1" {{ $acara->is_featured ? 'checked' : '' }}>
                                Tandai sebagai acara "Terbaru"
                            </label>

                            <div class="ac-form-actions">
                                <button type="button" class="ac-btn-cancel" onclick="acToggleEdit({{ $acara->id }})">Batal</button>
                                <button type="submit" class="ac-btn-save">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <div class="ac-empty">Belum ada acara. Klik "Tambah Acara" untuk membuat yang pertama.</div>
                @endforelse
            </div>
        </main>
    </div>

    <button class="lp-fab" aria-label="Bantuan">?</button>

    <style>
    .ac-wrap { max-width: 760px; }
    .ac-header { display: flex; align-items: center; justify-content: flex-end; margin-bottom: 20px; }

    .ac-alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 0.88rem; }
    .ac-alert-success { background: #e4eee6; color: #0f4a38; }
    .ac-alert-error { background: #fbe4e4; color: #8a2c2c; }

    .ac-btn-add {
        background: #0f4a38; color: #fff; border: none; padding: 11px 22px;
        border-radius: 999px; font-weight: 600; cursor: pointer; font-size: 0.88rem;
        white-space: nowrap;
    }
    .ac-btn-add:hover { background: #15583f; }

    .ac-form-box {
        background: #faf8f3; border: 1px solid #e5e2d8; border-radius: 14px;
        padding: 18px; margin-bottom: 16px;
    }
    .ac-form { display: flex; flex-direction: column; }
    .ac-label { font-size: 0.8rem; font-weight: 600; color: #4b5a50; margin: 12px 0 6px; }
    .ac-hint { font-size: 0.72rem; color: #8a9891; margin-top: 4px; }
    .ac-input {
        border: 1px solid #e5e2d8; border-radius: 10px; padding: 10px 12px;
        font-size: 0.92rem; font-family: inherit; width: 100%; background: #fff;
    }
    .ac-input:focus { outline: none; border-color: #c0923d; }
    .ac-textarea { resize: vertical; }
    .ac-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .ac-row .ac-label { margin-top: 0; }
    .ac-checkbox { display: flex; align-items: center; gap: 8px; margin-top: 16px; font-size: 0.84rem; color: #4b5a50; }

    .ac-photo-preview {
        display: flex; align-items: center; gap: 12px; margin-top: 8px;
        padding: 10px; background: #fff; border: 1px solid #e5e2d8; border-radius: 10px;
    }
    .ac-photo-preview img { width: 54px; height: 54px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
    .ac-photo-preview span { font-size: 0.76rem; color: #6b7a71; }

    .ac-form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
    .ac-btn-cancel {
        padding: 10px 18px; border-radius: 999px; border: 1px solid #e5e2d8;
        background: transparent; color: #4b5a50; font-size: 0.86rem; font-weight: 600; cursor: pointer;
    }
    .ac-btn-save {
        background: #0f4a38; color: #fff; border: none; padding: 10px 24px;
        border-radius: 999px; font-weight: 600; cursor: pointer; font-size: 0.86rem;
    }
    .ac-btn-save:hover { background: #15583f; }

    .ac-card {
        background: #fff; border: 1px solid #e5e2d8; border-radius: 14px;
        padding: 16px; margin-bottom: 12px;
    }
    .ac-card-view { display: flex; align-items: center; gap: 16px; }
    .ac-card-date {
        flex-shrink: 0; width: 54px; height: 54px; border-radius: 999px 999px 0 0;
        background: #0f4a38; color: #fff; display: flex; flex-direction: column;
        align-items: center; justify-content: center; line-height: 1.1;
    }
    .ac-card-month { font-size: 0.6rem; letter-spacing: 0.06em; text-transform: uppercase; color: #e7cd8e; }
    .ac-card-day { font-size: 1.05rem; font-weight: 700; }

    .ac-card-photo { flex-shrink: 0; width: 54px; height: 54px; border-radius: 10px; overflow: hidden; }
    .ac-card-photo img { width: 100%; height: 100%; object-fit: cover; }

    .ac-card-body { flex: 1; min-width: 0; }
    .ac-card-top { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .ac-card-title { font-weight: 700; font-size: 1.02rem; }
    .ac-badge {
        font-size: 0.66rem; font-weight: 700; letter-spacing: 0.04em;
        color: #93672a; background: #e7cd8e; padding: 3px 9px; border-radius: 999px;
    }
    .ac-card-meta { margin-top: 4px; font-size: 0.84rem; color: #6b7a71; }

    .ac-card-actions { flex-shrink: 0; display: flex; align-items: center; gap: 8px; }
    .ac-btn-edit, .ac-btn-delete {
        border: 1px solid #e5e2d8; background: #faf8f3; border-radius: 8px;
        padding: 8px 14px; font-size: 0.82rem; font-weight: 600; cursor: pointer;
        color: #1c2620;
    }
    .ac-btn-edit:hover { border-color: #1e6e4c; background: #e4eee6; }
    .ac-btn-delete:hover { border-color: #c0392b; background: #fbe4e4; color: #c0392b; }

    .ac-empty {
        text-align: center; padding: 48px 20px; color: #6b7a71;
        border: 1.5px dashed #e5e2d8; border-radius: 14px; font-size: 0.92rem;
    }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const lpToggle = document.getElementById('lpToggle');
        if (lpToggle) {
            lpToggle.addEventListener('click', () => {
                document.getElementById('lpSidebar')?.classList.toggle('collapsed');
                document.getElementById('lpBody')?.classList.toggle('lp-sidebar-collapsed');
            });
        }

        document.getElementById('acToggleAdd')?.addEventListener('click', function () {
            const box = document.getElementById('acAddForm');
            box.style.display = (box.style.display === 'none') ? 'block' : 'none';
        });
    });

    function acToggleEdit(id) {
        const box = document.getElementById('acEditForm-' + id);
        box.style.display = (box.style.display === 'none') ? 'block' : 'none';
    }
    </script>
</body>
</html>