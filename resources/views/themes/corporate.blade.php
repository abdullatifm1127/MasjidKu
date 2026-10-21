<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masjid Al-Innura - Tema Corporate (Executive Professional)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100">
    <!-- Navbar / Theme Switcher Bar -->
    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">⚡</div>
                <span class="font-bold text-lg text-white">Masjid Al-Innura</span>
            </div>
            <nav class="hidden md:flex space-x-6 text-sm font-medium text-slate-300">
                <a href="#jadwal" class="hover:text-blue-400 transition">Jadwal Shalat</a>
                <a href="#program" class="hover:text-blue-400 transition">Korporat</a>
                <a href="#donasi" class="hover:text-blue-400 transition">Endowment</a>
                <a href="#kegiatan" class="hover:text-blue-400 transition">Agenda</a>
                <a href="#transparansi" class="hover:text-blue-400 transition">Audit Keuangan</a>
            </nav>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-slate-400 hidden sm:inline">Pilih Tema:</span>
                <a href="{{ url('/theme/emerald') }}" class="px-3 py-1 text-xs font-semibold bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300">Emerald</a>
                <a href="{{ url('/theme/terracotta') }}" class="px-3 py-1 text-xs font-semibold bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300">Terracotta</a>
                <a href="{{ url('/theme/corporate') }}" class="px-3 py-1 text-xs font-semibold bg-blue-600 text-white rounded-lg shadow">Corporate</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white py-20 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto text-center">
            <span class="px-3 py-1 bg-blue-950 text-blue-400 rounded-full text-xs font-semibold uppercase tracking-wider border border-blue-800">Enterprise Mosque Management</span>
            <h1 class="text-4xl md:text-6xl font-bold mt-4 mb-6">Modern, Akuntabel, & Berbasis Teknologi</h1>
            <p class="max-w-2xl mx-auto text-slate-300 text-lg mb-8">Pusat ekosistem digital masjid korporat dengan tata kelola transparan dan pelaporan keuangan real-time.</p>
            <div class="flex justify-center space-x-4">
                <a href="#donasi" class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:bg-blue-500 transition">Corporate Endowment</a>
                <a href="#jadwal" class="border border-slate-700 text-slate-200 font-semibold px-6 py-3 rounded-xl hover:bg-slate-800 transition">Jadwal Shalat</a>
            </div>
        </div>
    </section>

    <!-- Jadwal Shalat & Widget -->
    <section id="jadwal" class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">
        <div class="bg-slate-900 rounded-2xl shadow-2xl p-6 grid grid-cols-2 md:grid-cols-6 gap-4 text-center border border-slate-800">
            <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                <div class="text-xs text-slate-400 font-medium">Subuh</div>
                <div class="text-lg font-bold text-blue-400 mt-1">04:32</div>
            </div>
            <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                <div class="text-xs text-slate-400 font-medium">Dzuhur</div>
                <div class="text-lg font-bold text-blue-400 mt-1">11:50</div>
            </div>
            <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                <div class="text-xs text-slate-400 font-medium">Ashar</div>
                <div class="text-lg font-bold text-blue-400 mt-1">15:12</div>
            </div>
            <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                <div class="text-xs text-slate-400 font-medium">Maghrib</div>
                <div class="text-lg font-bold text-blue-400 mt-1">17:55</div>
            </div>
            <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                <div class="text-xs text-slate-400 font-medium">Isya</div>
                <div class="text-lg font-bold text-blue-400 mt-1">19:05</div>
            </div>
            <div class="p-3 bg-blue-950/80 border border-blue-800 rounded-xl col-span-2 md:col-span-1 shadow-inner">
                <div class="text-xs text-blue-300 font-semibold">Menuju Isya</div>
                <div class="text-lg font-bold text-blue-400 mt-1">01:15:20</div>
            </div>
        </div>
    </section>

    <!-- Pengumuman / Berita -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-xl">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-xl shrink-0">📊</div>
                <div>
                    <h3 class="font-bold text-white text-lg">Audit Keuangan & Laporan Triwulan</h3>
                    <p class="text-slate-400 text-sm">Laporan publikasi keuangan korporat masjid Q3 telah dapat diakses secara digital oleh jamaah.</p>
                </div>
            </div>
            <a href="#transparansi" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-xl hover:bg-blue-500 transition shadow">Unduh Laporan</a>
        </div>
    </section>

    <!-- Program & Donasi Unggulan -->
    <section id="donasi" class="max-w-7xl mx-auto px-4 py-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-white">Program & Corporate Endowment</h2>
            <p class="text-slate-400 mt-2">Kolaborasi strategis pengembangan fasilitas dan pemberdayaan ekonomi umat.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-blue-950 text-blue-400 text-xs font-semibold rounded-full border border-blue-800/50">Infrastruktur</span>
                    <h3 class="text-xl font-bold text-white mt-4 mb-2">Smart Building & Digitalisasi Masjid</h3>
                    <p class="text-slate-400 text-sm mb-4">Integrasi sistem IoT manajemen energi, pendingin ruangan, dan sistem audio terpusat.</p>
                    <div class="w-full bg-slate-800 rounded-full h-2.5 mb-2">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-400 font-medium">
                        <span>Terkumpul: Rp 150.000.000</span>
                        <span>Target: Rp 200.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-slate-900/50 border-t border-slate-800">
                    <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl transition shadow">Kontribusi Korporat</button>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-blue-950 text-blue-400 text-xs font-semibold rounded-full border border-blue-800/50">Pemberdayaan</span>
                    <h3 class="text-xl font-bold text-white mt-4 mb-2">Inkubasi Bisnis & UMKM Masjid</h3>
                    <p class="text-slate-400 text-sm mb-4">Program pelatihan kewirausahaan mandiri dan digital marketing untuk jamaah sekitar.</p>
                    <div class="w-full bg-slate-800 rounded-full h-2.5 mb-2">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 60%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-400 font-medium">
                        <span>Terkumpul: Rp 60.000.000</span>
                        <span>Target: Rp 100.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-slate-900/50 border-t border-slate-800">
                    <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl transition shadow">Kontribusi Korporat</button>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-blue-950 text-blue-400 text-xs font-semibold rounded-full border border-blue-800/50">Pendidikan</span>
                    <h3 class="text-xl font-bold text-white mt-4 mb-2">Beasiswa IT & Tahfidz Profesional</h3>
                    <p class="text-slate-400 text-sm mb-4">Pendidikan khusus pemuda mahir teknologi berakhlak Qur'ani.</p>
                    <div class="w-full bg-slate-800 rounded-full h-2.5 mb-2">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 90%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-400 font-medium">
                        <span>Terkumpul: Rp 90.000.000</span>
                        <span>Target: Rp 100.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-slate-900/50 border-t border-slate-800">
                    <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl transition shadow">Kontribusi Korporat</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Transparansi Keuangan -->
    <section id="transparansi" class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-slate-900 rounded-2xl shadow-2xl p-8 border border-slate-800">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Dashboard Audit Keuangan</h2>
                    <p class="text-slate-400 text-sm mt-1">Laporan analitik keuangan real-time berbasis standar enterprise</p>
                </div>
                <div class="px-4 py-2 bg-blue-950 text-blue-400 rounded-xl font-semibold text-sm border border-blue-800">
                    Periode: Oktober 2026
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="p-6 bg-slate-800/50 rounded-xl border border-slate-700/50">
                    <div class="text-sm text-slate-400">Total Saldo Kas</div>
                    <div class="text-2xl font-bold text-blue-400 mt-2">Rp 340.500.000</div>
                </div>
                <div class="p-6 bg-slate-800/50 rounded-xl border border-slate-700/50">
                    <div class="text-sm text-slate-400">Pemasukan Bulan Ini</div>
                    <div class="text-2xl font-bold text-emerald-400 mt-2">+ Rp 78.400.000</div>
                </div>
                <div class="p-6 bg-slate-800/50 rounded-xl border border-slate-700/50">
                    <div class="text-sm text-slate-400">Pengeluaran Bulan Ini</div>
                    <div class="text-2xl font-bold text-rose-400 mt-2">- Rp 32.100.000</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 mt-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="text-white font-bold text-lg mb-4 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">⚡</div>
                    <span>Masjid Al-Innura</span>
                </div>
                <p class="text-sm text-slate-400">Platform manajemen masjid berstandar enterprise yang transparan dan andal.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#jadwal" class="hover:text-white transition">Jadwal Shalat</a></li>
                    <li><a href="#donasi" class="hover:text-white transition">Endowment</a></li>
                    <li><a href="#transparansi" class="hover:text-white transition">Audit Keuangan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                <p class="text-sm mb-2">Jl. Sudirman Business District No. 10, Metropolis</p>
                <p class="text-sm">Email: enterprise@masjidalinnura.com</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Layanan Korporat</h4>
                <p class="text-sm">Operasional dan sistem digital terpadu 24/7.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
            &copy; 2026 Masjid Al-Innura. Hak cipta dilindungi undang-undang.
        </div>
    </footer>
</body>
</html>