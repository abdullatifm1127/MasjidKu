<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Masjid Annur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="login-page">

    {{-- ===== NAVBAR ===== --}}
    <nav class="ln-navbar">
        <a href="{{ url('/') }}" class="nav-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('register') }}" class="nav-daftar">
            Daftar
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </nav>

    {{-- ===== MAIN ===== --}}
    <main class="ln-container">

        {{-- Header --}}
        <div class="ln-header">
            <h1>Selamat Datang</h1>
            <p>Masuk ke akun pengurus masjid Anda</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div style="margin-bottom:14px; font-size:0.88rem; color:#1a6640; background:#e8f5ee; padding:10px 14px; border-radius:8px;">
                {{ session('status') }}
            </div>
        @endif

        {{-- ===== FORM ===== --}}
        <form class="ln-form" method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Alamat Email --}}
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <input type="email" id="email" name="email"
                           placeholder="nama@masjid.id"
                           value="{{ old('email') }}"
                           autocomplete="email" autofocus required>
                </div>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

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
                           placeholder="Masukkan kata sandi"
                           class="has-icon-right"
                           autocomplete="current-password" required>
                    <button type="button" class="input-icon-right"
                            onclick="lnTogglePass()"
                            aria-label="Tampilkan kata sandi">
                        <svg id="lnEye" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Ingat saya + Lupa kata sandi --}}
            <div class="ln-options">
                <label class="ln-remember">
                    <input type="checkbox" name="remember" id="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="ln-forgot">Lupa kata sandi?</a>
                @endif
            </div>

            {{-- Tombol Masuk --}}
            <button type="submit" class="btn-masuk">Masuk ke Akun</button>

            {{-- Divider --}}
            <div class="ln-divider">atau masuk dengan</div>

            {{-- Social Login --}}
            <div class="ln-social">
                <a href="#" class="btn-social">
                    <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                        <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
                        <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0124 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                        <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                    </svg>
                    Google
                </a>
                <a href="#" class="btn-social">
                    <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#3F51B5" d="M42 37a5 5 0 01-5 5H11a5 5 0 01-5-5V11a5 5 0 015-5h26a5 5 0 015 5v26z"/>
                        <path fill="#fff" d="M34.368 25H31v13h-5V25h-3v-4h3v-2.41C26 15.05 27.192 13 31.208 13H35v4h-2.514C31.022 17 31 17.516 31 18.085V21h4l-.632 4z"/>
                    </svg>
                    Facebook
                </a>
            </div>

        </form>

        {{-- Footer link --}}
        <p class="ln-footer-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </p>

    </main>


    <script>
        function lnTogglePass() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('lnEye');
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            icon.innerHTML = show
                ? `<path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>`
                : `<path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                   <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
        }
    </script>
</body>
</html>
