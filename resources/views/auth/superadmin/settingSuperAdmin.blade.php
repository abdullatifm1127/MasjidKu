{{--
    resources/views/auth/superadmin/settingSuperAdmin.blade.php

    Variabel dari PengaturanController@index:
      $setting  => App\Models\Setting (Setting::current())
      $stats    => ['total_masjid', 'aktif', 'pending', 'total_pengguna']
--}}
@php
    $authUser   = auth()->user();
    $initials   = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($authUser->name ?? 'SA', 0, 2));
    $pending    = (int) ($stats['pending'] ?? 0);
    $hasOld     = session()->hasOldInput();

    // Checkbox: jika validasi gagal pakai input lama, selain itu pakai nilai database
    $chkRegistration = $hasOld ? (bool) old('notif_registration') : (bool) $setting->notif_registration;
    $chkDonation     = $hasOld ? (bool) old('notif_donation')     : (bool) $setting->notif_donation;
    $chkMaintenance  = $hasOld ? (bool) old('maintenance_mode')   : (bool) $setting->maintenance_mode;

    $navItems = [
        [
            'route'  => 'superadmin.dashboard',
            'match'  => 'superadmin.dashboard',
            'label'  => 'Dashboard',
            'paths'  => ['M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
        ],
        [
            'route'  => 'superadmin.verifikasi',
            'match'  => 'superadmin.verifikasi*',
            'label'  => 'Verifikasi Pendaftaran',
            'badge'  => $pending > 0,
            'paths'  => ['M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
        ],
        [
            'route'  => 'superadmin.manajemen-masjid',
            'match'  => 'superadmin.manajemen-masjid*',
            'label'  => 'Manajemen Masjid',
            'paths'  => ['M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21'],
        ],
        [
            'route'  => 'superadmin.pembayaran.index',
            'match'  => 'superadmin.pembayaran*',
            'label'  => 'Pembayaran',
            'paths'  => ['M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
        ],
        [
            'route'  => 'superadmin.pengguna',
            'match'  => 'superadmin.pengguna*',
            'label'  => 'Pengguna',
            'paths'  => ['M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
        ],
        [
            'route'  => 'superadmin.halaman-utama.edit',
            'match'  => 'superadmin.halaman-utama*',
            'label'  => 'Halaman Utama',
            'paths'  => ['M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10'],
        ],
        [
            'route'  => 'superadmin.themes.index',
            'match'  => 'superadmin.themes*',
            'label'  => 'Manajemen Tema',
            'paths'  => ['M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h13.125c.621 0 1.125.504 1.125 1.125v5.25m-9.75 9.75h9.75a3.75 3.75 0 003.75-3.75V11.25M12 6.75h.008v.008H12V6.75zm3 3h.008v.008H15V9.75zm-6 3h.008v.008H9V12.75z'],
        ],
        [
            'route'  => 'superadmin.pengaturan',
            'match'  => 'superadmin.pengaturan*',
            'label'  => 'Pengaturan',
            'paths'  => [
                'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z',
                'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            ],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - {{ $setting->platform_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/superadmin/berandaSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/settingSuperAdmin.css') }}">

    {{-- Gaya modal konfirmasi hapus. Boleh dipindah ke settingSuperAdmin.css --}}
    <style>
        .st-modal-backdrop {
            position: fixed; inset: 0; z-index: 1000;
            display: none; align-items: center; justify-content: center;
            padding: 16px; background: rgba(15, 23, 42, .55);
        }
        .st-modal-backdrop.open { display: flex; }
        .st-modal {
            width: 100%; max-width: 420px; padding: 24px;
            background: #fff; color: #0f172a; border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
        }
        .st-modal h3 { margin: 0 0 8px; font-size: 17px; font-weight: 700; color: #b91c1c; }
        .st-modal p { margin: 0 0 14px; font-size: 13px; line-height: 1.5; color: #475569; }
        .st-modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; }
        .st-modal-cancel {
            padding: 9px 16px; border: 1px solid #cbd5e1; border-radius: 8px;
            background: #fff; color: #334155; font: inherit; font-size: 13px; cursor: pointer;
        }
    </style>
</head>
<body class="sa-page" id="saBody">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sa-sidebar" id="saSidebar">

        <div class="sa-brand">
            <div class="sa-brand-avatar">SA</div>
            <div class="sa-brand-info">
                <strong>{{ $setting->platform_name }} Admin</strong>
                <span>Super Administrator</span>
            </div>
        </div>

        <nav class="sa-nav">
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="sa-nav-item {{ !empty($item['badge']) ? 'sa-nav-has-badge' : '' }} {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                    <span class="sa-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                            @foreach($item['paths'] as $d)
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </span>
                    <span class="sa-nav-label">{{ $item['label'] }}</span>
                    @if(!empty($item['badge']))
                        <span class="sa-nav-badge-dot amber" title="{{ $pending }} pendaftaran menunggu"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="sa-user-footer">
            <div class="sa-user-avatar-sm">{{ $initials }}</div>
            <div class="sa-user-info">
                <div class="sa-user-name">{{ $authUser->name }}</div>
                <div class="sa-user-email">{{ $authUser->email }}</div>
            </div>
            <a href="{{ route('logout') }}" class="sa-logout-btn"
               onclick="event.preventDefault(); document.getElementById('sa-logout-form').submit();"
               aria-label="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
            </a>
            <form id="sa-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="sa-main" id="saMain">

        <header class="sa-topbar">
            <div class="sa-topbar-title">Pengaturan</div>
            <div class="sa-topbar-right">
                <a href="{{ url('/') }}" class="sa-btn-website" target="_blank" rel="noopener">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                    Lihat Website
                </a>

                {{-- Lonceng: menuju verifikasi, titik hanya muncul jika ada pendaftaran pending --}}
                <a href="{{ route('superadmin.verifikasi') }}" class="sa-notif-btn" aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    @if($pending > 0)
                        <span class="sa-notif-dot"></span>
                    @endif
                </a>
            </div>
        </header>

        <main class="sa-content st-content">

            @if(session('success'))
                <div class="st-alert st-alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="st-alert st-alert-error" style="background:#fef2f2;color:#b91c1c;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- FORM UTAMA: UPDATE PENGATURAN & PASSWORD --}}
            <form action="{{ route('superadmin.pengaturan.update') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="st-layout">

                    {{-- Kolom kiri: form --}}
                    <div class="st-col-form">

                        {{-- Informasi Platform --}}
                        <div class="st-section">
                            <div class="st-section-title">Informasi Platform</div>

                            <div class="st-field">
                                <label class="st-label" for="platform_name">NAMA PLATFORM</label>
                                <input type="text" id="platform_name" name="platform_name"
                                       class="st-input @error('platform_name') is-invalid @enderror"
                                       value="{{ old('platform_name', $setting->platform_name) }}"
                                       maxlength="100" required>
                                @error('platform_name')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="support_email">EMAIL SUPPORT</label>
                                <input type="email" id="support_email" name="support_email"
                                       class="st-input @error('support_email') is-invalid @enderror"
                                       value="{{ old('support_email', $setting->support_email) }}"
                                       placeholder="support@domain.id" maxlength="150">
                                @error('support_email')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="whatsapp">NOMOR WHATSAPP</label>
                                <input type="text" id="whatsapp" name="whatsapp"
                                       class="st-input @error('whatsapp') is-invalid @enderror"
                                       value="{{ old('whatsapp', $setting->whatsapp) }}"
                                       placeholder="+62 8xx xxxx xxxx" maxlength="30">
                                @error('whatsapp')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Batas Tenant --}}
                        <div class="st-section">
                            <div class="st-section-title">Batas Tenant</div>

                            <div class="st-field">
                                <label class="st-label" for="max_mosque_free">MAKS. MASJID PER PAKET GRATIS</label>
                                <input type="number" id="max_mosque_free" name="max_mosque_free"
                                       class="st-input @error('max_mosque_free') is-invalid @enderror"
                                       value="{{ old('max_mosque_free', $setting->max_mosque_free) }}"
                                       min="1" max="100" required>
                                @error('max_mosque_free')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="max_announcement">MAKS. PENGUMUMAN PER BULAN</label>
                                <input type="number" id="max_announcement" name="max_announcement"
                                       class="st-input @error('max_announcement') is-invalid @enderror"
                                       value="{{ old('max_announcement', $setting->max_announcement) }}"
                                       min="1" max="1000" required>
                                @error('max_announcement')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="st-field">
                                <label class="st-label" for="max_event">MAKS. ACARA PER BULAN</label>
                                <input type="number" id="max_event" name="max_event"
                                       class="st-input @error('max_event') is-invalid @enderror"
                                       value="{{ old('max_event', $setting->max_event) }}"
                                       min="1" max="1000" required>
                                @error('max_event')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Notifikasi Sistem --}}
                        <div class="st-section">
                            <div class="st-section-title">Notifikasi Sistem</div>

                            <div class="st-toggle-list">
                                <div class="st-toggle-item">
                                    <div>
                                        <div class="st-toggle-name">Notifikasi Pendaftaran Baru</div>
                                        <div class="st-toggle-desc">Kirim email ke super admin saat ada masjid baru mendaftar</div>
                                    </div>
                                    <label class="st-switch">
                                        <input type="checkbox" name="notif_registration" value="1" {{ $chkRegistration ? 'checked' : '' }}>
                                        <span class="st-switch-track"></span>
                                    </label>
                                </div>

                                <div class="st-toggle-item">
                                    <div>
                                        <div class="st-toggle-name">Notifikasi Donasi Masuk</div>
                                        <div class="st-toggle-desc">Kirim ringkasan donasi harian ke email super admin</div>
                                    </div>
                                    <label class="st-switch">
                                        <input type="checkbox" name="notif_donation" value="1" {{ $chkDonation ? 'checked' : '' }}>
                                        <span class="st-switch-track"></span>
                                    </label>
                                </div>

                                <div class="st-toggle-item">
                                    <div>
                                        <div class="st-toggle-name">Mode Maintenance</div>
                                        <div class="st-toggle-desc">Nonaktifkan akses publik sementara untuk pemeliharaan sistem</div>
                                    </div>
                                    <label class="st-switch">
                                        <input type="checkbox" name="maintenance_mode" value="1" {{ $chkMaintenance ? 'checked' : '' }}>
                                        <span class="st-switch-track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Keamanan Akun --}}
                        <div class="st-section">
                            <div class="st-section-title">Keamanan Akun</div>

                            <div class="st-field">
                                <label class="st-label" for="current_password">PASSWORD SAAT INI</label>
                                <input type="password" id="current_password" name="current_password"
                                       class="st-input @error('current_password') is-invalid @enderror"
                                       placeholder="Password saat ini" autocomplete="current-password">
                                @error('current_password')
                                    <div class="st-error-msg">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="st-grid-2">
                                <div class="st-field">
                                    <label class="st-label" for="new_password">PASSWORD BARU</label>
                                    <input type="password" id="new_password" name="new_password"
                                           class="st-input @error('new_password') is-invalid @enderror"
                                           placeholder="Min. 8 karakter" autocomplete="new-password">
                                    @error('new_password')
                                        <div class="st-error-msg">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="st-field">
                                    <label class="st-label" for="new_password_confirmation">KONFIRMASI PASSWORD</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                           class="st-input"
                                           placeholder="Ulangi password baru" autocomplete="new-password">
                                </div>
                            </div>

                            <p class="st-hint">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>

                        <div class="st-footer">
                            <button type="submit" class="st-btn-simpan">Simpan Pengaturan</button>
                        </div>

                    </div>

                    {{-- Kolom kanan: info card --}}
                    <div class="st-col-info">

                        <div class="st-info-card">
                            <div class="st-info-title">Versi Sistem</div>
                            <div class="st-info-val">{{ $setting->platform_name }} v{{ config('app.version', '1.0.0') }}</div>
                            <div class="st-info-sub">Laravel {{ app()->version() }}</div>
                        </div>

                        <div class="st-info-card">
                            <div class="st-info-title">Status Platform</div>
                            <div class="st-info-status-row">
                                @if($setting->maintenance_mode)
                                    <span class="st-status-dot amber"></span>
                                    <span class="st-info-val">Mode Maintenance</span>
                                @else
                                    <span class="st-status-dot green"></span>
                                    <span class="st-info-val">Berjalan Normal</span>
                                @endif
                            </div>
                            <div class="st-info-sub">
                                {{ $setting->maintenance_mode ? 'Akses publik dinonaktifkan' : 'Akses publik aktif' }}
                            </div>
                        </div>

                        <div class="st-info-card">
                            <div class="st-info-title">Ringkasan Tenant</div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Total Masjid</span>
                                <span class="st-info-stat-val">{{ $stats['total_masjid'] }}</span>
                            </div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Aktif</span>
                                <span class="st-info-stat-val green">{{ $stats['aktif'] }}</span>
                            </div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Pending</span>
                                <span class="st-info-stat-val amber">{{ $stats['pending'] }}</span>
                            </div>
                            <div class="st-info-stat-row">
                                <span class="st-info-stat-label">Total Pengguna</span>
                                <span class="st-info-stat-val">{{ $stats['total_pengguna'] }}</span>
                            </div>
                        </div>

                        <div class="st-info-card st-danger-card">
                            <div class="st-info-title red">Zona Berbahaya</div>
                            <p class="st-danger-desc">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan.</p>
                            <button type="button" class="st-btn-danger" id="openResetModal">
                                Hapus Semua Data Tenant
                            </button>
                        </div>

                    </div>

                </div>
            </form>

        </main>
    </div>

    {{-- ===== MODAL KONFIRMASI HAPUS DATA TENANT ===== --}}
    {{-- Form ini TIDAK boleh berada di dalam form utama (form bersarang tidak valid). --}}
    <div class="st-modal-backdrop {{ $errors->has('password') ? 'open' : '' }}" id="resetModal"
         role="dialog" aria-modal="true" aria-labelledby="resetModalTitle">
        <div class="st-modal">
            <h3 id="resetModalTitle">Hapus Semua Data Tenant?</h3>
            <p>
                Semua masjid beserta data terkaitnya akan dihapus permanen dan tidak bisa dikembalikan.
                Akun super admin tidak ikut terhapus. Masukkan password Anda untuk melanjutkan.
            </p>

            <form action="{{ route('superadmin.pengaturan.reset') }}" method="POST" autocomplete="off">
                @csrf
                @method('DELETE')

                <div class="st-field">
                    <label class="st-label" for="reset_password">PASSWORD ANDA</label>
                    <input type="password" id="reset_password" name="password"
                           class="st-input @error('password') is-invalid @enderror"
                           placeholder="Password saat ini" autocomplete="current-password" required>
                    @error('password')
                        <div class="st-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="st-modal-actions">
                    <button type="button" class="st-modal-cancel" id="closeResetModal">Batal</button>
                    <button type="submit" class="st-btn-danger">Ya, Hapus Semua</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Help FAB --}}
    <button type="button" class="help-fab" aria-label="Bantuan">?</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar selalu terbuka: bersihkan status collapsed lama dari browser
            try { localStorage.removeItem('sidebarCollapsed'); } catch (e) {}

            // Modal hapus data tenant
            var modal    = document.getElementById('resetModal');
            var password = document.getElementById('reset_password');

            function openModal()  { modal.classList.add('open'); password.focus(); }
            function closeModal() { modal.classList.remove('open'); password.value = ''; }

            document.getElementById('openResetModal').addEventListener('click', openModal);
            document.getElementById('closeResetModal').addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
            });

            // Jika validasi password gagal, modal terbuka otomatis (server) -> fokuskan input
            if (modal.classList.contains('open')) password.focus();
        });
    </script>
</body>
</html>