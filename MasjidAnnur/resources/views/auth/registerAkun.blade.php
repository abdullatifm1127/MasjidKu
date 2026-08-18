<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Masjid Annur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registerAkun.css') }}">
</head>
<body class="register-akun-page">

    {{-- ===== NAVBAR ===== --}}
    <nav class="ra-navbar">
        <a href="{{ url('/') }}" class="nav-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('login') }}" class="nav-masuk">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Masuk
        </a>
    </nav>

    {{-- ===== MAIN ===== --}}
    <main class="ra-container">

        {{-- Header --}}
        <div class="ra-header">
            <h1>Daftarkan Masjid</h1>
            <p>Buat akun baru untuk mengelola masjid Anda</p>
        </div>

        {{-- Step Indicator --}}
        <div class="ra-steps" id="raSteps">
            <div class="ra-step is-active" id="raStep1">
                <div class="ra-step-num">1</div>
                <span class="ra-step-label">Data Diri</span>
            </div>
            <div class="ra-step-line" id="raStepLine"></div>
            <div class="ra-step is-inactive" id="raStep2">
                <div class="ra-step-num">2</div>
                <span class="ra-step-label">Keamanan</span>
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="form-error" style="margin-bottom:14px;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ===== FORM ===== --}}
        <form class="ra-form" method="POST" action="{{ route('register') }}" id="raForm">
            @csrf

            {{-- ─── PANEL 1 : Data Diri ─── --}}
            <div class="ra-panel is-visible" id="raPanel1">

                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <input type="text" id="name" name="name"
                               placeholder="Nama pengurus masjid"
                               value="{{ old('name') }}" autocomplete="name" required>
                    </div>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                {{-- Nama Masjid --}}
                <div class="form-group">
                    <label for="mosque_name">Nama Masjid</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                        </svg>
                        <input type="text" id="mosque_name" name="mosque_name"
                               placeholder="Nama masjid Anda"
                               value="{{ old('mosque_name') }}" required>
                    </div>
                    @error('mosque_name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                {{-- Email & No. Telepon (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                            <input type="email" id="email" name="email"
                                   placeholder="nama@masjid.id"
                                   value="{{ old('email') }}" autocomplete="email" required>
                        </div>
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                            <input type="tel" id="phone" name="phone"
                                   placeholder="08xx-xxxx-xxxx"
                                   value="{{ old('phone') }}" autocomplete="tel">
                        </div>
                        @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Lanjutkan --}}
                <button type="button" class="btn-lanjutkan" id="raBtnNext">
                    Lanjutkan
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.5" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>

            </div>{{-- end panel1 --}}

            {{-- ─── PANEL 2 : Keamanan ─── --}}
            <div class="ra-panel" id="raPanel2">

                {{-- Kata Sandi --}}
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        <input type="password" id="password" name="password"
                               placeholder="Minimal 8 karakter"
                               class="has-icon-right" autocomplete="new-password">
                        <button type="button" class="input-icon-right"
                                onclick="raTogglePass('password','raEye1')"
                                aria-label="Tampilkan kata sandi">
                            <svg id="raEye1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    <span class="input-hint">Gunakan kombinasi huruf, angka, dan simbol</span>
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                {{-- Konfirmasi Kata Sandi --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                        <input type="password" id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Ulangi kata sandi"
                               class="has-icon-right" autocomplete="new-password">
                        <button type="button" class="input-icon-right"
                                onclick="raTogglePass('password_confirmation','raEye2')"
                                aria-label="Tampilkan konfirmasi kata sandi">
                            <svg id="raEye2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Terms --}}
                <label class="terms-check">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span>Saya setuju dengan <a href="#">Syarat &amp; Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></span>
                </label>

                {{-- Buttons --}}
                <div class="btn-group">
                    <button type="submit" class="btn-lanjutkan">Buat Akun</button>
                    <button type="button" class="btn-kembali" id="raBtnBack">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        Kembali
                    </button>
                </div>

            </div>{{-- end panel2 --}}

        </form>

        {{-- Footer link --}}
        <p class="ra-footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

    </main>

    {{-- Page footer --}}
    <footer class="ra-page-footer">
        &copy; {{ date('Y') }} Baitul Digital
        <span class="sep">&middot;</span>
        <a href="#">Privasi</a>
        <span class="sep">&middot;</span>
        <a href="#">Syarat</a>
    </footer>

    <script>
        /* ---- Toggle password visibility ---- */
        function raTogglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            icon.innerHTML = show
        }

        /* ---- Multi-step logic ---- */
        const panel1  = document.getElementById('raPanel1');
        const panel2  = document.getElementById('raPanel2');
        const step1   = document.getElementById('raStep1');
        const step2   = document.getElementById('raStep2');
        const line    = document.getElementById('raStepLine');
        const btnNext = document.getElementById('raBtnNext');
        const btnBack = document.getElementById('raBtnBack');

        btnNext.addEventListener('click', function () {
            const name   = document.getElementById('name').value.trim();
            const mosque = document.getElementById('mosque_name').value.trim();
            const email  = document.getElementById('email').value.trim();

            if (!name)   { alert('Nama lengkap wajib diisi.'); return; }
            if (!mosque) { alert('Nama masjid wajib diisi.'); return; }
            if (!email)  { alert('Email wajib diisi.'); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Format email tidak valid.'); return;
            }

            panel1.classList.remove('is-visible');
            panel2.classList.add('is-visible');

            step1.className = 'ra-step is-done';
            step2.className = 'ra-step is-active';
            line.classList.add('is-done');
        });

        btnBack.addEventListener('click', function () {
            panel2.classList.remove('is-visible');
            panel1.classList.add('is-visible');

            step1.className = 'ra-step is-active';
            step2.className = 'ra-step is-inactive';
            line.classList.remove('is-done');
        });
    </script>
</body>
</html>
