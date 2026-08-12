<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard — MasjidKu</title>

    <link rel="stylesheet"
          href="{{ asset('css/masjid.css') }}">
</head>

<body>

    <header class="site-header">
        <div class="container nav-wrap">

            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark">🕌</span>
                <span>
                    <strong>Masjid</strong>Ku
                </span>
            </a>

            <nav class="nav-links">

                <a href="{{ route('home') }}">
                    Beranda
                </a>

                <a href="{{ route('daftar.masjid') }}">
                    Daftarkan Masjid
                </a>

                <form method="POST"
                      action="{{ route('logout') }}"
                      style="display:inline;">
                    @csrf

                    <button type="submit"
                            class="nav-login">
                        Logout
                    </button>
                </form>

            </nav>

        </div>
    </header>


    <main class="container" style="padding: 50px 20px;">

        <div class="section-heading left">

            <span class="section-kicker">
                DASHBOARD
            </span>

            <h1>
                Selamat Datang, {{ Auth::user()->name }}
            </h1>

            <p>
                Kelola data masjid Anda melalui dashboard.
            </p>

        </div>


        @if(session('success'))
            <div style="
                padding: 15px;
                margin-bottom: 25px;
                background: #e8f5ee;
                color: #17633d;
                border-radius: 10px;
            ">
                {{ session('success') }}
            </div>
        @endif


        @forelse($mosques as $mosque)

            <div class="feature-card" style="margin-bottom: 20px;">

                <h2>
                    {{ $mosque->mosque_name }}
                </h2>

                @if($mosque->tagline)
                    <p>
                        {{ $mosque->tagline }}
                    </p>
                @endif

                <p>
                    📍 {{ $mosque->city }},
                    {{ $mosque->province }}
                </p>

                <p>
                    📞 {{ $mosque->phone }}
                </p>

                <p>
                    ✉️ {{ $mosque->email }}
                </p>

            </div>

        @empty

            <div class="feature-card">

                <h2>
                    Belum ada masjid
                </h2>

                <p>
                    Anda belum mendaftarkan masjid.
                </p>

                <a href="{{ route('daftar.masjid') }}"
                   class="btn btn-gold">
                    Daftarkan Masjid
                </a>

            </div>

        @endforelse

    </main>

</body>
</html>