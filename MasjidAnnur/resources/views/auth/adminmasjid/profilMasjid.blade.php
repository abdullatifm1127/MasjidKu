<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Masjid - SIM Masjid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/berandaAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminmasjid/profilMasjid.css') }}">
</head>
<body class="ba2-body" id="ba2Body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="ba2-sidebar" id="ba2Sidebar">

        {{-- Brand --}}
        <div class="ba2-brand">
            <div class="ba2-brand-avatar">A</div>
            <div class="ba2-brand-info">
                <div class="ba2-brand-name">SIM Masjid</div>
                <div class="ba2-brand-sub">Baitul Digital</div>
            </div>
            <button class="ba2-collapse-btn" id="ba2CollapseBtn" aria-label="Collapse">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="ba2-nav">
            <a href="{{ route('admin2.dashboard') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-table-cells-large"></i></span>
                <span class="ba2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin2.landing-page') }}" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-globe"></i></span>
                <span class="ba2-nav-label">Landing Page</span>
            </a>
            <a href="{{ route('admin2.profil-masjid') }}" class="ba2-nav-item active">
                <span class="ba2-nav-icon"><i class="fa-solid fa-mosque"></i></span>
                <span class="ba2-nav-label">Profil Masjid</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-clock"></i></span>
                <span class="ba2-nav-label">Jadwal Shalat</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="ba2-nav-label">Pengumuman</span>
                <span class="ba2-nav-badge">3</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="ba2-nav-label">Kegiatan &amp; Acara</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="ba2-nav-label">Donasi</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
            <a href="#" class="ba2-nav-item">
                <span class="ba2-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="ba2-nav-label">Data Jamaah</span>
                <span class="ba2-nav-soon">dev</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="ba2-user">
            <div class="ba2-user-avatar">A</div>
            <div class="ba2-user-info">
                <div class="ba2-user-name">Admin Masjid</div>
                <div class="ba2-user-email">admin@baituldigital.id</div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="ba2-main" id="ba2Main">

        {{-- Topbar --}}
        <header class="ba2-topbar">
            <div class="ba2-topbar-left">
                <div class="ba2-page-title">Profil Masjid</div>
                <div class="ba2-page-sub">Kelola modul Profil Masjid</div>
            </div>
            <div class="ba2-topbar-right">
                <a href="{{ url('/') }}" class="ba2-btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Publik
                </a>
                <button class="ba2-notif-btn" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                    <span class="ba2-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Content --}}
        <main class="pm-content">

            {{-- ===== TABS ===== --}}
            <div class="pm-tabs" id="pmTabs">
                <button class="pm-tab active" data-tab="identitas">
                    <i class="fa-solid fa-id-card"></i> Identitas
                </button>
                <button class="pm-tab" data-tab="pengurus">
                    <i class="fa-solid fa-user-tie"></i> Pengurus
                </button>
                <button class="pm-tab" data-tab="lokasi">
                    <i class="fa-solid fa-location-dot"></i> Lokasi &amp; Kontak
                </button>
                <button class="pm-tab" data-tab="program">
                    <i class="fa-solid fa-list-check"></i> Program
                </button>
                <button class="pm-tab pm-tab-preview" data-tab="preview">
                    <i class="fa-solid fa-eye"></i> Preview
                </button>
            </div>

            {{-- ===========================
                 TAB 1: IDENTITAS
                 =========================== --}}
            <div class="pm-panel active" id="tab-identitas">
                <div class="pm-card">
                    <div class="pm-card-title">
                        <span class="pm-card-bar"></span>
                        Identitas Masjid
                    </div>

                    {{-- Row: Nama Masjid + Nama Arab --}}
                    <div class="pm-grid-2">
                        <div class="pm-field">
                            <label class="pm-label">Nama Masjid<span class="pm-req">*</span></label>
                            <input type="text" class="pm-input" value="Baitul Digital" placeholder="Nama masjid Anda">
                        </div>
                        <div class="pm-field">
                            <label class="pm-label">Nama Arab</label>
                            <input type="text" class="pm-input pm-input-rtl" value="بيت الديجيتال" placeholder="الاسم بالعربية" dir="rtl">
                        </div>
                    </div>

                    {{-- Tagline --}}
                    <div class="pm-field">
                        <label class="pm-label">Tagline / Slogan</label>
                        <input type="text" class="pm-input" value="Pusat Ilmu dan Ibadah" placeholder="Tagline singkat masjid">
                    </div>

                    {{-- Row: Tahun + Kapasitas --}}
                    <div class="pm-grid-2">
                        <div class="pm-field">
                            <label class="pm-label">Tahun Berdiri</label>
                            <input type="text" class="pm-input" value="1987" placeholder="cth: 1975">
                        </div>
                        <div class="pm-field">
                            <label class="pm-label">Kapasitas Jamaah</label>
                            <input type="text" class="pm-input" value="2.500 jamaah" placeholder="cth: 1.000 jamaah">
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="pm-field">
                        <label class="pm-label">Deskripsi / Tentang Masjid</label>
                        <textarea class="pm-textarea" rows="4" placeholder="Ceritakan sejarah dan visi misi masjid...">Baitul Digital berdiri sejak 1987 sebagai pusat kegiatan ibadah dan pendidikan Islam di lingkungan Cilandak, Jakarta Selatan. Kami hadir untuk menjadi rumah bagi seluruh jamaah dalam menimba ilmu, mempererat ukhuwah, dan memperkuat keimanan.</textarea>
                    </div>
                </div>
            </div>

            {{-- ===========================
                 TAB 2: PENGURUS
                 =========================== --}}
            <div class="pm-panel" id="tab-pengurus">
                <div class="pm-card">
                    <div class="pm-card-title">
                        <span class="pm-card-bar"></span>
                        Data Pengurus
                    </div>

                    <div class="pm-field">
                        <label class="pm-label">Imam / Khatib Utama</label>
                        <input type="text" class="pm-input" value="Ustadz Dr. Ahmad Fauzi, Lc. MA" placeholder="Nama imam atau khatib utama">
                    </div>

                    <div class="pm-field">
                        <label class="pm-label">Ketua Takmir / DKM</label>
                        <input type="text" class="pm-input" value="H. Budi Santoso, SE." placeholder="Nama ketua takmir atau DKM">
                    </div>

                    <div class="pm-info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        Data pengurus lainnya (sekretaris, bendahara, dll.) dapat ditambahkan dari modul
                        <strong>Manajemen Pengurus</strong> yang sedang dalam pengembangan.
                    </div>
                </div>
            </div>

            {{-- ===========================
                 TAB 3: LOKASI & KONTAK
                 =========================== --}}
            <div class="pm-panel" id="tab-lokasi">
                <div class="pm-card">
                    <div class="pm-card-title">
                        <span class="pm-card-bar"></span>
                        Lokasi &amp; Kontak
                    </div>

                    {{-- Alamat --}}
                    <div class="pm-field">
                        <label class="pm-label">Alamat Lengkap</label>
                        <input type="text" class="pm-input" value="Jl. Fatmawati No. 88, Cilandak" placeholder="Alamat lengkap masjid">
                    </div>

                    {{-- Kota + Provinsi --}}
                    <div class="pm-grid-2">
                        <div class="pm-field">
                            <label class="pm-label">Kota / Kabupaten</label>
                            <input type="text" class="pm-input" value="Jakarta Selatan" placeholder="cth: Kota Surabaya">
                        </div>
                        <div class="pm-field">
                            <label class="pm-label">Provinsi</label>
                            <input type="text" class="pm-input" value="DKI Jakarta" placeholder="cth: Jawa Timur">
                        </div>
                    </div>

                    {{-- Telepon + Email + Website --}}
                    <div class="pm-grid-3">
                        <div class="pm-field">
                            <label class="pm-label">Nomor Telepon</label>
                            <input type="text" class="pm-input" value="+62 21 7590 1234" placeholder="+62 ...">
                        </div>
                        <div class="pm-field">
                            <label class="pm-label">Email</label>
                            <input type="email" class="pm-input" value="info@baituldigital.id" placeholder="email@masjid.id">
                        </div>
                        <div class="pm-field">
                            <label class="pm-label">Website</label>
                            <input type="url" class="pm-input" value="https://baituldigital.id" placeholder="https://...">
                        </div>
                    </div>

                    {{-- Map placeholder --}}
                    <div class="pm-field" style="margin-top: 4px;">
                        <label class="pm-label">Lokasi di Peta</label>
                        <div class="pm-map-box" role="button" tabindex="0" aria-label="Set lokasi di peta">
                            <div class="pm-map-icon"><i class="fa-solid fa-map-pin"></i></div>
                            <div class="pm-map-label-green">Integrasi Google Maps</div>
                            <div class="pm-map-label-gray">Klik untuk set lokasi masjid</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===========================
                 TAB 4: PROGRAM
                 =========================== --}}
            <div class="pm-panel" id="tab-program">
                <div class="pm-card">
                    <div class="pm-card-title">
                        <span class="pm-card-bar"></span>
                        Program &amp; Kegiatan Rutin
                    </div>
                    <p class="pm-card-subtitle">Program yang ditampilkan di halaman profil masjid.</p>

                    <div class="pm-program-list" id="pmProgramList">
                        <div class="pm-program-row">
                            <span class="pm-program-num">01</span>
                            <input type="text" class="pm-program-text" value="Tahsin Al-Quran">
                            <button class="pm-program-del" aria-label="Hapus" title="Hapus program"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="pm-program-row">
                            <span class="pm-program-num">02</span>
                            <input type="text" class="pm-program-text" value="Kajian Hadits">
                            <button class="pm-program-del" aria-label="Hapus" title="Hapus program"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="pm-program-row">
                            <span class="pm-program-num">03</span>
                            <input type="text" class="pm-program-text" value="TPQ Anak">
                            <button class="pm-program-del" aria-label="Hapus" title="Hapus program"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="pm-program-row">
                            <span class="pm-program-num">04</span>
                            <input type="text" class="pm-program-text" value="Konsultasi Keluarga">
                            <button class="pm-program-del" aria-label="Hapus" title="Hapus program"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="pm-program-row">
                            <span class="pm-program-num">05</span>
                            <input type="text" class="pm-program-text" value="Majelis Taklim Ibu-Ibu">
                            <button class="pm-program-del" aria-label="Hapus" title="Hapus program"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>

                    <div class="pm-program-add-row">
                        <input type="text" class="pm-input-add" id="pmNewProgram" placeholder="Tambah program baru...">
                        <button class="pm-btn-add-program" id="pmBtnAddProgram">
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===========================
                 TAB 5: PREVIEW
                 =========================== --}}
            <div class="pm-panel" id="tab-preview">
                <div class="pm-card">
                    <div class="pm-card-title">
                        <span class="pm-card-bar"></span>
                        Preview Profil Masjid
                    </div>

                    <div class="pm-preview-card">
                        <div class="pm-preview-arabic">بيت الديجيتال</div>
                        <div class="pm-preview-name">Baitul Digital</div>
                        <div class="pm-preview-tagline">Pusat Ilmu dan Ibadah</div>
                        <div class="pm-preview-desc">
                            Baitul Digital berdiri sejak 1987 sebagai pusat kegiatan ibadah dan pendidikan Islam di lingkungan Cilandak, Jakarta Selatan. Kami hadir untuk menjadi rumah bagi seluruh jamaah dalam menimba ilmu, mempererat ukhuwah, dan memperkuat keimanan.
                        </div>

                        <hr class="pm-preview-divider">

                        <div class="pm-preview-info-grid">
                            <div class="pm-preview-info-item">
                                <div class="pm-preview-info-label">Tahun Berdiri</div>
                                <div class="pm-preview-info-value">1987</div>
                            </div>
                            <div class="pm-preview-info-item">
                                <div class="pm-preview-info-label">Kapasitas</div>
                                <div class="pm-preview-info-value">2.500 jamaah</div>
                            </div>
                            <div class="pm-preview-info-item">
                                <div class="pm-preview-info-label">Imam</div>
                                <div class="pm-preview-info-value">Ustadz Dr. Ahmad Fauzi</div>
                            </div>
                            <div class="pm-preview-info-item">
                                <div class="pm-preview-info-label">Ketua Takmir</div>
                                <div class="pm-preview-info-value">H. Budi Santoso, SE.</div>
                            </div>
                            <div class="pm-preview-info-item">
                                <div class="pm-preview-info-label">Kota</div>
                                <div class="pm-preview-info-value">Jakarta Selatan</div>
                            </div>
                            <div class="pm-preview-info-item">
                                <div class="pm-preview-info-label">Kontak</div>
                                <div class="pm-preview-info-value">+62 21 7590 1234</div>
                            </div>
                        </div>

                        <hr class="pm-preview-divider">

                        <div class="pm-preview-prog-label">Program Kegiatan</div>
                        <div class="pm-preview-prog-tags">
                            <span class="pm-preview-prog-tag"><span class="pm-preview-prog-num">01</span> Tahsin Al-Quran</span>
                            <span class="pm-preview-prog-tag"><span class="pm-preview-prog-num">02</span> Kajian Hadits</span>
                            <span class="pm-preview-prog-tag"><span class="pm-preview-prog-num">03</span> TPQ Anak</span>
                            <span class="pm-preview-prog-tag"><span class="pm-preview-prog-num">04</span> Konsultasi Keluarga</span>
                            <span class="pm-preview-prog-tag"><span class="pm-preview-prog-num">05</span> Majelis Taklim Ibu-Ibu</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    {{-- ===== STICKY FOOTER (hidden on preview tab) ===== --}}
    <div class="pm-footer" id="pmFooter">
        <span class="pm-footer-status">Perubahan belum disimpan</span>
        <div class="pm-footer-btns">
            <button class="pm-btn-reset">Reset</button>
            <button class="pm-btn-save">Simpan Perubahan</button>
        </div>
    </div>

    <button class="ba2-fab" aria-label="Bantuan">?</button>

    <script>
        // ── Sidebar collapse ──────────────────────────────────────
        document.getElementById('ba2CollapseBtn').addEventListener('click', function () {
            const sidebar = document.getElementById('ba2Sidebar');
            const main    = document.getElementById('ba2Main');
            const footer  = document.getElementById('pmFooter');

            sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded');

            // Sync footer offset
            if (sidebar.classList.contains('collapsed')) {
                footer.style.left = '52px';
            } else {
                footer.style.left = '160px';
            }
        });

        // ── Tab switching ─────────────────────────────────────────
        const tabs   = document.querySelectorAll('.pm-tab');
        const panels = document.querySelectorAll('.pm-panel');
        const footer = document.getElementById('pmFooter');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const target = tab.dataset.tab;

                // Update active tab
                tabs.forEach(function (t) { t.classList.remove('active'); });
                tab.classList.add('active');

                // Update active panel
                panels.forEach(function (p) { p.classList.remove('active'); });
                document.getElementById('tab-' + target).classList.add('active');

                // Hide footer on preview tab
                if (target === 'preview') {
                    footer.style.display = 'none';
                } else {
                    footer.style.display = 'flex';
                }
            });
        });

        // ── Program list: add ─────────────────────────────────────
        document.getElementById('pmBtnAddProgram').addEventListener('click', function () {
            const input = document.getElementById('pmNewProgram');
            const text  = input.value.trim();
            if (!text) return;

            const list = document.getElementById('pmProgramList');
            const rows = list.querySelectorAll('.pm-program-row');
            const num  = String(rows.length + 1).padStart(2, '0');

            const row = document.createElement('div');
            row.className = 'pm-program-row';
            row.innerHTML =
                '<span class="pm-program-num">' + num + '</span>' +
                '<input type="text" class="pm-program-text" value="' + escapeHtml(text) + '">' +
                '<button class="pm-program-del" aria-label="Hapus" title="Hapus program"><i class="fa-solid fa-xmark"></i></button>';

            list.appendChild(row);
            input.value = '';
            input.focus();

            // Attach delete handler
            row.querySelector('.pm-program-del').addEventListener('click', deleteRow);
        });

        // Enter key on add input
        document.getElementById('pmNewProgram').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('pmBtnAddProgram').click();
            }
        });

        // ── Program list: delete ──────────────────────────────────
        function deleteRow(e) {
            var row = e.currentTarget.closest('.pm-program-row');
            row.remove();
            renumberRows();
        }

        document.querySelectorAll('.pm-program-del').forEach(function (btn) {
            btn.addEventListener('click', deleteRow);
        });

        function renumberRows() {
            var rows = document.querySelectorAll('#pmProgramList .pm-program-row');
            rows.forEach(function (row, i) {
                row.querySelector('.pm-program-num').textContent = String(i + 1).padStart(2, '0');
            });
        }

        // ── Helper: escape HTML in dynamic content ────────────────
        function escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    </script>
</body>
</html>
