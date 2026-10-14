<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artikel->title }} - MasjidKu</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 150) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #14532d;
            --green-2: #166534;
            --green-soft: #ecf6ef;
            --gold: #c8a24a;
            --ink: #1b2a22;
            --muted: #66756c;
            --line: #e3ebe5;
            --bg: #f6f8f6;
            --card: #ffffff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }

        /* ===== Top bar ===== */
        .bar {
            position: sticky; top: 0; z-index: 10;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
        }
        .bar-in {
            max-width: 920px; margin: 0 auto; padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .brand { font-weight: 500; font-size: 1.15rem; display: flex; align-items: center; gap: 8px; }
        .brand strong { color: var(--green); font-weight: 700; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; border-radius: 999px;
            font: 500 .9rem 'Poppins', sans-serif;
            border: 1.5px solid var(--green); color: var(--green); background: transparent;
            cursor: pointer; transition: .2s;
        }
        .btn:hover { background: var(--green); color: #fff; transform: translateY(-1px); }
        .btn.solid { background: var(--green); color: #fff; }
        .btn.solid:hover { background: var(--green-2); }
        .btn.gold { background: var(--gold); border-color: var(--gold); color: #1b1b1b; }
        .btn.gold:hover { filter: brightness(1.05); background: var(--gold); color: #1b1b1b; }

        /* ===== Hero judul ===== */
        .hero {
            background: linear-gradient(135deg, #0f3d22 0%, var(--green) 55%, var(--green-2) 100%);
            color: #fff; padding: 56px 20px 84px; position: relative; overflow: hidden;
        }
        .hero::after {
            content: ''; position: absolute; right: -80px; top: -80px;
            width: 320px; height: 320px; border-radius: 50%;
            background: radial-gradient(circle, rgba(200,162,74,.25), transparent 70%);
        }
        .hero-in { max-width: 820px; margin: 0 auto; position: relative; z-index: 1; }
        .crumb { font-size: .85rem; opacity: .8; margin-bottom: 14px; }
        .crumb a:hover { text-decoration: underline; }
        .tag {
            display: inline-block; background: rgba(255,255,255,.14);
            padding: 4px 14px; border-radius: 999px; font-size: .78rem; margin-bottom: 16px;
        }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4.5vw, 2.8rem); line-height: 1.25; font-weight: 700;
        }
        .meta { margin-top: 18px; display: flex; flex-wrap: wrap; gap: 8px 20px; font-size: .88rem; opacity: .9; }

        /* ===== Konten ===== */
        .wrap { max-width: 820px; margin: -48px auto 60px; padding: 0 20px; position: relative; z-index: 2; }
        .card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: 18px; padding: 36px;
            box-shadow: 0 10px 30px rgba(20,83,45,.07);
        }
        .card + .card { margin-top: 22px; }
        .isi p { font-size: 1.03rem; margin-bottom: 1.1em; color: #26362d; }
        .isi p:first-child::first-letter {
            font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 700;
            color: var(--green); float: left; line-height: 1; padding: 6px 10px 0 0;
        }
        .isi p:last-child { margin-bottom: 0; }

        .card h2 { font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 14px; color: var(--green); }

        /* Link */
        .src { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; justify-content: space-between; background: var(--green-soft); border-color: #cfe4d5; }
        .src small { display: block; color: var(--muted); font-size: .8rem; }
        .src b { font-weight: 600; word-break: break-all; }

        /* File */
        .file {
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
            padding: 16px; border: 1px solid var(--line); border-radius: 14px; background: #fbfdfb;
        }
        .file-ico {
            width: 52px; height: 52px; border-radius: 12px; background: var(--green-soft);
            display: grid; place-items: center; font-size: 1.6rem; flex-shrink: 0;
        }
        .file-info { flex: 1; min-width: 160px; }
        .file-info b { display: block; font-weight: 600; word-break: break-word; }
        .file-info small { color: var(--muted); text-transform: uppercase; letter-spacing: .04em; font-size: .75rem; }
        .preview { margin-top: 18px; width: 100%; height: 640px; border: 1px solid var(--line); border-radius: 12px; background: #fff; }

        .foot { text-align: center; margin-top: 30px; }

        @media (max-width: 640px) {
            .card { padding: 22px; border-radius: 14px; }
            .hero { padding: 40px 20px 72px; }
            .preview { height: 460px; }
            .btn.full-m { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

@php
    $ikon = ['pdf'=>'📕','doc'=>'📘','docx'=>'📘','xls'=>'📗','xlsx'=>'📗','ppt'=>'📙','pptx'=>'📙','zip'=>'🗜️','txt'=>'📄'];
    $paragraf = preg_split('/\R{2,}/', trim($artikel->isi ?? ''));
    $menit = max(1, (int) ceil(str_word_count(strip_tags($artikel->isi ?? '')) / 200));
@endphp

<header class="bar">
    <div class="bar-in">
        <a href="{{ url('/') }}" class="brand">🕌 <span>Masjid<strong>Ku</strong></span></a>
        <a href="{{ url('/') }}#artikel" class="btn">← Kembali</a>
    </div>
</header>

<section class="hero">
    <div class="hero-in">
        <div class="crumb"><a href="{{ url('/') }}">Beranda</a> / <a href="{{ url('/') }}#artikel">Artikel</a></div>
        <span class="tag">Artikel</span>
        <h1>{{ $artikel->title }}</h1>
        <div class="meta">
            <span>📅 {{ $artikel->created_at->translatedFormat('d F Y') }}</span>
            <span>⏱ {{ $menit }} menit baca</span>
            @if($artikel->file)<span>📎 Ada lampiran</span>@endif
        </div>
    </div>
</section>

<main class="wrap">

    <article class="card isi">
        @foreach($paragraf as $p)
            <p>{!! nl2br(e($p)) !!}</p>
        @endforeach
    </article>

    @if($artikel->link)
        <div class="card src">
            <div>
                <small>Sumber / artikel terkait</small>
                <b>🔗 {{ $artikel->link_host }}</b>
            </div>
            <a href="{{ $artikel->link }}" target="_blank" rel="noopener noreferrer" class="btn gold full-m">
                Baca selengkapnya ↗
            </a>
        </div>
    @endif

    @if($artikel->file)
        <div class="card">
            <h2>Lampiran</h2>
            <div class="file">
                <div class="file-ico">{{ $ikon[$artikel->file_ext] ?? '📄' }}</div>
                <div class="file-info">
                    <b>{{ $artikel->file_nama ?? 'Lampiran' }}</b>
                    <small>File {{ $artikel->file_ext }}</small>
                </div>
                <a href="{{ $artikel->file_url }}" download="{{ $artikel->file_nama }}" class="btn solid full-m">⬇ Unduh</a>
            </div>

            @if($artikel->file_ext === 'pdf')
                <iframe class="preview" src="{{ $artikel->file_url }}" title="Pratinjau {{ $artikel->file_nama }}"></iframe>
            @endif
        </div>
    @endif

    <div class="foot">
        <a href="{{ url('/') }}#artikel" class="btn">← Kembali ke Artikel</a>
    </div>
</main>

</body>
</html>