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

    // Helper kepala kartu: ikon + judul + keterangan (+ tombol buka/tutup semua item)
    $card = function ($icon, $title, $sub = null, $fold = false) {
        $h  = '<div class="hu-card-head"><span class="hu-ico"><i class="fa-solid '.$icon.'"></i></span>';
        $h .= '<div class="hu-card-tx"><h2>'.e($title).'</h2>'.($sub ? '<p class="sub">'.e($sub).'</p>' : '').'</div>';
        if ($fold) {
            $h .= '<div class="hu-fold">'
                .'<button type="button" class="hu-mini" data-fold="open"><i class="fa-solid fa-angles-down"></i> Buka semua</button>'
                .'<button type="button" class="hu-mini" data-fold="close"><i class="fa-solid fa-angles-up"></i> Tutup semua</button>'
                .'</div>';
        }
        return $h.'</div>';
    };

    // Ringkasan singkat untuk judul item
    $sum = fn ($t) => e(\Illuminate\Support\Str::limit(strip_tags((string) $t), 60));

    // Daftar tab: id => [label, ikon]
    $tabs = [
        'hero'    => ['Hero',          'fa-house'],
        'strip'   => ['Strip',         'fa-list-check'],
        'fitur'   => ['Fitur',         'fa-wand-magic-sparkles'],
        'cara'    => ['Cara Kerja',    'fa-route'],
        'judul'   => ['Judul Section', 'fa-heading'],
        'artikel' => ['Artikel',       'fa-newspaper'],
        'donasi'  => ['Donasi & Ayat', 'fa-hand-holding-heart'],
        'faq'     => ['FAQ',           'fa-circle-question'],
        'kontak'  => ['Kontak & CTA',  'fa-address-book'],
        'footer'  => ['Footer',        'fa-grip-lines'],
    ];
@endphp

<div class="sa-main" id="saMain">
<main class="sa-content">
<div class="hu-wrap">

    {{-- HEADER --}}
    <div class="hu-top">
        <div class="hu-top-main">
            <span class="hu-top-ico"><i class="fa-solid fa-mosque"></i></span>
            <div>
                <h1>Edit Halaman Utama</h1>
                <p>Ubah teks yang tampil di halaman depan MasjidKu. Perubahan langsung tampil setelah disimpan.</p>
            </div>
        </div>
        <a href="{{ url('/') }}" target="_blank" class="hu-btn ghost">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman
        </a>
    </div>

    @if(session('success'))
        <div class="hu-alert"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="hu-err">
            <strong><i class="fa-solid fa-triangle-exclamation"></i> Ada isian yang perlu diperbaiki:</strong>
            @foreach($errors->all() as $err)<div>• {{ $err }}</div>@endforeach
        </div>
    @endif

    {{-- TAB --}}
    <div class="hu-tabs" role="tablist">
        @foreach($tabs as $id => [$label, $icon])
            <button type="button" role="tab" class="hu-tab {{ $loop->first ? 'active' : '' }}" data-tab="{{ $id }}">
                <i class="fa-solid {{ $icon }}"></i> {{ $label }}
            </button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('superadmin.halaman-utama.update') }}" id="huForm">
        @csrf
        @method('PUT')

        {{-- HERO --}}
        <div class="hu-panel active" data-panel="hero">
            <div class="hu-card">
                {!! $card('fa-house', 'Hero (bagian paling atas)', 'Judul besar, deskripsi, dan tombol utama.') !!}

                <div class="hu-group">
                    <div class="hu-group-title">Tampilan awal</div>
                    <div class="hu-grid">
                        <div class="hu-field full">
                            <label class="hu-check">
                                <input type="hidden" name="hero[show_bismillah]" value="0">
                                <input type="checkbox" name="hero[show_bismillah]" value="1" {{ old('hero.show_bismillah', $c['hero']['show_bismillah']) ? 'checked' : '' }}>
                                <span>Tampilkan tulisan Bismillah</span>
                            </label>
                        </div>
                        {!! $f('hero[badge]', 'Badge', $c['hero']['badge'], ['cls' => 'full']) !!}
                    </div>
                </div>

                <div class="hu-group">
                    <div class="hu-group-title">Judul &amp; deskripsi</div>
                    <div class="hu-grid">
                        {!! $f('hero[title_1]', 'Judul baris 1', $c['hero']['title_1']) !!}
                        {!! $f('hero[title_2]', 'Judul baris 2 (sebelum kata hijau)', $c['hero']['title_2']) !!}
                        {!! $f('hero[title_highlight]', 'Kata yang disorot (hijau miring)', $c['hero']['title_highlight'], ['cls' => 'full']) !!}
                        {!! $f('hero[subtitle]', 'Deskripsi', $c['hero']['subtitle'], ['cls' => 'full', 'type' => 'textarea']) !!}
                    </div>
                </div>

                <div class="hu-group">
                    <div class="hu-group-title">Tombol</div>
                    <div class="hu-grid">
                        {!! $f('hero[btn_primary]', 'Teks tombol utama', $c['hero']['btn_primary']) !!}
                        {!! $f('hero[btn_secondary]', 'Teks tombol kedua', $c['hero']['btn_secondary']) !!}
                    </div>
                </div>

                <div class="hu-group">
                    <div class="hu-group-title">Kartu di sisi kanan</div>
                    <div class="hu-grid">
                        {!! $f('hero[card_small]', 'Teks kecil', $c['hero']['card_small']) !!}
                        {!! $f('hero[card_title]', 'Judul', $c['hero']['card_title']) !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- STRIP --}}
        <div class="hu-panel" data-panel="strip">
            <div class="hu-card">
                {!! $card('fa-list-check', 'Strip Keunggulan', 'Empat poin singkat di bawah jadwal shalat.') !!}
                @foreach($c['strip'] as $i => $s)
                    <details class="hu-item" open>
                        <summary>
                            <span class="hu-item-title">Poin {{ $i + 1 }}</span>
                            <span class="hu-item-sum">{!! $sum($s['text']) !!}</span>
                        </summary>
                        <div class="hu-item-body">
                            <div class="hu-grid icon">
                                {!! $f("strip[$i][icon]", 'Ikon', $s['icon']) !!}
                                {!! $f("strip[$i][text]", 'Teks', $s['text']) !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>

        {{-- FITUR --}}
        <div class="hu-panel" data-panel="fitur">
            <div class="hu-card">
                {!! $card('fa-wand-magic-sparkles', 'Fitur Unggulan', 'Judul section dan enam kartu fitur.', true) !!}
                {!! $head('features_head') !!}
                <div class="hu-spacer"></div>
                @foreach($c['features'] as $i => $x)
                    <details class="hu-item" {{ $loop->first ? 'open' : '' }}>
                        <summary>
                            <span class="hu-item-title">Fitur {{ $i + 1 }}</span>
                            <span class="hu-item-sum">{!! $sum($x['title']) !!}</span>
                        </summary>
                        <div class="hu-item-body">
                            <div class="hu-grid icon">
                                {!! $f("features[$i][icon]", 'Ikon', $x['icon']) !!}
                                {!! $f("features[$i][title]", 'Judul', $x['title']) !!}
                                {!! $f("features[$i][desc]", 'Deskripsi', $x['desc'], ['cls' => 'full', 'type' => 'textarea']) !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>

        {{-- CARA KERJA --}}
        <div class="hu-panel" data-panel="cara">
            <div class="hu-card">
                {!! $card('fa-route', 'Cara Kerja', 'Tiga langkah pendaftaran.', true) !!}
                {!! $head('steps_head') !!}
                <div class="hu-spacer"></div>
                @foreach($c['steps'] as $i => $x)
                    <details class="hu-item" open>
                        <summary>
                            <span class="hu-item-title">Langkah {{ $i + 1 }}</span>
                            <span class="hu-item-sum">{!! $sum($x['title']) !!}</span>
                        </summary>
                        <div class="hu-item-body">
                            <div class="hu-grid">
                                {!! $f("steps[$i][title]", 'Judul', $x['title'], ['cls' => 'full']) !!}
                                {!! $f("steps[$i][desc]", 'Deskripsi', $x['desc'], ['cls' => 'full', 'type' => 'textarea']) !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>

        {{-- JUDUL SECTION --}}
        <div class="hu-panel" data-panel="judul">
            @foreach(['program_head' => 'Program', 'masjid_head' => 'Masjid Terdaftar', 'artikel_head' => 'Artikel', 'faq_head' => 'FAQ'] as $key => $label)
                <div class="hu-card">
                    {!! $card('fa-heading', 'Judul Section: '.$label) !!}
                    {!! $head($key) !!}
                </div>
            @endforeach
            <p class="hu-note"><i class="fa-solid fa-circle-info"></i> Isi artikel ada di tab Artikel. Kartu Program, Masjid, dan Agenda dikelola dari menu masing-masing.</p>
        </div>

        {{-- DONASI & AYAT --}}
        <div class="hu-panel" data-panel="donasi">
            <div class="hu-card">
                {!! $card('fa-hand-holding-heart', 'Section Donasi', null, true) !!}
                {!! $head('donasi_head') !!}
                <div class="hu-spacer"></div>
                @foreach($c['donasi'] as $i => $x)
                    <details class="hu-item" open>
                        <summary>
                            <span class="hu-item-title">Kartu {{ $i + 1 }}</span>
                            <span class="hu-item-sum">{!! $sum($x['title']) !!}</span>
                        </summary>
                        <div class="hu-item-body">
                            <div class="hu-grid icon">
                                {!! $f("donasi[$i][icon]", 'Ikon', $x['icon']) !!}
                                {!! $f("donasi[$i][title]", 'Judul', $x['title']) !!}
                                {!! $f("donasi[$i][desc]", 'Deskripsi', $x['desc'], ['cls' => 'full']) !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
            <div class="hu-card">
                {!! $card('fa-book-quran', 'Ayat / Kutipan') !!}
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
                {!! $card('fa-circle-question', 'FAQ', 'Kosongkan pertanyaan bila ingin menyembunyikan item tersebut.', true) !!}
                @foreach($c['faq'] as $i => $x)
                    <details class="hu-item" {{ $loop->first ? 'open' : '' }}>
                        <summary>
                            <span class="hu-item-title">Pertanyaan {{ $i + 1 }}</span>
                            <span class="hu-item-sum">{!! $sum($x['q']) !!}</span>
                        </summary>
                        <div class="hu-item-body">
                            <div class="hu-grid">
                                {!! $f("faq[$i][q]", 'Pertanyaan', $x['q'], ['cls' => 'full']) !!}
                                {!! $f("faq[$i][a]", 'Jawaban', $x['a'], ['cls' => 'full', 'type' => 'textarea']) !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>

        {{-- KONTAK & CTA --}}
        <div class="hu-panel" data-panel="kontak">
            <div class="hu-card">
                {!! $card('fa-address-book', 'Kontak') !!}
                <div class="hu-grid">
                    {!! $f('kontak[title]', 'Judul', $c['kontak']['title']) !!}
                    {!! $f('kontak[desc]', 'Deskripsi', $c['kontak']['desc']) !!}
                    {!! $f('kontak[email]', 'Email (kosong = pakai email sistem)', $c['kontak']['email'], ['type' => 'email']) !!}
                    {!! $f('kontak[alamat]', 'Pusat layanan', $c['kontak']['alamat']) !!}
                    {!! $f('kontak[jam]', 'Jam layanan', $c['kontak']['jam'], ['cls' => 'full']) !!}
                </div>
            </div>
            <div class="hu-card">
                {!! $card('fa-bullhorn', 'Ajakan Daftar (CTA)') !!}
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
                {!! $card('fa-grip-lines', 'Footer') !!}
                <div class="hu-grid">
                    {!! $f('footer[tagline]', 'Tagline', $c['footer']['tagline'], ['cls' => 'full']) !!}
                    {!! $f('footer[instagram]', 'Link Instagram', $c['footer']['instagram']) !!}
                    {!! $f('footer[facebook]', 'Link Facebook', $c['footer']['facebook']) !!}
                    {!! $f('footer[youtube]', 'Link YouTube', $c['footer']['youtube']) !!}
                    {!! $f('footer[copyright]', 'Teks hak cipta (tahun otomatis)', $c['footer']['copyright']) !!}
                </div>
            </div>
        </div>

        {{-- BAR SIMPAN --}}
        <div class="hu-bar">
            <span class="hu-dirty" id="huDirty" hidden><i class="fa-solid fa-circle"></i> Ada perubahan yang belum disimpan</span>
            <div class="hu-bar-btns">
                <button type="submit" form="huReset" class="hu-btn danger" onclick="return confirm('Kembalikan semua teks halaman utama ke bawaan?')">
                    <i class="fa-solid fa-rotate-left"></i> Kembalikan ke Bawaan
                </button>
                <button type="submit" class="hu-btn primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <form id="huReset" method="POST" action="{{ route('superadmin.halaman-utama.reset') }}">@csrf @method('DELETE')</form>

    {{-- ARTIKEL (form terpisah dari form pengaturan di atas) --}}
    @php
        $ikonFile = ['pdf'=>'📕','doc'=>'📘','docx'=>'📘','xls'=>'📗','xlsx'=>'📗','ppt'=>'📙','pptx'=>'📙','zip'=>'🗜️','txt'=>'📄'];
    @endphp
    <div class="hu-panel" data-panel="artikel">
        <div class="hu-card">
            {!! $card($edit ? 'fa-pen-to-square' : 'fa-pen-nib', $edit ? 'Edit artikel' : 'Tulis artikel baru', 'Artikel yang diterbitkan tampil di bagian Artikel halaman utama.') !!}

            <form method="POST" action="{{ route('superadmin.artikel.simpan') }}" enctype="multipart/form-data">
                @csrf
                @if($edit)<input type="hidden" name="id" value="{{ $edit->id }}">@endif

                <div class="hu-grid">
                    {!! $f('judul', 'Judul', $edit->title ?? '', ['cls' => 'full']) !!}
                    {!! $f('isi', 'Isi artikel (pisahkan paragraf dengan baris kosong)', $edit->isi ?? '', ['cls' => 'full', 'type' => 'textarea']) !!}

                    <div class="hu-field full">
                        <label>Lampiran file <span class="hu-hint">PDF, Word, Excel, PowerPoint, TXT, ZIP · boleh kosong · maks 10 MB</span></label>
                        @if($edit && $edit->file)
                            <p class="hu-current">
                                <i class="fa-solid fa-paperclip"></i> File saat ini:
                                <a href="{{ $edit->file_url }}" target="_blank">{{ $edit->file_nama ?? basename($edit->file) }}</a>
                                <span class="hu-hint">(pilih file baru di bawah untuk menggantinya)</span>
                            </p>
                        @endif
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip">
                    </div>

                    {!! $f('link', 'Link artikel (opsional, mis. dari Google atau situs berita)', $edit->link ?? '', ['cls' => 'full', 'type' => 'url']) !!}

                    <div class="hu-field full">
                        <label class="hu-check">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $edit->is_published ?? true) ? 'checked' : '' }}>
                            <span>Terbitkan sekarang</span>
                        </label>
                    </div>
                </div>

                <div class="hu-art-btns">
                    <button type="submit" class="hu-btn primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Artikel</button>
                    @if($edit)<a href="{{ route('superadmin.halaman-utama.edit', ['tab' => 'artikel']) }}" class="hu-btn ghost">Batal</a>@endif
                </div>
            </form>
        </div>

        <div class="hu-card">
            {!! $card('fa-rectangle-list', 'Daftar artikel') !!}
            @forelse($artikels as $a)
                <div class="hu-art-item">
                    <div class="hu-art-thumb">{{ $ikonFile[$a->file_ext] ?? '📰' }}</div>

                    <div class="hu-art-info">
                        <h3>{{ $a->title }}</h3>
                        <div class="hu-art-meta">
                            <span class="hu-badge {{ $a->is_published ? 'terbit' : 'draft' }}">{{ $a->is_published ? 'Terbit' : 'Draft' }}</span>
                            <span><i class="fa-regular fa-calendar"></i> {{ $a->created_at?->translatedFormat('d M Y') }}</span>
                            @if($a->file)<span><i class="fa-solid fa-paperclip"></i> {{ $a->file_nama ?? strtoupper($a->file_ext) }}</span>@endif
                            @if($a->link)<span><i class="fa-solid fa-link"></i> {{ $a->link_host }}</span>@endif
                        </div>
                    </div>

                    <div class="hu-art-acts">
                        @if($a->is_published)
                            <a href="{{ route('artikel.show', $a->slug) }}" target="_blank" class="hu-btn ghost sm"><i class="fa-regular fa-eye"></i> Lihat</a>
                        @endif
                        <a href="{{ route('superadmin.halaman-utama.edit', ['tab' => 'artikel', 'edit' => $a->id]) }}" class="hu-btn ghost sm"><i class="fa-solid fa-pen"></i> Edit</a>
                        <form method="POST" action="{{ route('superadmin.artikel.terbit', $a->id) }}">@csrf @method('PATCH')
                            <button type="submit" class="hu-btn ghost sm">{{ $a->is_published ? 'Jadikan Draft' : 'Terbitkan' }}</button>
                        </form>
                        <form method="POST" action="{{ route('superadmin.artikel.hapus', $a->id) }}" onsubmit="return confirm('Hapus artikel ini?')">@csrf @method('DELETE')
                            <button type="submit" class="hu-btn danger sm"><i class="fa-regular fa-trash-can"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="hu-empty">
                    <i class="fa-regular fa-newspaper"></i>
                    <p>Belum ada artikel. Tulis artikel pertama lewat form di atas.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>{{-- tutup .hu-wrap --}}
</main>
</div>{{-- tutup .sa-main --}}

<script>
    var wrap = document.querySelector('.hu-wrap');

    // Pindah tab
    function openTab(id) {
        var btn = document.querySelector('.hu-tab[data-tab="' + id + '"]');
        if (!btn) return;
        document.querySelectorAll('.hu-tab').forEach(function (x) { x.classList.toggle('active', x === btn); });
        document.querySelectorAll('.hu-panel').forEach(function (p) { p.classList.toggle('active', p.dataset.panel === id); });
        wrap.classList.toggle('mode-artikel', id === 'artikel');
        btn.scrollIntoView({ block: 'nearest', inline: 'center' });
    }
    document.querySelectorAll('.hu-tab').forEach(function (t) {
        t.addEventListener('click', function () { openTab(t.dataset.tab); history.replaceState(null, '', '#' + t.dataset.tab); });
    });
    var start = new URLSearchParams(location.search).get('tab') || location.hash.slice(1);
    if (start) openTab(start);

    // Buka / tutup semua item dalam satu kartu
    document.querySelectorAll('[data-fold]').forEach(function (b) {
        b.addEventListener('click', function () {
            var open = b.dataset.fold === 'open';
            b.closest('.hu-card').querySelectorAll('details.hu-item').forEach(function (d) { d.open = open; });
        });
    });

    // Tandai perubahan yang belum disimpan
    var huForm = document.getElementById('huForm');
    var huDirty = document.getElementById('huDirty');
    var dirty = false;
    huForm.addEventListener('input', function () { dirty = true; huDirty.hidden = false; });
    document.querySelectorAll('form').forEach(function (f) { f.addEventListener('submit', function () { dirty = false; }); });
    window.addEventListener('beforeunload', function (e) {
        if (dirty) { e.preventDefault(); e.returnValue = ''; }
    });
</script>
</body>
</html>