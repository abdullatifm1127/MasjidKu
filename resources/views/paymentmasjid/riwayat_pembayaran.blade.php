<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran & Perpanjangan - MasjidAnnur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: none; }
        .table th { background-color: #f1f5f9; color: #334155; font-weight: 600; }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark">📋 Riwayat Pembayaran & Perpanjangan Masjid</h3>
                <p class="text-muted mb-0">Rekapitulasi seluruh transaksi langganan dan paket masjid yang terdaftar.</p>
            </div>
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary btn-sm px-3">← Kembali ke Dashboard</a>
        </div>

        <div class="card p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Masjid</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nominal</th>
                            <th>Paket / Durasi</th>
                            <th>Status</th>
                            <th>Bukti Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $index => $item)
                        <tr>
                            <td>{{ $subscriptions->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-dark">{{ $item->mosque->mosque_name ?? 'Masjid Dihapus' }}</strong><br>
                                <small class="text-muted">Ketua: {{ $item->mosque->chairman_name ?? '-' }}</small>
                            </td>
                            <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                            <td><span class="fw-bold text-success">Rp {{ number_format($item->amount, 0, ',', '.') }}</span></td>
                            <td>
                                @if($item->amount == 100000)
                                    <span class="badge bg-info text-dark">1 Bulan</span>
                                @elseif($item->amount == 550000)
                                    <span class="badge bg-primary">6 Bulan</span>
                                @elseif($item->amount == 1000000)
                                    <span class="badge bg-warning text-dark">1 Tahun</span>
                                @else
                                    <span class="badge bg-secondary">Lainnya</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($item->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($item->payment_proof)
                                    <a href="{{ asset('storage/' . $item->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Bukti</a>
                                @else
                                    <span class="text-muted small">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat transaksi pembayaran langganan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>

</body>
</html>