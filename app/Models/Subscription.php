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
        'order_id',
        'package_type',
        'amount',
        'payment_proof',      // legacy (transfer manual), tidak dipakai lagi
        'status',             // paid | pending | rejected (disederhanakan dari status Midtrans)
        'transaction_status', // status mentah Midtrans: settlement, pending, expire, dst.
        'payment_type',
        'transaction_id',
        'fraud_status',
        'paid_at',
        'expired_date',
        'raw_notification',
    ];

    protected $casts = [
        'expired_date'     => 'datetime',
        'paid_at'          => 'datetime',
        'raw_notification' => 'array',
    ];

    // Relasi ke Mosque
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }
}