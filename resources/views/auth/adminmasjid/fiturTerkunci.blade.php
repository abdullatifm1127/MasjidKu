<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Belum Aktif - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #133B2C;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .lock-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .lock-icon {
            font-size: 60px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 22px;
        }
        p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .btn-action {
            display: inline-block;
            background: #198754;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
            margin-right: 8px;
        }
        .btn-action:hover {
            background: #157347;
        }
        .btn-back {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

    <div class="lock-card">
        <div class="lock-icon">
            <i class="fa-solid fa-lock"></i>
        </div>
        <h2>Fitur Ini Masih Belum Aktif</h2>
        <p>Maaf, menu donasi online dan pengelolaan zakat tidak tersedia untuk paket free. Silakan upgrade paket masjid Anda untuk mengaktifkan fitur ini.</p>
        
        <div>
            <a href="{{ route('masjid.payment') }}" class="btn-action">Upgrade Paket</a>
            <a href="{{ route('admin.dashboard') }}" class="btn-back">Kembali</a>
        </div>
    </div>

</body>
</html>