<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Mosque extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mosque_name',
        'arabic_name',
        'tagline',
        'founded',
        'capacity',
        'address',
        'kelurahan',
        'kecamatan',
        'postal_code',
        'city',
        'province',
        'phone',
        'email',
        'website',
        'organization_name',
        'imam_name',
        'imam_phone',
        'chairman_name',
        'chairman_phone',
        'secretary_name',
        'treasurer_name',
        'facilities',
        'programs',
        'has_online_donation',
        'has_prayer_schedule',
        'description',
        'status',
        'package_type',
        'slug',
        'payment_proof',
        'payment_status',
        'about_photo',
        'about_vision',
        'about_photo_secondary',
        'subscription_expires_at', // <-- DITAMBAHKAN
    ];

    protected $casts = [
        'facilities' => 'array',
        'programs' => 'array',
        'has_online_donation' => 'boolean',
        'has_prayer_schedule' => 'boolean',
        'subscription_expires_at' => 'datetime', // <-- DITAMBAHKAN
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($mosque) {
            if ($mosque->mosque_name && $mosque->city) {
                $string = $mosque->mosque_name . '-' . $mosque->city;
                $mosque->slug = Str::slug($string);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function landingPage(): HasOne
    {
        return $this->hasOne(LandingPage::class, 'mosque_id');
    }

    public function acaras(): HasMany
    {
        return $this->hasMany(Acara::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Cek apakah masa berlangganan sudah lewat, kalau iya matikan otomatis.
     * Dipanggil di dashboard() dan halaman donasi publik untuk pengecekan real-time,
     * juga dipanggil oleh command harian (subscriptions:expire) sebagai jaring pengaman.
     */
    public function checkAndExpireSubscription(): void
    {
        if ($this->package_type === 'paid'
            && $this->subscription_expires_at
            && $this->subscription_expires_at->isPast()) {
            $this->update([
                'has_online_donation' => false,
                'package_type'        => 'free',
                'payment_status'      => 'expired',
            ]);
        }
    }
}