<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpanjangan Langganan - MasjidKu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .payment-info-box { background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card border-0 p-4">
                    <h3 class="fw-bold text-dark mb-2">🔄 Perpanjangan Langganan Masjid</h3>
                    <p class="text-muted mb-4">Silakan pilih paket durasi langganan, lakukan transfer pembayaran, lalu unggah buktinya di bawah ini.</p>

                    <!-- == TAMBAHAN: ALERT / PESAN SUKSES & TERIMA KASIH == -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <strong>Terima Kasih!</strong> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- ================================================= -->

                    <form action="{{ route('masjid.perpanjangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Pilihan Paket Langganan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">1. Pilih Paket Durasi Langganan</label>
                            <div class="row g-3">
                                
                                <!-- Paket 1 Bulan -->
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="package" id="pkg1" value="100000_1" autocomplete="off" required>
                                    <label class="btn btn-outline-success w-100 p-3 text-start h-100 d-flex flex-column justify-content-between" for="pkg1">
                                        <div>
                                            <strong class="fs-6">Paket 1 Bulan</strong><br>
                                            <span class="text-success fw-bold fs-5">Rp 100.000</span>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Akses penuh fitur standar website masjid selama 30 hari.</p>
                                    </label>
                                </div>

                                <!-- Paket 6 Bulan -->
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="package" id="pkg2" value="550000_6" autocomplete="off">
                                    <label class="btn btn-outline-success w-100 p-3 text-start h-100 d-flex flex-column justify-content-between" for="pkg2">
                                        <div>
                                            <strong class="fs-6">Paket 6 Bulan</strong><br>
                                            <span class="text-success fw-bold fs-5">Rp 550.000</span>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Lebih hemat! Masa aktif setengah tahun penuh dengan prioritas sistem.</p>
                                    </label>
                                </div>

                                <!-- Paket 1 Tahun -->
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="package" id="pkg3" value="1000000_12" autocomplete="off">
                                    <label class="btn btn-outline-success w-100 p-3 text-start h-100 d-flex flex-column justify-content-between" for="pkg3">
                                        <div>
                                            <strong class="fs-6">Paket 1 Tahun</strong><br>
                                            <span class="text-success fw-bold fs-5">Rp 1.000.000</span>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Paling direkomendasikan & bebas repot memperpanjang tiap bulan.</p>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Informasi Rekening / Pembayaran -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">2. Informasi Rekening Tujuan Transfer</label>
                            <div class="payment-info-box">
                                <p class="mb-2 text-success fw-semibold">Silakan lakukan transfer pembayaran ke salah satu rekening resmi MasjidKu:</p>
                                <ul class="mb-0 text-dark small ps-3">
                                    <li><strong>Bank Syariah Indonesia (BSI):</strong> 7123-4567-89 a.n. Yayasan MasjidKu Digital</li>
                                    <li><strong>Bank Central Asia (BCA):</strong> 1234-5678-90 a.n. MasjidKu Indonesia</li>
                                </ul>
                                <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">*Pastikan nominal transfer sesuai dengan harga paket yang Anda pilih di atas.</p>
                            </div>
                        </div>

                        <!-- Upload Bukti Transfer -->
                        <div class="mb-4">
                            <label for="payment_proof" class="form-label fw-bold">3. Unggah Bukti Transfer</label>
                            <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" required>
                            <small class="text-muted">Format yang diizinkan: JPG, PNG, AVIF. Maksimal 2MB.</small>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-secondary px-4">Kembali</a>
                            <button type="submit" class="btn text-white fw-bold px-4" style="background-color: #d97706;">Kirim Bukti Perpanjangan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap JS (Opsional untuk tombol close alert) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>