<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OtaChannel extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status',
        'api_key',
        'api_secret',
        'channel_property_id',
        'last_sync_at',
        'sync_enabled',
        'meta',
    ];

    protected $casts = [
        'sync_enabled' => 'boolean',
        'last_sync_at' => 'datetime',
        'meta' => 'array',
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',
    ];

    public function ratePlans(): HasMany
    {
        return $this->hasMany(ChannelRatePlan::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ChannelReservation::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'booking_com' => 'Booking.com',
            'airbnb' => 'Airbnb',
            'expedia' => 'Expedia',
            'agoda' => 'Agoda',
            default => ucfirst($this->type),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active' => 'bg-green-100 text-green-700',
            'inactive' => 'bg-gray-100 text-gray-600',
            default => 'bg-yellow-100 text-yellow-700',
        };
    }
}
