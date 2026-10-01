<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerImam extends Model
{
    protected $fillable = ['mosque_id', 'prayer', 'imam_name'];
}