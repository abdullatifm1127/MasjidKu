<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mosque_id',
        'jenis',
        'zakat_subtype',
        'nominal',
        'nama_donatur',
        'metode_pembayaran',
        'no_referensi',
        'status',
    ];

    protected $casts = [
        'nominal' => 'integer',
    ];

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }
}