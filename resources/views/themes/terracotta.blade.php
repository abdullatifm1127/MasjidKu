<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masjid Al-Innura - Tema Terracotta (Klasik Nusantara)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-custom { font-family: 'Merriweather', serif; }
    </style>
</head>
<body class="bg-[#FEFCE8] text-stone-800">
    <!-- Navbar / Theme Switcher Bar -->
    <header class="bg-[#FFFBEB] border-b border-amber-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-[#C2410C] rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">🕌</div>
                <span class="font-bold text-lg font-serif-custom text-stone-900">Masjid Al-Innura</span>
            </div>
            <nav class="hidden md:flex space-x-6 text-sm font-medium">
                <a href="#jadwal" class="hover:text-[#C2410C] transition">Jadwal Shalat</a>
                <a href="#program" class="hover:text-[#C2410C] transition">Program</a>
                <a href="#donasi" class="hover:text-[#C2410C] transition">Infaq</a>
                <a href="#kegiatan" class="hover:text-[#C2410C] transition">Majelis</a>
                <a href="#transparansi" class="hover:text-[#C2410C] transition">Keuangan</a>
            </nav>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-stone-500 hidden sm:inline">Pilih Tema:</span>
                <a href="{{ url('/theme/emerald') }}" class="px-3 py-1 text-xs font-semibold bg-stone-100 hover:bg-stone-200 rounded-lg text-stone-700">Emerald</a>
                <a href="{{ url('/theme/terracotta') }}" class="px-3 py-1 text-xs font-semibold bg-[#C2410C] text-white rounded-lg shadow">Terracotta</a>
                <a href="{{ url('/theme/corporate') }}" class="px-3 py-1 text-xs font-semibold bg-stone-100 hover:bg-stone-200 rounded-lg text-stone-700">Corporate</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-[#9A3412] via-[#C2410C] to-[#D97706] text-amber-50 py-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <span class="px-3 py-1 bg-amber-900/60 text-amber-200 rounded-full text-xs font-semibold uppercase tracking-wider border border-amber-500/30">Nuansa Heritage Nusantara</span>
            <h1 class="text-4xl md:text-6xl font-bold font-serif-custom mt-4 mb-6">Merajut Ukhuwah, Memakmurkan Masjid</h1>
            <p class="max-w-2xl mx-auto text-amber-100 text-lg mb-8">Pusat syiar Islam yang hangat, terbuka, dan memberdayakan umat berlandaskan semangat gotong royong.</p>
            <div class="flex justify-center space-x-4">
                <a href="#donasi" class="bg-amber-100 text-[#9A3412] font-semibold px-6 py-3 rounded-xl shadow-lg hover:bg-white transition">Salurkan Sedekah</a>
                <a href="#jadwal" class="border border-amber-300/60 text-amber-50 font-semibold px-6 py-3 rounded-xl hover:bg-amber-900/40 transition">Jadwal Shalat</a>
            </div>
        </div>
    </section>

    <!-- Jadwal Shalat & Widget -->
    <section id="jadwal" class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">
        <div class="bg-white rounded-2xl shadow-xl p-6 grid grid-cols-2 md:grid-cols-6 gap-4 text-center border border-amber-100">
            <div class="p-3 bg-amber-50/50 rounded-xl">
                <div class="text-xs text-stone-500 font-medium">Subuh</div>
                <div class="text-lg font-bold text-[#C2410C] mt-1">04:32</div>
            </div>
            <div class="p-3 bg-amber-50/50 rounded-xl">
                <div class="text-xs text-stone-500 font-medium">Dzuhur</div>
                <div class="text-lg font-bold text-[#C2410C] mt-1">11:50</div>
            </div>
            <div class="p-3 bg-amber-50/50 rounded-xl">
                <div class="text-xs text-stone-500 font-medium">Ashar</div>
                <div class="text-lg font-bold text-[#C2410C] mt-1">15:12</div>
            </div>
            <div class="p-3 bg-amber-50/50 rounded-xl">
                <div class="text-xs text-stone-500 font-medium">Maghrib</div>
                <div class="text-lg font-bold text-[#C2410C] mt-1">17:55</div>
            </div>
            <div class="p-3 bg-amber-50/50 rounded-xl">
                <div class="text-xs text-stone-500 font-medium">Isya</div>
                <div class="text-lg font-bold text-[#C2410C] mt-1">19:05</div>
            </div>
            <div class="p-3 bg-amber-100/80 border border-amber-300 rounded-xl col-span-2 md:col-span-1 shadow-inner">
                <div class="text-xs text-amber-800 font-semibold">Menuju Isya</div>
                <div class="text-lg font-bold text-[#9A3412] mt-1">01:15:20</div>
            </div>
        </div>
    </section>

    <!-- Pengumuman / Berita -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-amber-100/60 border border-amber-300/60 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#C2410C] text-white rounded-xl flex items-center justify-center font-bold text-xl shrink-0">📜</div>
                <div>
                    <h3 class="font-bold font-serif-custom text-[#9A3412] text-lg">Majelis Taklim & Pengajian Kitab Kuning</h3>
                    <p class="text-stone-700 text-sm">Kajian rutin bersama para alim ulama setiap malam Jumat setelah Shalat Isya berjamaah.</p>
                </div>
            </div>
            <a href="#kegiatan" class="px-5 py-2.5 bg-[#C2410C] text-white font-medium text-sm rounded-xl hover:bg-[#9A3412] transition shadow">Selengkapnya</a>
        </div>
    </section>

    <!-- Program & Donasi Unggulan -->
    <section id="donasi" class="max-w-7xl mx-auto px-4 py-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold font-serif-custom text-stone-900">Program & Infaq Umat</h2>
            <p class="text-stone-600 mt-2">Salurkan kebaikan Anda untuk kemakmuran rumah Allah dan kemaslahatan sesama.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl shadow-lg border border-amber-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-amber-100 text-[#9A3412] text-xs font-semibold rounded-full">Pembangunan</span>
                    <h3 class="text-xl font-bold font-serif-custom text-stone-900 mt-4 mb-2">Perluasan Serambi & Tempat Wudhu</h3>
                    <p class="text-stone-600 text-sm mb-4">Menambah kenyamanan jamaah saat shalat Jumat dan kegiatan hari besar Islam.</p>
                    <div class="w-full bg-stone-100 rounded-full h-2.5 mb-2">
                        <div class="bg-[#C2410C] h-2.5 rounded-full" style="width: 65%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-stone-500 font-medium">
                        <span>Terkumpul: Rp 65.000.000</span>
                        <span>Target: Rp 100.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-amber-50/30 border-t border-amber-100">
                    <button class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white font-semibold py-3 rounded-xl transition shadow">Infaq Sekarang</button>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl shadow-lg border border-amber-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-amber-100 text-[#9A3412] text-xs font-semibold rounded-full">Pendidikan</span>
                    <h3 class="text-xl font-bold font-serif-custom text-stone-900 mt-4 mb-2">Beasiswa Santri TPQ & Madrasah</h3>
                    <p class="text-stone-600 text-sm mb-4">Bantuan biaya pendidikan bagi santri dhuafa penghafal Al-Qur'an.</p>
                    <div class="w-full bg-stone-100 rounded-full h-2.5 mb-2">
                        <div class="bg-[#C2410C] h-2.5 rounded-full" style="width: 80%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-stone-500 font-medium">
                        <span>Terkumpul: Rp 40.000.000</span>
                        <span>Target: Rp 50.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-amber-50/30 border-t border-amber-100">
                    <button class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white font-semibold py-3 rounded-xl transition shadow">Infaq Sekarang</button>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-2xl shadow-lg border border-amber-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <span class="px-3 py-1 bg-amber-100 text-[#9A3412] text-xs font-semibold rounded-full">Sosial</span>
                    <h3 class="text-xl font-bold font-serif-custom text-stone-900 mt-4 mb-2">Jumat Berkah & Paket Sembako</h3>
                    <p class="text-stone-600 text-sm mb-4">Distribusi nasi kotak dan sembako gratis untuk fakir miskin dan musafir.</p>
                    <div class="w-full bg-stone-100 rounded-full h-2.5 mb-2">
                        <div class="bg-[#C2410C] h-2.5 rounded-full" style="width: 90%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-stone-500 font-medium">
                        <span>Terkumpul: Rp 27.000.000</span>
                        <span>Target: Rp 30.000.000</span>
                    </div>
                </div>
                <div class="p-6 bg-amber-50/30 border-t border-amber-100">
                    <button class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white font-semibold py-3 rounded-xl transition shadow">Infaq Sekarang</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Transparansi Keuangan -->
    <section id="transparansi" class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-amber-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-serif-custom text-stone-900">Transparansi Keuangan Masjid</h2>
                    <p class="text-stone-600 text-sm mt-1">Laporan amanah umat yang terbuka, jujur, dan akuntabel</p>
                </div>
                <div class="px-4 py-2 bg-amber-50 text-[#9A3412] rounded-xl font-semibold text-sm border border-amber-200">
                    Periode: Oktober 2026
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="p-6 bg-amber-50/40 rounded-xl border border-amber-200">
                    <div class="text-sm text-stone-500">Total Saldo Kas</div>
                    <div class="text-2xl font-bold font-serif-custom text-[#9A3412] mt-2">Rp 118.200.000</div>
                </div>
                <div class="p-6 bg-amber-50/40 rounded-xl border border-amber-200">
                    <div class="text-sm text-stone-500">Pemasukan Bulan Ini</div>
                    <div class="text-2xl font-bold font-serif-custom text-emerald-700 mt-2">+ Rp 24.500.000</div>
                </div>
                <div class="p-6 bg-amber-50/40 rounded-xl border border-amber-200">
                    <div class="text-sm text-stone-500">Pengeluaran Bulan Ini</div>
                    <div class="text-2xl font-bold font-serif-custom text-rose-700 mt-2">- Rp 10.300.000</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-stone-900 text-stone-400 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="text-white font-bold font-serif-custom text-lg mb-4 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-[#C2410C] rounded-lg flex items-center justify-center text-white font-bold">🕌</div>
                    <span>Masjid Al-Innura</span>
                </div>
                <p class="text-sm text-stone-400">Pusat peradaban umat dengan nuansa kehangatan Islam nusantara.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#jadwal" class="hover:text-white transition">Jadwal Shalat</a></li>
                    <li><a href="#donasi" class="hover:text-white transition">Program Infaq</a></li>
                    <li><a href="#transparansi" class="hover:text-white transition">Laporan Kas</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                <p class="text-sm mb-2">Jl. Nusantara Indah No. 88, Kota Budaya</p>
                <p class="text-sm">Email: info@masjidalinnura.com</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Jam Layanan</h4>
                <p class="text-sm">Terbuka senantiasa untuk ibadah, kajian, dan musyawarah umat.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 pt-8 border-t border-stone-800 text-center text-xs text-stone-500">
            &copy; 2026 Masjid Al-Innura. Hak cipta dilindungi undang-undang.
        </div>
    </footer>
</body>
</html>