<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Masjid Annur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/register.css">
</head>
<body class="register-page">

    {{-- Top Navigation --}}
    <nav class="register-navbar">
        <a href="{{ url('/') }}" class="nav-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            Kembali
        </a>
        <a href="{{ route('login') }}" class="nav-login">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Masuk
        </a>
    </nav>

    {{-- Main Content --}}
    <div class="register-container">

        {{-- Header --}}
        <div class="register-header">
            <h1>Daftarkan Masjid</h1>
            <p>Buat akun baru untuk mengelola masjid Anda</p>
        </div>

        {{-- Step Indicator --}}
        <div class="step-indicator" id="stepIndicator">
            <div class="step-item active" id="step1Indicator">
                <div class="step-number">1</div>
                <span class="step-label">Data Diri</span>
            </div>
            <div class="step-connector" id="stepConnector"></div>
            <div class="step-item inactive" id="step2Indicator">
                <div class="step-number">2</div>
                <span class="step-label">Keamanan</span>
            </div>
        </div>

        {{-- Register Form --}}
        <form class="register-form" method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- ===== STEP 1: Data Diri ===== --}}
            <div class="step-panel active" id="panel1">

                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Nama pengurus masjid"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            required
                        >
                    </div>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nama Masjid --}}
                <div class="form-group">
                    <label for="mosque_name">Nama Masjid</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                        <input
                            type="text"
                            id="mosque_name"
                            name="mosque_name"
                            placeholder="Nama masjid Anda"
                            value="{{ old('mosque_name') }}"
                            required
                        >
                    </div>
                    @error('mosque_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email & No. Telepon (2 kolom) --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="nama@masjid.id"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                            >
                        </div>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                placeholder="08xx-xxxx-xxxx"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                            >
                        </div>
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Next Button --}}
                <button type="button" class="btn-register" id="btnNext">
                    Lanjutkan
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>

            </div>

            {{-- ===== STEP 2: Keamanan ===== --}}
            <div class="step-panel" id="panel2">

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            class="has-icon-right"
                            autocomplete="new-password"
                        >
                        <button type="button" class="input-icon-right" onclick="togglePassword('password', 'eye1')" aria-label="Tampilkan kata sandi">
                            <svg id="eye1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    <span class="password-hint">Gunakan kombinasi huruf, angka, dan simbol</span>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi"
                            class="has-icon-right"
                            autocomplete="new-password"
                        >
                        <button type="button" class="input-icon-right" onclick="togglePassword('password_confirmation', 'eye2')" aria-label="Tampilkan konfirmasi kata sandi">
                            <svg id="eye2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Terms & Conditions --}}
                <label class="terms-check">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span>Saya setuju dengan <a href="#">Syarat &amp; Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></span>
                </label>

                {{-- Button Group --}}
                <div class="btn-group">
                    <button type="submit" class="btn-register">
                        Buat Akun
                    </button>
                    <button type="button" class="btn-back-step" id="btnBack">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali
                    </button>
                </div>

            </div>

        </form>

        {{-- Login Link --}}
        <p class="register-footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

    </div>

    {{-- Page Footer --}}
    <footer class="register-page-footer">
        &copy; {{ date('Y') }} Baitul Digital
        <span>&middot;</span>
        <a href="#">Privasi</a>
        <span>&middot;</span>
        <a href="#">Syarat</a>
    </footer>

    {{-- Help FAB --}}
    <button class="help-fab" aria-label="Bantuan">?</button>

    <script>
        /* ---- Toggle Password Visibility ---- */
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />`
                : `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                   <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
        }

        /* ---- Multi-Step Form ---- */
        const panel1      = document.getElementById('panel1');
        const panel2      = document.getElementById('panel2');
        const step1Ind    = document.getElementById('step1Indicator');
        const step2Ind    = document.getElementById('step2Indicator');
        const connector   = document.getElementById('stepConnector');
        const btnNext     = document.getElementById('btnNext');
        const btnBack     = document.getElementById('btnBack');

        function validateStep1() {
            const name       = document.getElementById('name').value.trim();
            const mosque     = document.getElementById('mosque_name').value.trim();
            const email      = document.getElementById('email').value.trim();
            if (!name)   { alert('Nama lengkap wajib diisi.'); return false; }
            if (!mosque) { alert('Nama masjid wajib diisi.'); return false; }
            if (!email)  { alert('Email wajib diisi.'); return false; }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) { alert('Format email tidak valid.'); return false; }
            return true;
        }

        btnNext.addEventListener('click', function () {
            if (!validateStep1()) return;

            // Hide step 1, show step 2
            panel1.classList.remove('active');
            panel2.classList.add('active');

            // Update step indicators
            step1Ind.classList.remove('active');
            step1Ind.classList.add('completed');
            step2Ind.classList.remove('inactive');
            step2Ind.classList.add('active');
            connector.classList.add('done');
        });

        btnBack.addEventListener('click', function () {
            // Hide step 2, show step 1
            panel2.classList.remove('active');
            panel1.classList.add('active');

            // Reset step indicators
            step1Ind.classList.add('active');
            step1Ind.classList.remove('completed');
            step2Ind.classList.add('inactive');
            step2Ind.classList.remove('active');
            connector.classList.remove('done');
        });
    </script>
</body>
</html>
