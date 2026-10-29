<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pendaftaran - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/verifSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="sa-page" id="saBody">

@php
    /**
     * Pilihan paket untuk kontrol manual: diambil dari pengaturan Super Admin (tabel payment_settings),
     * bukan angka tetap. Kode paket berformat "harga_jumlahBulan", mis. 150000_1.
     */
    $paketRows = \Illuminate\Support\Facades\DB::table('payment_settings')
        ->whereIn('code', ['package_monthly', 'package_yearly'])
        ->get()
        ->keyBy('code');

    $opsiPaket = [];
    foreach ([['package_monthly', 1, 'Paket 1 Bulan'], ['package_yearly', 12, 'Paket 1 Tahun']] as [$kodeSetting, $bulanPaket, $namaDefault]) {
        $row = $paketRows[$kodeSetting] ?? null;
        if (! $row) { continue; }

        $cfgPaket   = json_decode($row->config_data ?? '', true) ?: [];
        $hargaPaket = (int) ($cfgPaket['price'] ?? 0);
        if ($hargaPaket <= 0) { continue; }

        $opsiPaket[$hargaPaket . '_' . $bulanPaket] =
            ($row->method_name ?: $namaDefault) . ' · Rp ' . number_format($hargaPaket, 0, ',', '.');
    }

    $pendingCount = \App\Models\Mosque::where('status', 'pending')->count();
@endphp

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sa-sidebar" id="saSidebar">
        <div class="sa-brand">
            <div class="sa-brand-avatar">SA</div>
            <div class="sa-brand-info">
                <strong>MasjidKu Admin</strong>
                <span>Super Administrator</span>
            </div>
        </div>

        <nav class="sa-nav">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="sa-nav-label">Dashboard</span>
            </a>

            <a href="{{ route('superadmin.verifikasi') }}" class="sa-nav-item sa-nav-has-badge {{ request()->routeIs('superadmin.verifikasi*') ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <span class="sa-nav-label">Verifikasi Pendaftaran</span>
                @if($pendingCount > 0)
                    <span class="sa-nav-badge-number vf-nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('superadmin.manajemen-masjid') }}" class="sa-nav-item {{ request()->routeIs('superadmin.manajemen-masjid*') ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="sa-nav-label">Manajemen Masjid</span>
            </a>

            <a href="{{ route('superadmin.pembayaran.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.pembayaran*') ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid fa-credit-card"></i></span>
                <span class="sa-nav-label">Pembayaran</span>
            </a>

            <a href="{{ route('superadmin.pengguna') }}" class="sa-nav-item {{ request()->routeIs('superadmin.pengguna*') ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="sa-nav-label">Pengguna</span>
            </a>

            <a href="{{ route('superadmin.halaman-utama.edit') }}" class="sa-nav-item {{ request()->routeIs('superadmin.halaman-utama*') ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                <span class="sa-nav-label">Halaman Utama</span>
            </a>

            <a href="{{ route('superadmin.pengaturan') }}" class="sa-nav-item {{ request()->routeIs('superadmin.pengaturan*') ? 'active' : '' }}">
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

            {{-- ===== NOTIFIKASI ===== --}}
            @if (session('success'))
                <div class="vf-alert success">
                    <span><i class="fa-solid fa-circle-check"></i>&nbsp; {{ session('success') }}</span>
                    <button type="button" onclick="this.closest('.vf-alert').remove()" aria-label="Tutup">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="vf-alert error">
                    <span><i class="fa-solid fa-circle-exclamation"></i>&nbsp; {{ session('error') }}</span>
                    <button type="button" onclick="this.closest('.vf-alert').remove()" aria-label="Tutup">&times;</button>
                </div>
            @endif
            @if ($errors->any())
                <div class="vf-alert error">
                    <div>
                        @foreach ($errors->all() as $err)
                            <div><i class="fa-solid fa-circle-exclamation"></i>&nbsp; {{ $err }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== RINGKASAN ===== --}}
            <div class="vf-summary-grid">
                <div class="vf-summary-card amber">
                    <div class="vf-summary-icon">⏳</div>
                    <div>
                        <div class="vf-summary-val">{{ $totalPending ?? 0 }}</div>
                        <div class="vf-summary-label">Menunggu Verifikasi</div>
                    </div>
                </div>
                <div class="vf-summary-card green">
                    <div class="vf-summary-icon">✅</div>
                    <div>
                        <div class="vf-summary-val">{{ $totalApproved ?? 0 }}</div>
                        <div class="vf-summary-label">Disetujui</div>
                    </div>
                </div>
                <div class="vf-summary-card red">
                    <div class="vf-summary-icon">❌</div>
                    <div>
                        <div class="vf-summary-val">{{ $totalRejected ?? 0 }}</div>
                        <div class="vf-summary-label">Ditolak</div>
                    </div>
                </div>
            </div>

            {{-- ===== FILTER ===== --}}
            <div class="vf-filter-bar">
                <div class="vf-filter-tabs">
                    <button class="vf-filter-tab active" data-filter="semua">Semua ({{ $totalSemua ?? 0 }})</button>
                    <button class="vf-filter-tab" data-filter="pending">⏳ Pending ({{ $totalPending ?? 0 }})</button>
                    <button class="vf-filter-tab" data-filter="disetujui">✓ Disetujui ({{ $totalApproved ?? 0 }})</button>
                    <button class="vf-filter-tab" data-filter="ditolak">✕ Ditolak ({{ $totalRejected ?? 0 }})</button>
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
                            if (in_array($dbStatus, ['approved', 'aktif', 'disetujui'])) {
                                $filterStatus = 'disetujui';
                            } elseif (in_array($dbStatus, ['rejected', 'ditolak'])) {
                                $filterStatus = 'ditolak';
                            } else {
                                $filterStatus = 'pending';
                            }

                            $hasPendingPayment = strtolower(trim($p->payment_status ?? '')) === 'pending';
                            $tabStatus = $hasPendingPayment ? 'pending' : $filterStatus;

                            $pStatus = strtolower(trim($p->payment_status ?? $p->status_pembayaran ?? ''));
                            $isFree  = strtolower(trim($p->package_type ?? '')) === 'free';

                            $programsList = is_string($p->programs) ? json_decode($p->programs, true) : $p->programs;

                            $subs      = collect($p->subscriptions ?? []);
                            $latestSub = $subs->sortByDesc('created_at')->first();

                            // Jumlah transfer manual yang menunggu pengecekan bukti
                            $manualMenunggu = $subs->filter(fn ($s) =>
                                strtolower((string) data_get($s, 'transaction_status', data_get($s, 'status', ''))) === 'manual_pending'
                            )->count();

                            // Jika kode paket tersimpan sudah tidak ada di pengaturan (mis. harga diubah),
                            // tetap ditampilkan supaya pilihan tidak hilang dan tidak berubah jadi Free saat disimpan.
                            $opsiMasjid = $opsiPaket;
                            if (! empty($p->package_type) && $p->package_type !== 'free' && ! isset($opsiMasjid[$p->package_type])) {
                                $infoLama = \App\Support\PackagePricing::resolve($p->package_type);
                                $opsiMasjid[$p->package_type] = $infoLama['name'] . ' · Rp ' . number_format($infoLama['amount'], 0, ',', '.') . ' (kode lama)';
                            }
                        @endphp

                        <div class="vf-card {{ $tabStatus }}"
                             data-status="{{ $tabStatus }}"
                             data-search="{{ strtolower(($p->mosque_name ?? '') . ' ' . ($p->city ?? '') . ' ' . ($p->email ?? '')) }}">
                            <div class="vf-card-inner">

                                <div class="vf-card-header">
                                    <div class="vf-mosque-avatar">{{ strtoupper(substr($p->mosque_name ?? 'MS', 0, 2)) }}</div>
                                    <div class="vf-mosque-title">
                                        <div class="vf-mosque-name-row">
                                            <span class="vf-mosque-name">{{ $p->mosque_name ?? 'Tanpa Nama' }}</span>
                                            <span class="vf-status-badge {{ $filterStatus }}">
                                                @if($filterStatus === 'pending') ⏳ Menunggu
                                                @elseif($filterStatus === 'disetujui') ✓ Disetujui
                                                @else ✕ Ditolak
                                                @endif
                                            </span>
                                        </div>
                                        <div class="vf-mosque-sub">
                                            {{ $p->city ?? '-' }} · {{ $p->founded ?? '-' }} · {{ $p->capacity ?? '-' }} jamaah
                                        </div>
                                    </div>
                                </div>

                                <div class="vf-info-grid">
                                    <div><div class="vf-info-label">Imam</div><div class="vf-info-val">{{ $p->imam_name ?? '-' }}</div></div>
                                    <div><div class="vf-info-label">Ketua</div><div class="vf-info-val">{{ $p->chairman_name ?? '-' }}</div></div>
                                    <div><div class="vf-info-label">Email</div><div class="vf-info-val">{{ $p->email ?? '-' }}</div></div>
                                    <div><div class="vf-info-label">Telepon</div><div class="vf-info-val">{{ $p->phone ?? '-' }}</div></div>
                                </div>

                                {{-- Status pembayaran --}}
                                <div class="vf-payment-info">
                                    <div>
                                        <span class="lbl">Pembayaran:</span>
                                        @if($isFree)
                                            <span class="vf-pill info"><i class="fa-solid fa-gift"></i> Paket Free / Gratis</span>
                                        @elseif($pStatus === 'pending')
                                            <span class="vf-pill warn"><i class="fa-solid fa-clock"></i> Menunggu Pembayaran (Midtrans)</span>
                                        @elseif(in_array($pStatus, ['approved', 'paid', 'settlement', 'capture']))
                                            <span class="vf-pill ok"><i class="fa-solid fa-check"></i> Lunas / Disetujui</span>
                                        @elseif(in_array($pStatus, ['expire', 'cancel', 'deny', 'failure', 'failed']))
                                            <span class="vf-pill bad"><i class="fa-solid fa-triangle-exclamation"></i> Gagal / Kedaluwarsa</span>
                                        @else
                                            <span class="vf-pill bad"><i class="fa-solid fa-xmark"></i> Belum Bayar</span>
                                        @endif

                                        @if($manualMenunggu > 0)
                                            <span class="vf-pill warn" style="margin-left:6px;">
                                                <i class="fa-solid fa-image"></i> {{ $manualMenunggu }} bukti transfer perlu dicek
                                            </span>
                                        @endif
                                    </div>

                                    @if($latestSub && ! empty(data_get($latestSub, 'order_id')))
                                        <span class="vf-order-ref"><i class="fa-solid fa-receipt"></i> {{ data_get($latestSub, 'order_id') }}</span>
                                    @endif
                                </div>

                                {{-- Kontrol manual Super Admin --}}
                                <div class="vf-manual">
                                    <div class="vf-manual-title">
                                        <i class="fa-solid fa-sliders"></i> Kontrol paket & donasi manual (tanpa Midtrans)
                                    </div>
                                    <form action="{{ route('superadmin.mosque.subscription', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="field">
                                            <select name="package_type" aria-label="Paket">
                                                <option value="free" {{ $p->package_type == 'free' ? 'selected' : '' }}>Free (nonaktif donasi)</option>
                                                @foreach($opsiMasjid as $kodeOpsi => $labelOpsi)
                                                    <option value="{{ $kodeOpsi }}" {{ $p->package_type == $kodeOpsi ? 'selected' : '' }}>{{ $labelOpsi }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="field">
                                            <select name="payment_status" aria-label="Status pembayaran">
                                                <option value="approved" {{ $p->payment_status == 'approved' ? 'selected' : '' }}>Approved (aktif)</option>
                                                <option value="pending"  {{ $p->payment_status == 'pending'  ? 'selected' : '' }}>Pending (menunggu Midtrans)</option>
                                                <option value="failed"   {{ $p->payment_status == 'failed'   ? 'selected' : '' }}>Failed (gagal / kedaluwarsa)</option>
                                                <option value="unpaid"   {{ $p->payment_status == 'unpaid'   ? 'selected' : '' }}>Unpaid (nonaktif)</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="vf-btn-save">
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

                                <div class="vf-actions">
                                    <div class="vf-actions-left">
                                        <button type="button" class="vf-btn-detail" onclick="vfOpenDetail({{ $p->id }})">
                                            <i class="fa-solid fa-eye"></i> Lihat Detail
                                        </button>

                                        @if($filterStatus === 'pending' || $hasPendingPayment)
                                            <form method="POST" action="{{ route('superadmin.verifikasi.approve', $p->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="vf-btn-approve"><i class="fa-solid fa-check"></i> Setujui</button>
                                            </form>

                                            <form method="POST" action="{{ route('superadmin.verifikasi.reject', $p->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="vf-btn-reject"><i class="fa-solid fa-xmark"></i> Tolak</button>
                                            </form>
                                        @elseif($filterStatus === 'disetujui')
                                            <span class="vf-status-label green"><i class="fa-solid fa-circle-check"></i> Sudah disetujui</span>
                                        @else
                                            <span class="vf-status-label red"><i class="fa-solid fa-xmark"></i> Pendaftaran ditolak</span>
                                        @endif
                                    </div>

                                    {{-- Pesan konfirmasi disimpan di data-msg agar aman dari tanda kutip --}}
                                    <form method="POST"
                                          action="{{ route('superadmin.verifikasi.destroy', $p->id) }}"
                                          data-msg="Hapus data masjid '{{ $p->mosque_name ?? 'ini' }}' secara permanen? Tindakan ini tidak bisa dibatalkan."
                                          onsubmit="return confirm(this.dataset.msg);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vf-btn-delete"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="vf-empty" id="vfEmpty" style="display:none;">
                <div class="vf-empty-icon">🔍</div>
                <div class="vf-empty-text">Tidak ada pendaftaran ditemukan</div>
            </div>

        </main>
    </div>

    <button class="help-fab" aria-label="Bantuan">?</button>

    {{-- ===== MODAL DETAIL ===== --}}
    <div class="vf-modal-overlay" id="vfModalOverlay">
        <div class="vf-modal" role="dialog" aria-modal="true" aria-labelledby="vfModalTitle">
            <div class="vf-modal-head">
                <span class="vf-modal-title" id="vfModalTitle">Detail Pendaftaran</span>
                <button class="vf-modal-close" id="vfModalClose" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="vf-modal-body" id="vfModalBody"></div>
        </div>
    </div>

    {{-- Pembesar foto bukti --}}
    <div class="vf-lightbox" id="vfLightbox">
        <img id="vfLightboxImg" src="" alt="Bukti pembayaran">
    </div>

    <script>
    // Jika $pendaftaran berupa paginator, ambil isi 'data'-nya agar .find() tidak error.
    const rawDetail  = @json($pendaftaran ?? []);
    const detailData = Array.isArray(rawDetail)
        ? rawDetail
        : (rawDetail && Array.isArray(rawDetail.data) ? rawDetail.data : Object.values(rawDetail || {}));

    /* ---------- Helper ---------- */

    // Escape HTML agar data pendaftar tidak bisa menyisipkan markup/script
    function esc(v) {
        return String(v ?? '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function fmtDate(v, withTime) {
        if (!v) return '-';
        const dt = new Date(v);
        if (isNaN(dt.getTime())) return '-';
        const opt = { day: '2-digit', month: 'short', year: 'numeric' };
        if (withTime) { opt.hour = '2-digit'; opt.minute = '2-digit'; }
        return dt.toLocaleString('id-ID', opt);
    }

    function fmtRupiah(n) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n || 0);
    }

    // Status transaksi (Midtrans + manual) -> label + warna pill
    function txStatus(raw) {
        const st = String(raw || '').toLowerCase();
        if (['settlement', 'capture', 'paid', 'approved', 'disetujui', 'lunas', 'manual_approved', 'manual_paid'].includes(st))
            return { label: 'Lunas', cls: 'ok' };
        if (st === 'manual_pending') return { label: 'Menunggu Verifikasi', cls: 'warn' };
        if (st === 'pending')        return { label: 'Menunggu Pembayaran', cls: 'warn' };
        if (st === 'expire')         return { label: 'Kedaluwarsa', cls: 'bad' };
        if (['cancel', 'deny', 'failure', 'failed', 'rejected', 'ditolak', 'manual_rejected'].includes(st))
            return { label: 'Gagal / Ditolak', cls: 'bad' };
        return { label: st || '-', cls: 'mute' };
    }

    function isManualTx(sub) {
        return String(sub.payment_type || '').toLowerCase().includes('manual')
            || String(sub.transaction_status || sub.status || '').toLowerCase().startsWith('manual');
    }

    // Ambil URL foto bukti dari beberapa kemungkinan nama kolom.
    // >>> Sesuaikan daftar ini bila kolom di tabel subscription punya nama lain. <<<
    function proofUrl(sub) {
    // 1) kolom khusus (kalau ada)
    let raw = sub.payment_proof || sub.proof_image || sub.proof || sub.bukti_transfer
           || sub.bukti_pembayaran || sub.proof_path || sub.receipt || null;

    // 2) cadangan: path disimpan di dalam raw_notification (JSON)
    if (!raw && sub.raw_notification) {
        try {
            const n = typeof sub.raw_notification === 'string'
                ? JSON.parse(sub.raw_notification)
                : sub.raw_notification;
            raw = n.proof_of_payment || n.payment_proof || n.proof || null;
        } catch (e) { /* abaikan JSON rusak */ }
    }

    if (!raw) return null;
    const s = String(raw);
    if (/^(https?:)?\/\//.test(s) || s.startsWith('/') || s.startsWith('data:')) return s;
    return '/storage/' + s.replace(/^public\//, '');
}

    function field(label, valueHtml) {
        return `<div class="vf-dt-field"><div class="l">${label}</div><div class="v">${valueHtml}</div></div>`;
    }

    /* ---------- Filter & pencarian ---------- */

    const tabs  = document.querySelectorAll('.vf-filter-tab');
    const cards = document.querySelectorAll('.vf-card');
    const searchInput = document.getElementById('vfSearch');

    function filterCards() {
        const filter = document.querySelector('.vf-filter-tab.active').dataset.filter;
        const search = searchInput.value.trim().toLowerCase();
        let visible = 0;
        cards.forEach(card => {
            const show = (filter === 'semua' || card.dataset.status === filter)
                      && (!search || card.dataset.search.includes(search));
            card.style.display = show ? 'block' : 'none';
            if (show) visible++;
        });
        const emptyEl = document.getElementById('vfEmpty');
        if (emptyEl) emptyEl.style.display = (visible === 0 && cards.length) ? 'flex' : 'none';
    }

    tabs.forEach(tab => tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        filterCards();
    }));
    if (searchInput) searchInput.addEventListener('input', filterCards);

    /* ---------- Modal detail ---------- */

    let currentId   = null;
    let currentSubs = [];

    function vfOpenDetail(id) {
        const d = detailData.find(x => Number(x.id) === Number(id));
        if (!d) return;
        currentId = d.id;

        document.getElementById('vfModalTitle').textContent = 'Detail — ' + (d.mosque_name || '');

        // Status pendaftaran
        const dbSt = String(d.status || '').toLowerCase();
        const reg  = ['approved', 'aktif', 'disetujui'].includes(dbSt) ? { t: '✓ Disetujui', c: 'ok' }
                   : ['rejected', 'ditolak'].includes(dbSt)            ? { t: '✕ Ditolak',   c: 'bad' }
                   :                                                     { t: '⏳ Menunggu',  c: 'warn' };

        // Status pembayaran akun
        const pStatus = String(d.payment_status || d.status_pembayaran || '').toLowerCase();
        let payPill = '<span class="vf-pill bad"><i class="fa-solid fa-xmark"></i> Belum Bayar</span>';
        if (String(d.package_type || '').toLowerCase() === 'free')
            payPill = '<span class="vf-pill info"><i class="fa-solid fa-gift"></i> Paket Free</span>';
        else if (['approved', 'paid', 'lunas', 'disetujui', 'settlement', 'capture'].includes(pStatus))
            payPill = '<span class="vf-pill ok"><i class="fa-solid fa-check"></i> Lunas / Disetujui</span>';
        else if (pStatus === 'pending')
            payPill = '<span class="vf-pill warn"><i class="fa-solid fa-clock"></i> Menunggu Pembayaran</span>';
        else if (['rejected', 'ditolak', 'expire', 'cancel', 'deny', 'failure', 'failed'].includes(pStatus))
            payPill = '<span class="vf-pill bad"><i class="fa-solid fa-triangle-exclamation"></i> Gagal / Kedaluwarsa</span>';

        // Program kegiatan
        let programs = [];
        try { programs = typeof d.programs === 'string' ? JSON.parse(d.programs) : (d.programs || []); } catch (e) {}
        if (!Array.isArray(programs)) programs = [];
        const programTags = programs.length
            ? programs.map(p => `<span class="vf-tag">${esc(p)}</span>`).join('')
            : '<span style="color:#9ca3af; font-style:italic; font-size:.9rem;">Tidak ada program yang dipilih.</span>';

        // Riwayat transaksi: terbaru di atas
        currentSubs = (d.subscriptions || []).slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        let historyHTML = `<div class="vf-empty-box"><i class="fa-solid fa-receipt"></i>Belum ada riwayat transaksi.</div>`;
        if (currentSubs.length) {
            historyHTML = '<div class="vf-tx-list">' + currentSubs.map((sub, i) => {
                const st     = txStatus(sub.transaction_status || sub.status);
                const manual = isManualTx(sub);
                const method = (sub.payment_type || '-').replace(/_/g, ' ');
                const proof  = proofUrl(sub);

                let thumb = '';
                if (manual) {
                    thumb = proof
                        ? `<img class="vf-proof-thumb" src="${esc(proof)}" alt="Bukti transfer" loading="lazy">`
                        : `<div class="vf-proof-none"><i class="fa-regular fa-image"></i>Belum ada bukti</div>`;
                }

                return `
                <button type="button" class="vf-tx" onclick="vfShowTx(${i})">
                    <div class="vf-tx-icon ${manual ? 'manual' : ''}">
                        <i class="fa-solid ${manual ? 'fa-hand-holding-dollar' : 'fa-credit-card'}"></i>
                    </div>
                    <div class="vf-tx-main">
                        <div class="vf-tx-order">${esc(sub.order_id || '-')}</div>
                        <div class="vf-tx-meta">${esc(method)} · ${esc(fmtDate(sub.created_at, true))}</div>
                    </div>
                    ${thumb}
                    <div class="vf-tx-side">
                        <div class="vf-tx-amount">${esc(fmtRupiah(sub.gross_amount ?? sub.amount))}</div>
                        <span class="vf-pill ${st.cls}">${esc(st.label)}</span>
                    </div>
                </button>`;
            }).join('') + '</div>';
        }

        document.getElementById('vfModalBody').innerHTML = `
            <div class="vf-dt-hero">
                <div class="vf-dt-hero-avatar">${esc((d.mosque_name || 'MS').substring(0, 2).toUpperCase())}</div>
                <div>
                    <div class="vf-dt-hero-name">${esc(d.mosque_name ?? '-')}</div>
                    <div class="vf-dt-hero-sub">${esc(d.city ?? '-')} · ${esc(d.founded ?? '-')} · ${esc(d.capacity ?? '-')} jamaah</div>
                    <span class="vf-pill ${reg.c}">${reg.t}</span>
                </div>
            </div>

            <div class="vf-dt-grid">
                ${field('Imam', esc(d.imam_name ?? '-'))}
                ${field('Ketua', esc(d.chairman_name ?? '-'))}
                ${field('Email', esc(d.email ?? '-'))}
                ${field('Telepon', esc(d.phone ?? '-'))}
                ${field('Tanggal daftar', esc(fmtDate(d.created_at, false)))}
                ${field('Status pembayaran', payPill)}
            </div>

            <div class="vf-dt-section"><i class="fa-solid fa-list-check"></i> Program kegiatan</div>
            <div class="vf-tags">${programTags}</div>

            <div class="vf-dt-section"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat pembayaran & bukti transfer</div>
            ${historyHTML}
        `;

        document.getElementById('vfModalOverlay').classList.add('active');
    }

    // Rincian satu transaksi (dengan foto bukti besar untuk transfer manual)
    function vfShowTx(i) {
        const sub = currentSubs[i];
        if (!sub) return;

        const st     = txStatus(sub.transaction_status || sub.status);
        const manual = isManualTx(sub);
        const proof  = proofUrl(sub);

        let proofSection = '';
        if (manual) {
            proofSection = `
                <div class="vf-dt-section"><i class="fa-solid fa-image"></i> Bukti pembayaran</div>
                ${proof ? `
                    <img class="vf-proof-img" src="${esc(proof)}" alt="Bukti pembayaran" onclick="vfZoom(this.src)">
                    <div class="vf-proof-actions">
                        <a class="vf-btn-link" href="${esc(proof)}" target="_blank" rel="noopener"><i class="fa-solid fa-up-right-from-square"></i> Buka ukuran penuh</a>
                        <a class="vf-btn-link" href="${esc(proof)}" download><i class="fa-solid fa-download"></i> Unduh</a>
                    </div>`
                : `<div class="vf-empty-box"><i class="fa-regular fa-image"></i>Pengguna belum mengunggah bukti pembayaran.</div>`}
            `;
        }

        document.getElementById('vfModalBody').innerHTML = `
            <div class="vf-back-row">
                <div>
                    <div class="cap">Rincian transaksi</div>
                    <div class="ttl">${esc(sub.order_id || '-')}</div>
                </div>
                <button type="button" class="vf-btn-detail" onclick="vfOpenDetail(${Number(currentId)})">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
            </div>

            <div class="vf-dt-hero split">
                <div>
                    <div class="vf-dt-hero-cap">Nominal</div>
                    <div class="vf-dt-hero-amount">${esc(fmtRupiah(sub.gross_amount ?? sub.amount))}</div>
                </div>
                <span class="vf-pill ${st.cls}">${esc(st.label)}</span>
            </div>

            <div class="vf-dt-grid">
                ${field('Tanggal dibuat', esc(fmtDate(sub.created_at, true)))}
                ${field('Waktu pembayaran', esc(fmtDate(sub.paid_at || sub.settlement_time, true)))}
                ${field('Metode', `<span style="text-transform:capitalize;">${esc((sub.payment_type || '-').replace(/_/g, ' '))}</span>`)}
                ${field('Jenis', manual ? 'Transfer manual' : 'Midtrans')}
            </div>

            ${proofSection}
        `;
    }

    /* ---------- Lightbox & penutup modal ---------- */

    const overlay  = document.getElementById('vfModalOverlay');
    const lightbox = document.getElementById('vfLightbox');

    function vfZoom(url) {
        document.getElementById('vfLightboxImg').src = url;
        lightbox.classList.add('active');
    }

    document.getElementById('vfModalClose').addEventListener('click', () => overlay.classList.remove('active'));
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('active'); });
    lightbox.addEventListener('click', () => lightbox.classList.remove('active'));

    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if (lightbox.classList.contains('active')) lightbox.classList.remove('active');
        else overlay.classList.remove('active');
    });
    </script>
</body>
</html>