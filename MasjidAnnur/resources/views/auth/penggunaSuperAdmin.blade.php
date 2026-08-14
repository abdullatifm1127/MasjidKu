<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/penggunaSuperAdmin.css') }}">
</head>
<body class="sa-page" id="saBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sa-sidebar" id="saSidebar">
        <div class="sa-brand">
            <div class="sa-brand-avatar">SA</div>
            <div class="sa-brand-info">
                <strong>MasjidKu Admin</strong>
                <span>Super Administrator</span>
            </div>
            <button class="sa-sidebar-toggle" id="saSidebarToggle" aria-label="Collapse sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </button>
        </div>

        <nav class="sa-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item sa-nav-has-badge">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                <span class="sa-nav-badge-dot amber"></span>
            </a>

            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Manajemen Masjid</span>
            </a>

            <a href="{{ route('superadmin.pengguna') }}" class="sa-nav-item active">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Pengguna</span>
            </a>

            <a href="{{ route('superadmin.pengaturan') }}" class="sa-nav-item">
                <span class="sa-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span class="sa-nav-label">Pengaturan</span>
            </a>
        </nav>

        <div class="sa-user-footer">
            <div class="sa-user-avatar-sm">SA</div>
            <div class="sa-user-info">
                <div class="sa-user-name">Super Admin</div>
                <div class="sa-user-email">admin@masjidku.id</div>
            </div>
            <a href="{{ route('logout') }}" class="sa-logout-btn"
               onclick="event.preventDefault(); document.getElementById('sa-logout-form').submit();"
               aria-label="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
            </a>
            <form id="sa-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="sa-main" id="saMain">
        <header class="sa-topbar">
            <div class="sa-topbar-title">Pengguna</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                    Lihat Website
                </a>
                <button class="sa-notif-btn" aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <span class="sa-notif-dot"></span>
                </button>
            </div>
        </header>

        <main class="sa-content pg-content">
            @if(session('success'))
            <div class="pg-alert pg-alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- ===== SUBHEADER ===== --}}
            <div class="pg-subheader">
                <span class="pg-count">4 pengguna terdaftar</span>
                <button class="pg-btn-tambah" id="pgTambahBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Tambah Pengguna
                </button>
            </div>

            {{-- ===== TABEL PENGGUNA ===== --}}
            <div class="pg-table-wrap">
                <table class="pg-table">
                    <thead>
                        <tr>
                            <th class="pg-th-user">Pengguna</th>
                            <th class="pg-th-peran">Peran</th>
                            <th class="pg-th-masjid">Masjid</th>
                            <th class="pg-th-aktif">Terakhir Aktif</th>
                            <th class="pg-th-aksi"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        
                        @endphp

                        @foreach($pengguna as $u)
                        <tr class="pg-row" id="pg-row-{{ $u->id }}">
                            {{-- Pengguna --}}
                            <td class="pg-td-user">
                                <div class="pg-user-cell">
                                    <div class="pg-avatar" style="background:{{ $u->warna ?? '#1a4731' }}">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                        @if(!empty($u->online))
                                        <span class="pg-online-dot"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="pg-nama">{{ $u->name }}</div>
                                        <div class="pg-email">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Peran --}}
                            <td class="pg-td-peran">
                                <span class="pg-peran-badge {{ ($u->role ?? '') === 'super_admin' ? 'super' : 'tenant' }}">
                                    {{ ($u->role ?? '') === 'super_admin' ? 'Super Admin' : 'Tenant Admin' }}
                                </span>
                            </td>

                            {{-- Masjid --}}
                            <td class="pg-td-masjid">{{ $u->masjid ?? '-' }}</td>

                            {{-- Terakhir aktif --}}
                            <td class="pg-td-aktif">
                                {{ $u->last_active_at ? \Carbon\Carbon::parse($u->last_active_at)->diffForHumans() : '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="pg-td-aksi">
                                <button type="button" class="pg-btn-edit" onclick="pgOpenEdit({{ $u->id }})">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

    {{-- ===== MODAL TAMBAH/EDIT PENGGUNA ===== --}}
    <div class="pg-modal-overlay" id="pgModalOverlay">
        <div class="pg-modal">
            <div class="pg-modal-head">
                <span class="pg-modal-title" id="pgModalTitle">Tambah Pengguna</span>
                <button type="button" class="pg-modal-close" id="pgModalClose" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form class="pg-modal-body" id="pgModalForm" method="POST" action="{{ route('superadmin.pengguna.store') }}">
                @csrf
                <div id="pgMethodContainer"></div>
                <input type="hidden" name="user_id" id="pgUserId" value="">

                <div class="pg-modal-grid">
                    <div class="pg-modal-field">
                        <label class="pg-modal-label" for="pgFieldName">Nama Lengkap <span class="pg-req">*</span></label>
                        <input type="text" name="name" id="pgFieldName" class="pg-modal-input" placeholder="Nama pengguna" required>
                    </div>
                    <div class="pg-modal-field">
                        <label class="pg-modal-label" for="pgFieldEmail">Email <span class="pg-req">*</span></label>
                        <input type="email" name="email" id="pgFieldEmail" class="pg-modal-input" placeholder="email@domain.id" required>
                    </div>
                    <div class="pg-modal-field">
                        <label class="pg-modal-label" for="pgFieldRole">Peran <span class="pg-req">*</span></label>
                        <select name="role" id="pgFieldRole" class="pg-modal-input">
                            <option value="tenant_admin">Tenant Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="pg-modal-field">
                        <label class="pg-modal-label" for="pgFieldMasjid">Masjid</label>
                        <select name="masjid" id="pgFieldMasjid" class="pg-modal-input">
                        <option value="">— Pilih Masjid —</option>
                        <option value="Baitul Digital">Baitul Digital</option>
                        <option value="Masjid Ar-Rahman">Masjid Ar-Rahman</option>
                        <option value="Masjid Al-Aqsa">Masjid Al-Aqsa</option>
                    </select>
                    </div>
                    <div class="pg-modal-field" id="pgPasswordWrap">
                        <label class="pg-modal-label" for="pgFieldPassword">Password <span class="pg-req" id="pgPasswordReq">*</span></label>
                        <input type="password" name="password" id="pgFieldPassword" class="pg-modal-input" placeholder="Min. 8 karakter">
                    </div>
                    <div class="pg-modal-field" id="pgPasswordConfirmWrap">
                        <label class="pg-modal-label" for="pgFieldPasswordConfirm">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="pgFieldPasswordConfirm" class="pg-modal-input" placeholder="Ulangi password">
                    </div>
                </div>

                <div class="pg-modal-footer">
                    <button type="button" class="pg-btn-cancel" id="pgCancelBtn">Batal</button>
                    <button type="submit" class="pg-btn-simpan" id="pgSimpanBtn">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mengubah data array PHP langsung menjadi objek JSON yang aman untuk JS
        const pgData = {!! json_encode($pengguna) !!};

        // Sidebar toggle
        document.getElementById('saSidebarToggle').addEventListener('click', () => {
            document.getElementById('saSidebar').classList.toggle('collapsed');
            document.getElementById('saMain').classList.toggle('expanded');
        });

        // Buka modal edit
       function pgOpenEdit(id) {
            const u = pgData.find(x => x.id === id);
            if (!u) return;

            resetModal();
            document.getElementById('pgModalTitle').textContent = 'Edit Pengguna';
            document.getElementById('pgMethodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('pgUserId').value = u.id;
            document.getElementById('pgFieldName').value = u.name; // <-- Pastikan u.name, bukan u.nama
            document.getElementById('pgFieldEmail').value = u.email;
            document.getElementById('pgFieldRole').value = u.role; // <-- Pastikan u.role, bukan u.peran
            document.getElementById('pgFieldMasjid').value = u.masjid ?? '';
            
            document.getElementById('pgPasswordReq').style.display = 'none';
            document.getElementById('pgSimpanBtn').textContent = 'Simpan Perubahan';
            document.getElementById('pgModalForm').action = '/superadmin/pengguna/' + u.id;
            
            openModal();
        }

        function openModal()  { document.getElementById('pgModalOverlay').classList.add('active'); }
        function closeModal() { document.getElementById('pgModalOverlay').classList.remove('active'); }

        function resetModal() {
            document.getElementById('pgModalForm').reset();
            document.getElementById('pgSimpanBtn').textContent = 'Simpan Pengguna';
            document.getElementById('pgPasswordReq').style.display = 'inline';
            document.getElementById('pgMethodContainer').innerHTML = '';
        }

        // Event listener tombol tambah pengguna baru
        document.getElementById('pgTambahBtn').addEventListener('click', () => {
            resetModal();
            document.getElementById('pgModalTitle').textContent = 'Tambah Pengguna';
            document.getElementById('pgModalForm').action = "{{ route('superadmin.pengguna.store') }}";
            openModal();
        });

        document.getElementById('pgModalClose').addEventListener('click', closeModal);
        document.getElementById('pgCancelBtn').addEventListener('click', closeModal);
        document.getElementById('pgModalOverlay').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>