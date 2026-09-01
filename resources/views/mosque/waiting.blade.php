<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Verifikasi - MasjidKu</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Memanggil CSS Terpisah -->
    <link rel="stylesheet" href="{{ asset('css/waiting/waiting.css') }}">
</head>
<body>

    <div class="card">
        <div class="icon-container">⏳</div>
        <h2>Pendaftaran Masjid Berhasil</h2>
        
        <p>
            Data masjid <strong>{{ $mosque->mosque_name }}</strong> sedang menunggu verifikasi dari admin. Silakan tunggu beberapa saat sampai akun Anda disetujui.
        </p>

        {{-- JIKA MEMILIH PAKET KUSTOM / DONASI --}}
        @if(isset($mosque->package_type) && $mosque->package_type === 'custom_donation')
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 16px; border-radius: 8px; margin: 20px 0; text-align: left;">
                <h4 style="margin: 0 0 8px 0; color: #1e293b; font-size: 0.95rem;">💳 Instruksi Pembayaran Paket Kustom</h4>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 12px 0;">
                    Karena Anda memilih paket <strong>Kustom Tampilan & Menu Donasi</strong>, silakan lakukan transfer biaya aktivasi sebesar <strong>Rp 150.000</strong> ke rekening berikut:
                </p>
                <div style="background: #fff; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: #334155;">
                    <strong>Bank Syariah Indonesia (BSI)</strong><br>
                    No. Rekening: <strong>7123456789</strong><br>
                    Atas Nama: Yayasan MasjidKu Indonesia
                </div>
                <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 10px; margin-bottom: 0;">
                    *Akun dan fitur kustom akan diaktifkan setelah admin memverifikasi pembayaran Anda.
                </p>
            </div>
        @endif

        <button onclick="window.location.href='{{ route('home') }}'" class="btn-home">
            ← Kembali ke Beranda
        </button>
    </div>

</body>
</html>