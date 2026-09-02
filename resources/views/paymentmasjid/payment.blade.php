<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Aktivasi - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1c2b27;
            --ink-soft: #5b6d67;
            --parchment: #faf7f0;
            --sand: #e9e2d2;
            --emerald: #14524a;
            --emerald-dark: #0d3d37;
            --gold: #b3894a;
            --gold-soft: #d9bb85;
            --amber-bg: #f6f0e1;
            --amber-text: #8a5a1f;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--emerald-dark);
            background-image:
                radial-gradient(circle at 1px 1px, rgba(255,255,255,0.06) 1px, transparent 0);
            background-size: 26px 26px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 3rem 1.25rem;
        }
        .pay-card {
            background: var(--parchment);
            padding: 0;
            border-radius: 14px;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 480px;
            box-sizing: border-box;
            overflow: hidden;
            border: 1px solid rgba(179,137,74,0.25);
        }
        .pay-card > .card-inner {
            padding: 2.5rem 2.25rem 2.25rem;
        }
        .card-band {
            height: 10px;
            background: repeating-linear-gradient(
                135deg,
                var(--gold) 0px, var(--gold) 8px,
                var(--emerald) 8px, var(--emerald) 16px
            );
        }
        .pay-title {
            font-family: 'Fraunces', serif;
            font-size: 1.55rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.65rem;
            display: flex;
            align-items: baseline;
            gap: 0.55rem;
            line-height: 1.25;
        }
        .pay-title svg { flex-shrink: 0; align-self: center; }
        .pay-desc {
            color: var(--ink-soft);
            font-size: 0.93rem;
            margin-bottom: 1.75rem;
            line-height: 1.6;
            max-width: 42ch;
        }
        .pay-desc strong { color: var(--ink); font-weight: 600; }
        .bank-info {
            background: #ffffff;
            border: 1px solid var(--sand);
            border-left: 3px solid var(--gold);
            padding: 1.15rem 1.35rem;
            border-radius: 4px;
            margin-bottom: 1.75rem;
            font-size: 0.9rem;
            color: var(--ink);
        }
        .bank-info > strong:first-child {
            display: block;
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--ink);
            margin-bottom: 0.7rem;
        }
        .bank-info strong { color: var(--emerald); }
        .form-group { margin-bottom: 1.6rem; }
        .form-group label.field-label {
            display: block;
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
            color: var(--ink);
        }
        .form-control {
            width: 100%;
            padding: 0.7rem 0.85rem;
            border: 1px solid var(--sand);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 0.92rem;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--emerald);
        }
        .btn-submit {
            background: var(--emerald);
            color: #fdfbf5;
            border: none;
            padding: 0.85rem 1.5rem;
            width: 100%;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
        }
        .btn-submit:hover:not(:disabled) { background: var(--emerald-dark); }
        .btn-submit:disabled {
            background: var(--sand);
            color: #9a9184;
            cursor: not-allowed;
        }
        .alert-status {
            background: var(--amber-bg);
            color: var(--amber-text);
            border-left: 3px solid var(--gold);
            padding: 0.8rem 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            line-height: 1.5;
        }
        .alert-status svg { margin-top: 1px; }

        .dropzone {
            border: 1.5px dashed var(--sand);
            border-radius: 6px;
            padding: 1.85rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease;
            background: #ffffff;
            display: block;
        }
        .dropzone:hover { border-color: var(--gold); background: #fdfbf5; }
        .dropzone.has-file { border-color: var(--emerald); border-style: solid; background: #f4f8f6; }
        .dropzone svg { color: #a89f8f; margin-bottom: 0.5rem; }
        .dropzone.has-file svg { color: var(--emerald); }
        .dropzone-text { font-size: 0.85rem; color: var(--ink-soft); font-weight: 500; }
        .dropzone-hint { font-size: 0.76rem; color: #a89f8f; margin-top: 3px; }
        .preview-img { max-width: 100%; max-height: 160px; border-radius: 4px; margin-top: 12px; display: none; border: 1px solid var(--sand); }
        .error-text { color: #b3401f; font-size: 0.8rem; margin-top: 6px; display: none; }
    </style>
</head>
<body>
    <div class="pay-card">
        <div class="card-band"></div>
        <div class="card-inner">
        <div class="pay-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#14524a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h1"/><path d="M9 18h1"/><path d="M14 15h1"/><path d="M14 18h1"/></svg>
            Pembayaran Aktivasi Web
        </div>
        <div class="pay-desc">Selesaikan pembayaran biaya aktivasi platform untuk membuka akses dashboard dan modul publik masjid <strong>{{ $mosque->mosque_name }}</strong>.</div>

        @if($mosque->payment_status === 'pending')
            <div class="alert-status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8a5a1f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Anda sudah mengunggah bukti pembayaran. Menunggu verifikasi dari Superadmin.
            </div>
        @endif

        <div class="bank-info">
            <strong>Silakan transfer melalui rekening berikut:</strong>
            Bank Syariah Indonesia (BSI)<br>
            No. Rekening: <strong>7123-4567-8900</strong><br>
            Atas Nama: <strong>Yayasan SIM Masjid Indonesia</strong><br>
            Nominal Tagihan: <strong style="font-size: 1.05rem;">Rp 150.000</strong>
        </div>

        <form action="{{ route('masjid.payment.upload') }}" method="POST" enctype="multipart/form-data" id="payment-form">
            @csrf
            <div class="form-group">
                <label class="field-label">Upload Bukti Transfer (Format: JPG, PNG, Maks. 2MB)</label>

                <label for="payment_proof" class="dropzone" id="dropzone">
                    <svg id="dropzone-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/></svg>
                    <div class="dropzone-text" id="dropzone-text">Klik untuk pilih foto bukti transfer</div>
                    <div class="dropzone-hint">JPG atau PNG, maksimal 2MB</div>
                    <img id="preview-img" class="preview-img" alt="Preview bukti transfer">
                </label>
                <input
                    type="file"
                    name="payment_proof"
                    id="payment_proof"
                    accept="image/png, image/jpeg, image/jpg"
                    style="display:none;"
                    onchange="handleFileSelect(this)"
                    required
                >
                <p class="error-text" id="error-text">Ukuran file maksimal 2MB. Silakan pilih gambar lain.</p>
            </div>

            <button type="submit" class="btn-submit" id="submit-btn" disabled>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Kirim Bukti Pembayaran
            </button>
        </form>
        </div>
    </div>

    <script>
        function handleFileSelect(input) {
            const file = input.files[0];
            const dropzone = document.getElementById('dropzone');
            const dzText = document.getElementById('dropzone-text');
            const dzIcon = document.getElementById('dropzone-icon');
            const previewImg = document.getElementById('preview-img');
            const errorText = document.getElementById('error-text');
            const submitBtn = document.getElementById('submit-btn');
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!file) return;

            if (file.size > maxSize) {
                errorText.style.display = 'block';
                input.value = '';
                previewImg.style.display = 'none';
                dropzone.classList.remove('has-file');
                submitBtn.disabled = true;
                return;
            }

            errorText.style.display = 'none';
            dzText.textContent = file.name;
            dropzone.classList.add('has-file');
            submitBtn.disabled = false;

            dzIcon.innerHTML = '<polyline points="20 6 9 17 4 12"></polyline>';

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>