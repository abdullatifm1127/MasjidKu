<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — Baitul Digital</title><link rel="stylesheet" href="{{ asset('css/masjid.css') }}">
</head>
<body class="auth-page">
<div class="auth-shell">
    <a class="auth-brand" href="{{ url('/') }}">🕌 <span>Baitul <strong>Digital</strong></span></a>
    <div class="auth-card">
        <div class="auth-icon">🕌</div>
        <span class="section-kicker">SELAMAT DATANG</span>
        <h1>Masuk ke Dashboard</h1>
        <p class="auth-muted">Kelola informasi dan kegiatan masjid Anda.</p>
        @if(session('status')) <div class="alert success">{{ session('status') }}</div> @endif
        @if($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <label>Email</label>
            <input class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="admin@masjid.id" required autofocus>
            <label>Password</label>
            <input class="form-input" type="password" name="password" placeholder="Masukkan password" required>
            <div class="form-row"><label class="check"><input type="checkbox" name="remember"> Ingat saya</label><a href="{{ route('password.request') }}">Lupa password?</a></div>
            <button class="btn btn-green full" type="submit">Masuk</button>
        </form>
        <p class="auth-bottom">Belum punya akun? <a href="{{ route('register') }}">Daftarkan Masjid</a></p>
    </div>
</div>
</body></html>