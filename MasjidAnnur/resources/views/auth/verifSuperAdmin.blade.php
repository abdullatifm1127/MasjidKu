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
                <span class="vf-topbar-badge">{{ $totalPending ?? 0 }} pending</span>
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
                        <div class="vf-summary-val">{{ $totalPending ?? 0 }}</div>
                        <div class="vf-summary-label">Menunggu Verifikasi</div>
                    </div>
                </div>
                <div class="vf-summary-card green">
                    <div class="vf-summary-icon">✅</div>
                    <div class="vf-summary-info">
                        <div class="vf-summary-val">{{ $totalApproved ?? 0 }}</div>
                        <div class="vf-summary-label">Disetujui</div>
                    </div>
                </div>
                <div class="vf-summary-card red">
                    <div class="vf-summary-icon">❌</div>
                    <div class="vf-summary-info">
                        <div class="vf-summary-val">{{ $totalRejected ?? 0 }}</div>
                        <div class="vf-summary-label">Ditolak</div>
                    </div>
                </div>
            </div>

            <div class="vf-filter-bar">
                <div class="vf-filter-tabs">
                    <button class="vf-filter-tab active" data-filter="semua">Semua ({{ $totalSemua ?? 0 }})</button>
                    <button class="vf-filter-tab pending" data-filter="pending">⏳ Pending ({{ $totalPending ?? 0 }})</button>
                    <button class="vf-filter-tab disetujui" data-filter="disetujui">✓ Disetujui ({{ $totalApproved ?? 0 }})</button>
                    <button class="vf-filter-tab ditolak" data-filter="ditolak">✕ Ditolak ({{ $totalRejected ?? 0 }})</button>
                </div>
                <div class="vf-search-wrap">
                    <i class="fa-solid fa-magnifying-glass vf-search-icon"></i>
                    <input type="text" id="vfSearch" class="vf-search" placeholder="Cari nama, kota, email...">
                </div>
            </div>

            {{-- ===== DAFTAR PENDAFTARAN ===== --}}
            <div id="vfList">

                @foreach($pendaftaran as $p)
                @php
                    // Normalisasi status database ('approved'/'rejected') ke format filter JS ('disetujui'/'ditolak')
                    $dbStatus = strtolower(trim($p->status));
                    if ($dbStatus === 'approved' || $dbStatus === 'aktif') {
                        $filterStatus = 'disetujui';
                    } elseif ($dbStatus === 'rejected') {
                        $filterStatus = 'ditolak';
                    } else {
                        $filterStatus = 'pending';
                    }
                @endphp
                <div class="vf-card {{ $filterStatus }}"
                     data-status="{{ $filterStatus }}"
                     data-search="{{ strtolower($p->mosque_name.' '.$p->city.' '.$p->email) }}">

                    <div class="vf-card-inner">

                        <div class="vf-card-header">
                            <div class="vf-mosque-avatar" style="background:#4f46e5">
                                {{ strtoupper(substr($p->mosque_name, 0, 2)) }}
                            </div>

                            <div class="vf-mosque-title">
                                <div class="vf-mosque-name-row">
                                    <span class="vf-mosque-name">
                                        {{ $p->mosque_name }}
                                    </span>

                                    <span class="vf-status-badge {{ $filterStatus }}">
                                        @if($filterStatus === 'pending')
                                            ⏳ Menunggu
                                        @elseif($filterStatus === 'disetujui')
                                            ✓ Disetujui
                                        @else
                                            ✕ Ditolak
                                        @endif
                                    </span>
                                </div>

                                <div class="vf-mosque-sub">
                                    {{ $p->city }} · {{ $p->founded ?? '-' }} · {{ $p->capacity ?? '-' }} jamaah
                                </div>
                            </div>
                        </div>

                        <div class="vf-info-grid">
                            <div class="vf-info-item">
                                <div class="vf-info-label">Imam</div>
                                <div class="vf-info-val">{{ $p->imam_name ?? '-' }}</div>
                            </div>
                            <div class="vf-info-item">
                                <div class="vf-info-label">Ketua</div>
                                <div class="vf-info-val">{{ $p->chairman_name ?? '-' }}</div>
                            </div>
                            <div class="vf-info-item">
                                <div class="vf-info-label">Email</div>
                                <div class="vf-info-val">{{ $p->email }}</div>
                            </div>
                            <div class="vf-info-item">
                                <div class="vf-info-label">Telepon</div>
                                <div class="vf-info-val">{{ $p->phone ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="vf-tags">
                            @if(is_array($p->programs))
                                @foreach(array_slice($p->programs, 0, 4) as $prog)
                                    <span class="vf-tag">{{ $prog }}</span>
                                @endforeach

                                @if(count($p->programs) > 4)
                                    <span class="vf-tag-more">
                                        +{{ count($p->programs) - 4 }} lainnya
                                    </span>
                                @endif
                            @endif
                        </div>

                        <div class="vf-actions">
                            <button type="button"
                                    class="vf-btn-detail"
                                    onclick="vfOpenDetail({{ $p->id }})">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Detail
                            </button>

                            @if($filterStatus === 'pending')
                                <form method="POST"
                                      action="{{ route('superadmin.verifikasi.approve', $p->id) }}"
                                      style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="vf-btn-approve">
                                        <i class="fa-solid fa-check"></i>
                                        Setujui
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('superadmin.verifikasi.reject', $p->id) }}"
                                      style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="vf-btn-reject">
                                        <i class="fa-solid fa-xmark"></i>
                                        Tolak
                                    </button>
                                </form>
                            @elseif($filterStatus === 'disetujui')
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

            document.getElementById('vfModalTitle').textContent = 'Detail — ' + d.mosque_name;

            const statusMap = { pending: '⏳ Menunggu', approved: '✓ Disetujui', rejected: '✕ Ditolak', disetujui: '✓ Disetujui', ditolak: '✕ Ditolak' };
            const programTags = (d.programs || []).map(p => `<span class="vf-tag">${p}</span>`).join('');
            
            const formattedDate = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

            document.getElementById('vfModalBody').innerHTML = `
                <div class="vf-modal-row">
                    <div class="vf-modal-avatar" style="background:#4f46e5">${d.mosque_name.substring(0, 2).toUpperCase()}</div>
                    <div>
                        <div class="vf-modal-mosque-name">${d.mosque_name}</div>
                        <div class="vf-modal-mosque-sub">${d.city} · ${d.founded ?? '-'} · ${d.capacity ?? '-'} jamaah</div>
                        <span class="vf-status-badge ${d.status} vf-modal-status">${statusMap[d.status] || d.status}</span>
                    </div>
                </div>
                <div class="vf-modal-grid">
                    <div class="vf-modal-field"><div class="vf-info-label">Imam</div><div class="vf-info-val">${d.imam_name ?? '-'}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Ketua</div><div class="vf-info-val">${d.chairman_name ?? '-'}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Email</div><div class="vf-info-val">${d.email}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Telepon</div><div class="vf-info-val">${d.phone ?? '-'}</div></div>
                    <div class="vf-modal-field"><div class="vf-info-label">Tanggal Daftar</div><div class="vf-info-val">${formattedDate}</div></div>
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