<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_cycle',
        'max_bookings',
        'max_resources',
        'max_staff',
        'features',
        'is_active',
        'tagline',
        'description',
        'highlight_features',
        'is_featured',
        'sort_order',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'highlight_features' => 'array',
        'is_featured' => 'boolean',
    ];

    /** @return HasMany<Subscription, $this> */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /** @return HasMany<PlanFeature, $this> */
    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }
}
