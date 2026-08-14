<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pendaftaran - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verifSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="sa-page" id="saBody">


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

            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item active sa-nav-has-badge">
                <span class="sa-nav-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                <span class="sa-nav-badge-dot amber"></span>
            </a>

            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item">
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
            <div class="sa-topbar-left">
                <div class="sa-topbar-title">Verifikasi Pendaftaran</div>
                <span class="vf-topbar-badge">2 pending</span>
            </div>
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

        <main class="sa-content vf-content">

            {{-- ===== SUMMARY CARDS ===== --}}
            <div class="vf-summary-grid">
                <div class="vf-summary-card amber">
                    <div class="vf-summary-icon">⏳</div>
                    <div class="vf-summary-info">
                        <div class="vf-summary-val">2</div>
                        <div class="vf-summary-label">Menunggu Verifikasi</div>
                    </div>
                </div>
                <div class="vf-summary-card green">
                    <div class="vf-summary-icon">✅</div>
                    <div class="vf-summary-info">
                        <div class="vf-summary-val">1</div>
                        <div class="vf-summary-label">Disetujui</div>
                    </div>
                </div>
                <div class="vf-summary-card red">
                    <div class="vf-summary-icon">❌</div>
                    <div class="vf-summary-info">
                        <div class="vf-summary-val">1</div>
                        <div class="vf-summary-label">Ditolak</div>
                    </div>
                </div>
            </div>

            {{-- ===== FILTER BAR ===== --}}
            <div class="vf-filter-bar">
                <div class="vf-filter-tabs">
                    <button class="vf-filter-tab active" data-filter="semua">Semua (4)</button>
                    <button class="vf-filter-tab pending" data-filter="pending">⏳ Pending (2)</button>
                    <button class="vf-filter-tab disetujui" data-filter="disetujui">✓ Disetujui (1)</button>
                    <button class="vf-filter-tab ditolak" data-filter="ditolak">✕ Ditolak (1)</button>
                </div>
                <div class="vf-search-wrap">
                    <i class="fa-solid fa-magnifying-glass vf-search-icon"></i>
                    <input type="text" id="vfSearch" class="vf-search" placeholder="Cari nama, kota, email...">
                </div>
            </div>

            {{-- ===== DAFTAR PENDAFTARAN ===== --}}
            <div id="vfList">

                @php
                $pendaftaran = [
                    [
                        'id'        => 1,
                        'initial'   => 'M',
                        'color'     => '#6b7280',
                        'nama'      => 'Masjid Al-Nur',
                        'status'    => 'pending',
                        'kota'      => 'Yogyakarta, Di Yogyakarta',
                        'tahun'     => '2001',
                        'jamaah'    => '800 jamaah',
                        'imam'      => 'Ustadz H. Mahmud Fauzi, Lc.',
                        'ketua'     => 'Dr. Irwan Setiadi, M.Si.',
                        'email'     => 'info@masjidalnur.id',
                        'telepon'   => '+62 274 512 345',
                        'program'   => ['TPA / TPQ','Tahsin Al-Quran','Kajian Hadits','Majelis Taklim','Qurban'],
                        'daftar'    => '13 Agu 2026, 09:15',
                    ],
                    [
                        'id'        => 2,
                        'initial'   => 'M',
                        'color'     => '#374151',
                        'nama'      => 'Masjid Baiturrohman',
                        'status'    => 'pending',
                        'kota'      => 'Samarinda, Kalimantan Timur',
                        'tahun'     => '1998',
                        'jamaah'    => '1.200 jamaah',
                        'imam'      => 'KH. Abdurrahman Wahid, S.Ag.',
                        'ketua'     => 'H. Sutrisno Hadi',
                        'email'     => 'admin@baiturrohman-smr.id',
                        'telepon'   => '+62 541 201 456',
                        'program'   => ['Hafalan Quran 30 Juz','Kajian Fiqih','Pengajian Bulanan','Program Yatim Piatu','Donasi Sosial'],
                        'daftar'    => '12 Agu 2026, 14:30',
                    ],
                    [
                        'id'        => 3,
                        'initial'   => 'B',
                        'color'     => '#1a4731',
                        'nama'      => 'Baitul Digital',
                        'status'    => 'disetujui',
                        'kota'      => 'Jakarta Selatan, DKI Jakarta',
                        'tahun'     => '1987',
                        'jamaah'    => '2.500 jamaah',
                        'imam'      => 'Ustadz Ahmad Fauzan, Lc.',
                        'ketua'     => 'Ir. Hendra Kusuma, M.T.',
                        'email'     => 'admin@baituldigital.id',
                        'telepon'   => '+62 21 780 1234',
                        'program'   => ['TPA / TPQ','Kajian Rutin','Tahfidz Al-Qur\'an','Kajian Fiqih'],
                        'daftar'    => '10 Agu 2026, 08:00',
                    ],
                    [
                        'id'        => 4,
                        'initial'   => 'M',
                        'color'     => '#7c3aed',
                        'nama'      => 'Masjid Nurul Huda',
                        'status'    => 'ditolak',
                        'kota'      => 'Medan, Sumatera Utara',
                        'tahun'     => '2010',
                        'jamaah'    => '400 jamaah',
                        'imam'      => 'Ustadz Rizki Amal',
                        'ketua'     => 'Bapak Suharto',
                        'email'     => 'nurulhuda@gmail.com',
                        'telepon'   => '+62 61 885 9900',
                        'program'   => ['Pengajian Ibu-ibu','Shalat Berjamaah'],
                        'daftar'    => '9 Agu 2026, 16:45',
                    ],
                ];
                @endphp

                @foreach($pendaftaran as $p)
                <div class="vf-card {{ $p['status'] }}" data-status="{{ $p['status'] }}" data-search="{{ strtolower($p['nama'].' '.$p['kota'].' '.$p['email']) }}">

                    {{-- Border kiri warna sesuai status --}}
                    <div class="vf-card-inner">

                        {{-- Header row --}}
                        <div class="vf-card-header">
                            <div class="vf-mosque-avatar" style="background:{{ $p['color'] }}">{{ $p['initial'] }}</div>
                            <div class="vf-mosque-title">
                                <div class="vf-mosque-name-row">
                                    <span class="vf-mosque-name">{{ $p['nama'] }}</span>
                                    <span class="vf-status-badge {{ $p['status'] }}">
                                        @if($p['status'] === 'pending') ⏳ Menunggu
                                        @elseif($p['status'] === 'disetujui') ✓ Disetujui
                                        @else ✕ Ditolak
                                        @endif
                                    </span>
                                </div>
                                <div class="vf-mosque-sub">
                                    {{ $p['kota'] }} · {{ $p['tahun'] }} · {{ $p['jamaah'] }}
                                </div>
                            </div>
                            <div class="vf-daftar-info">
                                <div class="vf-daftar-label">Daftar</div>
                                <div class="vf-daftar-val">{{ $p['daftar'] }}</div>
                            </div>
                        </div>

                        {{-- Info grid --}}
                        <div class="vf-info-grid">
                            <div class="vf-info-item">
                                <div class="vf-info-label">Imam</div>
                                <div class="vf-info-val">{{ $p['imam'] }}</div>
                            </div>
                            <div class="vf-info-item">
                                <div class="vf-info-label">Ketua</div>
                                <div class="vf-info-val">{{ $p['ketua'] }}</div>
                            </div>
                            <div class="vf-info-item">
                                <div class="vf-info-label">Email</div>
                                <div class="vf-info-val">{{ $p['email'] }}</div>
                            </div>
                            <div class="vf-info-item">
                                <div class="vf-info-label">Telepon</div>
                                <div class="vf-info-val">{{ $p['telepon'] }}</div>
                            </div>
                        </div>

                        {{-- Program tags --}}
                        <div class="vf-tags">
                            @foreach(array_slice($p['program'], 0, 4) as $prog)
                                <span class="vf-tag">{{ $prog }}</span>
                            @endforeach
                            @if(count($p['program']) > 4)
                                <span class="vf-tag vf-tag-more">+{{ count($p['program']) - 4 }} lainnya</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="vf-actions">
                            <button class="vf-btn-detail" onclick="vfOpenDetail({{ $p['id'] }})">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Detail
                            </button>

                            @if($p['status'] === 'pending')
                            <form method="POST" action="{{ route('superadmin.verifikasi.approve', $p['id']) }}" style="display:inline;">
                                @csrf @method('PUT')
                                <button type="submit" class="vf-btn-approve">
                                    <i class="fa-solid fa-check"></i>
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('superadmin.verifikasi.reject', $p['id']) }}" style="display:inline;">
                                @csrf @method('PUT')
                                <button type="submit" class="vf-btn-reject">
                                    <i class="fa-solid fa-xmark"></i>
                                    Tolak
                                </button>
                            </form>
                            @elseif($p['status'] === 'disetujui')
                            <span class="vf-status-label green">
                                <i class="fa-solid fa-circle-check"></i>
                                Sudah disetujui
                            </span>
                            @else
                            <span class="vf-status-label red">
                                <i class="fa-solid fa-xmark"></i>
                                Pendaftaran ditolak
                            </span>
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach

                {{-- Empty state --}}
                <div class="vf-empty" id="vfEmpty" style="display:none;">
                    <div class="vf-empty-icon">🔍</div>
                    <div class="vf-empty-text">Tidak ada pendaftaran ditemukan</div>
                </div>

            </div>

        </main>
    </div>

    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

    {{-- ===== MODAL DETAIL ===== --}}
    <div class="vf-modal-overlay" id="vfModalOverlay">
        <div class="vf-modal" id="vfModal">
            <div class="vf-modal-head">
                <span class="vf-modal-title" id="vfModalTitle">Detail Pendaftaran</span>
                <button class="vf-modal-close" id="vfModalClose" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="vf-modal-body" id="vfModalBody">
                {{-- Diisi via JS --}}
            </div>
        </div>
    </div>

    <script>
        // ---- Sidebar toggle ----
        document.getElementById('saSidebarToggle').addEventListener('click', () => {
            document.getElementById('saSidebar').classList.toggle('collapsed');
            document.getElementById('saMain').classList.toggle('expanded');
        });

        // ---- Filter tabs ----
        const tabs  = document.querySelectorAll('.vf-filter-tab');
        const cards = document.querySelectorAll('.vf-card');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                filterCards(tab.dataset.filter, document.getElementById('vfSearch').value);
            });
        });

        // ---- Search ----
        document.getElementById('vfSearch').addEventListener('input', function () {
            const activeFilter = document.querySelector('.vf-filter-tab.active').dataset.filter;
            filterCards(activeFilter, this.value.trim().toLowerCase());
        });

        function filterCards(filter, search) {
            let visible = 0;
            cards.forEach(card => {
                const matchFilter = filter === 'semua' || card.dataset.status === filter;
                const matchSearch = !search || card.dataset.search.includes(search);
                const show = matchFilter && matchSearch;
                card.style.display = show ? 'block' : 'none';
                if (show) visible++;
            });
            document.getElementById('vfEmpty').style.display = visible === 0 ? 'flex' : 'none';
        }

        // ---- Detail modal ----
        const detailData = @json($pendaftaran ?? []);

        function vfOpenDetail(id) {
            const d = detailData.find(x => x.id === id);
            if (!d) return;

            document.getElementById('vfModalTitle').textContent = 'Detail — ' + d.nama;

            const statusMap = { pending: '⏳ Menunggu', disetujui: '✓ Disetujui', ditolak: '✕ Ditolak' };
            const programTags = (d.program || []).map(p => `<span class="vf-tag">${p}</span>`).join('');

            document.getElementById('vfModalBody').innerHTML = `
                <div class="vf-modal-row">
                    <div class="vf-modal-avatar" style="background:${d.color}">${d.initial}</div>
                    <div>
                        <div class="vf-modal-mosque-name">${d.nama}</div>
                        <div class="vf-modal-mosque-sub">${d.kota} · ${d.tahun} · ${d.jamaah}</div>
                        <span class="vf-status-badge ${d.status} vf-modal-status">${statusMap[d.status]}</span>
                    </div>
                </div>
                <div class="vf-modal-grid">
                    <div class="vf-modal-field"><div class="vf-info-label">Imam</div><div class="vf-info-val">${d.imam}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Ketua</div><div class="vf-info-val">${d.ketua}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Email</div><div class="vf-info-val">${d.email}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Telepon</div><div class="vf-info-val">${d.telepon}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Tanggal Daftar</div><div class="vf-info-val">${d.daftar}</div></div>
                </div>
                <div class="vf-info-label" style="margin:16px 0 8px">Program Kegiatan</div>
                <div class="vf-tags">${programTags}</div>
            `;

            document.getElementById('vfModalOverlay').classList.add('active');
        }

        document.getElementById('vfModalClose').addEventListener('click', () => {
            document.getElementById('vfModalOverlay').classList.remove('active');
        });

        document.getElementById('vfModalOverlay').addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('active');
        });
    </script>
</body>
</html>
