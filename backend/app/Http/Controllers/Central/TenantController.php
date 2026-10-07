<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\DataTables\Central\TenantDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Central\StoreTenantRequest;
use App\Http\Requests\Central\UpdateTenantRequest;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\Central\TenantManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(
        private readonly TenantManagementService $tenantService
    ) {}

    public function index(TenantDataTable $dataTable): mixed
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('central.tenants.index');
    }

    public function create(): View
    {
        $plans = Plan::where('is_active', true)->get();

        return view('central.tenants.create', compact('plans'));
    }

    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $tenant = $this->tenantService->create($request->validated());

        return redirect()->route('central.tenants.show', $tenant)
            ->with('success', "Tenant \"{$tenant->name}\" created successfully.");
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load('subscription.plan');

        $stats = [
            'bookings_count' => 0,
        ];

        return view('central.tenants.show', compact('tenant', 'stats'));
    }

    public function edit(Tenant $tenant): View
    {
        $plans = Plan::where('is_active', true)->get();
        $tenant->load('subscription');

        return view('central.tenants.edit', compact('tenant', 'plans'));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validated();

        $tenant->update([
            'name' => $data['name'],
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'phone' => $data['phone'] ?? null,
            'business_type' => $data['business_type'],
            'logo' => $data['logo'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        if (isset($data['plan_id']) && $tenant->subscription) {
            $tenant->subscription->update(['plan_id' => $data['plan_id']]);
        }

        return redirect()->route('central.tenants.show', $tenant)
            ->with('success', "Tenant \"{$tenant->name}\" updated successfully.");
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $this->tenantService->delete($tenant);

        return redirect()->route('central.tenants.index')
            ->with('success', "Tenant \"{$tenant->name}\" deleted successfully.");
    }

    public function suspend(Tenant $tenant): RedirectResponse
    {
        $this->tenantService->suspend($tenant);

        return back()->with('success', "Tenant \"{$tenant->name}\" has been suspended.");
    }

    public function reactivate(Tenant $tenant): RedirectResponse
    {
        $this->tenantService->reactivate($tenant);

        return back()->with('success', "Tenant \"{$tenant->name}\" has been reactivated.");
    }

    public function impersonate(Tenant $tenant): RedirectResponse
    {
        return $this->tenantService->impersonate($tenant);
    }
}
