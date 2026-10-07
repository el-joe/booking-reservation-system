<?php

namespace App\Services\Central;

use App\Enums\PlanFeature as PlanFeatureEnum;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Models\Tenant;

class PlanService
{
    public function create(array $data): Plan
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        return Plan::create($data);
    }

    public function update(Plan $plan, array $data): Plan
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $plan->update($data);

        return $plan->fresh();
    }

    public function toggleStatus(Plan $plan): Plan
    {
        $plan->update(['is_active' => ! $plan->is_active]);

        return $plan->fresh();
    }

    public function applyToTenant(Tenant $tenant, Plan $plan): Subscription
    {
        // Cancel any existing active subscription
        Subscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trial'])
            ->update(['status' => 'cancelled']);

        return Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $plan->billing_cycle === 'yearly'
                ? now()->addYear()
                : now()->addMonth(),
        ]);
    }

    public function hasFeature(Tenant $tenant, PlanFeatureEnum $feature): bool
    {
        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trial'])
            ->with('plan')
            ->latest()
            ->first();

        if (! $subscription || ! $subscription->plan) {
            return false;
        }

        $features = $subscription->plan->features ?? [];

        return ! empty($features[$feature->value]);
    }
}
