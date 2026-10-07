<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingType;
use App\Enums\ResourceStatus;
use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'resource_type',
        'booking_type',
        'capacity',
        'description',
        'base_price',
        'price_unit',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'resource_type' => ResourceType::class,
        'booking_type' => BookingType::class,
        'status' => ResourceStatus::class,
        'base_price' => 'decimal:2',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function blackoutDates(): HasMany
    {
        return $this->hasMany(BlackoutDate::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ResourceMedia::class)->orderBy('sort_order');
    }

    public function availability(): HasMany
    {
        return $this->hasMany(ResourceAvailability::class);
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class)->orderByDesc('priority');
    }

    public function addOns(): HasMany
    {
        return $this->hasMany(AddOn::class);
    }

    public function getCoverImageAttribute(): ?string
    {
        return $this->media()->where('is_cover', true)->value('file_path')
            ?? $this->media()->first()?->file_path;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format((float) $this->base_price, 2).' / '.str_replace('per_', '', $this->price_unit);
    }

    public function getRatingAverageAttribute(): float
    {
        return (float) $this->reviews()->published()->avg('rating') ?? 0.0;
    }
}
