<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Aktivasi - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paymentmasjid/payment.css') }}">
</head>
<body>

@php
    $pendingRenewal = session('pending_renewal');
    $rawPackage = $pendingRenewal['package'] ?? ($mosque->package_type ?? null);

    // Masjid yang masih "free" / kode paket kosong -> pakai paket bulanan (harga dari Super Admin) sebagai default pembelian
    $kodePaket = (empty($rawPackage) || $rawPackage === 'free')
        ? \App\Support\PackagePricing::defaultCode()
        : $rawPackage;

    // Harga & nama paket dari PackagePricing (mengikuti pengaturan Super Admin).
    // Bila controller sudah mengirim $amount / $packageName, nilai itu yang dipakai.
    $paketHarga = \App\Support\PackagePricing::resolve($kodePaket);
    $nominal    = isset($amount) ? (int) $amount : $paketHarga['amount'];
    $namaPaket  = $packageName ?? $paketHarga['name'];

    $midtransSetting = \Illuminate\Support\Facades\DB::table('payment_settings')->where('code', 'midtrans')->first();
    $manualSetting   = \Illuminate\Support\Facades\DB::table('payment_settings')->where('code', 'manual')->first();
    $qrisSetting     = \Illuminate\Support\Facades\DB::table('payment_settings')->where('code', 'qris')->first();

    $manualConfig = $manualSetting ? (json_decode($manualSetting->config_data, true) ?: []) : [];
    $qrisConfig   = $qrisSetting ? (json_decode($qrisSetting->config_data, true) ?: []) : [];

    // Daftar rekening (mendukung format lama: satu rekening)
    $accounts = $manualConfig['accounts'] ?? [];
    if (!$accounts && !empty($manualConfig['bank_name'])) {
        $accounts = [[
            'bank_name'      => $manualConfig['bank_name'],
            'account_number' => $manualConfig['account_number'] ?? '',
            'account_holder' => $manualConfig['account_holder'] ?? '',
        ]];
    }

    $qrisImage = !empty($qrisConfig['qris_image']) ? asset('storage/' . $qrisConfig['qris_image']) : null;

    $isMidtransActive = $midtransSetting ? (bool) $midtransSetting->is_active : true;
    $isManualActive   = (bool) ($manualSetting && $manualSetting->is_active && count($accounts) > 0);
    $isQrisActive     = (bool) ($qrisSetting && $qrisSetting->is_active && $qrisImage);

    $activeCount = (int) $isMidtransActive + (int) $isManualActive + (int) $isQrisActive;
    $defaultTab  = $isMidtransActive ? 'midtrans' : ($isManualActive ? 'manual' : ($isQrisActive ? 'qris' : null));
    $firstOffline = $isManualActive ? 'manual' : 'qris';
    $offlineTab   = in_array($defaultTab, ['manual', 'qris']) ? $defaultTab : $firstOffline;

    $isPending = $mosque->payment_status === 'pending';
@endphp

<div class="pay-card">
    <div class="card-band"></div>
    <div class="card-inner">

        {{-- Langkah --}}
        <ol class="steps">
            <li class="done" data-n="1">Pilih paket</li>
            @if($isPending)
                <li class="done" data-n="2">Bayar</li>
                <li class="now" data-n="3">Verifikasi</li>
            @else
                <li class="now" data-n="2">Bayar</li>
                <li data-n="3">Aktif</li>
            @endif
        </ol>

        <div class="pay-head">
            <div class="pay-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 13h6"/><path d="M9 17h4"/></svg>
            </div>
            <h1 class="pay-title">Status Pembayaran Dan Aktivasi</h1>
        </div>
        <p class="pay-desc">Selesaikan pembayaran biaya aktivasi platform untuk membuka akses dashboard dan modul publik masjid <strong>{{ $mosque->mosque_name }}</strong>.</p>

        {{-- NOTIFIKASI --}}
        @if(session('success'))<div class="alert ok">✔ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert err">⚠ {{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert err">⚠ {{ $errors->first() }}</div>@endif

        @if($isPending)
            {{-- ===== MENUNGGU VERIFIKASI ===== --}}
            <div class="wait">
                <div class="ring">⏳</div>
                <h4>Menunggu Verifikasi Super Admin</h4>
                <p>Bukti pembayaran Anda telah dikirim dan sedang dalam pengecekan oleh Super Admin. Mohon tunggu beberapa saat hingga akun Anda diaktifkan.</p>
            </div>

            <div class="foot-link"><a href="{{ route('dashboard') }}" class="btn-home">Kembali ke Dashboard</a></div>

        @else
            {{-- ===== RINGKASAN TAGIHAN ===== --}}
            <div class="order">
                <div class="order-row"><span>Masjid</span><b>{{ $mosque->mosque_name }}</b></div>
                <div class="order-row"><span>Jenis paket</span><b>{{ $namaPaket }}</b></div>
                <div class="order-total"><span>Total tagihan</span><strong>Rp {{ number_format($nominal, 0, ',', '.') }}</strong></div>
            </div>

            @if($nominal <= 0)
                <div class="alert err">⚠ Harga paket belum diatur oleh Super Admin. Silakan hubungi Super Admin.</div>
            @endif

            @if($activeCount === 0)
                <div class="alert info">Belum ada metode pembayaran yang aktif. Silakan hubungi Super Admin.</div>
            @endif

            {{-- ===== PILIH METODE ===== --}}
            @if($activeCount > 1)
                <p class="label-small">Pilih cara bayar</p>
                <div class="methods">
                    @if($isMidtransActive)
                    <button type="button" class="method {{ $defaultTab === 'midtrans' ? 'active' : '' }}" onclick="switchTab('midtrans', this)">
                        <span class="rec">REKOMENDASI</span>
                        <span class="chk">✓</span>
                        <span class="m-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="3"/><path d="M2 10h20"/></svg></span>
                        <b>Bayar Otomatis</b>
                        <small>Midtrans, aktif langsung setelah bayar</small>
                    </button>
                    @endif

                    @if($isQrisActive)
                    <button type="button" class="method {{ $defaultTab === 'qris' ? 'active' : '' }}" onclick="switchTab('qris', this)">
                        <span class="chk">✓</span>
                        <span class="m-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v.01M14 20h3M20 17v4"/></svg></span>
                        <b>QRIS</b>
                        <small>Scan, lalu kirim bukti bayar</small>
                    </button>
                    @endif

                    @if($isManualActive)
                    <button type="button" class="method {{ $defaultTab === 'manual' ? 'active' : '' }}" onclick="switchTab('manual', this)">
                        <span class="chk">✓</span>
                        <span class="m-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v8M9 10v8M15 10v8M19 10v8"/><path d="M3 21h18"/></svg></span>
                        <b>Transfer Bank</b>
                        <small>Kirim foto bukti transfer</small>
                    </button>
                    @endif
                </div>
            @endif

            {{-- ===== MIDTRANS ===== --}}
            @if($isMidtransActive)
            <div id="section-midtrans" class="payment-section {{ $defaultTab === 'midtrans' ? 'active' : '' }}">
                <div class="gate">
                    <div class="gate-top">
                        <div class="gate-logo">ID</div>
                        <div><b>Midtrans Payment Gateway</b><small>Pilih cara bayar setelah menekan tombol</small></div>
                    </div>
                    <div class="chips"><span>QRIS</span><span>Virtual Account</span><span>E-Wallet</span><span>Kartu Kredit</span></div>
                    <div class="secure">🔒 Pembayaran diproses aman oleh Midtrans</div>
                </div>
                <button type="button" class="btn-submit" id="pay-button" @if($nominal <= 0) disabled @endif>Bayar Sekarang (Otomatis)</button>
            </div>
            @endif

            {{-- ===== QRIS & TRANSFER BANK (satu form bukti pembayaran) ===== --}}
            @if($isManualActive || $isQrisActive)
            <div id="section-offline" class="payment-section {{ in_array($defaultTab, ['manual', 'qris']) ? 'active' : '' }}">
                <form action="{{ route('payment.manual.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="payment_method" id="payMethod" value="{{ $offlineTab }}">

                    @if($isManualActive)
                    <div class="pane" id="pane-manual" style="{{ $offlineTab === 'manual' ? '' : 'display:none' }}">
                        @foreach($accounts as $a)
                            <div class="acct">
                                <small>Transfer ke</small>
                                <div class="bank">{{ $a['bank_name'] }}</div>
                                <small>Nomor rekening</small>
                                <div class="num-row">
                                    <div class="num">{{ $a['account_number'] }}</div>
                                    <button type="button" class="copy" data-copy="{{ $a['account_number'] }}">Salin</button>
                                </div>
                                @if(!empty($a['account_holder']))
                                    <small>Atas nama</small>
                                    <div class="an">{{ $a['account_holder'] }}</div>
                                @endif
                            </div>
                        @endforeach

                        <ol class="howto">
                            <li>Pilih salah satu rekening, lalu transfer sesuai total tagihan <b>Rp {{ number_format($nominal, 0, ',', '.') }}</b>.</li>
                            <li>Foto atau screenshot bukti transfernya.</li>
                            <li>Unggah fotonya di bawah, lalu kirim untuk diverifikasi.</li>
                        </ol>
                    </div>
                    @endif

                    @if($isQrisActive)
                    <div class="pane" id="pane-qris" style="{{ $offlineTab === 'qris' ? '' : 'display:none' }}">
                        <div class="qris-box">
                            <small>Scan dengan e-wallet atau m-banking</small>
                            @if(!empty($qrisConfig['merchant_name']))
                                <div class="merchant">{{ $qrisConfig['merchant_name'] }}</div>
                            @endif
                            <img src="{{ $qrisImage }}" alt="Kode QRIS">
                            <a href="{{ $qrisImage }}" download="qris.png" class="qris-dl">Unduh gambar QRIS</a>
                        </div>

                        <ol class="howto">
                            <li>Buka aplikasi e-wallet atau m-banking, lalu scan QRIS di atas.</li>
                            <li>Masukkan nominal tepat <b>Rp {{ number_format($nominal, 0, ',', '.') }}</b>.</li>
                            <li>Screenshot bukti pembayaran, unggah di bawah, lalu kirim untuk diverifikasi.</li>
                        </ol>
                    </div>
                    @endif

                    <label class="drop" id="drop">
                        <img id="preview" alt="Pratinjau bukti pembayaran">
                        <div class="d-ico">📷</div>
                        <b id="dropText">Pilih foto bukti pembayaran</b>
                        <small>Foto atau screenshot (JPG, PNG)</small>
                        <input type="file" name="proof_of_payment" id="proof" accept="image/*" required>
                    </label>

                    <button type="submit" class="btn-submit blue" @if($nominal <= 0) disabled @endif>Kirim Bukti Pembayaran</button>
                </form>
            </div>
            @endif

            <div class="foot-link">
                <a href="{{ route('masjid.cancel') }}" onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?');" class="btn-cancel">Batalkan & Kembali</a>
            </div>
        @endif

    </div>
</div>

<script>
    // Ganti metode pembayaran
    function switchTab(type, el) {
        document.querySelectorAll('.method').forEach(function (b) { b.classList.remove('active'); });
        document.querySelectorAll('.payment-section').forEach(function (s) { s.classList.remove('active'); });

        var offline = type !== 'midtrans';
        var section = document.getElementById(offline ? 'section-offline' : 'section-midtrans');
        if (section) section.classList.add('active');

        if (offline) {
            document.querySelectorAll('.pane').forEach(function (p) {
                p.style.display = (p.id === 'pane-' + type) ? '' : 'none';
            });
            var pm = document.getElementById('payMethod');
            if (pm) pm.value = type;
        }
        if (el) el.classList.add('active');
    }

    // Salin nomor rekening (bisa lebih dari satu)
    document.querySelectorAll('.copy[data-copy]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.dataset.copy;
            var done = function () { btn.textContent = 'Tersalin ✓'; setTimeout(function () { btn.textContent = 'Salin'; }, 1800); };
            if (navigator.clipboard) { navigator.clipboard.writeText(text).then(done); }
            else { var t = document.createElement('textarea'); t.value = text; document.body.appendChild(t); t.select(); document.execCommand('copy'); t.remove(); done(); }
        });
    });

    // Pratinjau foto bukti pembayaran
    var proof = document.getElementById('proof');
    if (proof) proof.addEventListener('change', function () {
        var f = proof.files[0], drop = document.getElementById('drop');
        if (!f) { drop.classList.remove('has'); return; }
        document.getElementById('dropText').textContent = f.name;
        var r = new FileReader();
        r.onload = function (e) { document.getElementById('preview').src = e.target.result; drop.classList.add('has'); };
        r.readAsDataURL(f);
    });
</script>

{{-- Midtrans Snap (otomatis memakai URL sandbox / production sesuai config) --}}
@if($isMidtransActive)
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    const payBtn = document.getElementById('pay-button');
    if (payBtn) {
        payBtn.onclick = function () {
            let btn = this;
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            fetch('{{ route("masjid.payment.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            alert("Pembayaran berhasil!");
                            window.location.href = "{{ route('dashboard') }}";
                        },
                        onPending: function (result) {
                            alert("Menunggu penyelesaian pembayaran Anda.");
                            location.reload();
                        },
                        onError: function (result) {
                            alert("Pembayaran gagal!");
                            location.reload();
                        },
                        onClose: function () {
                            window.location.href = "{{ route('masjid.cancel') }}";
                        }
                    });
                } else {
                    alert('Gagal memuat token pembayaran: ' + (data.error || 'Terjadi kesalahan'));
                    btn.disabled = false;
                    btn.textContent = 'Bayar Sekarang (Otomatis)';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi server.');
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang (Otomatis)';
            });
        };
    }
</script>
@endif

</body>
</html>