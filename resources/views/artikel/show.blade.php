<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artikel->title }} - MasjidKu</title>
    <link rel="stylesheet" href="{{ asset('css/halaman-utama.css') }}">
</head>
<body>
<main class="section-container" style="max-width:820px;padding:40px 20px">
    <a href="{{ url('/') }}#artikel">← Kembali</a>

    <h1>{{ $artikel->title }}</h1>
    <small>{{ $artikel->created_at->translatedFormat('d F Y') }}</small>

    <div style="margin-top:20px;line-height:1.8">
        {!! nl2br(e($artikel->isi)) !!}
    </div>

    @if($artikel->file)
        <h3 style="margin-top:32px">Lampiran</h3>

        @if($artikel->file_ext === 'pdf')
            <iframe src="{{ $artikel->file_url }}" width="100%" height="600" style="border:1px solid #ddd;border-radius:8px"></iframe>
        @endif

        <p>
            <a href="{{ $artikel->file_url }}" download="{{ $artikel->file_nama }}" class="btn-primary">
                ⬇ Unduh {{ $artikel->file_nama ?? 'lampiran' }}
            </a>
        </p>
    @endif
</main>
</body>
</html>