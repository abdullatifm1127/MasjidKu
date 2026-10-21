<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Super Admin - MasjidKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/halamanloginsuperadmin.css') }}">
</head>
<body>

<div class="bg"></div>

@php
    $pesanError = $errors->any() ? $errors->first() : session('error');
    $pesanOk    = session('status') ?? session('success');
@endphp

<div class="shell">

    {{-- ===== SISI KIRI ===== --}}
    <section class="brand-side">
        <div class="logo"><span class="logo-mark">🕌</span><span>Masjid<span class="accent">Ku</span></span></div>

        <span class="badge">🌙 Akses Super Admin</span>
        <h1>Masuk ke <span class="hi">Panel Kendali</span> Utama</h1>
        <p class="lede">Khusus untuk administrator tertinggi platform MasjidKu. Kelola seluruh masjid, pengguna, dan konfigurasi sistem dari sini.</p>

        <ul class="points">
            <li><span class="pi">✅</span>Verifikasi pendaftaran dan pembayaran masjid</li>
            <li><span class="pi">🕌</span>Kelola semua masjid dan paket langganan</li>
            <li><span class="pi">📰</span>Atur halaman utama dan artikel</li>
        </ul>
    </section>

    {{-- ===== SISI KANAN: FORM ===== --}}
    <section class="form-side">
        <div class="card">
            <div class="card-head">
                <h2>Masuk</h2>
                <p>Gunakan akun Super Admin Anda.</p>
            </div>

            @if($pesanError)
                <div class="alert bad" role="alert"><span>⚠</span><span>{{ $pesanError }}</span></div>
            @endif
            @if($pesanOk)
                <div class="alert good" role="status"><span>✔</span><span>{{ $pesanOk }}</span></div>
            @endif

            <form action="{{ route('superadmin.login.process') }}" method="POST" id="loginForm" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="m3 8 9 6 9-6"/></svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="superadmin@masjidku.id" autocomplete="email" required class="{{ $errors->has('email') ? 'err' : '' }}">
                    </div>
                    <div class="error-msg" id="err-email">{{ $errors->first('email') }}</div>
                </div>

                <div class="field">
                    <label for="password">Kata sandi</label>
                    <div class="input-wrap has-eye">
                        <span class="ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="3"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="••••••••••••" autocomplete="current-password" required class="{{ $errors->has('password') ? 'err' : '' }}">
                        <button type="button" class="toggle-eye" id="togglePw" aria-label="Tampilkan kata sandi">TAMPIL</button>
                    </div>
                    <div class="error-msg" id="err-password">{{ $errors->first('password') }}</div>
                    <div class="caps" id="capsMsg" hidden>⚠ Caps Lock sedang aktif</div>
                </div>

                <div class="row-between">
                    <label class="remember">
                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        Ingat perangkat ini
                    </label>
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="submit" id="submitBtn">
                    <span class="spinner"></span>
                    <span class="btn-label">Masuk sebagai Super Admin</span>
                </button>
            </form>

            <div class="footnote">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3Z"/></svg>
                Sesi ini dipantau demi keamanan sistem MasjidKu
            </div>
        </div>

        <div class="back-home"><a href="{{ url('/') }}">← Kembali ke beranda</a></div>
    </section>
</div>

<div class="toast" id="toast" data-show="{{ $pesanOk ? '1' : '0' }}">{{ $pesanOk }}</div>

<script>
(function () {
    var form = document.getElementById('loginForm');
    if (!form) return;

    var email = document.getElementById('email');
    var pw = document.getElementById('password');
    var btn = document.getElementById('submitBtn');
    var eye = document.getElementById('togglePw');
    var caps = document.getElementById('capsMsg');
    var toast = document.getElementById('toast');

    function setErr(input, id, msg) {
        input.classList.toggle('err', !!msg);
        var el = document.getElementById(id);
        if (el) el.textContent = msg || '';
    }

    // Tampil / sembunyikan kata sandi
    eye.addEventListener('click', function () {
        var show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        eye.textContent = show ? 'SEMBUNYI' : 'TAMPIL';
        eye.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });

    // Peringatan Caps Lock
    pw.addEventListener('keyup', function (e) {
        if (caps && e.getModifierState) caps.hidden = !e.getModifierState('CapsLock');
    });
    pw.addEventListener('blur', function () { if (caps) caps.hidden = true; });

    // Hapus pesan salah saat mengetik lagi
    email.addEventListener('input', function () { setErr(email, 'err-email', ''); });
    pw.addEventListener('input', function () { setErr(pw, 'err-password', ''); });

    // Validasi sederhana sebelum dikirim, lalu tampilkan loading
    form.addEventListener('submit', function (e) {
        var ok = true, v = email.value.trim();

        if (!v) { setErr(email, 'err-email', 'Email wajib diisi.'); ok = false; }
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { setErr(email, 'err-email', 'Format email belum benar.'); ok = false; }

        if (!pw.value) { setErr(pw, 'err-password', 'Kata sandi wajib diisi.'); ok = false; }

        if (!ok) { e.preventDefault(); return; }

        btn.classList.add('loading');
        btn.disabled = true;
        btn.querySelector('.btn-label').textContent = 'Memeriksa...';
    });

    // Tombol Kembali di browser: kembalikan tombol seperti semula
    window.addEventListener('pageshow', function () {
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.querySelector('.btn-label').textContent = 'Masuk sebagai Super Admin';
    });

    // Toast (mis. setelah logout)
    if (toast && toast.dataset.show === '1') {
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 4000);
    }

    // Fokus otomatis ke kolom pertama yang kosong / bermasalah
    (email.value ? pw : email).focus();
})();
</script>
</body>
</html>