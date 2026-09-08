<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'mosque_id',
        'key',
        'title',
        'description',
        'calc_type',
        'icon_key',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Path SVG (isi <path>/<circle> dst) untuk tiap ikon.
     * Dipakai di admin (icon picker) & halaman publik (render ikon)
     * supaya tidak perlu simpan HTML mentah dari admin (aman dari XSS).
     */
    public static function iconLibrary(): array
    {
        return [
            'zakat' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'infaq' => '<path d="M12 3v18M5 8h14M5 16h14"/>',
            'sedekah' => '<path d="M12 21s-7-4.6-9.5-9C1 8 3 4 7 4c2 0 4 1.5 5 3 1-1.5 3-3 5-3 4 0 6 4 4.5 8-2.5 4.4-9.5 9-9.5 9Z"/>',
            'pembangunan' => '<path d="M4 21V10l8-6 8 6v11M9 21v-7h6v7"/>',
            'yatim' => '<circle cx="12" cy="8" r="3.2"/><path d="M5 21c0-4 3-6.5 7-6.5S19 17 19 21"/>',
            'bencana' => '<path d="M13 2 3 14h7l-1 8 11-14h-7l0-6Z"/>',
            'wakaf' => '<path d="M12 3v18M6 7h12M6 7c0 5-2 6-2 6h16s-2-1-2-6"/>',
            'qurban' => '<circle cx="12" cy="13" r="7"/><path d="M8 8 6 4M16 8l2-4"/>',
            'masjid' => '<path d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75"/>',
            'pendidikan' => '<path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M6 10v6c0 1.5 3 3 6 3s6-1.5 6-3v-6"/>',
            'kesehatan' => '<path d="M12 21s-7-4.6-9.5-9C1 8 3 4 7 4c2 0 4 1.5 5 3 1-1.5 3-3 5-3 4 0 6 4 4.5 8-2.5 4.4-9.5 9-9.5 9Z"/><path d="M9 12h6M12 9v6"/>',
            'lainnya' => '<path d="M12 5v14M5 12h14"/>',
        ];
    }

    public static function iconOptions(): array
    {
        // dipakai untuk dropdown/picker di form admin: key => label
        return [
            'zakat' => 'Jam pasir',
            'infaq' => 'Kotak infaq',
            'sedekah' => 'Hati',
            'pembangunan' => 'Bangunan',
            'yatim' => 'Orang & anak',
            'bencana' => 'Petir/darurat',
            'wakaf' => 'Sumur',
            'qurban' => 'Kepala hewan',
            'masjid' => 'Masjid',
            'pendidikan' => 'Topi wisuda',
            'kesehatan' => 'Hati + nadi',
            'lainnya' => 'Plus (umum)',
        ];
    }

    public function iconPath(): string
    {
        return self::iconLibrary()[$this->icon_key] ?? self::iconLibrary()['lainnya'];
    }
}