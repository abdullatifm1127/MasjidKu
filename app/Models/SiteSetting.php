<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function defaults(): array
    {
        return [
            'hero' => [
                'show_bismillah' => 1,
                'badge'          => '🌙 Platform Masjid Digital',
                'title_1'        => 'Kelola Masjid Anda',
                'title_2'        => 'dengan',
                'title_highlight'=> 'Lebih Mudah',
                'subtitle'       => 'Satu platform lengkap untuk mengelola keuangan, jadwal shalat, program dakwah, dan komunitas masjid Anda.',
                'btn_primary'    => 'Daftarkan Masjid',
                'btn_secondary'  => 'Pelajari Lebih Lanjut',
                'card_small'     => 'Baru bergabung',
                'card_title'     => 'Masjid Terverifikasi',
            ],
            'strip' => [
                ['icon' => '✔',  'text' => 'Setiap masjid diverifikasi tim kami'],
                ['icon' => '📊', 'text' => 'Laporan kas terbuka untuk jamaah'],
                ['icon' => '📱', 'text' => 'Bisa diakses dari ponsel'],
                ['icon' => '🇮🇩', 'text' => 'Dibuat untuk masjid di Indonesia'],
            ],
            'features_head' => ['badge' => 'Fitur Unggulan', 'title' => 'Semua yang Anda Butuhkan dalam Satu Platform', 'desc' => 'Dirancang khusus untuk kebutuhan masjid modern di Indonesia.'],
            'features' => [
                ['icon' => '💰', 'title' => 'Manajemen Keuangan', 'desc' => 'Catat pemasukan, pengeluaran, dan laporan keuangan masjid secara transparan dan mudah dipahami.'],
                ['icon' => '📅', 'title' => 'Jadwal & Agenda', 'desc' => 'Kelola jadwal imam, khatib, kajian, dan acara masjid dalam satu kalender terintegrasi.'],
                ['icon' => '📢', 'title' => 'Pengumuman Digital', 'desc' => 'Kirim informasi dan pengumuman kepada jamaah melalui notifikasi dan papan pengumuman digital.'],
                ['icon' => '🤲', 'title' => 'Pengelolaan Donasi', 'desc' => 'Terima donasi online dan offline, kelola zakat, infaq, dan sedekah dengan laporan yang transparan.'],
                ['icon' => '📖', 'title' => 'Program Dakwah', 'desc' => 'Daftarkan dan pantau program TPA, tahfidz, majelis taklim, dan kegiatan dakwah lainnya.'],
                ['icon' => '👥', 'title' => 'Data Jamaah', 'desc' => 'Kelola data anggota jamaah, pantau kehadiran, dan bangun komunitas masjid yang solid.'],
            ],
            'steps_head' => ['badge' => 'Cara Kerja', 'title' => 'Mulai dalam Tiga Langkah', 'desc' => 'Dari membuat akun sampai masjid Anda tampil di MasjidKu.'],
            'steps' => [
                ['title' => 'Buat akun', 'desc' => 'Daftar dengan akun pribadi Anda sebagai pengurus atau takmir masjid.'],
                ['title' => 'Daftarkan masjid', 'desc' => 'Isi data masjid: nama, alamat, kontak, dan foto agar mudah ditemukan jamaah.'],
                ['title' => 'Tunggu verifikasi', 'desc' => 'Setelah data kami periksa dan disetujui, dashboard masjid langsung terbuka.'],
            ],
            'program_head' => ['badge' => 'Program', 'title' => 'Program Unggulan Masjid', 'desc' => 'Berbagai program untuk membangun jamaah yang berkualitas.'],
            'masjid_head'  => ['badge' => 'Masjid Terdaftar', 'title' => 'Masjid yang Sudah Bergabung', 'desc' => 'Daftar masjid yang telah terverifikasi dan menggunakan platform MasjidKu.'],
            'donasi_head'  => ['badge' => 'Donasi', 'title' => 'Berbagi Kebaikan Lewat Masjid', 'desc' => 'Pilih masjid terverifikasi, lalu salurkan zakat, infaq, sedekah, dan wakaf Anda.'],
            'donasi' => [
                ['icon' => '💎', 'title' => 'Zakat', 'desc' => 'Tunaikan kewajiban zakat mal dan fitrah melalui masjid.'],
                ['icon' => '🤲', 'title' => 'Infaq', 'desc' => 'Dukung operasional dan kegiatan masjid sehari-hari.'],
                ['icon' => '🌱', 'title' => 'Sedekah', 'desc' => 'Berbagi untuk jamaah dan warga yang membutuhkan.'],
                ['icon' => '🏗️', 'title' => 'Wakaf', 'desc' => 'Bantu pembangunan dan perawatan sarana masjid.'],
            ],
            'ayat' => [
                'arab'   => 'مَنْ ذَا الَّذِيْ يُقْرِضُ اللّٰهَ قَرْضًا حَسَنًا فَيُضٰعِفَهٗ لَهٗٓ اَضْعَافًا كَثِيْرَةً',
                'arti'   => 'Siapakah yang mau memberi pinjaman kepada Allah, pinjaman yang baik, maka Allah melipatgandakannya.',
                'sumber' => 'QS. Al-Baqarah: 245',
            ],
            'artikel_head' => ['badge' => 'Artikel', 'title' => 'Menambah Ilmu, Mencerahkan Iman', 'desc' => 'Selamat datang di ruang literasi masjid untuk menambah ilmu dan mencerahkan iman.'],
            'faq_head' => ['badge' => 'FAQ', 'title' => 'Pertanyaan yang Sering Diajukan'],
            'faq' => [
                ['q' => 'Bagaimana cara mendaftarkan masjid?', 'a' => 'Buat akun lebih dulu, lalu pilih "Daftarkan Masjid" dan isi data masjid Anda. Setelah dikirim, data akan diperiksa oleh tim MasjidKu.'],
                ['q' => 'Apa arti status "Menunggu Verifikasi"?', 'a' => 'Data masjid Anda sudah kami terima dan sedang diperiksa. Statusnya bisa dipantau dari tombol di pojok kanan atas setelah Anda masuk.'],
                ['q' => 'Apa yang bisa dilakukan setelah masjid disetujui?', 'a' => 'Tombol berubah menjadi "Dashboard Masjid". Dari sana Anda bisa mengelola keuangan, jadwal, pengumuman, donasi, program, dan data jamaah.'],
                ['q' => 'Bagaimana jamaah menemukan masjid saya?', 'a' => 'Masjid yang sudah terverifikasi tampil pada daftar di halaman ini dan bisa dicari berdasarkan nama, kelurahan, atau kota.'],
                ['q' => 'Dari mana jadwal shalat berasal?', 'a' => 'Jadwal dihitung berdasarkan kota yang dipilih dengan metode Kementerian Agama RI, lewat layanan Aladhan.'],
                ['q' => 'Bagaimana cara menyalurkan donasi?', 'a' => 'Pilih masjid terverifikasi pada daftar, buka profilnya, lalu ikuti petunjuk donasi yang disediakan pengurus masjid tersebut.'],
            ],
            'kontak' => [
                'title'  => 'Hubungi Kami',
                'desc'   => 'Ada pertanyaan seputar pendaftaran atau penggunaan MasjidKu?',
                'email'  => '',
                'alamat' => 'Baitul Digital, Indonesia',
                'jam'    => 'Senin sampai Jumat, 08.00 sampai 17.00 WIB',
            ],
            'cta' => [
                'title' => 'Siap Merapikan Pengelolaan Masjid Anda?',
                'desc'  => 'Daftarkan masjid Anda dan mulai kelola keuangan, jadwal, dan jamaah dari satu tempat.',
                'btn_primary'   => 'Daftarkan Masjid',
                'btn_secondary' => 'Baca FAQ',
            ],
            'footer' => [
                'tagline'   => 'Platform digital untuk kemakmuran masjid Indonesia.',
                'instagram' => '#',
                'facebook'  => '#',
                'youtube'   => '#',
                'copyright' => 'Baitul Digital. Semua hak dilindungi.',
            ],
        ];
    }

    public static function content(): array
    {
        $defaults = static::defaults();
        try {
            if (!Schema::hasTable('site_settings')) return $defaults;

            $saved = Cache::remember('site_content', 3600, fn () =>
                static::pluck('value', 'key')->map(fn ($v) => json_decode($v, true))->filter()->all()
            );
        } catch (\Throwable $e) {
            return $defaults;
        }

        foreach ($saved as $group => $value) {
            if (isset($defaults[$group]) && is_array($value)) {
                $defaults[$group] = array_replace_recursive($defaults[$group], $value);
            }
        }
        return $defaults;
    }

    public static function saveGroup(string $group, array $value): void
    {
        static::updateOrCreate(['key' => $group], ['value' => json_encode($value, JSON_UNESCAPED_UNICODE)]);
    }

    public static function flush(): void
    {
        Cache::forget('site_content');
    }
}