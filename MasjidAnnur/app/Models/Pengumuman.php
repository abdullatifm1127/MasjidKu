<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumumans';

    protected $fillable = [
        'mosque_id',
        'judul',
        'isi',
        'kategori',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_penting',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_penting'      => 'boolean',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    /** Hanya pengumuman yang masih aktif dan belum kadaluarsa. */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now()->toDateString());
            });
    }
}
