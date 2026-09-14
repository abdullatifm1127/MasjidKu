<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpanjangan Langganan - MasjidKu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #191919;
            --muted: #6b6b6b;
            --line: #e7e5e0;
            --paper: #faf9f6;
            --accent: #2f7a4f;
            --accent-soft: #eef7f0;
            --highlight: #f5b400;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--paper);
            color: var(--ink);
            margin: 0;
            padding: 56px 24px;
        }

        .wrap {
            max-width: 1080px;
            margin: 0 auto;
        }

        .page-head {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-head h1 {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin: 0 0 8px;
        }

        .page-head p {
            color: var(--muted);
            font-size: 15px;
            margin: 0;
        }

        .status-alert {
            max-width: 640px;
            margin: 0 auto 32px;
            background: var(--accent-soft);
            border: 1px solid #bfe3cb;
            color: #1f5c39;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .status-alert strong { font-weight: 700; }

        .status-alert button {
            background: none;
            border: none;
            font-size: 18px;
            line-height: 1;
            color: #1f5c39;
            cursor: pointer;
        }

        /* Pricing cards */
        .plans {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            align-items: stretch;
        }

        .plan {
            position: relative;
        }

        .plan input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .plan-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            padding: 28px 26px;
            height: 100%;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .plan input:checked + .plan-card {
            border-color: var(--ink);
            box-shadow: 0 0 0 1px var(--ink);
        }

        .plan-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }

        .plan-icon svg { width: 18px; height: 18px; }

        .plan-name {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .plan-tag {
            color: var(--muted);
            font-size: 13.5px;
            margin: 0 0 20px;
        }

        .plan-price {
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 4px;
        }

        .plan-price .amount {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .plan-price .unit {
            font-size: 12.5px;
            color: var(--muted);
        }

        .plan-note {
            font-size: 12.5px;
            color: var(--muted);
            margin: 0 0 20px;
            min-height: 18px;
        }

        .plan-select {
            width: 100%;
            padding: 11px 16px;
            border-radius: 8px;
            border: 1px solid var(--ink);
            background: #fff;
            color: var(--ink);
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            transition: background .15s ease, color .15s ease;
        }

        .plan input:checked + .plan-card .plan-select {
            background: var(--ink);
            color: #fff;
        }

        .plan-select::before {
            content: "Pilih paket ini";
        }

        .plan input:checked + .plan-card .plan-select::before {
            content: "✓ Paket dipilih";
        }

        .plan-divider {
            border: none;
            border-top: 1px solid var(--line);
            margin: 24px 0 18px;
        }

        .plan-feats {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex-grow: 1;
        }

        .plan-feats li {
            font-size: 13.5px;
            color: #333;
            display: flex;
            gap: 10px;
            line-height: 1.4;
        }

        .plan-feats li svg {
            flex-shrink: 0;
            margin-top: 2px;
            width: 14px;
            height: 14px;
            color: var(--accent);
        }

        .plan.featured .plan-card { border-color: var(--ink); }
        .plan-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--ink);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 100px;
            letter-spacing: 0.02em;
        }

        /* Section: payment info */
        .section {
            margin-top: 44px;
        }

        .section-label {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 14px;
        }

        .pay-box {
            background: var(--accent-soft);
            border: 1px solid #bfe3cb;
            border-radius: 12px;
            padding: 20px 22px;
        }

        .pay-box p.lead {
            color: var(--accent);
            font-weight: 600;
            font-size: 14px;
            margin: 0 0 12px;
        }

        .pay-box ul {
            margin: 0;
            padding-left: 18px;
            font-size: 14px;
            color: #333;
        }

        .pay-box ul li { margin-bottom: 6px; }

        .pay-box .hint {
            color: var(--muted);
            font-size: 12.5px;
            margin: 12px 0 0;
        }

        /* Upload */
        .upload-box {
            border: 1.5px dashed var(--line);
            border-radius: 12px;
            padding: 22px;
            background: #fff;
        }

        .upload-box input[type="file"] {
            width: 100%;
            font-size: 14px;
            padding: 10px 0;
        }

        .upload-box small {
            display: block;
            color: var(--muted);
            font-size: 12.5px;
            margin-top: 8px;
        }

        .invalid-feedback {
            color: #c0392b;
            font-size: 12.5px;
            margin-top: 6px;
        }

        /* Actions */
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
        }

        .btn-back {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-back:hover { color: var(--ink); }

        .btn-submit {
            background: var(--ink);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px 28px;
            font-weight: 700;
            font-size: 14.5px;
            cursor: pointer;
        }

        .btn-submit:hover { background: #000; }

        @media (max-width: 860px) {
            .plans { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="wrap">

        <div class="page-head">
            <h1>Perpanjangan Langganan Masjid</h1>
            <p>Pilih paket durasi langganan, lakukan transfer pembayaran, lalu unggah buktinya di bawah ini.</p>
        </div>

        @if (session('status'))
            <div class="status-alert" role="alert">
                <span><strong>Terima kasih.</strong> {{ session('status') }}</span>
                <button type="button" onclick="this.closest('.status-alert').remove()" aria-label="Tutup">&times;</button>
            </div>
        @endif

        <form action="{{ route('masjid.perpanjangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Pilihan Paket Langganan -->
            <div class="plans">

                <!-- Paket Free -->
                <label class="plan">
                    <input type="radio" name="package" value="0_0" required>
                    <div class="plan-card">
                        <div class="plan-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        </div>
                        <p class="plan-name">Free</p>
                        <p class="plan-tag">Kenali fitur dasar website masjid</p>
                        <div class="plan-price">
                            <span class="amount">Rp 0</span>
                        </div>
                        <p class="plan-note">Masa uji coba terbatas</p>
                        <span class="plan-select"></span>
                        <hr class="plan-divider">
                        <ul class="plan-feats">
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Profil & informasi masjid dasar</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Jadwal sholat otomatis</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Batas fitur & kapasitas terbatas</li>
                        </ul>
                    </div>
                </label>

                <!-- Paket 1 Bulan -->
                <label class="plan">
                    <input type="radio" name="package" value="100000_1">
                    <div class="plan-card">
                        <div class="plan-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3v4M12 17v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M3 12h4M17 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
                        </div>
                        <p class="plan-name">Paket 1 Bulan</p>
                        <p class="plan-tag">Untuk pengelolaan sehari-hari</p>
                        <div class="plan-price">
                            <span class="amount">Rp 100rb</span>
                        </div>
                        <p class="plan-note">Rp 100.000 / bulan</p>
                        <span class="plan-select"></span>
                        <hr class="plan-divider">
                        <ul class="plan-feats">
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Semua fitur Free, dan:</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Akses penuh fitur standar website masjid</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Masa aktif 30 hari</li>
                        </ul>
                    </div>
                </label>

                <!-- Paket 1 Tahun -->
                <label class="plan featured">
                    <span class="plan-badge">Paling direkomendasikan</span>
                    <input type="radio" name="package" value="1000000_12">
                    <div class="plan-card">
                        <div class="plan-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3v4M12 17v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M3 12h4M17 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
                        </div>
                        <p class="plan-name">Paket 1 Tahun</p>
                        <p class="plan-tag">Bebas repot memperpanjang tiap bulan</p>
                        <div class="plan-price">
                            <span class="amount">Rp 1jt</span>
                        </div>
                        <p class="plan-note">Setara Rp 83.300 / bulan</p>
                        <span class="plan-select"></span>
                        <hr class="plan-divider">
                        <ul class="plan-feats">
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Semua fitur Paket 1 Bulan, dan:</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Masa aktif penuh 12 bulan</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>Harga per bulan paling rendah</li>
                        </ul>
                    </div>
                </label>

            </div>

            <!-- Informasi Rekening / Pembayaran -->
            <div class="section" id="payment-section">
                <p class="section-label">Informasi rekening tujuan transfer</p>
                <div class="pay-box">
                    <p class="lead">Lakukan transfer pembayaran ke salah satu rekening resmi MasjidKu:</p>
                    <ul>
                        <li><strong>Bank Syariah Indonesia (BSI):</strong> 7123-4567-89 a.n. Yayasan MasjidKu Digital</li>
                        <li><strong>Bank Central Asia (BCA):</strong> 1234-5678-90 a.n. MasjidKu Indonesia</li>
                    </ul>
                    <p class="hint">Pastikan nominal transfer sesuai dengan harga paket yang Anda pilih di atas.</p>
                </div>
            </div>

            <!-- Upload Bukti Transfer -->
            <div class="section" id="upload-section">
                <p class="section-label">Unggah bukti transfer</p>
                <div class="upload-box">
                    <input type="file" name="payment_proof" id="payment-proof-input" class="@error('payment_proof') is-invalid @enderror" required>
                    <small>Format yang diizinkan: JPG, PNG, AVIF. Maksimal 2MB.</small>
                    @error('payment_proof')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="actions">
                <a href="{{ url('/dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
                <button type="submit" class="btn-submit">Kirim Bukti Perpanjangan</button>
            </div>

        </form>

    </div>

    <script>
        const packageInputs = document.querySelectorAll('input[name="package"]');
        const paymentSection = document.getElementById('payment-section');
        const uploadSection = document.getElementById('upload-section');
        const proofInput = document.getElementById('payment-proof-input');

        function togglePaymentSections() {
            const checked = document.querySelector('input[name="package"]:checked');
            const isFree = checked && checked.value.startsWith('0_');
            paymentSection.style.display = isFree ? 'none' : 'block';
            uploadSection.style.display = isFree ? 'none' : 'block';
            proofInput.required = !isFree;
        }

        packageInputs.forEach(input => input.addEventListener('change', togglePaymentSections));
        togglePaymentSections();
    </script>

</body>
</html>