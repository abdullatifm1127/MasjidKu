<?php

namespace App\Models\Concerns;

/**
 * @property string|null $package_type
 * @property \Illuminate\Support\Carbon|null $package_expires_at
 *
 * @method $this mergeCasts(array $casts)
 * @method $this forceFill(array $attributes)
 * @method bool save(array $options = [])
 */
trait HasSubscription
{
    public function initializeHasSubscription(): void
    {
        $this->mergeCasts(['package_expires_at' => 'datetime']);
    }

    public function isPaid(): bool
    {
        return $this->package_type !== 'free'
            && $this->package_expires_at !== null
            && $this->package_expires_at->isFuture();
    }

    public function daysLeft(): int
    {
        if (! $this->isPaid()) {
            return 0;
        }

        return (int) abs(now()->startOfDay()->diffInDays($this->package_expires_at->copy()->startOfDay()));
    }

    public function activateSubscription(string $type, string $period): void
    {
        $base = $this->isPaid() ? $this->package_expires_at->copy() : now();

        $expires = match ($period) {
            'monthly' => $base->addMonthNoOverflow(),
            'yearly'  => $base->addYearNoOverflow(),
            default   => throw new \InvalidArgumentException("Periode tidak dikenal: {$period}"),
        };

        $this->forceFill([
            'package_type'       => $type,
            'package_expires_at' => $expires,
        ])->save();
    }
}