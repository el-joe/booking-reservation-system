<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\ResourceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Resource\StoreResourceRequest;
use App\Models\Resource;
use App\Services\Tenant\Resource\ResourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function __construct(private readonly ResourceService $resourceService) {}

    public function index(ResourceDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.resources.index');
    }

    public function create(): View
    {
        return view('tenant.resources.create');
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $resource = $this->resourceService->create($request->validated());

        return redirect()
            ->route('tenant.resources.show', $resource)
            ->with('success', 'Resource created successfully.');
    }

    public function show(Resource $resource): View
    {
        $resource->load(['media', 'pricingRules', 'addOns', 'timeSlots']);

        return view('tenant.resources.show', compact('resource'));
    }

    public function edit(Resource $resource): View
    {
        $resource->load(['media', 'pricingRules', 'addOns', 'timeSlots', 'blackoutDates']);

        return view('tenant.resources.edit', compact('resource'));
    }

    public function update(StoreResourceRequest $request, Resource $resource): RedirectResponse
    {
        $this->resourceService->update($resource, $request->validated());

        return redirect()
            ->route('tenant.resources.edit', $resource)
            ->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        $resource->delete();

        return redirect()
            ->route('tenant.resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}
