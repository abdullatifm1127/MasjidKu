<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pendaftaran - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/verifSuperAdmin.css') }}">
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
    
    {{-- Hitung jumlah data pending secara otomatis --}}
    @php
        $pendingCount = \App\Models\Mosque::where('status', 'pending')->count();
    @endphp

    {{-- Tampilkan angka jika lebih dari 0, jika 0 bisa disembunyikan atau dibiarkan --}}
    @if($pendingCount > 0)
        <span class="sa-nav-badge-number" style="background: #f59e0b; color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px; font-weight: bold;">
            {{ $pendingCount }}
        </span>
    @endif
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
            <a href="{{ route('home') }}" class="sa-btn-website" target="_blank">
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
                    $dbStatus = strtolower(trim($p->status));
                    if ($dbStatus === 'approved' || $dbStatus === 'aktif' || $dbStatus === 'disetujui') {
                        $filterStatus = 'disetujui';
                    } elseif ($dbStatus === 'rejected' || $dbStatus === 'ditolak') {
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
                                {{ strtoupper(substr($p->mosque_name ?? 'MS', 0, 2)) }}
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

                        {{-- ===== TAMBAHAN: INFORMASI PEMBAYARAN & BUKTI TRANSFER ===== --}}
                        <div class="vf-payment-info" style="margin: 12px 0; padding: 10px 12px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                            <div>
                                <span style="color: #64748b; font-weight: 500;">Pembayaran:</span>
                                @if($p->payment_status === 'pending')
                                    <span style="color: #d97706; font-weight: 600; background: #fef3c7; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                        <i class="fa-solid fa-clock"></i> Sudah Transfer (Menunggu Verifikasi)
                                    </span>
                                @elseif($p->payment_status === 'paid' || $filterStatus === 'disetujui')
                                    <span style="color: #059669; font-weight: 600; background: #d1fae5; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                        <i class="fa-solid fa-check"></i> Lunas / Disetujui
                                    </span>
                                @else
                                    <span style="color: #dc2626; font-weight: 600; background: #fee2e2; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                        <i class="fa-solid fa-xmark"></i> Belum Bayar
                                    </span>
                                @endif
                            </div>

                            @if(!empty($p->payment_proof))
                                <a href="{{ asset('storage/' . $p->payment_proof) }}" target="_blank" style="color: #0284c7; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-image"></i> Lihat Bukti Transfer
                                </a>
                            @endif
                        </div>
                        {{-- ========================================================== --}}

                        <div class="vf-tags">
                            @if(is_array($p->programs))
                                @foreach(array_slice($p->programs, 0, 4) as $prog)
                                    <span class="vf-tag">{{ $prog }}</span>
                                @endforeach

                                @if(count($p->programs) > 4)
                                    <span class="vf-tag vf-tag-more">
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
            </div>



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
        
        // Logika pemetaan status pembayaran untuk modal
        let paymentBadgeText = '<span style="color: #dc2626; font-weight: 600;">Belum Bayar</span>';
        if (d.payment_status === 'pending') {
            paymentBadgeText = '<span style="color: #d97706; font-weight: 600; background: #fef3c7; padding: 2px 6px; border-radius: 4px;">Sudah Transfer (Menunggu Verifikasi)</span>';
        } else if (d.payment_status === 'paid' || d.status === 'approved' || d.status === 'disetujui') {
            paymentBadgeText = '<span style="color: #059669; font-weight: 600; background: #d1fae5; padding: 2px 6px; border-radius: 4px;">Lunas / Disetujui</span>';
        }

        // Logika pengecekan file bukti transfer
        let paymentProofSection = '<div style="color: #9ca3af; font-style: italic; font-size: 0.9rem;">Belum mengunggah bukti pembayaran.</div>';
        if (d.payment_proof) {
            paymentProofSection = `
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="/storage/${d.payment_proof}" target="_blank" style="color: #0284c7; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-external-link-alt"></i> Buka Gambar Ukuran Penuh
                    </a>
                    <img src="/storage/${d.payment_proof}" alt="Bukti Transfer" style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #cbd5e1; object-fit: contain; background: #f8fafc; padding: 4px;">
                </div>
            `;
        }

        // === LOGIKA TABEL RIWAYAT TRANSAKSI DARI RELASI (ARRAY) ===
        let historyListHTML = '<div style="color: #64748b; font-size: 0.85rem; font-style: italic;">Belum ada riwayat transaksi lain untuk masjid ini.</div>';
        
        if (d.subscriptions && d.subscriptions.length > 0) {
            historyListHTML = `
                <div style="max-height: 160px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <table style="width: 100%; font-size: 0.82rem; border-collapse: collapse; text-align: left;">
                        <thead style="background: #f1f5f9; color: #334155; position: sticky; top: 0;">
                            <tr>
                                <th style="padding: 6px 8px;">Tanggal</th>
                                <th style="padding: 6px 8px;">Nominal</th>
                                <th style="padding: 6px 8px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            d.subscriptions.forEach(sub => {
                const subDate = new Date(sub.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                const formattedAmount = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(sub.amount || 0);
                
                let badgeSubColor = 'color: #d97706;';
                if (sub.status === 'paid' || sub.status === 'approved' || sub.status === 'disetujui') {
                    badgeSubColor = 'color: #059669; font-weight: 600;';
                } else if (sub.status === 'rejected' || sub.status === 'ditolak') {
                    badgeSubColor = 'color: #dc2626; font-weight: 600;';
                }

                // Mengubah baris agar bisa diklik dan memanggil fungsi detail transaksi
                const safeProof = sub.payment_proof ? sub.payment_proof : '';
                historyListHTML += `
                    <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;" 
                        onmouseover="this.style.background='#f8fafc'" 
                        onmouseout="this.style.background='transparent'"
                        onclick="showSubDetail(${d.id}, '${subDate}', '${formattedAmount}', '${sub.status}', '${safeProof}')">
                        <td style="padding: 6px 8px;">${subDate}</td>
                        <td style="padding: 6px 8px; font-weight: 600; color: #334155;">${formattedAmount}</td>
                        <td style="padding: 6px 8px;"><span style="text-transform: capitalize; ${badgeSubColor}">${sub.status}</span> <i class="fa-solid fa-chevron-right" style="float: right; font-size: 0.75rem; color: #94a3b8; margin-top: 3px;"></i></td>
                    </tr>
                `;
            });
            historyListHTML += `</tbody></table></div>`;
        }
        // ==========================================================

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
                <div class="vf-modal-field"><div class="vf-info-label">Status Pembayaran</div><div class="vf-info-val">${paymentBadgeText}</div></div>
            </div>

            <div class="vf-info-label" style="margin:16px 0 8px">Program Kegiatan</div>
            <div class="vf-tags" style="margin-bottom: 16px;">${programTags}</div>

            <div class="vf-info-label" style="margin:16px 0 8px">Bukti Transfer Pembayaran</div>
            <div style="margin-bottom: 16px;">${paymentProofSection}</div>

            <!-- Bagian Riwayat Pembayaran Per Akun -->
            <div class="vf-info-label" style="margin:16px 0 8px">Riwayat Transaksi Akun Ini</div>
            <div>${historyListHTML}</div>
        `;

        document.getElementById('vfModalOverlay').classList.add('active');
    }

    // Fungsi untuk menampilkan detail transaksi spesifik saat baris tabel diklik
    function showSubDetail(mosqueId, date, amount, status, proof) {
        let proofHtml = '<div style="color: #9ca3af; font-style: italic; font-size: 0.9rem;">Tidak ada bukti pembayaran untuk transaksi ini.</div>';
        if (proof && proof !== '') {
            proofHtml = `
                <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px;">
                    <a href="/storage/${proof}" target="_blank" style="color: #0284c7; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-external-link-alt"></i> Buka Gambar Ukuran Penuh
                    </a>
                    <img src="/storage/${proof}" alt="Bukti Transaksi" style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #cbd5e1; object-fit: contain; background: #f8fafc; padding: 4px;">
                </div>
            `;
        }

        document.getElementById('vfModalBody').innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0;">Rincian Detail Transaksi</h3>
                <button onclick="vfOpenDetail(${mosqueId})" style="background: #e2e8f0; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; color: #334155;">← Kembali ke Profil</button>
            </div>
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Tanggal Transaksi</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #334155;">${date}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Nominal</div>
                    <div style="font-size: 1.1rem; font-weight: 700; color: #059669;">${amount}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Status Transaksi</div>
                    <div style="font-size: 0.95rem; font-weight: 600; text-transform: capitalize; color: #334155;">${status}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">Bukti Pembayaran</div>
                    ${proofHtml}
                </div>
            </div>
        `;
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