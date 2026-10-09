@php
    $active = $active ?? '';
    $authUser = auth()->user();
    $items = [
        ['dashboard',     'superadmin.dashboard',          'fa-table-cells-large', 'Dashboard'],
        ['verifikasi',    'superadmin.verifikasi',         'fa-shield-halved',     'Verifikasi Pendaftaran'],
        ['manajemen',     'superadmin.manajemen-masjid',   'fa-mosque',            'Manajemen Masjid'],
        ['pengguna',      'superadmin.pengguna',           'fa-users',             'Pengguna'],
        ['halaman-utama', 'superadmin.halaman-utama.edit', 'fa-pen-to-square',     'Halaman Utama'],
        ['pengaturan',    'superadmin.pengaturan',         'fa-gear',              'Pengaturan'],
    ];
@endphp
<aside class="sa-sidebar" id="saSidebar">
    <div class="sa-brand">
        <div class="sa-brand-avatar">SA</div>
        <div class="sa-brand-info">
            <strong>MasjidKu Admin</strong>
            <span>Super Administrator</span>
        </div>
        <button class="sa-sidebar-toggle" id="saSidebarToggle" aria-label="Collapse sidebar">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    </div>

    <nav class="sa-nav">
        @foreach($items as [$key, $routeName, $icon, $label])
            <a href="{{ route($routeName) }}" class="sa-nav-item {{ $key === 'verifikasi' ? 'sa-nav-has-badge' : '' }} {{ $active === $key ? 'active' : '' }}">
                <span class="sa-nav-icon"><i class="fa-solid {{ $icon }}"></i></span>
                <span class="sa-nav-label">{{ $label }}</span>
                @if($key === 'verifikasi')<span class="sa-nav-badge-dot amber"></span>@endif
            </a>
        @endforeach
    </nav>

    <div class="sa-user-footer">
        <div class="sa-user-avatar-sm">SA</div>
        <div class="sa-user-info">
            <div class="sa-user-name">{{ $authUser->name ?? 'Super Admin' }}</div>
            <div class="sa-user-email">{{ $authUser->email ?? 'admin@masjidku.id' }}</div>
        </div>
        <a href="{{ route('logout') }}" class="sa-logout-btn"
           onclick="event.preventDefault(); document.getElementById('sa-logout-form').submit();" aria-label="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
        <form id="sa-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
    </div>
</aside>