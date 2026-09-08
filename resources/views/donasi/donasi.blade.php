<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donasi & Zakat — Masjid {{ $mosque->mosque_name ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/donasi/donasi.css') }}?v={{ time() }}">
</head>
<body>

    <div class="donation-wrapper">
        <!-- Header Identitas Masjid -->
        <header class="mosque-header">
            <div class="mosque-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 21h18M5 21V7l7-4 7 4v14M9 9v12M15 9v12"/>
                </svg>
            </div>
            <div>
                <h1>Masjid {{ $mosque->mosque_name ?? 'Al-Ikhlas' }}</h1>
                <p>{{ $mosque->city ?? 'Pusat Layanan Umat' }}</p>
            </div>
        </header>

        <!-- Progress Steps -->
        <div class="stepper">
            <div class="step active" id="st-1"><span>1</span> Kategori</div>
            <div class="step" id="st-2"><span>2</span> Nominal</div>
            <div class="step" id="st-3"><span>3</span> Pembayaran</div>
        </div>

        <!-- STEP 1: PILIH KATEGORI & GALERI DOKUMENTASI -->
        <div class="panel-card" id="panel-1">
            <div class="panel-title">
                <h2>Pilih Kategori Donasi</h2>
                <p>Tentukan jenis kebaikan yang ingin Anda salurkan hari ini.</p>
            </div>

            @if ($categories->isEmpty())
                <div class="empty-box">Belum ada kategori donasi yang tersedia.</div>
            @else
                <div class="category-list">
                    @foreach ($categories as $cat)
                        <button type="button" class="category-item" onclick="selectCategory('{{ $cat->key }}', '{{ addslashes($cat->title) }}', '{{ $cat->calc_type ?? 'nominal' }}')">
                            <div class="ci-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    {!! $cat->iconPath() !!}
                                </svg>
                            </div>
                            <div class="ci-info">
                                <h3>{{ $cat->title }}</h3>
                                <p>{{ $cat->description }}</p>
                            </div>
                            <div class="ci-arrow">&rsaquo;</div>
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- BAGIAN GALERI DOKUMENTASI PENYALURAN (Menggunakan $items dari Controller) -->
            <div style="margin-top: 32px; border-top: 1px solid #f1f5f9; padding-top: 24px;">
                <div class="panel-title" style="margin-bottom: 14px;">
                    <h2>Dokumentasi Penyaluran</h2>
                    <p>Bukti transparansi penyaluran dana kebaikan dari jamaah.</p>
                </div>

                @if(isset($items) && $items->isNotEmpty())
                    <div class="public-gallery-grid">
                        @foreach($items as $gal)
                            <div class="pub-gal-item">
                                <div class="pub-gal-img" style="background-image: url('{{ asset('storage/' . $gal->foto) }}')"></div>
                                <div class="pub-gal-info">
                                    <span class="pub-tag">{{ $gal->kategori ?? 'Penyaluran' }}</span>
                                    <h4>{{ $gal->judul }}</h4>
                                    <p>{{ Str::limit($gal->deskripsi, 60) }}</p>
                                    <div class="pub-meta">
                                        <span>Rp {{ number_format($gal->nominal_terpakai ?? 0, 0, ',', '.') }}</span>
                                        <span>{{ $gal->tanggal ? \Carbon\Carbon::parse($gal->tanggal)->format('d M Y') : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-box" style="padding: 20px;">
                        Belum ada dokumentasi foto penyaluran yang diunggah.
                    </div>
                @endif
            </div>
        </div>

        <!-- STEP 2: MASUKKAN NOMINAL -->
        <div class="panel-card hidden" id="panel-2">
            <button type="button" class="btn-back" onclick="changeStep(1)">&larr; Kembali ke Kategori</button>
            
            <div class="panel-title" style="margin-top: 10px;">
                <h2 id="selected-cat-title">Nominal Donasi</h2>
                <p>Pilih atau ketik jumlah dana yang ingin disumbangkan.</p>
            </div>

            <!-- Khusus Kalkulator Zakat -->
            <div id="section-zakat" class="hidden">
                <div class="zakat-toggle">
                    <button type="button" class="z-tab active" onclick="setZakatSub('fitrah', this)">Zakat Fitrah</button>
                    <button type="button" class="z-tab" onclick="setZakatSub('mal', this)">Zakat Mal</button>
                </div>
                
                <div id="form-fitrah">
                    <div class="input-group">
                        <label>Jumlah Jiwa</label>
                        <input type="number" id="f-jiwa" value="1" min="1" oninput="calcZakatFitrah()">
                    </div>
                    <div class="input-group">
                        <label>Nominal per Jiwa (Rp)</label>
                        <input type="number" id="f-nominal" value="{{ $mosque->zakat_fitrah_default ?? 45000 }}" oninput="calcZakatFitrah()">
                    </div>
                </div>

                <div id="form-mal" class="hidden">
                    <div class="input-group">
                        <label>Total Harta Haul (Rp)</label>
                        <input type="number" id="m-harta" placeholder="Contoh: 80000000" oninput="calcZakatMal()">
                        <small style="color: #888; margin-top: 4px; display:block;">Dihitung otomatis 2,5% dari total harta.</small>
                    </div>
                </div>
            </div>

            <!-- Pilihan Nominal Cepat (Untuk Donasi Umum) -->
            <div id="section-nominal">
                <div class="nominal-chips">
                    <button type="button" onclick="setAmount(20000, this)">Rp 20.000</button>
                    <button type="button" onclick="setAmount(50000, this)">Rp 50.000</button>
                    <button type="button" onclick="setAmount(100000, this)">Rp 100.000</button>
                    <button type="button" onclick="setAmount(250000, this)">Rp 250.000</button>
                </div>
                <div class="input-group">
                    <label>Atau Masukkan Nominal Lain (Rp)</label>
                    <input type="number" id="custom-nominal" placeholder="Contoh: 75000" oninput="setCustomAmount()">
                </div>
            </div>

            <div class="input-group" style="margin-top: 16px;">
                <label>Nama Donatur (Opsional)</label>
                <input type="text" id="donor-name" placeholder="Tulis nama atau kosongkan (Hamba Allah)">
            </div>

            <div class="total-display">
                <span>Total Donasi</span>
                <strong id="final-amount-text">Rp 0</strong>
            </div>

            <button type="button" class="btn-submit" id="to-pay-btn" onclick="changeStep(3)" disabled>Lanjut ke Pembayaran</button>
        </div>

        <!-- STEP 3: PEMBAYARAN -->
        <div class="panel-card hidden" id="panel-3">
            <button type="button" class="btn-back" onclick="changeStep(2)">&larr; Ubah Nominal</button>

            <div class="panel-title" style="margin-top: 10px;">
                <h2>Metode Pembayaran</h2>
                <p>Silakan pilih kanal pembayaran yang Anda inginkan.</p>
            </div>

            <div class="summary-box">
                <div class="sb-row"><span>Kategori</span><b id="sum-cat">-</b></div>
                <div class="sb-row"><span>Donatur</span><b id="sum-name">Hamba Allah</b></div>
                <div class="sb-row total"><span>Total Transfer</span><b id="sum-total">Rp 0</b></div>
            </div>

            <div class="payment-channels">
                <label class="channel-option">
                    <input type="radio" name="payment" value="QRIS" checked>
                    <div>
                        <strong>QRIS (All Payment)</strong>
                        <span>Scan pakai GoPay, OVO, Dana, BCA, Mandiri, dll</span>
                    </div>
                </label>
                <label class="channel-option">
                    <input type="radio" name="payment" value="Transfer Bank Syariah">
                    <div>
                        <strong>Transfer Bank Syariah Indonesia (BSI)</strong>
                        <span>No. Rek: 7123456789 a.n. Masjid</span>
                    </div>
                </label>
            </div>

            <button type="button" class="btn-submit" onclick="processDonation()" style="margin-top: 20px;">Konfirmasi & Selesaikan</button>
        </div>

        <!-- STEP 4: SUKSES -->
        <div class="panel-card hidden" id="panel-4" style="text-align: center;">
            <div class="success-icon">&#10003;</div>
            <h2 style="margin-bottom: 6px;">Jazaakumullahu Khairan</h2>
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Donasi Anda berhasil dicatat dalam sistem kebaikan masjid.</p>
            
            <div class="receipt-card">
                No. Transaksi: <b id="res-code">TRX-001</b><br>
                Kategori: <b id="res-cat">-</b><br>
                Jumlah: <b id="res-total">Rp 0</b>
            </div>

            <button type="button" class="btn-submit" onclick="resetAll()">Donasi Kembali</button>
        </div>

    </div>

    <script>
        window.categoryData = {!! $categoriesJson ?? '[]' !!};
    </script>
    <script src="{{ asset('js/donasi/donasi.js') }}?v={{ time() }}"></script>
</body>
</html>