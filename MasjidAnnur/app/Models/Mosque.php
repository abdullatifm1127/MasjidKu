<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected $casts = [
        'facilities' => 'array',
        'programs' => 'array',
        'has_online_donation' => 'boolean',
        'has_prayer_schedule' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}