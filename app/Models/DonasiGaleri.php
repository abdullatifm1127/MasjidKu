<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiGaleri extends Model
{
    use HasFactory;

    protected $fillable = [
        'mosque_id',
        'judul',
        'kategori',
        'deskripsi',
        'tanggal',
        'nominal_terpakai',
        'foto',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'nominal_terpakai' => 'decimal:2',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}