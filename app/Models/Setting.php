<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'platform_name', 'support_email', 'whatsapp',
        'max_mosque_free', 'max_announcement', 'max_event',
        'notif_registration', 'notif_donation', 'maintenance_mode',
    ];

    protected $casts = [
        'notif_registration' => 'boolean',
        'notif_donation'     => 'boolean',
        'maintenance_mode'   => 'boolean',
    ];

    // Selalu ambil/buat 1 baris pengaturan
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}