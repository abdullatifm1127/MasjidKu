<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftarkan Masjid - Masjid Annur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registerMasjid.css') }}">
</head>
<body class="rm-page">

    {{-- ===== NAVBAR ===== --}}
    <nav class="rm-navbar">
        <a href="{{ url('/') }}" class="nav-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('login') }}" class="nav-masuk">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Masuk
        </a>
    </nav>

    {{-- ===== MAIN ===== --}}
    <main class="rm-container">

        {{-- Progress Steps --}}
        <div class="rm-steps" id="rmSteps">
            <div class="rm-step is-active" id="rmS1">
                <div class="rm-step-num">1</div>
                <span class="rm-step-label">Informasi Masjid</span>
            </div>
            <div class="rm-step-line" id="rmL1"></div>
            <div class="rm-step is-inactive" id="rmS2">
                <div class="rm-step-num">2</div>
                <span class="rm-step-label">Data Pengurus</span>
            </div>
            <div class="rm-step-line" id="rmL2"></div>
            <div class="rm-step is-inactive" id="rmS3">
                <div class="rm-step-num">3</div>
                <span class="rm-step-label">Fasilitas</span>
            </div>
            <div class="rm-step-line" id="rmL3"></div>
            <div class="rm-step is-inactive" id="rmS4">
                <div class="rm-step-num">4</div>
                <span class="rm-step-label">Konfirmasi</span>
            </div>
        </div>

        {{-- ===== FORM ===== --}}
        <form id="rmForm" method="POST" action="{{ route('daftar.masjid') }}">
            @csrf

            {{-- ─── PANEL 1 : Informasi Masjid ─── --}}
            <div class="rm-panel" id="rmP1">
                <div class="rm-card">
                    <h2 class="rm-card-title">Informasi Masjid</h2>
                    <p class="rm-card-subtitle">Data utama masjid yang akan ditampilkan di platform.</p>

                    {{-- Nama Masjid + Nama Arab --}}
                    <div class="rm-grid-2" style="margin-bottom:16px;">
                        <div class="rm-group">
                            <label>Nama Masjid <span class="req">*</span></label>
                            <input class="rm-input" name="mosque_name" type="text"
                                   placeholder="cth. Masjid Al-Ikhlas"
                                   value="{{ old('mosque_name') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Nama Arab <span class="opt">(opsional)</span></label>
                            <input class="rm-input" name="arabic_name" type="text"
                                   placeholder="مسجد ..." dir="rtl"
                                   value="{{ old('arabic_name') }}">
                            <span class="rm-hint">Contoh: مسجد الإخلاص</span>
                        </div>
                    </div>

                    {{-- Tagline --}}
                    <div class="rm-group" style="margin-bottom:16px;">
                        <label>Tagline / Slogan Masjid</label>
                        <input class="rm-input" name="tagline" type="text"
                               placeholder="cth. Masjid Rahmatan Lil Alamin"
                               value="{{ old('tagline') }}">
                        <span class="rm-hint">Kalimat singkat yang menggambarkan visi masjid Anda</span>
                    </div>

                    {{-- Tahun Berdiri + Kapasitas --}}
                    <div class="rm-grid-2" style="margin-bottom:16px;">
                        <div class="rm-group">
                            <label>Tahun Berdiri <span class="req">*</span></label>
                            <input class="rm-input" name="founded" type="number"
                                   placeholder="cth. 1990" min="1800" max="{{ date('Y') }}"
                                   value="{{ old('founded') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Kapasitas Jamaah <span class="req">*</span></label>
                            <input class="rm-input" name="capacity" type="text"
                                   placeholder="cth. 1.500 orang"
                                   value="{{ old('capacity') }}" required>
                            <span class="rm-hint">Perkiraan jumlah jamaah yang dapat ditampung</span>
                        </div>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="rm-group" style="margin-bottom:16px;">
                        <label>Alamat Lengkap <span class="req">*</span></label>
                        <textarea class="rm-textarea" name="address" rows="2"
                                  placeholder="Nama jalan, nomor, RT/RW" required>{{ old('address') }}</textarea>
                    </div>

                    {{-- Kelurahan + Kecamatan + Kode Pos --}}
                    <div class="rm-grid-3" style="margin-bottom:16px;">
                        <div class="rm-group">
                            <label>Kelurahan <span class="req">*</span></label>
                            <input class="rm-input" name="kelurahan" type="text"
                                   placeholder="Kelurahan"
                                   value="{{ old('kelurahan') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Kecamatan <span class="req">*</span></label>
                            <input class="rm-input" name="kecamatan" type="text"
                                   placeholder="Kecamatan"
                                   value="{{ old('kecamatan') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Kode Pos</label>
                            <input class="rm-input" name="postal_code" type="text"
                                   placeholder="12345"
                                   value="{{ old('postal_code') }}">
                        </div>
                    </div>

                    {{-- Kota + Provinsi --}}
                    <div class="rm-grid-2" style="margin-bottom:16px;">
                        <div class="rm-group">
                            <label>Kota / Kabupaten <span class="req">*</span></label>
                            <input class="rm-input" name="city" type="text"
                                   placeholder="cth. Jakarta Selatan"
                                   value="{{ old('city') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Provinsi <span class="req">*</span></label>
                            <select class="rm-select" name="province" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach(['Aceh','Sumatera Utara','Sumatera Barat','Riau','Kepulauan Riau','Jambi','Sumatera Selatan','Kepulauan Bangka Belitung','Bengkulu','Lampung','DKI Jakarta','Jawa Barat','Banten','Jawa Tengah','DI Yogyakarta','Jawa Timur','Bali','Nusa Tenggara Barat','Nusa Tenggara Timur','Kalimantan Barat','Kalimantan Tengah','Kalimantan Selatan','Kalimantan Timur','Kalimantan Utara','Sulawesi Utara','Gorontalo','Sulawesi Tengah','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tenggara','Maluku','Maluku Utara','Papua Barat','Papua','Papua Selatan','Papua Tengah','Papua Pegunungan'] as $prov)
                                    <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Telepon + Email + Website --}}
                    <div class="rm-grid-3">
                        <div class="rm-group">
                            <label>Nomor Telepon <span class="req">*</span></label>
                            <input class="rm-input" name="phone" type="tel"
                                   placeholder="+62 21 ..."
                                   value="{{ old('phone') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Email Masjid <span class="req">*</span></label>
                            <input class="rm-input" name="email" type="email"
                                   placeholder="info@masjid.id"
                                   value="{{ old('email') }}" required>
                        </div>
                        <div class="rm-group">
                            <label>Website <span class="opt">(opsional)</span></label>
                            <input class="rm-input" name="website" type="url"
                                   placeholder="https://masjid.id"
                                   value="{{ old('website') }}">
                        </div>
                    </div>
                </div>

                <div class="rm-nav-buttons">
                    <span class="btn-rm-prev" style="opacity:0; pointer-events:none;">← Sebelumnya</span>
                    <button type="button" class="btn-rm-next" id="rmNext1">
                        Lanjut →
                    </button>
                </div>
            </div>

            {{-- ─── PANEL 2 : Data Pengurus ─── --}}
            <div class="rm-panel" id="rmP2" style="display:none;">
                <div class="rm-card">
                    <h2 class="rm-card-title">Data Pengurus</h2>
                    <p class="rm-card-subtitle">Informasi pengurus yang bertanggung jawab atas masjid.</p>

                    <div class="rm-group" style="margin-bottom:16px;">
                        <label>Nama Organisasi / Yayasan</label>
                        <input class="rm-input" name="organization_name" type="text"
                               placeholder="Nama yayasan atau DKM"
                               value="{{ old('organization_name') }}">
                    </div>

                    <p class="rm-section-title">Imam / Khatib Utama</p>
                    <div class="rm-grid-2" style="margin-bottom:20px;">
                        <div class="rm-group">
                            <label>Nama Lengkap <span class="req">*</span></label>
                            <input class="rm-input" name="imam_name" type="text"
                                   placeholder="Nama imam" value="{{ old('imam_name') }}">
                        </div>
                        <div class="rm-group">
                            <label>Nomor HP</label>
                            <input class="rm-input" name="imam_phone" type="tel"
                                   placeholder="+62 8xx ..." value="{{ old('imam_phone') }}">
                        </div>
                    </div>

                    <p class="rm-section-title">Ketua Takmir / DKM</p>
                    <div class="rm-grid-2" style="margin-bottom:20px;">
                        <div class="rm-group">
                            <label>Nama Lengkap <span class="req">*</span></label>
                            <input class="rm-input" name="chairman_name" type="text"
                                   placeholder="Nama ketua" value="{{ old('chairman_name') }}">
                        </div>
                        <div class="rm-group">
                            <label>Nomor HP <span class="req">*</span></label>
                            <input class="rm-input" name="chairman_phone" type="tel"
                                   placeholder="+62 8xx ..." value="{{ old('chairman_phone') }}">
                        </div>
                    </div>

                    <p class="rm-section-title">Sekretaris & Bendahara</p>
                    <div class="rm-grid-2">
                        <div class="rm-group">
                            <label>Nama Sekretaris</label>
                            <input class="rm-input" name="secretary_name" type="text"
                                   placeholder="Nama sekretaris" value="{{ old('secretary_name') }}">
                        </div>
                        <div class="rm-group">
                            <label>Nama Bendahara</label>
                            <input class="rm-input" name="treasurer_name" type="text"
                                   placeholder="Nama bendahara" value="{{ old('treasurer_name') }}">
                        </div>
                    </div>
                </div>

                <div class="rm-nav-buttons">
                    <button type="button" class="btn-rm-prev" id="rmPrev2">← Sebelumnya</button>
                    <button type="button" class="btn-rm-next" id="rmNext2">Lanjut →</button>
                </div>
            </div>

            {{-- ─── PANEL 3 : Fasilitas & Program ─── --}}
            <div class="rm-panel" id="rmP3" style="display:none;">
                <div class="rm-card">
                    <h2 class="rm-card-title">Fasilitas & Program</h2>
                    <p class="rm-card-subtitle">Pilih fasilitas dan program yang tersedia di masjid Anda.</p>

                    <p class="rm-section-title">Fasilitas</p>
                    <div class="rm-chip-grid" style="margin-bottom:24px;">
                        @foreach(['Parkir Luas','Toilet Bersih','Tempat Wudhu Memadai','AC / Kipas Angin','Sound System','Proyektor / Layar','Perpustakaan','Klinik / Poliklinik','Kantin / Dapur','Ruang Pertemuan','Area Bermain Anak','WiFi Jamaah'] as $f)
                            <label class="rm-chip">
                                <input type="checkbox" name="facilities[]" value="{{ $f }}">
                                {{ $f }}
                            </label>
                        @endforeach
                    </div>

                    <p class="rm-section-title">Program</p>
                    <div class="rm-chip-grid" style="margin-bottom:24px;">
                        @foreach(['TPA / TPQ','Tahsin Al-Quran','Tahfidz Al-Quran','Majelis Taklim','Kajian Hadits','Kajian Fiqih','Pengajian Bulanan','Konsultasi Agama','Zakat & Infaq','Program Yatim Piatu','Koperasi Syariah','Kesehatan Gratis','Beasiswa Santri','Nikah Gratis Dhuafa','Pemberdayaan Ekonomi'] as $p)
                            <label class="rm-chip">
                                <input type="checkbox" name="programs[]" value="{{ $p }}">
                                {{ $p }}
                            </label>
                        @endforeach
                    </div>

                    <p class="rm-section-title">Layanan Digital</p>
                    <div>
                        <div class="rm-toggle-row">
                            <div>
                                <div class="rm-toggle-label">💳 Donasi Online</div>
                                <div class="rm-toggle-desc">Terima donasi melalui platform digital</div>
                            </div>
                            <label class="rm-toggle">
                                <input type="checkbox" name="has_online_donation">
                                <span class="rm-toggle-track"></span>
                            </label>
                        </div>
                        <div class="rm-toggle-row">
                            <div>
                                <div class="rm-toggle-label">🕐 Jadwal Shalat</div>
                                <div class="rm-toggle-desc">Tampilkan jadwal shalat di halaman masjid</div>
                            </div>
                            <label class="rm-toggle">
                                <input type="checkbox" name="has_prayer_schedule">
                                <span class="rm-toggle-track"></span>
                            </label>
                        </div>
                    </div>

                    <div class="rm-group" style="margin-top:20px;">
                        <label>Deskripsi Masjid</label>
                        <textarea class="rm-textarea" name="description" rows="4"
                                  placeholder="Ceritakan singkat tentang masjid Anda...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="rm-nav-buttons">
                    <button type="button" class="btn-rm-prev" id="rmPrev3">← Sebelumnya</button>
                    <button type="button" class="btn-rm-next" id="rmNext3">Lanjut →</button>
                </div>
            </div>

            {{-- ─── PANEL 4 : Konfirmasi ─── --}}
            <div class="rm-panel" id="rmP4" style="display:none;">
                <div class="rm-card">
                    <h2 class="rm-card-title">Konfirmasi Pendaftaran</h2>
                    <p class="rm-card-subtitle">Periksa kembali data sebelum mengirim pendaftaran masjid.</p>

                    <div class="rm-confirm-box">
                        Data masjid dan pengurus yang Anda masukkan akan dikirim ke sistem kami
                        untuk diproses dan diverifikasi. Proses verifikasi memakan waktu 1–3 hari kerja.
                    </div>

                    <label class="rm-agree">
                        <input type="checkbox" name="agree" id="rmAgree" required>
                        <span>Saya menyatakan bahwa semua data yang saya masukkan adalah benar dan dapat dipertanggungjawabkan.</span>
                    </label>
                </div>

                <div class="rm-nav-buttons">
                    <button type="button" class="btn-rm-prev" id="rmPrev4">← Sebelumnya</button>
                    <button type="submit" class="btn-rm-next" id="rmSubmit">
                        Daftarkan Masjid ✓
                    </button>
                </div>
            </div>

        </form>
    </main>

    {{-- Page Footer --}}
    <footer class="rm-footer">
        Butuh bantuan? Hubungi kami di
        <a href="mailto:bantuan@masjidannur.id">bantuan@masjidannur.id</a>
        atau WhatsApp <a href="https://wa.me/6281200000000">+62 812 0000 0000</a>
    </footer>
    
    <script>
        /* ---- Step navigation ---- */
        const panels = [
            document.getElementById('rmP1'),
            document.getElementById('rmP2'),
            document.getElementById('rmP3'),
            document.getElementById('rmP4'),
        ];
        const stepEls = [
            document.getElementById('rmS1'),
            document.getElementById('rmS2'),
            document.getElementById('rmS3'),
            document.getElementById('rmS4'),
        ];
        const lineEls = [
            document.getElementById('rmL1'),
            document.getElementById('rmL2'),
            document.getElementById('rmL3'),
        ];

        let current = 0;

        function goTo(idx) {
            panels[current].style.display = 'none';
            stepEls[current].className = 'rm-step is-done';

            current = idx;
            panels[current].style.display = 'block';
            stepEls[current].className = 'rm-step is-active';

            for (let i = 0; i < lineEls.length; i++) {
                lineEls[i].classList.toggle('is-done', i < current);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goBack(idx) {
            panels[current].style.display = 'none';
            stepEls[current].className = 'rm-step is-inactive';
            current = idx;
            panels[current].style.display = 'block';
            stepEls[current].className = 'rm-step is-active';

            for (let i = 0; i < lineEls.length; i++) {
                lineEls[i].classList.toggle('is-done', i < current);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('rmNext1').addEventListener('click', () => {
            const name = document.querySelector('[name="mosque_name"]').value.trim();
            if (!name) { alert('Nama masjid wajib diisi.'); return; }
            goTo(1);
        });

        document.getElementById('rmPrev2').addEventListener('click', () => goBack(0));
        document.getElementById('rmNext2').addEventListener('click', () => goTo(2));

        document.getElementById('rmPrev3').addEventListener('click', () => goBack(1));
        document.getElementById('rmNext3').addEventListener('click', () => goTo(3));

        document.getElementById('rmPrev4').addEventListener('click', () => goBack(2));

        /* ---- Chip toggle visual ---- */
        document.querySelectorAll('.rm-chip input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', () => {
                cb.closest('.rm-chip').classList.toggle('checked', cb.checked);
            });
        });
    </script>
</body>
</html>
