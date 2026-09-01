<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Aktivasi - SIM Masjid</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .pay-card { background: white; padding: 2.5rem; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 520px; box-sizing: border-box; }
        .pay-title { font-size: 1.4rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .pay-desc { color: #6b7280; font-size: 0.92rem; margin-bottom: 1.5rem; line-height: 1.5; }
        .bank-info { background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.92rem; color: #334155; }
        .bank-info strong { color: #0f172a; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: #374151; }
        .form-control { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; }
        .btn-submit { background: #059669; color: white; border: none; padding: 0.85rem 1.5rem; width: 100%; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #047857; }
        .alert-status { background: #fef3c7; color: #b45309; padding: 0.75rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem; font-weight: 500; }
    </style>
</head>
<body>
    <div class="pay-card">
        <div class="pay-title"><i class="fa-solid fa-file-invoice-dollar" style="color: #059669;"></i> Pembayaran Aktivasi Web</div>
        <div class="pay-desc">Selesaikan pembayaran biaya aktivasi platform untuk membuka akses dashboard dan modul publik masjid <strong>{{ $mosque->mosque_name }}</strong>.</div>
        
        @if($mosque->payment_status === 'pending')
            <div class="alert-status">
                <i class="fa-solid fa-clock"></i> Anda sudah mengunggah bukti pembayaran. Menunggu verifikasi dari Superadmin.
            </div>
        @endif

        <div class="bank-info">
            <strong>Silakan transfer melalui rekening berikut:</strong><br><br>
            Bank Syariah Indonesia (BSI)<br>
            No. Rekening: <strong>7123-4567-8900</strong><br>
            Atas Nama: <strong>Yayasan SIM Masjid Indonesia</strong><br>
            Nominal Tagihan: <strong style="color: #059669; font-size: 1.1rem;">Rp 150.000</strong>
        </div>

        <form action="{{ route('masjid.payment.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Upload Bukti Transfer (Format: JPG, PNG, Maks. 2MB)</label>
                <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn-submit"><i class="fa-solid fa-upload"></i> Kirim Bukti Pembayaran</button>
        </form>
    </div>
</body>
</html>