<?php

declare(strict_types=1);

namespace App\Services\Central;

use App\Enums\TenantStatus;
use App\Jobs\ProvisionTenantDatabase;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Notifications\TenantWelcomeNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class TenantManagementService
{
    public function create(array $data): Tenant
    {
        $tenant = Tenant::create([
            'id' => Str::slug($data['name']).'-'.Str::random(6),
            'name' => $data['name'],
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'phone' => $data['phone'] ?? null,
            'business_type' => $data['business_type'],
            'status' => TenantStatus::Trial,
            'logo' => $data['logo'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        $tenant->domains()->create(['domain' => $data['domain']]);

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $data['plan_id'],
            'status' => 'trialing',
            'starts_at' => now(),
        ]);

        ProvisionTenantDatabase::dispatch($tenant)->onQueue('default');

        $tenant->notify(new TenantWelcomeNotification($tenant));

        return $tenant;
    }

    public function suspend(Tenant $tenant): void
    {
        $tenant->update(['status' => TenantStatus::Suspended]);
    }

    public function reactivate(Tenant $tenant): void
    {
        $tenant->update(['status' => TenantStatus::Active]);
    }

    public function delete(Tenant $tenant): void
    {
        $tenant->delete();
    }

    public function impersonate(Tenant $tenant): RedirectResponse
    {
        session(['impersonating_tenant' => $tenant->id]);

        return redirect()->away('http://'.$tenant->domains()->first()?->domain.'/dashboard');
    }
}
