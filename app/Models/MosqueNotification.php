<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MosqueNotification extends Model
{
    protected $fillable = [
        'batch_id',
        'mosque_id',
        'title',
        'message',
        'type',
        'url',
        'sent_by',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}