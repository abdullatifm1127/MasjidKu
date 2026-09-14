<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $fillable = [
        'mosque_id',
        'title',
        'event_date',
        'event_time',
        'organizer',
        'description',
        'photo',
        'is_featured',
    ];

    protected $casts = [
        'event_date'  => 'date',
        'is_featured' => 'boolean',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Hanya acara yang tanggalnya hari ini atau ke depan.
     * Dipakai di halaman publik untuk section "Acara Mendatang".
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }
}