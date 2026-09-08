<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasis';

    protected $fillable = [
        'mosque_id',
        'nama_donatur',
        'no_hp',
        'jumlah',
        'kategori',
        'keterangan',
        'metode',
        'status',
        'tanggal_donasi',
        'bukti_transfer',
    ];

    protected $casts = [
        'jumlah'         => 'decimal:2',
        'tanggal_donasi' => 'date',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    /** Total donasi lunas untuk satu masjid. */
    public static function totalLunas(int $mosqueId): float
    {
        return static::where('mosque_id', $mosqueId)
            ->where('status', 'lunas')
            ->sum('jumlah');
    }

    /** Total donasi bulan berjalan. */
    public static function totalBulanIni(int $mosqueId): float
    {
        return static::where('mosque_id', $mosqueId)
            ->where('status', 'lunas')
            ->whereMonth('tanggal_donasi', now()->month)
            ->whereYear('tanggal_donasi', now()->year)
            ->sum('jumlah');
    }
}
