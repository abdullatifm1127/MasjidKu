<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masjid Al-Innura - Tema Emerald</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <!-- Navbar / Theme Switcher Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">M</div>
                <span class="font-bold text-lg text-slate-900">Masjid Al-Innura</span>
            </div>
            <nav class="hidden md:flex space-x-6 text-sm font-medium">
                <a href="#jadwal" class="hover:text-emerald-600 transition">Jadwal Shalat</a>
                <a href="#program" class="hover:text-emerald-600 transition">Program</a>
                <a href="#donasi" class="hover:text-emerald-600 transition">Donasi</a>
                <a href="#kegiatan" class="hover:text-emerald-600 transition">Kegiatan</a>
                <a href="#transparansi" class="hover:text-emerald-600 transition">Keuangan</a>
            </nav>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-slate-500 hidden sm:inline">Pilih Tema:</span>
                <a href="{{ url('/theme/emerald') }}" class="px-3 py-1 text-xs font-semibold bg-emerald-600 text-white rounded-lg shadow">Emerald</a>
                <a href="{{ url('/theme/terracotta') }}" class="px-3 py-1 text-xs font-semibold bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700">Terracotta</a>
                <a href="{{ url('/theme/corporate') }}" class="px-3 py-1 text-xs font-semibold bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700">Corporate</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-700 text-white py-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <span class="px-3 py-1 bg-emerald-800 text-emerald-200 rounded-full text-xs font-semibold uppercase tracking-wider">Platform Digital Masjid</span>
            <h1 class="text-4xl md:text-6xl font-bold mt-4 mb-6">Pusat Peradaban & Pemberdayaan Umat</h1>
            <p class="max-w-2xl mx-auto text-emerald-100 text-lg mb-8">Menghadirkan transparansi, kenyamanan, dan kemudahan dalam beribadah serta berdonasi secara digital.</p>
            <div class="flex justify-center space-x-4">
                <a href="#donasi" class="bg-white text-emerald-900 font-semibold px-6 py-3 rounded-xl shadow-lg hover:bg-emerald-50 transition">Salurkan Infaq</a>
                <a href="#jadwal" class="border border-emerald-400 text-white font-semibold px-6 py-3 rounded-xl hover:bg-emerald-800/50 transition">Jadwal Shalat</a>
            </div>
        </div>
    </section>

    <!-- Jadwal Shalat & Widget -->
    <section id="jadwal" class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">
        <div class="bg-white rounded-2xl shadow-xl p-6 grid grid-cols-2 md:grid-cols-6 gap-4 text-center border border-slate-100">
            <div class="p-3 bg-slate-50 rounded-xl">
                <div class="text-xs text-slate-500 font-medium">Subuh</div>
                <div class="text-lg font-bold text-emerald-800 mt-1">04:32</div>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <div class="text-xs text-slate-500 font-medium">Dzuhur</div>
                <div class="text-lg font-bold text-emerald-800 mt-1">11:50</div>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <div class="text-xs text-slate-500 font-medium">Ashar</div>
                <div class="text-lg font-bold text-emerald-800 mt-1">15:12</div>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <div class="text-xs text-slate-500 font-medium">Maghrib</div>
                <div class="text-lg font-bold text-emerald-800 mt-1">17:55</div>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <div class="text-xs text-slate-500 font-medium">Isya</div>
                <div class="text-lg font-bold text-emerald-800 mt-1">19:05</div>
            </div>
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl col-span-2 md:col-span-1 shadow-inner">
                <div class="text-xs text-emerald-700 font-semibold">Menuju Isya</div>
                <div class="text-lg font-bold text-emerald-900 mt-1">01:15:20</div>
            </div>
        </div>
    </section>

    <!-- Pengumuman / Berita -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-emerald-600 text-white rounded-xl flex items-center justify-center font-bold text-xl shrink-0">📢</div>
                <div>
                    <h3 class="font-bold text-emerald-900 text-lg">Pengumuman Kegiatan Pekan Ini</h3>
                    <p class="text-emerald-700 text-sm">Kajian Subuh Berjamaah Bersama Ustadz Dr. H. Ahmad Fauzi, Lc. M.Ag pada hari Ahad.</p>
                </div>
            </div>
            <a href="#kegiatan" class="px-5 py-2.5 bg-emerald-600 text-white font-medium text-sm rounded-xl hover:bg-emerald-700 transition shadow">Lihat Detail</a>
        </div>
    </section>

    <!-- Program & Donasi Unggulan -->
    <section id="donasi" class="max-w-7xl mx-auto px-4 py-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900">Program & Donasi Umat</h2>
            <p class="text-slate-600 mt-2">Mari ambil bagian dalam pengembangan fasilitas dan kemaslahatan umat.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">Pembangunan</span>
                    <h3 class="text-xl font-bold text-slate-900 mt-4 mb-2">Renovasi Kubah & Plafon Utama</h3>
                    <p class="text-slate-600 text-sm mb-4">Perbaikan struktur kubah utama yang mengalami kebocoran saat musim hujan.</p>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                        <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 70%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 font-medium">
                        <span>Terkumpul: Rp 70.000.000</span>
                        <span>Target: Rp 100.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition shadow">Donasi Sekarang</button>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">Sosial</span>
                    <h3 class="text-xl font-bold text-slate-900 mt-4 mb-2">Santunan Anak Yatim & Dhuafa</h3>
                    <p class="text-slate-600 text-sm mb-4">Program rutin bulanan untuk mendukung pendidikan dan kebutuhan pokok anak yatim.</p>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                        <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 85%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 font-medium">
                        <span>Terkumpul: Rp 42.500.000</span>
                        <span>Target: Rp 50.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition shadow">Donasi Sekarang</button>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">Operasional</span>
                    <h3 class="text-xl font-bold text-slate-900 mt-4 mb-2">Operasional & TPA Al-Innura</h3>
                    <p class="text-slate-600 text-sm mb-4">Pengadaan fasilitas belajar mengajar Al-Qur'an gratis untuk anak-anak sekitar.</p>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                        <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 50%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 font-medium">
                        <span>Terkumpul: Rp 15.000.000</span>
                        <span>Target: Rp 30.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition shadow">Donasi Sekarang</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Transparansi Keuangan -->
    <section id="transparansi" class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Transparansi Keuangan Masjid</h2>
                    <p class="text-slate-600 text-sm mt-1">Laporan real-time pemasukan dan pengeluaran kas masjid</p>
                </div>
                <div class="px-4 py-2 bg-emerald-50 text-emerald-800 rounded-xl font-semibold text-sm">
                    Periode: Oktober 2026
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-sm text-slate-500">Total Saldo Kas</div>
                    <div class="text-2xl font-bold text-emerald-800 mt-2">Rp 124.500.000</div>
                </div>
                <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-sm text-slate-500">Pemasukan Bulan Ini</div>
                    <div class="text-2xl font-bold text-emerald-600 mt-2">+ Rp 28.400.000</div>
                </div>
                <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-sm text-slate-500">Pengeluaran Bulan Ini</div>
                    <div class="text-2xl font-bold text-rose-600 mt-2">- Rp 12.100.000</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="text-white font-bold text-lg mb-4 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold">M</div>
                    <span>Masjid Al-Innura</span>
                </div>
                <p class="text-sm text-slate-400">Pusat ibadah, pendidikan, dan sosial kemasyarakatan yang transparan dan akuntabel.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#jadwal" class="hover:text-white transition">Jadwal Shalat</a></li>
                    <li><a href="#donasi" class="hover:text-white transition">Program Donasi</a></li>
                    <li><a href="#transparansi" class="hover:text-white transition">Laporan Keuangan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                <p class="text-sm mb-2">Jl. Raya Masjid No. 45, Kota Metropolitan</p>
                <p class="text-sm">Email: info@masjidalinnura.com</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Jam Operasional</h4>
                <p class="text-sm">Terbuka 24 Jam untuk Ibadah Wajib & Kegiatan Majelis.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
            &copy; 2026 Masjid Al-Innura. Hak cipta dilindungi undang-undang.
        </div>
    </footer>
</body>
</html>