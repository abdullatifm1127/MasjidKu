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
            Data masjid Anda sedang menunggu verifikasi dari admin. Silakan tunggu beberapa saat sampai akun Anda disetujui.
        </p>

        <button onclick="window.location.href='{{ route('home') }}'" class="btn-home">
            ← Kembali ke Beranda
        </button>
    </div>

</body>
</html>