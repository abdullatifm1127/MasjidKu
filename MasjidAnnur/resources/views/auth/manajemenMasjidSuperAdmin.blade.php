<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Masjid - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/manajemenMasjidSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <nav class="sa-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="sa-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item sa-nav-has-badge">
                <span class="sa-nav-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                <span class="sa-nav-badge-dot amber"></span>
            </a>
            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item active">
                <span class="sa-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="sa-nav-label">Manajemen Masjid</span>
            </a>
            <a href="{{ route('superadmin.pengguna') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="sa-nav-label">Pengguna</span>
            </a>
            <a href="{{ route('superadmin.pengaturan') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-gear"></i></span>
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
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
            <form id="sa-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="sa-main" id="saMain">

        {{-- Topbar --}}
        <header class="sa-topbar">
            <div class="sa-topbar-title">Manajemen Masjid</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    Lihat Website
                </a>
                <button class="sa-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="sa-notif-dot"></span>
                </button>
            </div>
        </header>

        <main class="sa-content mm-content">

            @if(session('success'))
            <div class="mm-alert mm-alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
            @endif

            {{-- ===== TOOLBAR ===== --}}
            <div class="mm-toolbar">
                {{-- Filter tabs dinamis --}}
                <div class="mm-filter-tabs">
                    <button class="mm-filter-tab active" data-filter="semua">Semua ({{ $totalSemua }})</button>
                    <button class="mm-filter-tab" data-filter="aktif">Aktif ({{ $totalAktif }})</button>
                    <button class="mm-filter-tab" data-filter="pending">Pending ({{ $totalPending }})</button>
                    <button class="mm-filter-tab" data-filter="nonaktif">Nonaktif ({{ $totalNonaktif }})</button>
                </div>

                {{-- Search + Tambah --}}
                <div class="mm-toolbar-right">
                    <div class="mm-search-wrap">
                        <i class="fa-solid fa-magnifying-glass mm-search-icon"></i>
                        <input type="text" id="mmSearch" class="mm-search" placeholder="Cari masjid atau kota...">
                    </div>
                    <button class="mm-btn-tambah" id="mmTambahBtn">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Masjid
                    </button>
                </div>
            </div>

            {{-- ===== TABEL ===== --}}
            <div class="mm-table-wrap">
                <table class="mm-table" id="mmTable">
                    <thead>
                        <tr>
                            <th class="mm-th-masjid">Masjid</th>
                            <th class="mm-th-kota">Kota</th>
                            <th class="mm-th-imam">Imam</th>
                            <th class="mm-th-status">Status</th>
                            <th class="mm-th-donasi">Donasi</th>
                            <th class="mm-th-aksi"></th>
                        </tr>
                    </thead>
                    <tbody id="mmTbody">

                        @foreach($masjids as $m)
                        @php
                            // Menyesuaikan status database dengan filter front-end ('aktif', 'pending', 'nonaktif')
                            $statusClass = 'pending';
                            if ($m->status === 'approved' || $m->status === 'aktif') {
                                $statusClass = 'aktif';
                            } elseif ($m->status === 'rejected') {
                                $statusClass = 'nonaktif';
                            } else {
                                $statusClass = $m->status;
                            }
                        @endphp
                        <tr class="mm-row" data-status="{{ $statusClass }}"
                            data-search="{{ strtolower($m->mosque_name.' '.$m->city) }}">
                            <td class="mm-td-masjid">
                                <div class="mm-mosque-cell">
                                    <div class="mm-mosque-avatar" style="background:#1a4731">{{ strtoupper(substr($m->mosque_name, 0, 1)) }}</div>
                                    <div class="mm-mosque-names">
                                        <div class="mm-mosque-nama">{{ $m->mosque_name }}</div>
                                        <div class="mm-mosque-arab">{{ $m->arabic_name ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="mm-td-kota">{{ $m->city }}</td>
                            <td class="mm-td-imam">{{ $m->imam_name ?? '-' }}</td>
                            <td class="mm-td-status">
                                <div class="mm-status-select-wrap">
                                    <select class="mm-status-select {{ $statusClass }}"
                                            data-id="{{ $m->id }}"
                                            onchange="mmChangeStatus(this)">
                                        <option value="aktif"    {{ $statusClass === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                        <option value="pending"  {{ $statusClass === 'pending'  ? 'selected' : '' }}>Pending</option>
                                        <option value="nonaktif" {{ $statusClass === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down mm-select-arrow"></i>
                                </div>
                            </td>
                            <td class="mm-td-donasi">
                                <div class="mm-donasi-wrap">
                                    <div class="mm-donasi-bar-track">
                                        <div class="mm-donasi-bar-fill"
                                             style="width:75%; background:#f59e0b"></div>
                                    </div>
                                    <span class="mm-donasi-pct">75%</span>
                                </div>
                            </td>
                            <td class="mm-td-aksi">
                                <a href="#" class="mm-kelola-link">Detail →</a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

                {{-- Empty state --}}
                <div class="mm-empty" id="mmEmpty" style="display:none;">
                    <div class="mm-empty-icon">🕌</div>
                    <div class="mm-empty-text">Tidak ada masjid ditemukan</div>
                </div>
            </div>

        </main>
    </div>

<<<<<<< HEAD
=======
    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

>>>>>>> 2ca46e20aed05c9f014802bc52f3d1c4bc5fd904
    {{-- ===== MODAL TAMBAH MASJID ===== --}}
    <div class="mm-modal-overlay" id="mmModalOverlay">
        <div class="mm-modal">
            <div class="mm-modal-head">
                <span class="mm-modal-title">Tambah Masjid</span>
                <button class="mm-modal-close" id="mmModalClose" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form class="mm-modal-body" method="POST" action="{{ route('superadmin.manajemen-masjid.store') }}">
                @csrf
                <div class="mm-modal-grid">
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Masjid <span class="mm-req">*</span></label>
                        <input type="text" name="mosque_name" class="mm-modal-input" placeholder="Nama masjid" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Arab</label>
                        <input type="text" name="arabic_name" class="mm-modal-input mm-rtl" placeholder="الاسم بالعربية" dir="rtl">
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Kota <span class="mm-req">*</span></label>
                        <input type="text" name="city" class="mm-modal-input" placeholder="Kota / Kabupaten" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Provinsi <span class="mm-req">*</span></label>
                        <input type="text" name="province" class="mm-modal-input" placeholder="Provinsi" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Imam <span class="mm-req">*</span></label>
                        <input type="text" name="imam_name" class="mm-modal-input" placeholder="Nama imam" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Nama Ketua <span class="mm-req">*</span></label>
                        <input type="text" name="chairman_name" class="mm-modal-input" placeholder="Nama ketua DKM" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Email <span class="mm-req">*</span></label>
                        <input type="email" name="email" class="mm-modal-input" placeholder="email@masjid.id" required>
                    </div>
                    <div class="mm-modal-field">
                        <label class="mm-modal-label">Telepon</label>
                        <input type="text" name="phone" class="mm-modal-input" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="mm-modal-field mm-modal-field-full">
                    <label class="mm-modal-label">Status</label>
                    <select name="status" class="mm-modal-input">
                        <option value="approved">Aktif</option>
                        <option value="pending" selected>Pending</option>
                    </select>
                </div>
                <div class="mm-modal-footer">
                    <button type="button" class="mm-btn-cancel" id="mmCancelBtn">Batal</button>
                    <button type="submit" class="mm-btn-simpan">Simpan Masjid</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar toggle
        document.getElementById('saSidebarToggle').addEventListener('click', () => {
            document.getElementById('saSidebar').classList.toggle('collapsed');
            document.getElementById('saMain').classList.toggle('expanded');
        });

        // Filter tabs
        const filterTabs = document.querySelectorAll('.mm-filter-tab');
        const rows = document.querySelectorAll('.mm-row');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                filterTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                applyFilter(tab.dataset.filter, document.getElementById('mmSearch').value.trim().toLowerCase());
            });
        });

        // Search
        document.getElementById('mmSearch').addEventListener('input', function () {
            const activeFilter = document.querySelector('.mm-filter-tab.active').dataset.filter;
            applyFilter(activeFilter, this.value.trim().toLowerCase());
        });

        function applyFilter(filter, search) {
            let visible = 0;
            rows.forEach(row => {
                const matchFilter = filter === 'semua' || row.dataset.status === filter;
                const matchSearch = !search || row.dataset.search.includes(search);
                const show = matchFilter && matchSearch;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('mmEmpty').style.display = visible === 0 ? 'flex' : 'none';
        }

        // Status select color update
        function mmChangeStatus(sel) {
            sel.className = 'mm-status-select ' + sel.value;
        }

        // Modal Tambah
        const overlay = document.getElementById('mmModalOverlay');
        document.getElementById('mmTambahBtn').addEventListener('click', () => overlay.classList.add('active'));
        document.getElementById('mmModalClose').addEventListener('click', () => overlay.classList.remove('active'));
        document.getElementById('mmCancelBtn').addEventListener('click',  () => overlay.classList.remove('active'));
        overlay.addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('active');
        });
    </script>
</body>
</html>