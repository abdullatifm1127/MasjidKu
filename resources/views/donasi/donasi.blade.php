{{--
  resources/views/donasi.blade.php

  Ini masih file mandiri (<!DOCTYPE> penuh) supaya bisa langsung dibuka & diuji.
  Kalau project Anda sudah punya layout utama (navbar Beranda/Profil/dst di screenshot),
  ganti bagian <html>...<body> dengan:

    @extends('layouts.app')
    @section('title', 'Donasi - ' . $masjid->nama)
    @section('content')
      ...isi <div class="donasi-page"> di bawah...
    @endsection

  dan pindahkan <link>/<script> ke @push('styles') / @push('scripts') sesuai stack Anda.
--}}

@php
  // Idealnya ini dikirim dari controller (mis. DonasiController@index), bukan hardcode di view.
  $categories = [
    'zakat' => [
      'title' => 'Zakat',
      'desc'  => "Zakat fitrah & zakat mal, wajib bagi yang memenuhi nisab dan haul.",
      'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    ],
    'infaq' => [
      'title' => 'Infaq',
      'desc'  => 'Pemberian sukarela rutin untuk mendukung kegiatan masjid sehari-hari.',
      'icon'  => '<path d="M12 3v18M5 8h14M5 16h14"/>',
    ],
    'sedekah' => [
      'title' => 'Sedekah',
      'desc'  => 'Sedekah bebas, bisa untuk siapa saja yang membutuhkan bantuan.',
      'icon'  => '<path d="M12 21s-7-4.6-9.5-9C1 8 3 4 7 4c2 0 4 1.5 5 3 1-1.5 3-3 5-3 4 0 6 4 4.5 8-2.5 4.4-9.5 9-9.5 9Z"/>',
    ],
    'pembangunan' => [
      'title' => 'Pembangunan Masjid',
      'desc'  => 'Mendukung renovasi dan perluasan bangunan masjid yang sedang berjalan.',
      'icon'  => '<path d="M4 21V10l8-6 8 6v11M9 21v-7h6v7"/>',
    ],
    'yatim' => [
      'title' => 'Santunan Yatim & Dhuafa',
      'desc'  => 'Bantuan rutin bagi anak yatim dan keluarga dhuafa binaan masjid.',
      'icon'  => '<circle cx="12" cy="8" r="3.2"/><path d="M5 21c0-4 3-6.5 7-6.5S19 17 19 21"/>',
    ],
    'bencana' => [
      'title' => 'Bantuan Bencana',
      'desc'  => 'Donasi cepat untuk bencana alam atau musibah terkini di sekitar kita.',
      'icon'  => '<path d="M13 2 3 14h7l-1 8 11-14h-7l0-6Z"/>',
    ],
    'wakaf' => [
      'title' => 'Wakaf',
      'desc'  => "Aset produktif jangka panjang seperti tanah, sumur, atau Al-Qur'an.",
      'icon'  => '<path d="M12 3v18M6 7h12M6 7c0 5-2 6-2 6h16s-2-1-2-6"/>',
    ],
    'qurban' => [
      'title' => 'Qurban',
      'desc'  => 'Tabungan atau donasi hewan qurban untuk Idul Adha mendatang.',
      'icon'  => '<circle cx="12" cy="13" r="7"/><path d="M8 8 6 4M16 8l2-4"/>',
    ],
    'lainnya' => [
      'title' => 'Donasi Bebas',
      'desc'  => 'Donasi tanpa kategori khusus, disalurkan sesuai kebutuhan masjid.',
      'icon'  => '<path d="M12 5v14M5 12h14"/>',
    ],
  ];

  // dipakai JS (donasi.js) untuk menampilkan judul/deskripsi tanpa hit server lagi
  $categoriesJson = collect($categories)->map(fn ($c) => ['title' => $c['title'], 'desc' => $c['desc']]);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Donasi — {{ $mosque->mosque_name ?? 'Masjid' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/donasi/donasi.css') }}">
</head>
<body class="donasi-page">
<div class="wrap">

  <div class="top">
    <div class="mark">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 21V10l8-6 8 6v11" stroke="#c99a46"/><path d="M9 21v-7h6v7" stroke="#c99a46"/></svg>
    </div>
    <div class="name">Masjid <b>{{ $mosque->mosque_name ?? 'Masjid' }}</b> · {{ $mosque->city ?? '' }}</div>
  </div>

  <div class="steps">
    <div class="s" id="bar-1"></div>
    <div class="s" id="bar-2"></div>
    <div class="s" id="bar-3"></div>
    <div class="s" id="bar-4"></div>
  </div>

  {{-- STEP 1: pilih jenis --}}
  <section id="step-1">
    <div class="head">
      <div class="eyebrow">Langkah 1 dari 4</div>
      <h1>Pilih jenis donasi</h1>
      <p>Setiap jenis memiliki ketentuan dan penyaluran yang berbeda. Pilih yang sesuai dengan niat Anda.</p>
    </div>
    <div class="grid">
      @foreach ($categories as $key => $cat)
        <button class="cat" onclick="openDetail('{{ $key }}')">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">{!! $cat['icon'] !!}</svg>
          <h3>{{ $cat['title'] }}</h3>
          <p>{{ $cat['desc'] }}</p>
        </button>
      @endforeach
    </div>
    <div class="foot-link">Ingin tahu ke mana dana disalurkan? <a href="{{ '#' }}">Lihat laporan transparansi donasi</a></div>
  </section>

  {{-- STEP 2: detail / kalkulator --}}
  <section id="step-2" class="hidden">
    <div class="head">
      <div class="eyebrow">Langkah 2 dari 4</div>
      <h1 id="detail-title">Detail donasi</h1>
      <p id="detail-desc"></p>
    </div>

    <div class="panel">
      <button class="back" onclick="goStep(1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg> Ganti jenis donasi</button>

      <div id="zakat-subtype" class="subtype-row hidden">
        <button class="active" onclick="setZakatType('fitrah', this)">Zakat Fitrah</button>
        <button onclick="setZakatType('mal', this)">Zakat Mal</button>
      </div>

      <div id="calc-fitrah" class="hidden">
        <div class="row2">
          <div class="field"><label>Jumlah jiwa</label><input type="number" id="fitrah-jiwa" value="1" min="1" oninput="calcFitrah()"></div>
          <div class="field"><label>Nominal per jiwa (Rp)</label><input type="number" id="fitrah-nominal" value="{{ $zakatFitrahDefault ?? 45000 }}" step="1000" oninput="calcFitrah()"></div>
        </div>
        <div class="calc-note">Nominal per jiwa mengikuti harga beras standar wilayah {{ $mosque->city ?? '' }}, dapat disesuaikan admin masjid setiap tahun.</div>
        <div class="calc-total"><span>Total zakat fitrah</span><span id="fitrah-total">Rp 45.000</span></div>
      </div>

      <div id="calc-mal" class="hidden">
        <div class="field"><label>Total harta yang sudah mencapai haul (Rp)</label><input type="number" id="mal-harta" placeholder="0" oninput="calcMal()"></div>
        <div class="calc-note">Zakat mal wajib dikeluarkan sebesar 2,5% dari harta yang telah mencapai nisab (setara 85 gram emas) dan dimiliki selama satu tahun penuh (haul).</div>
        <div class="calc-total"><span>Zakat yang harus dibayar (2,5%)</span><span id="mal-total">Rp 0</span></div>
      </div>

      <div id="generic-amount" class="hidden">
        <div class="amount-grid">
          <button onclick="pickAmount(50000,this)">Rp 50.000</button>
          <button onclick="pickAmount(100000,this)">Rp 100.000</button>
          <button onclick="pickAmount(250000,this)">Rp 250.000</button>
          <button onclick="pickAmount(500000,this)">Rp 500.000</button>
        </div>
        <div class="field"><label>Atau masukkan nominal lain (Rp)</label><input type="number" id="custom-amount" placeholder="0" oninput="pickCustom()"></div>
      </div>

      <div class="field"><label>Nama (opsional)</label><input type="text" id="donor-name" placeholder="Hamba Allah"></div>

      <button class="cta" id="to-payment-btn" onclick="goStep(3)" disabled>Lanjut ke pembayaran</button>
    </div>
  </section>

  {{-- STEP 3: pembayaran --}}
  <section id="step-3" class="hidden">
    <div class="head">
      <div class="eyebrow">Langkah 3 dari 4</div>
      <h1>Pilih metode pembayaran</h1>
      <p>Periksa kembali ringkasan donasi Anda sebelum melanjutkan.</p>
    </div>
    <div class="panel">
      <button class="back" onclick="goStep(2)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg> Ubah detail donasi</button>

      <div class="summary-line"><span>Jenis donasi</span><b id="sum-jenis">—</b></div>
      <div class="summary-line"><span>Atas nama</span><b id="sum-nama">Hamba Allah</b></div>
      <div class="summary-line" style="border-bottom:none;padding-top:14px;"><span>Total dibayarkan</span><b id="sum-total" style="font-size:18px;">Rp 0</b></div>

      <div style="margin-top:22px;">
        <div class="paymethods">
          <div class="sel" onclick="selectPay(this)">QRIS</div>
          <div onclick="selectPay(this)">Transfer Bank</div>
          <div onclick="selectPay(this)">Dompet Digital</div>
        </div>
      </div>

      <button class="cta">Selesaikan Donasi</button>
    </div>
  </section>

  {{-- STEP 4: konfirmasi --}}
  <section id="step-4" class="hidden">
    <div class="head">
      <div class="eyebrow">Langkah 4 dari 4</div>
      <h1>Donasi berhasil dicatat</h1>
      <p>Terima kasih, semoga menjadi amal jariyah yang terus mengalir pahalanya.</p>
    </div>
    <div class="panel">
      <div class="confirm-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg></div>
      <h2 id="conf-title">Jazaakumullahu khairan</h2>
      <div class="sub">Bukti donasi telah dikirim ke sistem masjid.</div>
      <div class="receipt">
        No. referensi: <b>DN-PENDING</b><br>
        Jenis: <b id="conf-jenis">—</b><br>
        Nominal: <b id="conf-total">Rp 0</b><br>
        Metode: <b id="conf-method">QRIS</b>
      </div>
      <button class="cta" onclick="resetAll()">Donasi lagi</button>
    </div>
  </section>

</div>

{{-- data kategori dikirim ke JS lewat window, dibuat dari array PHP di atas, bukan diketik ulang --}}
<script>
  window.donasiCategories = @json($categoriesJson);
</script>
<script src="{{ asset('js/donasi/donasi.js') }}"></script>
</body>
</html>