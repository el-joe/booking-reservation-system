<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingType;
use App\Enums\TenantStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'contact_name',
            'contact_email',
            'phone',
            'business_type',
            'status',
            'logo',
            'notes',
        ];
    }

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'contact_name',
        'contact_email',
        'phone',
        'business_type',
        'status',
        'logo',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'business_type' => BookingType::class,
            'status' => TenantStatus::class,
        ];
    }

    /** @return HasOne<Subscription, $this> */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'tenant_id');
    }

    /** @return HasMany<Subscription, $this> */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tenant_id');
    }

    public function isActive(): bool
    {
        return $this->status === TenantStatus::Active;
    }

    public function isSuspended(): bool
    {
        return $this->status === TenantStatus::Suspended;
    }

    public function isTrial(): bool
    {
        return $this->status === TenantStatus::Trial;
    }
}
