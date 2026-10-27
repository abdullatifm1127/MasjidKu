<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonasiGaleri extends Model
{
    use HasFactory;

    protected $fillable = [
        'mosque_id',
        'judul',
        'kategori',
        'deskripsi',
        'tujuan',
        'rincian',
        'tanggal',
        'nominal_terpakai',
        'foto',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'nominal_terpakai' => 'float',
        'rincian'          => 'array',
    ];

    /**
     * URL lengkap foto galeri, atau null jika penyaluran dicatat tanpa foto.
     */
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }
}