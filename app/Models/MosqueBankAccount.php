<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MosqueBankAccount extends Model
{
    protected $fillable = [
        'mosque_id',
        'bank_name',
        'account_number',
        'account_holder',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}