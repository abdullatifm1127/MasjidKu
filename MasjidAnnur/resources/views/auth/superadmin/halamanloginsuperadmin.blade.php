<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Super Admin — MasjidKu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/superadmin/halamanloginsuperadmin.css') }}">
</head>
<body>
 
<div class="stars"></div>
 
<header>
  <div class="logo">🕌 MasjidKu<span class="accent">Admin</span></div>
</header>
 
<main>
  <div class="wrap">
    <span class="badge">🌙 Akses Super Admin</span>
    <h1>Masuk ke <span class="hi">Panel Kendali</span> Utama</h1>
    <p class="lede">Khusus untuk administrator tertinggi platform MasjidKu. Kelola seluruh masjid, pengguna, dan konfigurasi sistem dari sini.</p>
 
    <div class="card">
     <form action="{{ route('login') }}" method="POST" id="loginForm">
    @csrf <!-- Token keamanan wajib di Laravel -->
    
    <div class="field">
        <label for="username">Username atau email</label>
        <div class="input-wrap">
            {{-- Ubah name menjadi 'email' agar sesuai dengan standar Laravel --}}
            <input type="email" id="username" name="email" value="{{ old('email') }}" placeholder="superadmin@masjidku.id" autocomplete="email" required>
        </div>
        <div class="error-msg" id="err-username"></div>
    </div>

    <div class="field">
        <label for="password">Kata sandi</label>
        <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="••••••••••••" autocomplete="current-password" required>
            <button type="button" class="toggle-eye" id="togglePw">TAMPIL</button>
        </div>
        <div class="error-msg" id="err-password"></div>
    </div>

    <div class="row-between">
        <label class="remember">
            <input type="checkbox" name="remember" id="remember">
            Ingat perangkat ini
        </label>
        <a href="#">Lupa kata sandi?</a>
    </div>

    <button type="submit" class="submit" id="submitBtn">
        <span class="spinner"></span>
        <span class="btn-label">Masuk sebagai Super Admin</span>
    </button>
</form>
    </div>
 
    <div class="footnote">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3Z"/></svg>
      Sesi ini dipantau demi keamanan sistem MasjidKu
    </div>
  </div>
</main>
 
<div class="toast" id="toast">Autentikasi berhasil — mengalihkan…</div>
 
<script src="script.js"></script>
</body>
</html>
 