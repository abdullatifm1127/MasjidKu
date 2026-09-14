<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    protected $table = 'jamaahs';

    protected $fillable = [
        'mosque_id',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'no_hp',
        'email',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'status',
        'peran',
        'tanggal_bergabung',
        'foto',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir'     => 'date',
        'tanggal_bergabung' => 'date',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    /** Hitung umur dari tanggal_lahir. */
    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->age
            : null;
    }

    /** Inisial 2 huruf untuk avatar. */
    public function getInisialAttribute(): string
    {
        $parts = explode(' ', trim($this->nama));
        $init  = strtoupper(substr($parts[0], 0, 1));
        if (isset($parts[1])) {
            $init .= strtoupper(substr($parts[1], 0, 1));
        }
        return $init;
    }
}