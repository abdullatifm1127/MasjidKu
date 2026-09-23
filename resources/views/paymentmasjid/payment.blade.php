<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Aktivasi - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <!-- Memuat file CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('css/paymentmasjid/payment.css') }}">
</head>
<body>
    <div class="pay-card">
        <div class="card-band"></div>
        <div class="card-inner">
            <div class="pay-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#14524a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h1"/><path d="M9 18h1"/><path d="M14 15h1"/><path d="M14 18h1"/></svg>
                Pembayaran Otomatis
            </div>
            <div class="pay-desc">Selesaikan pembayaran biaya aktivasi platform untuk membuka akses dashboard dan modul publik masjid <strong>{{ $mosque->mosque_name }}</strong> secara instan.</div>

            @php
                // Cek dari session pending_renewal terlebih dahulu, jika tidak ada baru ambil dari database
                $pendingRenewal = session('pending_renewal');
                $rawPackage = $pendingRenewal['package'] ?? ($mosque->package_type ?? '100000_1');
                
                $nominal = 100000;
                if ($rawPackage === '1000000_12') {
                    $nominal = 1000000;
                } elseif (str_contains($rawPackage, '_')) {
                    $nominal = (int)explode('_', $rawPackage)[0];
                }
            @endphp

            <div class="bank-card">
                <div class="bank-card-header">
                    <div class="bank-logo">ID</div>
                    <div class="bank-name-block">
                        <div class="bank-name">Midtrans Payment Gateway</div>
                        <div class="bank-sub">Virtual Account, QRIS, E-Wallet, Kartu Kredit</div>
                    </div>
                </div>
                <div class="bank-card-body">
                    <div class="bank-row">
                        <span class="bank-row-label">Jenis Paket</span>
                        <span class="bank-row-value">
                            @if($rawPackage === '1000000_12') Langganan 1 Tahun @else Langganan 1 Bulan @endif
                        </span>
                    </div>
                </div>
                <div class="bank-total">
                    <span class="bank-total-label">Total Tagihan</span>
                    <span class="bank-total-value">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Bayar Otomatis Midtrans -->
            <button type="button" class="btn-submit" id="pay-button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Bayar Sekarang (Otomatis)
            </button>
            <!-- Tombol Batalkan Transaksi -->
            <div class="btn-cancel-container">
                <a href="{{ route('masjid.cancel') }}" 
                   onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?');" 
                   class="btn-cancel" style="display: inline-block;">
                    Batalkan & Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Script Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            let btn = document.getElementById('pay-button');
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
                        onSuccess: function(result){
                            alert("Pembayaran berhasil!");
                            window.location.href = "{{ route('dashboard') }}";
                        },
                        onPending: function(result){
                            alert("Menunggu penyelesaian pembayaran Anda.");
                            location.reload();
                        },
                        onError: function(result){
                            alert("Pembayaran gagal!");
                            location.reload();
                        },
                        onClose: function(){
                            // Ketika tombol silang (X) pop-up Midtrans diklik, langsung arahkan ke rute pembatalan atau dashboard
                            window.location.href = "{{ route('masjid.cancel') }}";
                        }
                    });
                } else {
                    alert('Gagal memuat token pembayaran: ' + (data.error || 'Terjadi kesalahan'));
                    btn.disabled = false;
                    btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Bayar Sekarang (Otomatis)';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi server.');
                btn.disabled = false;
                btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Bayar Sekarang (Otomatis)';
            });
        };
    </script>
</body>
</html>