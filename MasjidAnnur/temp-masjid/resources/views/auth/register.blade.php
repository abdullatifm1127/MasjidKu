<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftarkan Masjid — Baitul Digital</title><link rel="stylesheet" href="{{ asset('css/masjid.css') }}">
</head>
<body class="register-page">
<div class="register-shell">
<header class="register-head"><a class="auth-brand" href="{{ url('/') }}">🕌 <span>Baitul <strong>Digital</strong></span></a><a href="{{ route('login') }}">Sudah punya akun? <strong>Masuk</strong></a></header>
<div class="register-card">
<div class="section-heading left"><span class="section-kicker">REGISTRASI MASJID</span><h1>Daftarkan Masjid Anda</h1><p>Lengkapi informasi berikut untuk membuat profil masjid.</p></div>
<div class="progress-steps" id="progress"></div>
<form id="mosqueForm" method="POST" action="{{ url('/register') }}">
@csrf
<section class="form-step active">
<h2>Informasi Masjid</h2><p class="auth-muted">Data utama masjid yang akan ditampilkan di platform.</p>
<div class="form-grid two">
<div><label>Nama Masjid *</label><input class="form-input" name="mosque_name" placeholder="cth. Masjid Al-Ikhlas" required></div>
<div><label>Nama Arab</label><input class="form-input" name="arabic_name" placeholder="مسجد الإخلاص" dir="rtl"></div>
</div>
<div><label>Tagline / Slogan Masjid</label><input class="form-input" name="tagline" placeholder="cth. Masjid Rahmatan Lil Alamin"></div>
<div class="form-grid two"><div><label>Tahun Berdiri *</label><input class="form-input" type="number" name="founded" placeholder="1990" required></div><div><label>Kapasitas Jamaah *</label><input class="form-input" name="capacity" placeholder="1.500 orang" required></div></div>
<div><label>Alamat Lengkap *</label><textarea class="form-input" name="address" rows="2" required placeholder="Nama jalan, nomor, RT/RW"></textarea></div>
<div class="form-grid three"><div><label>Kelurahan *</label><input class="form-input" name="kelurahan" required></div><div><label>Kecamatan *</label><input class="form-input" name="kecamatan" required></div><div><label>Kode Pos</label><input class="form-input" name="postal_code"></div></div>
<div class="form-grid two"><div><label>Kota / Kabupaten *</label><input class="form-input" name="city" required></div><div><label>Provinsi *</label><select class="form-select" name="province" required><option value="">Pilih Provinsi</option>@foreach(['DKI Jakarta','Jawa Barat','Jawa Tengah','Jawa Timur','Banten','DI Yogyakarta','Sumatera Utara','Sumatera Barat','Sumatera Selatan','Riau','Kalimantan Timur','Kalimantan Selatan','Sulawesi Selatan','Bali','Nusa Tenggara Barat','Papua','Maluku','Aceh'] as $p)<option>{{ $p }}</option>@endforeach</select></div></div>
<div class="form-grid three"><div><label>Nomor Telepon *</label><input class="form-input" name="phone" required></div><div><label>Email Masjid *</label><input class="form-input" type="email" name="email" required></div><div><label>Website</label><input class="form-input" name="website" placeholder="https://"></div></div>
</section>
<section class="form-step">
<h2>Data Pengurus</h2><p class="auth-muted">Informasi pengurus yang bertanggung jawab atas masjid.</p>
<div><label>Nama Organisasi / Yayasan</label><input class="form-input" name="organization_name" placeholder="Nama yayasan atau DKM"></div>
<div class="subsection"><h3>Imam / Khatib Utama</h3><div class="form-grid two"><div><label>Nama Lengkap *</label><input class="form-input" name="imam_name"></div><div><label>Nomor HP</label><input class="form-input" name="imam_phone"></div></div></div>
<div class="subsection"><h3>Ketua Takmir / DKM</h3><div class="form-grid two"><div><label>Nama Lengkap *</label><input class="form-input" name="chairman_name"></div><div><label>Nomor HP *</label><input class="form-input" name="chairman_phone"></div></div></div>
<div class="subsection"><h3>Sekretaris & Bendahara</h3><div class="form-grid two"><div><label>Nama Sekretaris</label><input class="form-input" name="secretary_name"></div><div><label>Nama Bendahara</label><input class="form-input" name="treasurer_name"></div></div></div>
</section>
<section class="form-step"><h2>Fasilitas & Program</h2><p class="auth-muted">Pilih fasilitas dan program yang tersedia.</p>
<label>Fasilitas</label><div class="chip-grid">@foreach(['Parkir Luas','Toilet Bersih','Tempat Wudhu Memadai','AC / Kipas Angin','Sound System','Proyektor / Layar','Perpustakaan','Klinik / Poliklinik','Kantin / Dapur','Ruang Pertemuan','Area Bermain Anak','Wifi Jamaah'] as $x)<label class="chip"><input type="checkbox" name="facilities[]" value="{{ $x }}"><span>{{ $x }}</span></label>@endforeach</div>
<label>Program</label><div class="chip-grid">@foreach(['TPA / TPQ','Tahsin Al-Quran','Tahfidz Al-Quran','Majelis Taklim','Kajian Hadits','Kajian Fiqih','Pengajian Bulanan','Konsultasi Agama','Zakat & Infaq','Program Yatim Piatu','Koperasi Syariah','Kesehatan Gratis','Beasiswa Santri','Nikah Gratis Dhuafa','Pemberdayaan Ekonomi'] as $x)<label class="chip"><input type="checkbox" name="programs[]" value="{{ $x }}"><span>{{ $x }}</span></label>@endforeach</div>
<div class="form-grid two"><label class="switch-row"><input type="checkbox" name="has_online_donation"><span>💳 Donasi online</span></label><label class="switch-row"><input type="checkbox" name="has_prayer_schedule"><span>🕐 Jadwal shalat</span></label></div>
<label>Deskripsi Masjid</label><textarea class="form-input" name="description" rows="4" placeholder="Ceritakan singkat tentang masjid..."></textarea>
</section>
<section class="form-step"><h2>Konfirmasi</h2><p class="auth-muted">Periksa kembali data sebelum mengirim pendaftaran.</p><div class="confirm-box">Data masjid dan pengurus akan dikirim ke sistem untuk diproses.</div><label class="check agree"><input type="checkbox" name="agree" required> Saya menyatakan data yang saya masukkan benar.</label></section>
<div class="wizard-actions"><button class="btn btn-outline" type="button" id="prev">← Kembali</button><button class="btn btn-green" type="button" id="next">Lanjut →</button><button class="btn btn-gold" type="submit" id="submit" hidden>Daftarkan Masjid ✓</button></div>
</form>
</div></div>
<script src="{{ asset('js/masjid.js') }}"></script>
</body></html>