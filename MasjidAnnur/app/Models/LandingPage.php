<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory;

    // Tentukan kolom database yang boleh diisi (mass assignment)
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_desc',
        'hero_image',
    ];
}