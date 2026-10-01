<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EidPrayer extends Model
{
    protected $fillable = [
        'mosque_id', 'title', 'event_date', 'prayer_time',
        'location', 'imam_name', 'khatib_name', 'notes',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}