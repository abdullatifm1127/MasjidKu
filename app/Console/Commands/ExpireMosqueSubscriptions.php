<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mosque;

class ExpireMosqueSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Nonaktifkan fitur donasi untuk masjid yang masa berlangganannya sudah habis';

    public function handle()
    {
        $expired = Mosque::where('package_type', 'paid')
            ->whereNotNull('subscription_expires_at')
            ->where('subscription_expires_at', '<=', now())
            ->get();

        foreach ($expired as $mosque) {
            $mosque->update([
                'has_online_donation' => false,
                'package_type'        => 'free',
                'payment_status'      => 'expired',
            ]);
        }

        $this->info($expired->count() . ' masjid dinonaktifkan otomatis.');
    }
}