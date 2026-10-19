<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade & Perpanjangan Langganan Masjid - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paymentmasjid/perpanjanganLangganan.css') }}">
</head>
<body>

@php
    $monthly = isset($packages['package_monthly']) ? json_decode($packages['package_monthly']->config_data, true) : ['price' => 100000, 'features' => []];
    $yearly  = isset($packages['package_yearly'])  ? json_decode($packages['package_yearly']->config_data, true)  : ['price' => 1000000, 'features' => []];

    $monthlyName = $packages['package_monthly']->method_name ?? 'Paket 1 Bulan';
    $yearlyName  = $packages['package_yearly']->method_name ?? 'Paket 1 Tahun';

    $monthlyPrice = $monthly['price'] ?? 100000;
    $yearlyPrice  = $yearly['price'] ?? 1000000;

    // Format value untuk controller: Harga_Bulan
    $monthlyCode = $monthlyPrice . '_1';
    $yearlyCode  = $yearlyPrice . '_12';

    // Hemat paket tahunan dibanding 12x bulanan
    $hemat = max(0, ($monthlyPrice * 12) - $yearlyPrice);
    $hematPersen = $monthlyPrice > 0 ? round($hemat / ($monthlyPrice * 12) * 100) : 0;

@endphp

{{-- ===== BANNER ===== --}}
<header class="hero">
    <div class="topline">
        <a href="{{ url('/') }}" class="brand">Masjid<b>Ku</b></a>
        <a href="{{ route('dashboard') }}" class="back">← Kembali ke Dashboard</a>
    </div>
    <span class="eyebrow">Langganan Masjid</span>
    <h1>Pilih paket yang cocok untuk masjid Anda</h1>
    <p>Pembayaran diproses otomatis lewat Midtrans. Paket aktif setelah pembayaran berhasil.</p>
</header>

<div class="wrap">
    <form action="{{ route('masjid.perpanjangan.store') }}" method="POST" id="planForm">
        @csrf

        <div class="plans">

            {{-- ===== FREE ===== --}}
            <label class="plan">
                <input type="radio" name="package" value="0_0" data-name="Free" data-price="0" data-dur="Gratis" required>
                <div class="card">
                    <span class="tick">✓</span>
                    <div class="icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                    </div>
                    <p class="name">Free</p>
                    <p class="tag">Fitur dasar website masjid</p>
                    <div class="price"><span class="amount">Rp 0</span></div>
                    <p class="note">Masa uji coba terbatas</p>
                    <hr class="divider">
                    <ul class="feats">
                        <li>Profil & informasi dasar</li>
                        <li>Jadwal sholat otomatis</li>
                    </ul>
                    <div class="choose"></div>
                </div>
            </label>

            {{-- ===== 1 BULAN (dinamis dari Superadmin) ===== --}}
            <label class="plan">
                <input type="radio" name="package" value="{{ $monthlyCode }}" data-name="{{ $monthlyName }}" data-price="{{ $monthlyPrice }}" data-dur="1 bulan" checked>
                <div class="card">
                    <span class="tick">✓</span>
                    <div class="icon green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M3 12h4M17 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
                    </div>
                    <p class="name">{{ $monthlyName }}</p>
                    <p class="tag">Pengelolaan sehari-hari</p>
                    <div class="price"><span class="rp">Rp</span><span class="amount">{{ number_format($monthlyPrice, 0, ',', '.') }}</span><span class="per">/ bulan</span></div>
                    <p class="note">Bayar per bulan, tanpa komitmen panjang</p>
                    <hr class="divider">
                    <ul class="feats">
                        @forelse($monthly['features'] ?? [] as $feature)
                            <li>{{ $feature }}</li>
                        @empty
                            <li class="none">Fitur belum diatur</li>
                        @endforelse
                    </ul>
                    <div class="choose"></div>
                </div>
            </label>

            {{-- ===== 1 TAHUN (dinamis dari Superadmin) ===== --}}
            <label class="plan featured">
                <span class="badge">★ Paling direkomendasikan</span>
                <input type="radio" name="package" value="{{ $yearlyCode }}" data-name="{{ $yearlyName }}" data-price="{{ $yearlyPrice }}" data-dur="12 bulan">
                <div class="card">
                    <span class="tick">✓</span>
                    <div class="icon gold">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 4 5-7 5 7 4-4-2 11H5L3 8z"/></svg>
                    </div>
                    <p class="name">{{ $yearlyName }}</p>
                    <p class="tag">Bebas repot tiap bulan</p>
                    <div class="price"><span class="rp">Rp</span><span class="amount">{{ number_format($yearlyPrice, 0, ',', '.') }}</span><span class="per">/ tahun</span></div>
                    <p class="note">
                        Setara Rp {{ number_format(round($yearlyPrice / 12), 0, ',', '.') }} / bulan
                        @if($hematPersen > 0) &nbsp;<span class="save">Hemat {{ $hematPersen }}%</span>@endif
                    </p>
                    <hr class="divider">
                    <ul class="feats">
                        @forelse($yearly['features'] ?? [] as $feature)
                            <li>{{ $feature }}</li>
                        @empty
                            <li class="none">Fitur belum diatur</li>
                        @endforelse
                    </ul>
                    <div class="choose"></div>
                </div>
            </label>
        </div>

        {{-- ===== KEUNGGULAN ===== --}}
        <div class="trust">
            <div><i>🔒</i><p style="margin:0"><b>Pembayaran aman</b><span>Diproses oleh Midtrans</span></p></div>
            <div><i>💳</i><p style="margin:0"><b>Banyak pilihan bayar</b><span>QRIS, e-wallet, dan virtual account</span></p></div>
            <div><i>⚡</i><p style="margin:0"><b>Aktif otomatis</b><span>Setelah pembayaran berhasil</span></p></div>
        </div>

        {{-- ===== BAR BAWAH ===== --}}
        <div class="bar">
            <div class="bar-in">
                <div class="sum">
                    <small>Paket dipilih</small>
                    <strong id="sumName">-</strong><span class="tot" id="sumTotal"></span>
                </div>
                <div class="actions">
                    <a href="{{ route('dashboard') }}" class="btn-back">Batal</a>
                    <button type="submit" class="btn-submit" id="submit-btn">Lanjutkan ke Pembayaran Otomatis</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var radios = document.querySelectorAll('input[name="package"]');
    var submitBtn = document.getElementById('submit-btn');
    var sumName = document.getElementById('sumName');
    var sumTotal = document.getElementById('sumTotal');

    function updateSummary() {
        var sel = document.querySelector('input[name="package"]:checked');
        if (!sel) return;
        var gratis = sel.value.indexOf('0_') === 0;
        var harga = parseInt(sel.dataset.price, 10) || 0;

        sumName.textContent = sel.dataset.name + ' · ' + sel.dataset.dur;
        sumTotal.textContent = gratis ? 'Gratis' : 'Rp ' + harga.toLocaleString('id-ID');
        submitBtn.textContent = gratis ? 'Simpan & Gunakan Paket Free' : 'Lanjutkan ke Pembayaran Otomatis';
    }

    radios.forEach(function (r) { r.addEventListener('change', updateSummary); });
    updateSummary();
</script>
</body>
</html>