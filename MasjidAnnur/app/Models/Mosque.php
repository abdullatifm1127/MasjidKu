<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str; // 1. Tambahkan import Str di sini

class Mosque extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mosque_name',
        'arabic_name',
        'tagline',
        'founded',
        'capacity',
        'address',
        'kelurahan',
        'kecamatan',
        'postal_code',
        'city',
        'province',
        'phone',
        'email',
        'website',
        'organization_name',
        'imam_name',
        'imam_phone',
        'chairman_name',
        'chairman_phone',
        'secretary_name',
        'treasurer_name',
        'facilities',
        'programs',
        'has_online_donation',
        'has_prayer_schedule',
        'description',
        'status',
        'slug', // 2. Tambahkan 'slug' ke dalam fillable

        // ===== Profil Masjid tambahan (foto & visi-misi) =====
        'about_photo',
        'about_vision',
        'about_photo_secondary',

        // ===== Landing Page: Hero / Banner =====
        'hero_title',
        'hero_subtitle',
        'hero_desc',
        'hero_image',
        'hero_bg_color',
        'hero_text_color',
        'btn_primary',
        'btn_primary_url',
        'btn_secondary',
        'btn_secondary_url',

        // ===== Landing Page: Kontak & Sosial (khusus tampilan publik) =====
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_maps',
        'social_ig',
        'social_fb',
        'social_yt',
        'social_wa',

        // ===== Landing Page: Modul & status publish =====
        'active_modules',
        'is_published',
    ];

    protected $casts = [
        'facilities' => 'array',
        'programs' => 'array',
        'has_online_donation' => 'boolean',
        'has_prayer_schedule' => 'boolean',
        'active_modules' => 'array',
        'is_published' => 'boolean',
    ];

    // 3. Tambahkan fungsi boot untuk otomatis membuat slug
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($mosque) {
            if ($mosque->mosque_name && $mosque->city) {
                $string = $mosque->mosque_name . '-' . $mosque->city;
                $mosque->slug = Str::slug($string);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}