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
        </div>

        <nav class="sa-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item">
                <span class="sa-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="sa-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item active sa-nav-has-badge">
                <span class="sa-nav-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>

                @php
                    $pendingCount = \App\Models\Mosque::where('status', 'pending')->count();
                @endphp

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

            {{-- ===== NOTIFIKASI SUKSES / ERROR ===== --}}
            @if (session('success'))
                <div style="background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:0.9rem; display:flex; justify-content:space-between; align-items:center;">
                    <span><i class="fa-solid fa-circle-check"></i>&nbsp; {{ session('success') }}</span>
                    <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; font-size:1rem; color:#065f46; cursor:pointer;">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:0.9rem; display:flex; justify-content:space-between; align-items:center;">
                    <span><i class="fa-solid fa-circle-exclamation"></i>&nbsp; {{ session('error') }}</span>
                    <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; font-size:1rem; color:#991b1b; cursor:pointer;">&times;</button>
                </div>
            @endif

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
                @if(isset($pendaftaran) && count($pendaftaran) > 0)
                    @foreach($pendaftaran as $p)
                        @php
                            $dbStatus = strtolower(trim($p->status ?? 'pending'));
                            if ($dbStatus === 'approved' || $dbStatus === 'aktif' || $dbStatus === 'disetujui') {
                                $filterStatus = 'disetujui';
                            } elseif ($dbStatus === 'rejected' || $dbStatus === 'ditolak') {
                                $filterStatus = 'ditolak';
                            } else {
                                $filterStatus = 'pending';
                            }

                            $hasPendingPayment = strtolower(trim($p->payment_status ?? '')) === 'pending';
                            $tabStatus = $hasPendingPayment ? 'pending' : $filterStatus;

                            $pStatus = strtolower(trim($p->payment_status ?? $p->status_pembayaran ?? ''));
                            $pPackageType = strtolower(trim($p->package_type ?? ''));
                            $isFree = $pPackageType === 'free';

                            $programsList = is_string($p->programs) ? json_decode($p->programs, true) : $p->programs;

                            // Transaksi Midtrans terbaru masjid ini
                            $latestSub = collect($p->subscriptions ?? [])->sortByDesc('created_at')->first();
                        @endphp
                        <div class="vf-card {{ $tabStatus }}"
                             data-status="{{ $tabStatus }}"
                             data-search="{{ strtolower(($p->mosque_name ?? '') . ' ' . ($p->city ?? '') . ' ' . ($p->email ?? '')) }}">

                            <div class="vf-card-inner">

                                <div class="vf-card-header">
                                    <div class="vf-mosque-avatar" style="background:#4f46e5">
                                        {{ strtoupper(substr($p->mosque_name ?? 'MS', 0, 2)) }}
                                    </div>

                                    <div class="vf-mosque-title">
                                        <div class="vf-mosque-name-row">
                                            <span class="vf-mosque-name">
                                                {{ $p->mosque_name ?? 'Tanpa Nama' }}
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
                                            {{ $p->city ?? '-' }} · {{ $p->founded ?? '-' }} · {{ $p->capacity ?? '-' }} jamaah
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
                                        <div class="vf-info-val">{{ $p->email ?? '-' }}</div>
                                    </div>
                                    <div class="vf-info-item">
                                        <div class="vf-info-label">Telepon</div>
                                        <div class="vf-info-val">{{ $p->phone ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="vf-payment-info" style="margin: 12px 0; padding: 10px 12px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                                    <div>
                                        <span style="color: #64748b; font-weight: 500;">Pembayaran:</span>

                                        @if($isFree)
                                            <span style="color: #0284c7; font-weight: 600; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                                <i class="fa-solid fa-gift"></i> Paket Free / Gratis
                                            </span>
                                        @elseif($pStatus === 'pending')
                                            <span style="color: #d97706; font-weight: 600; background: #fef3c7; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                                <i class="fa-solid fa-clock"></i> Menunggu Pembayaran (Midtrans)
                                            </span>
                                        @elseif(in_array($pStatus, ['approved', 'paid', 'settlement', 'capture']))
                                            <span style="color: #059669; font-weight: 600; background: #d1fae5; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                                <i class="fa-solid fa-check"></i> Lunas / Disetujui
                                            </span>
                                        @elseif(in_array($pStatus, ['expire', 'cancel', 'deny', 'failure']))
                                            <span style="color: #dc2626; font-weight: 600; background: #fee2e2; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Gagal / Kedaluwarsa
                                            </span>
                                        @else
                                            <span style="color: #dc2626; font-weight: 600; background: #fee2e2; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-left: 6px;">
                                                <i class="fa-solid fa-xmark"></i> Belum Bayar
                                            </span>
                                        @endif
                                    </div>

                                    @if($latestSub && !empty($latestSub->order_id))
                                        <span style="color: #64748b; font-size: 0.8rem; margin-left: 10px; white-space: nowrap;">
                                            <i class="fa-solid fa-receipt"></i> {{ $latestSub->order_id }}
                                        </span>
                                    @endif
                                </div>

                                {{-- ===== PANEL KONTROL MANUAL SUPER ADMIN (PAKET & DONASI) ===== --}}
                                <div style="margin: 12px 0; padding: 12px; background: #f1f5f9; border-radius: 8px; border: 1px solid #cbd5e1;">
                                    <div style="font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-sliders" style="color: #0d9488;"></i> Kontrol Paket & Donasi Manual (Tanpa Midtrans)
                                    </div>
                                    <form action="{{ route('superadmin.mosque.subscription', $p->id) }}" method="POST" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                                        @csrf
                                        @method('PATCH')

                                        <div style="flex: 1; min-width: 140px;">
                                            <select name="package_type" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.82rem; background: #fff;">
                                                <option value="free" {{ $p->package_type == 'free' ? 'selected' : '' }}>Free (Nonaktif Donasi)</option>
                                                <option value="100000_1" {{ $p->package_type == '100000_1' ? 'selected' : '' }}>Paket 1 Bulan (Pro)</option>
                                                <option value="1000000_12" {{ $p->package_type == '1000000_12' ? 'selected' : '' }}>Paket 1 Tahun (Premium)</option>
                                            </select>
                                        </div>

                                        <div style="flex: 1; min-width: 120px;">
                                            <select name="payment_status" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.82rem; background: #fff;">
                                                <option value="approved" {{ $p->payment_status == 'approved' ? 'selected' : '' }}>Approved (Aktif)</option>
                                                <option value="unpaid" {{ $p->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid (Nonaktif)</option>
                                            </select>
                                        </div>

                                        <button type="submit" style="background: #0d9488; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-floppy-disk"></i> Simpan
                                        </button>
                                    </form>
                                </div>

                                <div class="vf-tags">
                                    @if(is_array($programsList))
                                        @foreach($programsList as $prog)
                                            <span class="vf-tag">{{ $prog }}</span>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="vf-actions" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px;">
                                    <div style="display:flex; flex-wrap:wrap; gap:10px;">
                                        <button type="button"
                                                class="vf-btn-detail"
                                                onclick="vfOpenDetail({{ $p->id }})">
                                            <i class="fa-solid fa-eye"></i>
                                            Lihat Detail
                                        </button>

                                        @if($filterStatus === 'pending' || $hasPendingPayment)
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

                                    {{-- Pesan konfirmasi disimpan di data-msg agar aman dari tanda kutip --}}
                                    <form method="POST"
                                          action="{{ route('superadmin.verifikasi.destroy', $p->id) }}"
                                          style="display:inline;"
                                          data-msg="Hapus data masjid '{{ $p->mosque_name ?? 'ini' }}' secara permanen? Tindakan ini tidak bisa dibatalkan."
                                          onsubmit="return confirm(this.dataset.msg);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1.5px solid #fecaca; color:#dc2626; font-weight:600; font-size:0.85rem; padding:8px 14px; border-radius:8px; cursor:pointer; transition:background .15s ease;"
                                                onmouseover="this.style.background='#fee2e2'"
                                                onmouseout="this.style.background='#fff'">
                                            <i class="fa-solid fa-trash-can"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Empty state --}}
            <div class="vf-empty" id="vfEmpty" style="display:none;">
                <div class="vf-empty-icon">🔍</div>
                <div class="vf-empty-text">Tidak ada pendaftaran ditemukan</div>
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
    const detailData = @json($pendaftaran ?? []);

    // Escape HTML agar data pendaftar tidak bisa menyisipkan markup/script
    function esc(v) {
        return String(v ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Normalisasi status Midtrans -> label + gaya
    function midtransStatus(raw) {
        const st = String(raw || '').toLowerCase();
        if (['settlement', 'capture', 'paid', 'approved', 'disetujui', 'lunas'].includes(st))
            return { label: 'Lunas', style: 'color:#059669; font-weight:600;' };
        if (st === 'pending')
            return { label: 'Menunggu Pembayaran', style: 'color:#d97706; font-weight:600;' };
        if (st === 'expire')
            return { label: 'Kedaluwarsa', style: 'color:#dc2626; font-weight:600;' };
        if (['cancel', 'deny', 'failure', 'rejected', 'ditolak'].includes(st))
            return { label: 'Gagal / Dibatalkan', style: 'color:#dc2626; font-weight:600;' };
        return { label: st || '-', style: 'color:#64748b;' };
    }

    function fmtDate(v, withTime) {
        if (!v) return '-';
        const opt = { day: '2-digit', month: 'short', year: 'numeric' };
        if (withTime) { opt.hour = '2-digit'; opt.minute = '2-digit'; }
        return new Date(v).toLocaleString('id-ID', opt);
    }

    function fmtRupiah(n) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n || 0);
    }

    const tabs  = document.querySelectorAll('.vf-filter-tab');
    const cards = document.querySelectorAll('.vf-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            filterCards(tab.dataset.filter, document.getElementById('vfSearch').value.trim().toLowerCase());
        });
    });

    const searchInput = document.getElementById('vfSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const activeFilter = document.querySelector('.vf-filter-tab.active').dataset.filter;
            filterCards(activeFilter, this.value.trim().toLowerCase());
        });
    }

    function filterCards(filter, search) {
        let visible = 0;
        cards.forEach(card => {
            const matchFilter = filter === 'semua' || card.dataset.status === filter;
            const matchSearch = !search || card.dataset.search.includes(search);
            const show = matchFilter && matchSearch;
            card.style.display = show ? 'block' : 'none';
            if (show) visible++;
        });
        const emptyEl = document.getElementById('vfEmpty');
        if (emptyEl) emptyEl.style.display = visible === 0 ? 'flex' : 'none';
    }

    function vfOpenDetail(id) {
        const d = detailData.find(x => x.id === id);
        if (!d) return;

        document.getElementById('vfModalTitle').textContent = 'Detail — ' + (d.mosque_name || '');

        const statusMap = { pending: '⏳ Menunggu', approved: '✓ Disetujui', rejected: '✕ Ditolak', disetujui: '✓ Disetujui', ditolak: '✕ Ditolak' };

        const pStatus = (d.payment_status || d.status_pembayaran || '').toLowerCase();
        const pPackageType = (d.package_type || '').toLowerCase();
        const isFree = pPackageType === 'free';

        let paymentBadgeText = '<span style="color: #dc2626; font-weight: 600;">Belum Bayar</span>';

        if (isFree) {
            paymentBadgeText = '<span style="color: #0284c7; font-weight: 600; background: #e0f2fe; padding: 2px 6px; border-radius: 4px;">Paket Free / Gratis</span>';
        } else if (['approved', 'paid', 'lunas', 'disetujui', 'settlement', 'capture'].includes(pStatus)) {
            paymentBadgeText = '<span style="color: #059669; font-weight: 600; background: #d1fae5; padding: 2px 6px; border-radius: 4px;">Lunas / Disetujui</span>';
        } else if (pStatus === 'pending') {
            paymentBadgeText = '<span style="color: #d97706; font-weight: 600; background: #fef3c7; padding: 2px 6px; border-radius: 4px;">Menunggu Pembayaran (Midtrans)</span>';
        } else if (['rejected', 'ditolak', 'expire', 'cancel', 'deny', 'failure'].includes(pStatus)) {
            paymentBadgeText = '<span style="color: #dc2626; font-weight: 600; background: #fee2e2; padding: 2px 6px; border-radius: 4px;">Gagal / Kedaluwarsa</span>';
        }

        const subs = (d.subscriptions || []).slice().sort((x, y) => new Date(y.created_at) - new Date(x.created_at));
        const last = subs[0];
        let paymentProofSection = '<div style="color: #9ca3af; font-style: italic; font-size: 0.9rem;">Belum ada transaksi Midtrans.</div>';
        if (last) {
            const ls = midtransStatus(last.transaction_status || last.status);
            paymentProofSection = `
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; font-size:0.88rem; display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    <div><div style="font-size:0.75rem; color:#64748b;">Order ID</div><div style="font-weight:600;">${esc(last.order_id || '-')}</div></div>
                    <div><div style="font-size:0.75rem; color:#64748b;">Metode</div><div style="font-weight:600; text-transform:capitalize;">${esc((last.payment_type || '-').replace(/_/g, ' '))}</div></div>
                    <div><div style="font-size:0.75rem; color:#64748b;">Nominal</div><div style="font-weight:600;">${esc(fmtRupiah(last.gross_amount ?? last.amount))}</div></div>
                    <div><div style="font-size:0.75rem; color:#64748b;">Status</div><div style="${ls.style}">${esc(ls.label)}</div></div>
                </div>
            `;
        }

        let historyListHTML = '<div style="color: #64748b; font-size: 0.85rem; font-style: italic;">Belum ada riwayat transaksi lain untuk masjid ini.</div>';

        if (subs.length > 0) {
            historyListHTML = `
                <div style="max-height: 200px; overflow: auto; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <table style="width: 100%; font-size: 0.82rem; border-collapse: collapse; text-align: left; white-space: nowrap;">
                        <thead style="background: #f1f5f9; color: #334155; position: sticky; top: 0;">
                            <tr>
                                <th style="padding: 6px 8px;">Tanggal</th>
                                <th style="padding: 6px 8px;">Order ID</th>
                                <th style="padding: 6px 8px;">Metode</th>
                                <th style="padding: 6px 8px;">Nominal</th>
                                <th style="padding: 6px 8px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            subs.forEach(sub => {
                const st = midtransStatus(sub.transaction_status || sub.status);
                const method = (sub.payment_type || '-').replace(/_/g, ' ');
                const detail = {
                    id: d.id,
                    date: fmtDate(sub.created_at, true),
                    paid_at: fmtDate(sub.paid_at || sub.settlement_time, true),
                    order_id: sub.order_id || '-',
                    method: method,
                    amount: fmtRupiah(sub.gross_amount ?? sub.amount),
                    status: st.label,
                    statusStyle: st.style
                };
                const safeSubJson = JSON.stringify(detail).replace(/"/g, '&quot;').replace(/'/g, '&#39;');

                historyListHTML += `
                    <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='#f8fafc'"
                        onmouseout="this.style.background='transparent'"
                        onclick="showSubDetailObj(${safeSubJson})">
                        <td style="padding: 6px 8px;">${esc(fmtDate(sub.created_at, false))}</td>
                        <td style="padding: 6px 8px;">${esc(sub.order_id || '-')}</td>
                        <td style="padding: 6px 8px; text-transform: capitalize;">${esc(method)}</td>
                        <td style="padding: 6px 8px; font-weight: 600; color: #334155;">${esc(detail.amount)}</td>
                        <td style="padding: 6px 8px;"><span style="${st.style}">${esc(st.label)}</span> <i class="fa-solid fa-chevron-right" style="float: right; font-size: 0.75rem; color: #94a3b8; margin-top: 3px;"></i></td>
                    </tr>
                `;
            });
            historyListHTML += `</tbody></table></div>`;
        }

        let programsArray = [];
        try {
            programsArray = typeof d.programs === 'string' ? JSON.parse(d.programs) : (d.programs || []);
        } catch (e) {
            programsArray = [];
        }
        if (!Array.isArray(programsArray)) programsArray = [];

        const programTags = programsArray.length > 0
            ? programsArray.map(prog => `<span class="vf-tag" style="display:inline-block; margin-right:4px; margin-bottom:4px;">${esc(prog)}</span>`).join('')
            : '<span style="color: #9ca3af; font-style: italic; font-size: 0.9rem;">Tidak ada program yang dipilih.</span>';

        const formattedDate = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
        const mosqueInitials = (d.mosque_name || 'MS').substring(0, 2).toUpperCase();

        document.getElementById('vfModalBody').innerHTML = `
            <div class="vf-modal-row" style="display: flex; gap: 12px; align-items: center; margin-bottom: 16px;">
                <div class="vf-modal-avatar" style="background:#4f46e5; color:white; width:45px; height:45px; display:flex; align-items:center; justify-content:center; border-radius:8px; font-weight:bold;">${esc(mosqueInitials)}</div>
                <div>
                    <div class="vf-modal-mosque-name" style="font-weight: 700; font-size: 1.1rem;">${esc(d.mosque_name ?? '-')}</div>
                    <div class="vf-modal-mosque-sub" style="color: #64748b; font-size: 0.85rem;">${esc(d.city ?? '-')} · ${esc(d.founded ?? '-')} · ${esc(d.capacity ?? '-')} jamaah</div>
                    <span class="vf-status-badge ${esc(d.status)} vf-modal-status" style="margin-top: 4px; display: inline-block;">${esc(statusMap[d.status] || d.status)}</span>
                </div>
            </div>
            <div class="vf-modal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div class="vf-modal-field"><div class="vf-info-label" style="font-size:0.75rem; color:#64748b;">Imam</div><div class="vf-info-val" style="font-weight:600;">${esc(d.imam_name ?? '-')}</div></div>
                <div class="vf-modal-field"><div class="vf-info-label" style="font-size:0.75rem; color:#64748b;">Ketua</div><div class="vf-info-val" style="font-weight:600;">${esc(d.chairman_name ?? '-')}</div></div>
                <div class="vf-modal-field"><div class="vf-info-label" style="font-size:0.75rem; color:#64748b;">Email</div><div class="vf-info-val" style="font-weight:600;">${esc(d.email ?? '-')}</div></div>
                <div class="vf-modal-field"><div class="vf-info-label" style="font-size:0.75rem; color:#64748b;">Telepon</div><div class="vf-info-val" style="font-weight:600;">${esc(d.phone ?? '-')}</div></div>
                <div class="vf-modal-field"><div class="vf-info-label" style="font-size:0.75rem; color:#64748b;">Tanggal Daftar</div><div class="vf-info-val" style="font-weight:600;">${esc(formattedDate)}</div></div>
                <div class="vf-modal-field"><div class="vf-info-label" style="font-size:0.75rem; color:#64748b;">Status Pembayaran</div><div class="vf-info-val" style="font-weight:600;">${paymentBadgeText}</div></div>
            </div>

            <div class="vf-info-label" style="margin:16px 0 8px; font-weight:600;">Program Kegiatan</div>
            <div class="vf-tags" style="margin-bottom: 16px;">${programTags}</div>

            <div class="vf-info-label" style="margin:16px 0 8px; font-weight:600;">Transaksi Terakhir (Midtrans)</div>
            <div style="margin-bottom: 16px;">${paymentProofSection}</div>

            <div class="vf-info-label" style="margin:16px 0 8px; font-weight:600;">Riwayat Transaksi Akun Ini</div>
            <div>${historyListHTML}</div>
        `;

        document.getElementById('vfModalOverlay').classList.add('active');
    }

    function showSubDetailObj(sub) {
        const row = (label, val, style) => `
            <div>
                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 600;">${label}</div>
                <div style="font-size: 0.95rem; font-weight: 600; color: #334155; ${style || ''}">${esc(val)}</div>
            </div>`;

        document.getElementById('vfModalBody').innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0;">Rincian Transaksi Midtrans</h3>
                <button onclick="vfOpenDetail(${Number(sub.id)})" style="background: #e2e8f0; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; color: #334155;">← Kembali ke Profil</button>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                ${row('Order ID', sub.order_id)}
                ${row('Tanggal Dibuat', sub.date)}
                ${row('Waktu Pembayaran', sub.paid_at)}
                ${row('Metode Pembayaran', sub.method, 'text-transform: capitalize;')}
                ${row('Nominal', sub.amount, 'font-size: 1.1rem; font-weight: 700; color: #059669;')}
                ${row('Status', sub.status, sub.statusStyle)}
            </div>
        `;
    }

    const modalCloseBtn = document.getElementById('vfModalClose');
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', () => {
            document.getElementById('vfModalOverlay').classList.remove('active');
        });
    }

    const modalOverlay = document.getElementById('vfModalOverlay');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('active');
            }
        });
    }
    </script>
</body>
</html>