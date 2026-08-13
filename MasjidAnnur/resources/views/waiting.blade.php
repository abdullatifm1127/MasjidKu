<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Verifikasi - MasjidKu</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f4f6f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .card {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
            max-width: 480px;
            width: 100%;
            border-top: 5px solid #15803d; /* Warna hijau khas masjid */
        }
        .icon-container {
            font-size: 50px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        p {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-home {
            display: inline-block;
            padding: 12px 24px;
            background-color: #15803d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 4px 6px rgba(21, 128, 61, 0.2);
            border: none;
            cursor: pointer;
        }
        .btn-home:hover {
            background-color: #166534;
        }
        .btn-home:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon-container">⏳</div>
        <h2>Pendaftaran Masjid Berhasil</h2>
        <p>
            Data masjid Anda sedang menunggu verifikasi dari admin. Silakan tunggu beberapa saat sampai akun Anda disetujui.
        </p>

        <button onclick="window.location.href='{{ url('/') }}'" class="btn-home">
            ← Kembali ke Beranda
        </button>
    </div>

</body>
</html>