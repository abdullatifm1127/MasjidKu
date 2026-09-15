<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Donasi dan Zakat online untuk Masjid {{ $mosque->mosque_name ?? '' }}">
    <title>Donasi & Zakat — Masjid {{ $mosque->mosque_name ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/donasi/donasi.css') }}?v={{ time() }}">
</head>
<body>

    <!--
        data-submit-url : endpoint yang membuat donasi di server (POST, JSON in/out) ->
                           App\Http\Controllers\Donasi\DonasiController@store.
                           Response: { success: true, no_referensi: "DN-2609-00123" }.

                           CATATAN JUJUR SOAL ALUR PEMBAYARAN SAAT INI:
                           Sistem ini BELUM tersambung ke payment gateway otomatis
                           (Midtrans/Xendit/dsb). store() hanya mencatat niat donasi dengan
                           status "pending" dan mengembalikan nomor referensi. Donatur tetap
                           harus transfer manual sesuai metode yang dipilih, dan admin masjid
                           yang mengecek mutasi lalu menandai donasi "Diterima" di dashboard admin
                           (lihat DonasiAdminController::updateStatus). Karena itu JS TIDAK
                           menampilkan simulasi "pembayaran berhasil terverifikasi otomatis" —
                           yang ditampilkan hanya "donasi tercatat, nomor referensi ini",
                           supaya tidak menyesatkan donatur. Lihat CATATAN-BACKEND.md untuk opsi
                           menyambungkan payment gateway sungguhan ke depannya.
        data-nisab      : ambang nisab zakat mal saat ini (kolom mosques.zakat_nisab), hanya
                           untuk catatan bantuan di UI — validasi perhitungan tetap di server.
    -->
    <div class="donation-wrapper"
         id="app"
         data-submit-url="{{ $submitUrl ?? (\Illuminate\Support\Facades\Route::has('masjid.donasi.store') && isset($mosque) ? route('masjid.donasi.store', $mosque->slug) : '') }}"
         data-nisab="{{ $mosque->zakat_nisab ?? 85000000 }}">

        <!-- Header Identitas Masjid -->
        <header class="mosque-header">
            <div class="mosque-icon" aria-hidden="true">
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
        <ol class="stepper" role="list" aria-label="Tahapan donasi">
            <li class="step active" id="st-1" data-step="1" aria-current="step"><span aria-hidden="true">1</span> Kategori</li>
            <li class="step" id="st-2" data-step="2"><span aria-hidden="true">2</span> Nominal</li>
            <li class="step" id="st-3" data-step="3"><span aria-hidden="true">3</span> Pembayaran</li>
        </ol>

        <div role="status" aria-live="polite" class="sr-only" id="step-announcer"></div>

        <!-- STEP 1: PILIH KATEGORI & GALERI DOKUMENTASI -->
        <section class="panel-card" id="panel-1" aria-labelledby="panel-1-title">
            <div class="panel-title">
                <h2 id="panel-1-title">Pilih kategori donasi</h2>
                <p>Tentukan jenis kebaikan yang ingin Anda salurkan hari ini.</p>
            </div>

            @if ($categories->isEmpty())
                <p class="empty-box">Belum ada kategori donasi yang tersedia. Silakan kembali lagi nanti.</p>
            @else
                <div class="category-list">
                    @foreach ($categories as $cat)
                        {{-- data-key = kategori->key, dikirim sebagai field "jenis" ke
                             DonasiController@store (lihat validasi: 'jenis' => Rule::in($validKeys)
                             yang isinya categories()->pluck('key')). --}}
                        <button type="button"
                                class="category-item"
                                data-key="{{ $cat->key }}"
                                data-title="{{ $cat->title }}"
                                data-calc-type="{{ strtolower(trim($cat->calc_type ?? 'nominal')) }}">
                            <span class="ci-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    {!! $cat->iconPath() !!}
                                </svg>
                            </span>
                            <span class="ci-info">
                                <span class="ci-info-title">{{ $cat->title }}</span>
                                <span class="ci-info-desc">{{ $cat->description }}</span>
                            </span>
                            <span class="ci-arrow" aria-hidden="true">&rsaquo;</span>
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- RIWAYAT DONASI: kartu tersendiri, terpisah dari daftar kategori -->
            {{-- Hanya donasi yang sudah diverifikasi admin (status "diterima") yang tampil di
                 sini — lihat PublicMosqueController::showDonasi(). Donasi yang masih menunggu
                 verifikasi sengaja tidak ditampilkan ke publik. --}}
            @isset($recentDonations)
                @if ($recentDonations->isNotEmpty())
                    <div class="history-card">
                        <div class="history-card-head">
                            <h2>Riwayat donasi</h2>
                            <p>Donasi yang telah diverifikasi oleh pengurus masjid.</p>
                        </div>
                        <ul class="history-list">
                            @foreach ($recentDonations as $don)
                                <li class="history-row">
                                    <span class="history-avatar" aria-hidden="true">{{ strtoupper(substr($don->nama_donatur ?: 'H', 0, 1)) }}</span>
                                    <span class="history-info">
                                        <span class="history-name">{{ $don->nama_donatur ?: 'Hamba Allah' }}</span>
                                        <span class="history-meta">
                                            <span class="history-badge">{{ $don->category_title }}</span>
                                            <span class="history-time">{{ $don->created_at?->diffForHumans() }}</span>
                                        </span>
                                    </span>
                                    <span class="history-amount">Rp {{ number_format($don->nominal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endisset

            <!-- GALERI DOKUMENTASI PENYALURAN -->
            <div class="gallery-block">
                <div class="panel-title panel-title--tight">
                    <h2>Dokumentasi penyaluran</h2>
                    <p>Bukti transparansi penyaluran dana kebaikan dari jamaah.</p>
                </div>

                @if(isset($items) && $items->isNotEmpty())
                    <div class="public-gallery-grid">
                        @foreach($items as $gal)
                            @php
                                // $categories di sini adalah kategori AKTIF saja (lihat DonasiController::categories()),
                                // jadi kalau kategori dinonaktifkan/dihapus, badge otomatis jatuh ke "Penyaluran".
                                $galCatTitle = optional($categories->firstWhere('key', $gal->kategori))->title ?? 'Penyaluran';
                            @endphp
                            <figure class="pub-gal-item">
                                <div class="pub-gal-img" style="background-image: url('{{ asset('storage/' . $gal->foto) }}')" role="img" aria-label="{{ $gal->judul }}"></div>
                                <figcaption class="pub-gal-info">
                                    <span class="pub-tag">{{ $galCatTitle }}</span>
                                    <h4>{{ $gal->judul }}</h4>
                                    <p>{{ Str::limit($gal->deskripsi, 60) }}</p>
                                    <div class="pub-meta">
                                        <span>Rp {{ number_format($gal->nominal_terpakai ?? 0, 0, ',', '.') }}</span>
                                        <span>{{ $gal->tanggal ? \Carbon\Carbon::parse($gal->tanggal)->format('d M Y') : '' }}</span>
                                    </div>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                @else
                    <p class="empty-box empty-box--tight">Belum ada dokumentasi foto penyaluran yang diunggah.</p>
                @endif
            </div>
        </section>

        <!-- STEP 2: MASUKKAN NOMINAL -->
        <section class="panel-card hidden" id="panel-2" aria-labelledby="panel-2-title">
            <button type="button" class="btn-back" data-goto-step="1">&larr; Kembali ke kategori</button>

            <div class="panel-title">
                <h2 id="panel-2-title" data-role="selected-cat-title">Nominal donasi</h2>
                <p>Pilih atau ketik jumlah dana yang ingin disumbangkan.</p>
            </div>

            <!-- Kalkulator Zakat -->
            <div id="section-zakat" class="hidden">
                <div class="zakat-toggle" role="tablist" aria-label="Jenis zakat">
                    <button type="button" class="z-tab active" role="tab" aria-selected="true" data-zakat-sub="fitrah">Zakat Fitrah</button>
                    <button type="button" class="z-tab" role="tab" aria-selected="false" data-zakat-sub="mal">Zakat Mal</button>
                </div>

                <div id="form-fitrah">
                    <div class="input-group">
                        <label for="f-jiwa">Jumlah jiwa</label>
                        <input type="number" id="f-jiwa" inputmode="numeric" value="1" min="1" max="50" step="1">
                    </div>
                    <div class="input-group">
                        <label for="f-nominal">Nominal per jiwa (Rp)</label>
                        <input type="number" id="f-nominal" inputmode="numeric" min="0" step="1000" value="{{ $mosque->zakat_fitrah_default ?? 45000 }}">
                    </div>
                </div>

                <div id="form-mal" class="hidden">
                    <div class="input-group">
                        <label for="m-harta">Total harta haul (Rp)</label>
                        <input type="text" id="m-harta" inputmode="numeric" placeholder="Contoh: 80.000.000">
                        <small class="hint">Dizakatkan 2,5% dari total harta yang sudah mencapai haul dan nisab.</small>
                        <small class="hint hint--nisab" id="nisab-note" hidden></small>
                    </div>
                </div>
            </div>

            <!-- Nominal Cepat (Donasi Umum) -->
            <div id="section-nominal">
                <div class="nominal-chips" role="group" aria-label="Pilihan nominal cepat">
                    <button type="button" data-amount="20000">Rp 20.000</button>
                    <button type="button" data-amount="50000">Rp 50.000</button>
                    <button type="button" data-amount="100000">Rp 100.000</button>
                    <button type="button" data-amount="250000">Rp 250.000</button>
                </div>
                <div class="input-group">
                    <label for="custom-nominal">Atau masukkan nominal lain (Rp)</label>
                    <input type="text" id="custom-nominal" inputmode="numeric" placeholder="Contoh: 75.000">
                    <small class="hint">Minimal donasi Rp 5.000.</small>
                </div>
            </div>

            <div class="input-group">
                <label for="donor-name">Nama donatur (opsional)</label>
                <input type="text" id="donor-name" maxlength="60" placeholder="Tulis nama atau kosongkan untuk Hamba Allah">
            </div>

            <div class="total-display">
                <span>Total donasi</span>
                <strong id="final-amount-text" aria-live="polite">Rp 0</strong>
            </div>

            <p class="field-error hidden" id="amount-error" role="alert">Nominal donasi minimal Rp 5.000.</p>

            <button type="button" class="btn-submit" id="to-pay-btn" data-goto-step="3" disabled>Lanjut ke pembayaran</button>
        </section>

        <!-- STEP 3: PEMBAYARAN -->
        <section class="panel-card hidden" id="panel-3" aria-labelledby="panel-3-title">
            <button type="button" class="btn-back" data-goto-step="2">&larr; Ubah nominal</button>

            <div class="panel-title">
                <h2 id="panel-3-title">Metode pembayaran</h2>
                <p>Silakan pilih kanal pembayaran yang Anda inginkan.</p>
            </div>

            <div class="summary-box">
                <div class="sb-row"><span>Kategori</span><b id="sum-cat">-</b></div>
                <div class="sb-row"><span>Donatur</span><b id="sum-name">Hamba Allah</b></div>
                <div class="sb-row total"><span>Total transfer</span><b id="sum-total">Rp 0</b></div>
            </div>

            {{-- Nilai radio HARUS persis sama dengan enum yang divalidasi
                 DonasiController@store: Rule::in(['QRIS', 'Transfer Bank', 'Dompet Digital']). --}}
            <fieldset class="payment-channels">
                <legend class="sr-only">Pilih metode pembayaran</legend>
                <label class="channel-option">
                    <input type="radio" name="payment" value="QRIS" checked>
                    <span>
                        <strong>QRIS (semua metode)</strong>
                        <span>Scan pakai GoPay, OVO, Dana, BCA, Mandiri, dll</span>
                    </span>
                </label>
                <label class="channel-option">
                    <input type="radio" name="payment" value="Transfer Bank">
                    <span>
                        <strong>Transfer Bank Syariah Indonesia (BSI)</strong>
                        <span class="account-row">
                            No. Rek: <b id="bsi-account">{{ $mosque->bank_account_number ?? '7123456789' }}</b> a.n. {{ $mosque->bank_account_name ?? 'Masjid' }}
                            <button type="button" class="btn-copy" data-copy="{{ $mosque->bank_account_number ?? '7123456789' }}" aria-label="Salin nomor rekening">Salin</button>
                        </span>
                    </span>
                </label>
                <label class="channel-option">
                    <input type="radio" name="payment" value="Dompet Digital">
                    <span>
                        <strong>Transfer E-Wallet</strong>
                        <span class="account-row">
                            No. HP: <b id="ewallet-account">{{ $mosque->ewallet_number ?? '081234567890' }}</b> a.n. {{ $mosque->ewallet_account_name ?? $mosque->bank_account_name ?? 'Masjid' }}
                            <button type="button" class="btn-copy" data-copy="{{ $mosque->ewallet_number ?? '081234567890' }}" aria-label="Salin nomor e-wallet">Salin</button>
                        </span>
                    </span>
                </label>
            </fieldset>

            <p class="hint" style="margin-bottom: 14px;">Setelah transfer, simpan nomor referensi yang muncul di layar berikutnya sebagai bukti. Admin masjid akan memverifikasi penerimaan dana secara manual.</p>

            <p class="field-error hidden" id="submit-error" role="alert"></p>

            <button type="button" class="btn-submit" id="confirm-btn">
                <span class="btn-label">Catat Donasi Saya</span>
                <span class="btn-spinner hidden" aria-hidden="true"></span>
            </button>
        </section>

        <!-- STEP 4: DONASI TERCATAT -->
        <section class="panel-card hidden" id="panel-4" aria-labelledby="panel-4-title" style="text-align: center;">
            <div class="success-icon" aria-hidden="true">&#10003;</div>
            <h2 id="panel-4-title">Jazaakumullahu khairan</h2>
            <p class="success-sub">Donasi Anda tercatat. Selesaikan pembayaran sesuai metode yang Anda pilih, lalu simpan nomor referensi di bawah sebagai bukti — admin masjid akan memverifikasi penerimaan dana secara manual.</p>

            <div class="receipt-card">
                <div class="receipt-row">
                    <span>No. Referensi</span>
                    <b id="res-code">-</b>
                    <button type="button" class="btn-copy btn-copy--inline" id="copy-trx" aria-label="Salin nomor referensi">Salin</button>
                </div>
                <div class="receipt-row"><span>Kategori</span><b id="res-cat">-</b></div>
                <div class="receipt-row"><span>Jumlah</span><b id="res-total">Rp 0</b></div>
                <div class="receipt-row"><span>Status</span><b style="color:#b45309;">Menunggu verifikasi admin</b></div>
            </div>

            <div class="receipt-actions">
                <a class="btn-secondary" id="share-wa" href="#" target="_blank" rel="noopener">Bagikan ke WhatsApp</a>
                <button type="button" class="btn-submit" id="reset-btn">Donasi kembali</button>
            </div>
        </section>

    </div>

    <script src="{{ asset('js/donasi/donasi.js') }}?v={{ time() }}" defer></script>
</body>
</html>