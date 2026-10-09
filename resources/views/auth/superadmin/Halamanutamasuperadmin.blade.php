<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Halaman Utama - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/halamanUtamaSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="sa-page" id="saBody">

@include('auth.superadmin.partials.sidebar', ['active' => 'halaman-utama'])

@php
    // Helper input: nama "hero[badge]" -> old('hero.badge')
    $f = function ($name, $label, $val, $o = []) {
        $dot  = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
        $v    = e(old($dot, $val));
        $cls  = $o['cls'] ?? '';
        $in   = (($o['type'] ?? 'text') === 'textarea')
            ? '<textarea name="'.$name.'"'.(!empty($o['ar']) ? ' class="ar" lang="ar"' : '').'>'.$v.'</textarea>'
            : '<input type="'.($o['type'] ?? 'text').'" name="'.$name.'" value="'.$v.'">';
        return '<div class="hu-field '.$cls.'"><label>'.e($label).'</label>'.$in.'</div>';
    };
    // Helper judul section (badge, title, desc)
    $head = function ($key) use ($c, $f) {
        $h = $f($key.'[badge]', 'Badge', $c[$key]['badge']).$f($key.'[title]', 'Judul', $c[$key]['title']);
        if (isset($c[$key]['desc'])) $h .= $f($key.'[desc]', 'Deskripsi', $c[$key]['desc'], ['cls' => 'full']);
        return '<div class="hu-grid">'.$h.'</div>';
    };
@endphp

<div class="sa-main" id="saMain">
<main class="sa-content">
<div class="hu-wrap">

    <div class="hu-top">
        <div>
            <h1>Edit Halaman Utama</h1>
            <p>Ubah teks yang tampil di halaman depan MasjidKu. Perubahan langsung tampil setelah disimpan.</p>
        </div>
        <a href="{{ url('/') }}" target="_blank" class="hu-btn ghost">Lihat Halaman ↗</a>
    </div>

    @if(session('success'))<div class="hu-alert">✔ {{ session('success') }}</div>@endif
    @if($errors->any())<div class="hu-err">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>@endif

    <div class="hu-tabs">
        @foreach(['hero'=>'Hero','strip'=>'Strip','fitur'=>'Fitur','cara'=>'Cara Kerja','judul'=>'Judul Section','donasi'=>'Donasi & Ayat','faq'=>'FAQ','kontak'=>'Kontak & CTA','footer'=>'Footer'] as $id => $label)
            <button type="button" class="hu-tab {{ $loop->first ? 'active' : '' }}" data-tab="{{ $id }}">{{ $label }}</button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('superadmin.halaman-utama.update') }}">
        @csrf
        @method('PUT')

        {{-- HERO --}}
        <div class="hu-panel active" data-panel="hero">
            <div class="hu-card">
                <h2>Hero (bagian paling atas)</h2>
                <p class="sub">Judul besar, deskripsi, dan tombol utama.</p>
                <div class="hu-grid">
                    <div class="hu-field full">
                        <label class="hu-check">
                            <input type="hidden" name="hero[show_bismillah]" value="0">
                            <input type="checkbox" name="hero[show_bismillah]" value="1" {{ old('hero.show_bismillah', $c['hero']['show_bismillah']) ? 'checked' : '' }}>
                            Tampilkan tulisan Bismillah
                        </label>
                    </div>
                    {!! $f('hero[badge]', 'Badge', $c['hero']['badge'], ['cls' => 'full']) !!}
                    {!! $f('hero[title_1]', 'Judul baris 1', $c['hero']['title_1']) !!}
                    {!! $f('hero[title_2]', 'Judul baris 2 (sebelum kata hijau)', $c['hero']['title_2']) !!}
                    {!! $f('hero[title_highlight]', 'Kata yang disorot (hijau miring)', $c['hero']['title_highlight'], ['cls' => 'full']) !!}
                    {!! $f('hero[subtitle]', 'Deskripsi', $c['hero']['subtitle'], ['cls' => 'full', 'type' => 'textarea']) !!}
                    {!! $f('hero[btn_primary]', 'Teks tombol utama', $c['hero']['btn_primary']) !!}
                    {!! $f('hero[btn_secondary]', 'Teks tombol kedua', $c['hero']['btn_secondary']) !!}
                    {!! $f('hero[card_small]', 'Kartu kanan: teks kecil', $c['hero']['card_small']) !!}
                    {!! $f('hero[card_title]', 'Kartu kanan: judul', $c['hero']['card_title']) !!}
                </div>
            </div>
        </div>

        {{-- STRIP --}}
        <div class="hu-panel" data-panel="strip">
            <div class="hu-card">
                <h2>Strip Keunggulan</h2>
                <p class="sub">Empat poin singkat di bawah jadwal shalat.</p>
                @foreach($c['strip'] as $i => $s)
                    <div class="hu-item">
                        <div class="hu-item-title">Poin {{ $i + 1 }}</div>
                        <div class="hu-grid icon">
                            {!! $f("strip[$i][icon]", 'Ikon', $s['icon']) !!}
                            {!! $f("strip[$i][text]", 'Teks', $s['text']) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FITUR --}}
        <div class="hu-panel" data-panel="fitur">
            <div class="hu-card">
                <h2>Fitur Unggulan</h2>
                <p class="sub">Judul section dan enam kartu fitur.</p>
                {!! $head('features_head') !!}
                <div class="hu-spacer"></div>
                @foreach($c['features'] as $i => $x)
                    <div class="hu-item">
                        <div class="hu-item-title">Fitur {{ $i + 1 }}</div>
                        <div class="hu-grid icon">
                            {!! $f("features[$i][icon]", 'Ikon', $x['icon']) !!}
                            {!! $f("features[$i][title]", 'Judul', $x['title']) !!}
                            {!! $f("features[$i][desc]", 'Deskripsi', $x['desc'], ['cls' => 'full', 'type' => 'textarea']) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CARA KERJA --}}
        <div class="hu-panel" data-panel="cara">
            <div class="hu-card">
                <h2>Cara Kerja</h2>
                <p class="sub">Tiga langkah pendaftaran.</p>
                {!! $head('steps_head') !!}
                <div class="hu-spacer"></div>
                @foreach($c['steps'] as $i => $x)
                    <div class="hu-item">
                        <div class="hu-item-title">Langkah {{ $i + 1 }}</div>
                        <div class="hu-grid">
                            {!! $f("steps[$i][title]", 'Judul', $x['title'], ['cls' => 'full']) !!}
                            {!! $f("steps[$i][desc]", 'Deskripsi', $x['desc'], ['cls' => 'full', 'type' => 'textarea']) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- JUDUL SECTION --}}
        <div class="hu-panel" data-panel="judul">
            @foreach(['program_head' => 'Program', 'masjid_head' => 'Masjid Terdaftar', 'artikel_head' => 'Artikel', 'faq_head' => 'FAQ'] as $key => $label)
                <div class="hu-card">
                    <h2>Judul Section: {{ $label }}</h2>
                    {!! $head($key) !!}
                </div>
            @endforeach
            <p class="hu-note">Isi kartu Program, Artikel, Masjid, dan Agenda tetap dikelola dari menu masing-masing.</p>
        </div>

        {{-- DONASI & AYAT --}}
        <div class="hu-panel" data-panel="donasi">
            <div class="hu-card">
                <h2>Section Donasi</h2>
                {!! $head('donasi_head') !!}
                <div class="hu-spacer"></div>
                @foreach($c['donasi'] as $i => $x)
                    <div class="hu-item">
                        <div class="hu-item-title">Kartu {{ $i + 1 }}</div>
                        <div class="hu-grid icon">
                            {!! $f("donasi[$i][icon]", 'Ikon', $x['icon']) !!}
                            {!! $f("donasi[$i][title]", 'Judul', $x['title']) !!}
                            {!! $f("donasi[$i][desc]", 'Deskripsi', $x['desc'], ['cls' => 'full']) !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="hu-card">
                <h2>Ayat / Kutipan</h2>
                <div class="hu-grid">
                    {!! $f('ayat[arab]', 'Teks Arab', $c['ayat']['arab'], ['cls' => 'full', 'type' => 'textarea', 'ar' => true]) !!}
                    {!! $f('ayat[arti]', 'Terjemahan', $c['ayat']['arti'], ['cls' => 'full', 'type' => 'textarea']) !!}
                    {!! $f('ayat[sumber]', 'Sumber', $c['ayat']['sumber'], ['cls' => 'full']) !!}
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="hu-panel" data-panel="faq">
            <div class="hu-card">
                <h2>FAQ</h2>
                <p class="sub">Kosongkan pertanyaan bila ingin menyembunyikan item tersebut.</p>
                @foreach($c['faq'] as $i => $x)
                    <div class="hu-item">
                        <div class="hu-item-title">Pertanyaan {{ $i + 1 }}</div>
                        <div class="hu-grid">
                            {!! $f("faq[$i][q]", 'Pertanyaan', $x['q'], ['cls' => 'full']) !!}
                            {!! $f("faq[$i][a]", 'Jawaban', $x['a'], ['cls' => 'full', 'type' => 'textarea']) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- KONTAK & CTA --}}
        <div class="hu-panel" data-panel="kontak">
            <div class="hu-card">
                <h2>Kontak</h2>
                <div class="hu-grid">
                    {!! $f('kontak[title]', 'Judul', $c['kontak']['title']) !!}
                    {!! $f('kontak[desc]', 'Deskripsi', $c['kontak']['desc']) !!}
                    {!! $f('kontak[email]', 'Email (kosong = pakai email sistem)', $c['kontak']['email'], ['type' => 'email']) !!}
                    {!! $f('kontak[alamat]', 'Pusat layanan', $c['kontak']['alamat']) !!}
                    {!! $f('kontak[jam]', 'Jam layanan', $c['kontak']['jam'], ['cls' => 'full']) !!}
                </div>
            </div>
            <div class="hu-card">
                <h2>Ajakan Daftar (CTA)</h2>
                <div class="hu-grid">
                    {!! $f('cta[title]', 'Judul', $c['cta']['title'], ['cls' => 'full']) !!}
                    {!! $f('cta[desc]', 'Deskripsi', $c['cta']['desc'], ['cls' => 'full', 'type' => 'textarea']) !!}
                    {!! $f('cta[btn_primary]', 'Tombol utama', $c['cta']['btn_primary']) !!}
                    {!! $f('cta[btn_secondary]', 'Tombol kedua', $c['cta']['btn_secondary']) !!}
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="hu-panel" data-panel="footer">
            <div class="hu-card">
                <h2>Footer</h2>
                <div class="hu-grid">
                    {!! $f('footer[tagline]', 'Tagline', $c['footer']['tagline'], ['cls' => 'full']) !!}
                    {!! $f('footer[instagram]', 'Link Instagram', $c['footer']['instagram']) !!}
                    {!! $f('footer[facebook]', 'Link Facebook', $c['footer']['facebook']) !!}
                    {!! $f('footer[youtube]', 'Link YouTube', $c['footer']['youtube']) !!}
                    {!! $f('footer[copyright]', 'Teks hak cipta (tahun otomatis)', $c['footer']['copyright']) !!}
                </div>
            </div>
        </div>

        <div class="hu-bar">
            <button type="submit" form="huReset" class="hu-btn danger" onclick="return confirm('Kembalikan semua teks halaman utama ke bawaan?')">Kembalikan ke Bawaan</button>
            <button type="submit" class="hu-btn primary">Simpan Perubahan</button>
        </div>
    </form>

    <form id="huReset" method="POST" action="{{ route('superadmin.halaman-utama.reset') }}">@csrf @method('DELETE')</form>
</div>
</main>
</div>

<script>
    document.querySelectorAll('.hu-tab').forEach(function (t) {
        t.addEventListener('click', function () {
            document.querySelectorAll('.hu-tab').forEach(function (x) { x.classList.toggle('active', x === t); });
            document.querySelectorAll('.hu-panel').forEach(function (p) { p.classList.toggle('active', p.dataset.panel === t.dataset.tab); });
            location.hash = t.dataset.tab;
        });
    });
    var h = location.hash.slice(1);
    if (h) { var b = document.querySelector('.hu-tab[data-tab="' + h + '"]'); if (b) b.click(); }
</script>
</body>
</html>