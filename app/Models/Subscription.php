<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'mosque_id',
        'amount',
        'payment_proof',
        'status',
        'expired_date',
    ];

    protected $casts = [
        'expired_date' => 'datetime',
    ];

    // Relasi ke Mosque
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }
}