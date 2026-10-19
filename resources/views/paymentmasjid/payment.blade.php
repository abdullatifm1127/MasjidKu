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
    $rawPackage = $pendingRenewal['package'] ?? ($mosque->package_type ?? '100000_1');

    $nominal = 100000;
    if ($rawPackage === '1000000_12') {
        $nominal = 1000000;
    } elseif (str_contains($rawPackage, '_')) {
        $nominal = (int) explode('_', $rawPackage)[0];
    }

    // Lama langganan dibaca dari kode paket (harga_bulan)
    $bulan = str_contains($rawPackage, '_') ? (int) explode('_', $rawPackage)[1] : 1;
    $bulan = $bulan > 0 ? $bulan : 1;
    $namaPaket = $bulan % 12 === 0 ? 'Langganan ' . ($bulan / 12) . ' Tahun' : 'Langganan ' . $bulan . ' Bulan';

    $midtransSetting = \Illuminate\Support\Facades\DB::table('payment_settings')->where('code', 'midtrans')->first();
    $manualSetting   = \Illuminate\Support\Facades\DB::table('payment_settings')->where('code', 'manual')->first();

    $isMidtransActive = $midtransSetting ? $midtransSetting->is_active : true;
    $isManualActive   = $manualSetting ? $manualSetting->is_active : false;
    $manualConfig     = $manualSetting ? json_decode($manualSetting->config_data, true) : [];
    $isPending        = $mosque->payment_status === 'pending';
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

            @if(!$isMidtransActive && !$isManualActive)
                <div class="alert info">Belum ada metode pembayaran yang aktif. Silakan hubungi Super Admin.</div>
            @endif

            {{-- ===== PILIH METODE ===== --}}
            @if($isMidtransActive && $isManualActive)
                <p class="label-small">Pilih cara bayar</p>
                <div class="methods">
                    <button type="button" class="method active" onclick="switchTab('midtrans', this)">
                        <span class="rec">REKOMENDASI</span>
                        <span class="chk">✓</span>
                        <span class="m-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="3"/><path d="M2 10h20"/></svg></span>
                        <b>Bayar Otomatis</b>
                        <small>Midtrans, aktif langsung setelah bayar</small>
                    </button>
                    <button type="button" class="method" onclick="switchTab('manual', this)">
                        <span class="chk">✓</span>
                        <span class="m-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v8M9 10v8M15 10v8M19 10v8"/><path d="M3 21h18"/></svg></span>
                        <b>Transfer Manual</b>
                        <small>Kirim foto bukti transfer</small>
                    </button>
                </div>
            @endif

            {{-- ===== MIDTRANS ===== --}}
            <div id="section-midtrans" class="payment-section @if($isMidtransActive) active @endif">
                <div class="gate">
                    <div class="gate-top">
                        <div class="gate-logo">ID</div>
                        <div><b>Midtrans Payment Gateway</b><small>Pilih cara bayar setelah menekan tombol</small></div>
                    </div>
                    <div class="chips"><span>QRIS</span><span>Virtual Account</span><span>E-Wallet</span><span>Kartu Kredit</span></div>
                    <div class="secure">🔒 Pembayaran diproses aman oleh Midtrans</div>
                </div>
                <button type="button" class="btn-submit" id="pay-button">Bayar Sekarang (Otomatis)</button>
            </div>

            {{-- ===== TRANSFER MANUAL ===== --}}
            <div id="section-manual" class="payment-section @if(!$isMidtransActive && $isManualActive) active @endif">
                <form action="{{ route('payment.manual.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="acct">
                        <small>Transfer ke</small>
                        <div class="bank">{{ $manualConfig['bank_name'] ?? 'BSI' }}</div>
                        <small>Nomor rekening</small>
                        <div class="num-row">
                            <div class="num" id="acctNum">{{ $manualConfig['account_number'] ?? '1234567890' }}</div>
                            <button type="button" class="copy" id="copyBtn">Salin</button>
                        </div>
                        <small>Atas nama</small>
                        <div class="an">{{ $manualConfig['account_holder'] ?? 'Takmir Masjid' }}</div>
                    </div>

                    <ol class="howto">
                        <li>Transfer sesuai total tagihan <b>Rp {{ number_format($nominal, 0, ',', '.') }}</b> ke rekening di atas.</li>
                        <li>Foto atau screenshot bukti transfernya.</li>
                        <li>Unggah fotonya di bawah, lalu kirim untuk diverifikasi.</li>
                    </ol>

                    <label class="drop" id="drop">
                        <img id="preview" alt="Pratinjau bukti transfer">
                        <div class="d-ico">📷</div>
                        <b id="dropText">Pilih foto bukti transfer</b>
                        <small>Foto atau screenshot (JPG, PNG)</small>
                        <input type="file" name="proof_of_payment" id="proof" accept="image/*" required>
                    </label>

                    <button type="submit" class="btn-submit blue">Kirim Bukti Pembayaran</button>
                </form>
            </div>

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
        document.getElementById('section-' + type).classList.add('active');
        if (el) el.classList.add('active');
    }

    // Salin nomor rekening
    var copyBtn = document.getElementById('copyBtn');
    if (copyBtn) copyBtn.addEventListener('click', function () {
        var text = document.getElementById('acctNum').textContent.trim();
        var done = function () { copyBtn.textContent = 'Tersalin ✓'; setTimeout(function () { copyBtn.textContent = 'Salin'; }, 1800); };
        if (navigator.clipboard) { navigator.clipboard.writeText(text).then(done); }
        else { var t = document.createElement('textarea'); t.value = text; document.body.appendChild(t); t.select(); document.execCommand('copy'); t.remove(); done(); }
    });

    // Pratinjau foto bukti transfer
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

{{-- Midtrans Snap --}}
@if($isMidtransActive)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
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