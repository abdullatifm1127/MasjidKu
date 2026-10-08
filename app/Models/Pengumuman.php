<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'mosque_id', 'title', 'content', 'category',
        'image_path', 'is_pinned', 'status', 'expires_at',
    ];

    protected $casts = [
        'is_pinned'  => 'boolean',
        'expires_at' => 'date',
    ];

    public const CATEGORIES = [
        'umum'     => 'Umum',
        'kajian'   => 'Kajian',
        'kegiatan' => 'Kegiatan',
        'donasi'   => 'Donasi',
        'duka'     => 'Duka cita',
    ];

    /** Terbit dan belum lewat tanggal berakhir. Dipakai untuk badge sidebar & landing page. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'terbit')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhereDate('expires_at', '>=', now());
            });
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->lt(now()->startOfDay());
    }
}