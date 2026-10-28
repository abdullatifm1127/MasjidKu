<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Pembayaran & Paket - Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/pembayaranSuperAdmin.css') }}">
</head>
<body class="sa-page" id="saBody">

    {{-- SIDEBAR --}}
    @include('auth.superadmin.partials.sidebar', ['active' => 'pembayaran'])

    @php
        $kodeMetode = ['midtrans', 'manual', 'qris'];
        $metode = $settings->filter(fn ($s) => in_array($s->code, $kodeMetode));
        $paket  = $settings->reject(fn ($s) => in_array($s->code, $kodeMetode));
    @endphp

    {{-- MAIN CONTENT --}}
    <div class="sa-main" id="saMain">
    <main class="sa-content">
    <div class="pb-wrap">

        {{-- Banner --}}
        <div class="pb-hero">
            <div>
                <h1>Pembayaran & Paket</h1>
                <p>Atur cara masjid membayar langganan, serta harga dan fitur setiap paket.</p>
            </div>
            <div class="pb-chips">
                <div class="pb-chip"><strong>{{ $metode->where('is_active', true)->count() }}/{{ $metode->count() }}</strong><span>Metode aktif</span></div>
                <div class="pb-chip"><strong>{{ $paket->where('is_active', true)->count() }}/{{ $paket->count() }}</strong><span>Paket aktif</span></div>
            </div>
        </div>

        @if(session('success'))
            <div class="pb-alert"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="pb-alert" style="background:#fef2f2;color:#991b1b;border-color:#fecaca">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        {{-- ===== METODE PEMBAYARAN ===== --}}
        <div class="pb-section">
            <h2>Metode Pembayaran</h2>
            <p>Pilih cara yang bisa dipakai pembayar. Metode yang dimatikan tidak akan ditampilkan.</p>
        </div>

        <div class="pb-grid">
            @forelse($metode as $s)
                @php
                    $config = json_decode($s->config_data, true) ?: [];
                    $ikon = [
                        'midtrans' => ['blue', 'fa-credit-card'],
                        'manual'   => ['', 'fa-building-columns'],
                        'qris'     => ['gold', 'fa-qrcode'],
                    ][$s->code] ?? ['', 'fa-wallet'];
                @endphp

                <div class="pb-card {{ $s->is_active ? '' : 'off' }}" data-code="{{ $s->code }}">
                    <form action="{{ route('superadmin.pembayaran.update', $s->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf

                        <div class="pb-card-head">
                            <div class="pb-title">
                                <span class="pb-icon {{ $ikon[0] }}"><i class="fa-solid {{ $ikon[1] }}"></i></span>
                                <div>
                                    <h3>{{ $s->method_name }}</h3>
                                    <span class="pb-pill" data-pill>{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                            </div>
                            <label class="pb-switch">
                                <input type="checkbox" name="is_active" value="1" {{ $s->is_active ? 'checked' : '' }} data-toggle>
                                <span class="pb-track"></span>
                            </label>
                        </div>

                        <div class="pb-body">
                            @if($s->code === 'midtrans')
                                @php
                                    $sk = $config['server_key'] ?? '';
                                    $mode = $sk === '' ? 'kosong' : (str_starts_with($sk, 'SB-') ? 'sandbox' : 'live');
                                @endphp
                                <p class="pb-desc">Pembayaran otomatis lewat Midtrans: QRIS, e-wallet, dan virtual account. Status langsung berubah setelah dibayar.</p>

                                <span class="pb-mode {{ $mode }}">
                                    <i class="fa-solid {{ $mode === 'live' ? 'fa-circle-check' : ($mode === 'sandbox' ? 'fa-flask' : 'fa-circle-info') }}"></i>
                                    {{ $mode === 'live' ? 'Mode Production (transaksi asli)' : ($mode === 'sandbox' ? 'Mode Sandbox (uji coba)' : 'Key belum diisi') }}
                                </span>

                                <div class="pb-field">
                                    <label>Server Key</label>
                                    <div class="pb-secret">
                                        <input type="password" name="server_key" value="{{ $config['server_key'] ?? '' }}" placeholder="SB-Mid-server-xxxx" autocomplete="new-password">
                                        <button type="button" class="pb-eye" data-eye aria-label="Tampilkan atau sembunyikan"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                    <small>Rahasia. Jangan dibagikan ke siapa pun.</small>
                                </div>
                                <div class="pb-field">
                                    <label>Client Key</label>
                                    <div class="pb-secret">
                                        <input type="password" name="client_key" value="{{ $config['client_key'] ?? '' }}" placeholder="SB-Mid-client-xxxx" autocomplete="new-password">
                                        <button type="button" class="pb-eye" data-eye aria-label="Tampilkan atau sembunyikan"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="pb-spacer"></div>

                            @elseif($s->code === 'manual')
                                @php
                                    $akun = $config['accounts'] ?? [];
                                    if (!$akun && !empty($config['bank_name'])) {
                                        $akun = [[
                                            'bank_name'      => $config['bank_name'],
                                            'account_number' => $config['account_number'] ?? '',
                                            'account_holder' => $config['account_holder'] ?? '',
                                        ]];
                                    }
                                    if (!$akun) $akun = [['bank_name' => '', 'account_number' => '', 'account_holder' => '']];
                                @endphp

                                <p class="pb-desc">Pembayar transfer ke salah satu rekening di bawah ini, lalu mengunggah foto bukti transfer. Anda bisa menambahkan lebih dari satu bank.</p>

                                <div data-accounts>
                                    @foreach($akun as $i => $a)
                                        <div class="pb-account" data-account>
                                            <button type="button" class="pb-remove" data-remove aria-label="Hapus rekening"><i class="fa-solid fa-trash"></i></button>
                                            <div class="pb-row2">
                                                <div class="pb-field">
                                                    <label>Nama Bank / E-Wallet</label>
                                                    <input type="text" name="accounts[{{ $i }}][bank_name]" value="{{ $a['bank_name'] }}" placeholder="Contoh: BSI / BCA / DANA">
                                                </div>
                                                <div class="pb-field">
                                                    <label>Nomor Rekening</label>
                                                    <input type="text" name="accounts[{{ $i }}][account_number]" value="{{ $a['account_number'] }}" placeholder="1234567890">
                                                </div>
                                            </div>
                                            <div class="pb-field" style="margin-bottom:0">
                                                <label>Atas Nama (A.N)</label>
                                                <input type="text" name="accounts[{{ $i }}][account_holder]" value="{{ $a['account_holder'] }}" placeholder="Takmir Masjid">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="pb-add" data-add-account><i class="fa-solid fa-plus"></i> Tambah Rekening / Bank Lain</button>

                                <template data-account-template>
                                    <div class="pb-account" data-account>
                                        <button type="button" class="pb-remove" data-remove aria-label="Hapus rekening"><i class="fa-solid fa-trash"></i></button>
                                        <div class="pb-row2">
                                            <div class="pb-field">
                                                <label>Nama Bank / E-Wallet</label>
                                                <input type="text" name="accounts[__I__][bank_name]" placeholder="Contoh: BSI / BCA / DANA">
                                            </div>
                                            <div class="pb-field">
                                                <label>Nomor Rekening</label>
                                                <input type="text" name="accounts[__I__][account_number]" placeholder="1234567890">
                                            </div>
                                        </div>
                                        <div class="pb-field" style="margin-bottom:0">
                                            <label>Atas Nama (A.N)</label>
                                            <input type="text" name="accounts[__I__][account_holder]" placeholder="Takmir Masjid">
                                        </div>
                                    </div>
                                </template>
                                <div class="pb-spacer"></div>

                            @elseif($s->code === 'qris')
                                @php $qrisUrl = !empty($config['qris_image']) ? asset('storage/' . $config['qris_image']) : ''; @endphp

                                <p class="pb-desc">Pembayar scan kode QRIS di bawah memakai e-wallet atau m-banking, lalu mengunggah foto bukti pembayaran. QRIS baru bisa diaktifkan setelah gambarnya diunggah.</p>

                                <div class="pb-qris">
                                    <img src="{{ $qrisUrl }}" alt="QRIS" data-qris-preview style="{{ $qrisUrl ? '' : 'display:none' }}">
                                    <span data-qris-empty style="{{ $qrisUrl ? 'display:none' : '' }}">Belum ada gambar QRIS</span>
                                </div>

                                <div class="pb-field">
                                    <label>Nama Merchant</label>
                                    <input type="text" name="merchant_name" value="{{ $config['merchant_name'] ?? '' }}" placeholder="Contoh: Masjid Al-Ikhlas">
                                </div>
                                <div class="pb-field">
                                    <label>Unggah Gambar QRIS</label>
                                    <input type="file" name="qris_image" accept="image/png,image/jpeg,image/webp" data-qris-file>
                                    <small>JPG, PNG, atau WEBP, maksimal 2 MB. Kosongkan jika tidak ingin mengganti.</small>
                                </div>
                                <div class="pb-spacer"></div>
                            @endif
                        </div>

                        <div class="pb-foot">
                            <button type="submit" class="pb-btn"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="pb-empty">Belum ada metode pembayaran.</div>
            @endforelse
        </div>

        {{-- ===== PAKET BERLANGGANAN ===== --}}
        <div class="pb-section">
            <h2>Paket Berlangganan</h2>
            <p>Atur nama, harga, dan fitur yang didapat masjid. Pratinjau di bagian atas kartu ikut berubah saat Anda mengetik.</p>
        </div>

        <div class="pb-grid">
            @forelse($paket as $s)
                @php
                    $config = json_decode($s->config_data, true) ?: [];
                    $fitur  = $config['features'] ?? [];
                    $tahun  = str_contains($s->code, 'year');
                @endphp

                <div class="pb-card {{ $s->is_active ? '' : 'off' }}" data-code="{{ $s->code }}">
                    <form action="{{ route('superadmin.pembayaran.update', $s->id) }}" method="POST">
                        @csrf

                        <div class="pb-card-head">
                            <div class="pb-title">
                                <span class="pb-icon gold"><i class="fa-solid {{ $tahun ? 'fa-calendar-check' : 'fa-calendar-day' }}"></i></span>
                                <div>
                                    <h3>{{ $tahun ? 'Paket Tahunan' : 'Paket Bulanan' }}</h3>
                                    <span class="pb-pill" data-pill>{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                            </div>
                            <label class="pb-switch">
                                <input type="checkbox" name="is_active" value="1" {{ $s->is_active ? 'checked' : '' }} data-toggle>
                                <span class="pb-track"></span>
                            </label>
                        </div>

                        {{-- Pratinjau gaya kartu harga --}}
                        <div class="pb-plan">
                            <div class="nama" data-v="method_name">{{ $s->method_name }}</div>
                            <div class="pb-plan-price">
                                <small>Rp</small><strong data-v="price">{{ number_format($config['price'] ?? 0, 0, ',', '.') }}</strong><em>/ {{ $tahun ? 'tahun' : 'bulan' }}</em>
                            </div>
                            <ul class="pb-features" data-v="features">
                                @forelse($fitur as $f)<li>{{ $f }}</li>@empty<li class="kosong">Belum ada fitur</li>@endforelse
                            </ul>
                        </div>

                        <div class="pb-body">
                            <div class="pb-field">
                                <label>Nama Paket</label>
                                <input type="text" name="method_name" value="{{ $s->method_name }}" class="pb-name-input" data-bind="method_name">
                            </div>
                            <div class="pb-field">
                                <label>Harga Paket</label>
                                <div class="pb-price">
                                    <span>Rp</span>
                                    <input type="number" name="price" value="{{ $config['price'] ?? 0 }}" placeholder="100000" min="0" data-bind="price">
                                </div>
                            </div>
                            <div class="pb-field" style="margin-bottom:0">
                                <label>Daftar Fitur</label>
                                <textarea name="features" rows="5" placeholder="Masukkan fitur, pisahkan dengan enter..." data-bind="features">@if(isset($config['features'])){{ implode("\n", $config['features']) }}@endif</textarea>
                                <small>Satu baris sama dengan satu poin fitur.</small>
                            </div>
                        </div>

                        <div class="pb-foot">
                            <button type="submit" class="pb-btn"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="pb-empty">Belum ada paket berlangganan.</div>
            @endforelse
        </div>

    </div>
    </main>
    </div>

<script>
    document.querySelectorAll('.pb-card').forEach(function (card) {
        // Saklar aktif: ubah warna kartu & tulisan status
        var sw = card.querySelector('[data-toggle]'), pill = card.querySelector('[data-pill]');
        if (sw) sw.addEventListener('change', function () {
            card.classList.toggle('off', !sw.checked);
            if (pill) pill.textContent = sw.checked ? 'Aktif' : 'Nonaktif';
        });

        // Pratinjau langsung saat mengetik (kartu paket)
        card.querySelectorAll('[data-bind]').forEach(function (input) {
            input.addEventListener('input', function () {
                var key = input.dataset.bind, target = card.querySelector('[data-v="' + key + '"]');
                if (!target) return;
                var v = input.value.trim();
                if (key === 'price') {
                    target.textContent = (parseInt(v, 10) || 0).toLocaleString('id-ID');
                } else if (key === 'features') {
                    target.innerHTML = '';
                    var rows = v.split(/\r?\n/).map(function (x) { return x.trim(); }).filter(Boolean);
                    if (!rows.length) { var e = document.createElement('li'); e.className = 'kosong'; e.textContent = 'Belum ada fitur'; target.appendChild(e); }
                    rows.forEach(function (t) { var li = document.createElement('li'); li.textContent = t; target.appendChild(li); });
                } else {
                    target.textContent = v || input.placeholder;
                }
            });
        });
    });

    // Tombol mata untuk kunci rahasia
    document.querySelectorAll('[data-eye]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.parentElement.querySelector('input'), show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.innerHTML = '<i class="fa-solid ' + (show ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
        });
    });

    // Tambah / hapus rekening bank
    document.querySelectorAll('[data-accounts]').forEach(function (list) {
        var body = list.closest('.pb-body');
        var tpl = body.querySelector('[data-account-template]');
        var n = 0;

        body.querySelector('[data-add-account]').addEventListener('click', function () {
            var html = tpl.innerHTML.replace(/__I__/g, 'n' + Date.now() + (n++));
            list.insertAdjacentHTML('beforeend', html);
        });

        list.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-remove]');
            if (!btn) return;
            var items = list.querySelectorAll('[data-account]');
            if (items.length > 1) {
                btn.closest('[data-account]').remove();
            } else {
                items[0].querySelectorAll('input').forEach(function (i) { i.value = ''; });
            }
        });
    });

    // Pratinjau gambar QRIS sebelum disimpan
    document.querySelectorAll('[data-qris-file]').forEach(function (file) {
        file.addEventListener('change', function () {
            var f = file.files[0]; if (!f) return;
            var body = file.closest('.pb-body');
            var img = body.querySelector('[data-qris-preview]'), empty = body.querySelector('[data-qris-empty]');
            var r = new FileReader();
            r.onload = function (e) { img.src = e.target.result; img.style.display = 'block'; empty.style.display = 'none'; };
            r.readAsDataURL(f);
        });
    });
</script>
</body>
</html>