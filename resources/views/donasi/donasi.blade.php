<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Donasi dan Zakat online untuk Masjid {{ $mosque->mosque_name ?? '' }}">
    <title>Donasi & Zakat — Masjid {{ $mosque->mosque_name ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/donasi/donasi.css') }}?v={{ time() }}">
</head>
<body>

    @php
        $heroMasuk   = (float) $categories->sum('total_masuk');
        $heroKeluar  = (float) $categories->sum('total_keluar');
        $heroProgram = $categories->count();
        $heroPct     = $heroMasuk > 0 ? min(round($heroKeluar / $heroMasuk * 100), 100) : 0;
    @endphp

    <!-- HERO -->
    <section class="hero">
        <svg class="hero-skyline" viewBox="0 0 1440 140" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
            <g fill="currentColor">
                <rect x="0" y="112" width="1440" height="30"/>
                <rect x="250" y="40" width="14" height="80"/><path d="M247 40l10-26 10 26z"/>
                <rect x="300" y="86" width="150" height="40"/><path d="M310 86a65 55 0 0 1 130 0z"/><rect x="373" y="14" width="4" height="22"/>
                <rect x="590" y="54" width="12" height="70"/><path d="M588 54l8-22 8 22z"/>
                <rect x="620" y="82" width="200" height="50"/><path d="M630 82a90 70 0 0 1 180 0z"/><rect x="718" y="2" width="4" height="22"/>
                <rect x="838" y="54" width="12" height="70"/><path d="M836 54l8-22 8 22z"/>
                <rect x="990" y="86" width="150" height="40"/><path d="M1000 86a65 55 0 0 1 130 0z"/><rect x="1063" y="14" width="4" height="22"/>
                <rect x="1176" y="40" width="14" height="80"/><path d="M1173 40l10-26 10 26z"/>
            </g>
        </svg>

        <div class="hero-inner">
            <div class="hero-copy">
                <div class="hero-bismillah" lang="ar" dir="rtl">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>
                <span class="hero-tag">Donasi &amp; Zakat Online</span>

                <header class="mosque-header">
                    <h1>Masjid {{ $mosque->mosque_name ?? 'Al-Ikhlas' }}</h1>
                    <p>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        {{ $mosque->city ?? 'Pusat Layanan Umat' }}
                    </p>
                </header>

                <p class="hero-lead">Salurkan infaq, zakat, dan wakaf Anda dengan mudah. Setiap rupiah tercatat dan penyalurannya didokumentasikan secara terbuka.</p>

                <div class="hero-actions">
                    <a class="hero-btn primary" href="#app">Mulai Donasi <span aria-hidden="true">&rarr;</span></a>
                    <a class="hero-btn ghost" href="#galeri">Lihat Dokumentasi</a>
                </div>
            </div>

            <aside class="hero-panel" aria-label="Ringkasan dana">
                <div class="hp-label">Total dana terkumpul</div>
                <div class="hp-total">Rp {{ number_format($heroMasuk, 0, ',', '.') }}</div>
                <div class="hp-bar"><i style="width: {{ $heroPct }}%"></i></div>
                <div class="hp-caption">{{ $heroPct }}% dana sudah tersalurkan</div>
                <div class="hp-grid">
                    <div class="hp-item gold"><b>Rp {{ number_format($heroKeluar, 0, ',', '.') }}</b><span>Telah disalurkan</span></div>
                    <div class="hp-item"><b>{{ $heroProgram }} program</b><span>Aktif saat ini</span></div>
                </div>
            </aside>
        </div>
    </section>

    <main class="donation-wrapper"
          id="app"
          data-submit-url="{{ $submitUrl ?? (\Illuminate\Support\Facades\Route::has('masjid.donasi.store') && isset($mosque) ? route('masjid.donasi.store', $mosque->slug) : '') }}"
          data-nisab="{{ $mosque->zakat_nisab ?? 85000000 }}">

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
                <span class="eyebrow">Mulai berbagi</span>
                <h2 id="panel-1-title">Pilih kategori donasi</h2>
                <p>Tentukan jenis kebaikan yang ingin Anda salurkan hari ini.</p>
            </div>

            @if ($categories->isEmpty())
                <p class="empty-box">Belum ada kategori donasi yang tersedia. Silakan kembali lagi nanti.</p>
            @else
                <div class="category-list">
                    @foreach ($categories as $cat)
                        @php
                            $fotoKategori = $cat->image ? asset('storage/' . $cat->image) : null;
                            $catCalc      = strtolower(trim($cat->calc_type ?? 'nominal'));
                            $catMasuk     = (float) ($cat->total_masuk ?? 0);
                            $catKeluar    = (float) ($cat->total_keluar ?? 0);
                            $catPct       = $catMasuk > 0 ? min(round($catKeluar / $catMasuk * 100), 100) : 0;
                        @endphp

                        <button type="button"
                                class="category-item"
                                data-key="{{ $cat->key }}"
                                data-title="{{ $cat->title }}"
                                data-calc-type="{{ $catCalc }}">

                            @if($fotoKategori)
                                <span class="ci-cover" style="background-image: url('{{ $fotoKategori }}')">
                                    <span class="ci-badge {{ $catCalc === 'zakat' ? 'zakat' : '' }}">{{ $catCalc === 'zakat' ? 'Kalkulator Zakat' : 'Donasi' }}</span>
                                </span>
                            @else
                                <span class="ci-cover ci-cover--icon" aria-hidden="true">
                                    <span class="ci-badge {{ $catCalc === 'zakat' ? 'zakat' : '' }}">{{ $catCalc === 'zakat' ? 'Kalkulator Zakat' : 'Donasi' }}</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                        {!! $cat->iconPath() !!}
                                    </svg>
                                </span>
                            @endif

                            <span class="ci-body">
                                <span>
                                    <span class="ci-info-title">{{ $cat->title }}</span>
                                    <span class="ci-info-desc">{{ $cat->description }}</span>
                                </span>

                                <span class="ci-stats">
                                    <span class="ci-stat"><small>Masuk</small><b>Rp {{ number_format($catMasuk, 0, ',', '.') }}</b></span>
                                    <span class="ci-stat gold"><small>Tersalur</small><b>Rp {{ number_format($catKeluar, 0, ',', '.') }}</b></span>
                                </span>

                                <span class="ci-progress">
                                    <span class="ci-progress-track"><span class="ci-progress-fill" style="width: {{ $catPct }}%"></span></span>
                                    <span class="ci-progress-text">{{ $catPct }}% dana sudah tersalurkan</span>
                                </span>

                                <span class="ci-cta">
                                    <span>Donasi sekarang</span>
                                    <span class="ci-arrow" aria-hidden="true">&rsaquo;</span>
                                </span>
                            </span>
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- RIWAYAT DONASI PRIBADI -->
            @auth
                <div class="history-card">
                    <div class="history-card-head">
                        <h2>Riwayat Donasi Anda</h2>
                        <p>Daftar transaksi donasi yang Anda lakukan dengan akun ini.</p>
                    </div>

                    @if(isset($recentDonations) && $recentDonations->isNotEmpty())
                        <ul class="history-list">
                            @foreach ($recentDonations as $don)
                                <li class="history-row">
                                    <span class="history-avatar" aria-hidden="true">{{ strtoupper(substr($don->nama_donatur ?: 'H', 0, 1)) }}</span>
                                    <div class="history-info">
                                        <span class="history-name">{{ $don->nama_donatur ?: 'Hamba Allah' }}</span>
                                        <span class="history-meta">
                                            <span class="history-badge">{{ $don->category_title }}</span>
                                            <span>{{ $don->created_at?->diffForHumans() }}</span>
                                            <span class="history-status {{ strtolower($don->status) }}">{{ $don->status }}</span>
                                        </span>
                                        @if (!empty($don->keterangan))
                                            <span class="history-note">“{{ $don->keterangan }}”</span>
                                        @endif
                                    </div>
                                    <span class="history-amount">Rp {{ number_format($don->nominal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="empty-box empty-box--tight" style="margin-top: 1rem;">Belum ada riwayat donasi tercatat dari akun Anda.</p>
                    @endif
                </div>
            @endauth

            <!-- GALERI DOKUMENTASI PENYALURAN -->
            <div class="gallery-block" id="galeri">
                <div class="panel-title panel-title--tight">
                    <span class="eyebrow">Transparansi</span>
                    <h2>Dokumentasi penyaluran</h2>
                    <p>Bukti transparansi penyaluran dana kebaikan dari jamaah.</p>
                </div>

                @php
                    $galItems        = isset($items) ? $items : collect();
                    $galPrograms     = isset($categories) ? $categories : collect();
                    $galFallbackFoto = 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=600&q=80';
                    $galToday        = \Carbon\Carbon::now()->translatedFormat('d F Y');

                    // Satu fungsi pencocokan dipakai di mana-mana, supaya konsisten
                    $matchProgram = function ($g, $c) {
                        $k = strtolower(trim((string) $g->kategori));
                        if ($k === '') {
                            return false;
                        }
                        return in_array($k, [
                            strtolower(trim((string) $c->key)),
                            strtolower(trim((string) $c->id)),
                            strtolower(trim((string) $c->title)),
                        ], true);
                    };

                    // Foto yang tidak punya program -> tetap jadi kartu mandiri
                    $galStandalone = $galItems->filter(function ($g) use ($galPrograms, $matchProgram) {
                        return !$galPrograms->contains(fn ($c) => $matchProgram($g, $c));
                    });
                @endphp

                @if($galPrograms->isNotEmpty() || $galItems->isNotEmpty())
                    <div class="public-gallery-grid">

                        {{-- Kartu program + riwayat penyaluran di dalam "Lihat detail" --}}
                        @foreach($galPrograms as $prog)
                            @php
                                $pMasuk  = (float) ($prog->total_masuk ?? 0);
                                $pKeluar = (float) ($prog->total_keluar ?? 0);
                                $pSisa   = $pMasuk - $pKeluar;
                                $pFoto   = !empty($prog->image) ? asset('storage/' . $prog->image) : $galFallbackFoto;

                                $pItems = $galItems->filter(fn ($g) => $matchProgram($g, $prog))->values();
                                $pDok   = $pItems->count();

                                $pHistory = $pItems->map(function ($g) {
                                    return [
                                        'judul'     => $g->judul,
                                        'nominal'   => 'Rp ' . number_format($g->nominal_terpakai ?? 0, 0, ',', '.'),
                                        'foto'      => $g->foto_url ?? asset('storage/' . $g->foto),
                                        'tanggal'   => $g->tanggal ? \Carbon\Carbon::parse($g->tanggal)->translatedFormat('d F Y') : '',
                                        'deskripsi' => $g->deskripsi,
                                    ];
                                })->all();

                                $pDesc = 'Dana masuk Rp ' . number_format($pMasuk, 0, ',', '.')
                                       . ' · Tersalurkan Rp ' . number_format($pKeluar, 0, ',', '.')
                                       . ' · Sisa Rp ' . number_format($pSisa, 0, ',', '.')
                                       . ' · ' . $pDok . ' dokumentasi foto.'
                                       . (!empty($prog->description) ? ' ' . $prog->description : '');
                            @endphp
                            <figure class="pub-gal-item"
                                    tabindex="0"
                                    role="button"
                                    aria-haspopup="dialog"
                                    data-title="Penyaluran {{ $prog->title }}"
                                    data-desc="{{ $pDesc }}"
                                    data-category="{{ $prog->title }}"
                                    data-photo="{{ $pFoto }}"
                                    data-amount="Rp {{ number_format($pKeluar, 0, ',', '.') }}"
                                    data-date="Per {{ $galToday }}"
                                    data-history="{{ json_encode($pHistory) }}">
                                <div class="pub-gal-img" style="background-image: url('{{ $pFoto }}')" role="img" aria-label="Penyaluran {{ $prog->title }}"></div>
                                <figcaption class="pub-gal-info">
                                    <span class="pub-tag">{{ $prog->title }}</span>
                                    <h4>Penyaluran {{ $prog->title }}</h4>
                                    <p>Masuk Rp {{ number_format($pMasuk, 0, ',', '.') }} &middot; Sisa Rp {{ number_format($pSisa, 0, ',', '.') }}</p>
                                    <div class="pub-meta">
                                        <span>Tersalur Rp {{ number_format($pKeluar, 0, ',', '.') }}</span>
                                        <span>{{ $pDok }} foto</span>
                                    </div>
                                    <span class="pub-gal-more">Lihat detail &rsaquo;</span>
                                </figcaption>
                            </figure>
                        @endforeach

                        {{-- Hanya foto TANPA program yang jadi kartu sendiri --}}
                        @foreach($galStandalone as $gal)
                            @php
                                $galDate    = $gal->tanggal ? \Carbon\Carbon::parse($gal->tanggal)->translatedFormat('d F Y') : '';
                                $galFoto    = $gal->foto_url ?? asset('storage/' . $gal->foto);
                                $galNominal = number_format($gal->nominal_terpakai ?? 0, 0, ',', '.');
                            @endphp
                            <figure class="pub-gal-item"
                                    tabindex="0"
                                    role="button"
                                    aria-haspopup="dialog"
                                    data-title="{{ $gal->judul }}"
                                    data-desc="{{ $gal->deskripsi }}"
                                    data-category="Umum"
                                    data-photo="{{ $galFoto }}"
                                    data-amount="Rp {{ $galNominal }}"
                                    data-date="{{ $galDate }}"
                                    data-history="[]">
                                <div class="pub-gal-img" style="background-image: url('{{ $galFoto }}')" role="img" aria-label="{{ $gal->judul }}"></div>
                                <figcaption class="pub-gal-info">
                                    <span class="pub-tag">Umum</span>
                                    <h4>{{ $gal->judul }}</h4>
                                    <p>{{ \Illuminate\Support\Str::limit($gal->deskripsi, 60) }}</p>
                                    <div class="pub-meta">
                                        <span>Rp {{ $galNominal }}</span>
                                        <span>{{ $galDate }}</span>
                                    </div>
                                    <span class="pub-gal-more">Lihat detail &rsaquo;</span>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                @else
                    <p class="empty-box empty-box--tight">Belum ada program donasi maupun dokumentasi penyaluran.</p>
                @endif
            </div>
        </section>

        <!-- STEP 2: MASUKKAN NOMINAL -->
        <section class="panel-card hidden" id="panel-2" aria-labelledby="panel-2-title">
            <button type="button" class="btn-back" data-goto-step="1">&larr; Kembali ke kategori</button>

            <div class="panel-title">
                <span class="eyebrow">Langkah 2</span>
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

            <div class="input-group">
                <label for="donor-note">Keterangan / peruntukan donasi (opsional)</label>
                <textarea id="donor-note" rows="3" maxlength="200"
                          placeholder="Contoh: untuk pembangunan menara, santunan anak yatim, atau infaq atas nama keluarga"></textarea>
                <small class="hint"><span id="note-count">0</span>/200 karakter</small>
            </div>

            <div class="total-display">
                <span>Total donasi</span>
                <strong id="final-amount-text" aria-live="polite">Rp 0</strong>
            </div>

            <p class="field-error hidden" id="amount-error" role="alert">Nominal donasi minimal Rp 5.000.</p>

            <button type="button" class="btn-submit" id="to-pay-btn" data-goto-step="3" disabled>Lanjut ke pembayaran</button>
        </section>

        <!-- STEP 3: PEMBAYARAN (DINAMIS DARI DATABASE) -->
        <section class="panel-card hidden" id="panel-3" aria-labelledby="panel-3-title">
            <button type="button" class="btn-back" data-goto-step="2">&larr; Ubah nominal</button>

            <div class="panel-title">
                <span class="eyebrow">Langkah 3</span>
                <h2 id="panel-3-title">Metode pembayaran</h2>
                <p>Silakan pilih kanal pembayaran yang Anda inginkan.</p>
            </div>

            <div class="summary-box">
                <div class="sb-row"><span>Kategori</span><b id="sum-cat">-</b></div>
                <div class="sb-row"><span>Donatur</span><b id="sum-name">Hamba Allah</b></div>
                <div class="sb-row"><span>Keterangan</span><b id="sum-note">-</b></div>
                <div class="sb-row total"><span>Total transfer</span><b id="sum-total">Rp 0</b></div>
            </div>

            <fieldset class="payment-channels">
                <legend class="sr-only">Pilih metode pembayaran</legend>

                {{-- QRIS Dinamis --}}
                @if ($mosque->qris_image)
                    <label class="channel-option">
                        <input type="radio" name="payment" value="QRIS" checked>
                        <span>
                            <strong>QRIS (semua metode)</strong>
                            <span>Scan pakai GoPay, OVO, Dana, BCA, Mandiri, dll</span>
                        </span>
                    </label>
                    <div class="qris-preview" data-for-payment="QRIS">
                        <img src="{{ $mosque->qris_url }}" alt="QRIS {{ $mosque->mosque_name }}">
                    </div>
                @endif

                {{-- Rekening Bank Dinamis (Multi-Bank) --}}
                @foreach ($mosque->activeBankAccounts as $rek)
                    <label class="channel-option">
                        <input type="radio" name="payment" value="Transfer Bank" data-bank-id="{{ $rek->id }}" {{ !$mosque->qris_image && $loop->first ? 'checked' : '' }}>
                        <span>
                            <strong>Transfer Bank {{ $rek->bank_name }}</strong>
                            <span class="account-row">
                                No. Rek: <b>{{ $rek->account_number }}</b> a.n. {{ $rek->account_holder }}
                                <button type="button" class="btn-copy" data-copy="{{ $rek->account_number }}" aria-label="Salin nomor rekening">Salin</button>
                            </span>
                        </span>
                    </label>
                @endforeach

                {{-- E-Wallet (Fallback jika masih ada data lama) --}}
                @if ($mosque->ewallet_number)
                    <label class="channel-option">
                        <input type="radio" name="payment" value="Dompet Digital">
                        <span>
                            <strong>Transfer E-Wallet</strong>
                            <span class="account-row">
                                No. HP: <b>{{ $mosque->ewallet_number }}</b> a.n. {{ $mosque->ewallet_account_name ?? $mosque->bank_account_name ?? 'Masjid' }}
                                <button type="button" class="btn-copy" data-copy="{{ $mosque->ewallet_number }}" aria-label="Salin nomor e-wallet">Salin</button>
                            </span>
                        </span>
                    </label>
                @endif
            </fieldset>

            <p class="hint hint--block">Setelah transfer, simpan nomor referensi yang muncul di layar berikutnya sebagai bukti. Admin masjid akan memverifikasi penerimaan dana secara manual.</p>

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
                <div class="receipt-row"><span>Keterangan</span><b id="res-note">-</b></div>
                <div class="receipt-row"><span>Status</span><b class="status-pending">Menunggu verifikasi admin</b></div>
            </div>

            <div class="receipt-actions">
                <a class="btn-secondary" id="share-wa" href="#" target="_blank" rel="noopener">Bagikan ke WhatsApp</a>
                <button type="button" class="btn-submit" id="reset-btn">Donasi kembali</button>
            </div>
        </section>

    </main>

    <footer class="page-foot">
        Dikelola secara transparan oleh pengurus Masjid {{ $mosque->mosque_name ?? '' }}.<br>
        Setiap donasi diverifikasi admin dan penyalurannya didokumentasikan.
    </footer>

    <!-- MODAL DETAIL DOKUMENTASI PENYALURAN -->
    <div class="gallery-modal hidden" id="gallery-modal" role="dialog" aria-modal="true" aria-labelledby="gm-title">
        <div class="gallery-modal-backdrop" data-close-modal></div>
        <div class="gallery-modal-panel">
            <button type="button" class="gallery-modal-close" data-close-modal aria-label="Tutup detail">&times;</button>
            <div class="gallery-modal-img" id="gm-img"></div>
            <div class="gallery-modal-body">
                <span class="pub-tag" id="gm-category">-</span>
                <h3 id="gm-title">-</h3>
                <p id="gm-desc" class="gallery-modal-desc">-</p>
                <div class="gallery-modal-meta">
                    <div><span>Dana terpakai</span><b id="gm-amount">Rp 0</b></div>
                    <div><span>Tanggal</span><b id="gm-date">-</b></div>
                </div>

                <!-- Riwayat penyaluran (diisi lewat JS dari data-history) -->
                <div class="gm-history hidden" id="gm-history">
                    <h4>Riwayat penyaluran:</h4>
                    <div id="gm-history-list"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/donasi/donasi.js') }}?v={{ time() }}" defer></script>
</body>
</html>