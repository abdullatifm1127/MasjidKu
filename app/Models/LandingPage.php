<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory;

    // Tentukan kolom database yang boleh diisi (mass assignment)
    protected $fillable = [
        'mosque_id',
        'hero_title',
        'hero_subtitle',
        'hero_desc',
        'hero_image',
        'hero_background',
        'btn_primary',
        'btn_primary_url',
        'btn_secondary',
        'btn_secondary_url',
        'hero_bg_color',
        'hero_text_color',
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_maps',
        'social_ig',
        'social_fb',
        'social_yt',
        'social_wa',
        'active_modules',
        'is_published',
    ];

    protected $casts = [
        'active_modules' => 'array',
        'is_published'   => 'boolean',
    ];

    // Relasi ke Mosque (opsional tapi berguna)
    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}