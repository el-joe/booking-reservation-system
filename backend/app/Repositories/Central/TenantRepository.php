<?php

declare(strict_types=1);

namespace App\Repositories\Central;

use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TenantRepository
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = Tenant::with('subscription.plan');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['business_type'])) {
            $query->where('business_type', $filters['business_type']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findByDomain(string $domain): ?Tenant
    {
        return Tenant::whereHas('domains', function (Builder $q) use ($domain) {
            $q->where('domain', $domain);
        })->first();
    }

    public function withSubscription(): Builder
    {
        return Tenant::with('subscription.plan');
    }
}
