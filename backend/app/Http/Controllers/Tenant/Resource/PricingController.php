<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Resource;

use App\Http\Controllers\Controller;
use App\Models\PricingRule;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(Resource $resource): View
    {
        $pricingRules = $resource->pricingRules()->get();

        return view('tenant.resources.pricing.index', compact('resource', 'pricingRules'));
    }

    public function store(Resource $resource, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rule_type' => ['required', 'in:weekday,weekend,seasonal,dynamic'],
            'applies_from' => ['nullable', 'date'],
            'applies_to' => ['nullable', 'date', 'after_or_equal:applies_from'],
            'modifier_type' => ['required', 'in:fixed,percent'],
            'modifier_value' => ['required', 'numeric'],
            'priority' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $resource->pricingRules()->create($validated);

        return back()->with('success', 'Pricing rule created successfully.');
    }

    public function update(PricingRule $rule, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rule_type' => ['required', 'in:weekday,weekend,seasonal,dynamic'],
            'applies_from' => ['nullable', 'date'],
            'applies_to' => ['nullable', 'date', 'after_or_equal:applies_from'],
            'modifier_type' => ['required', 'in:fixed,percent'],
            'modifier_value' => ['required', 'numeric'],
            'priority' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $rule->update($validated);

        return back()->with('success', 'Pricing rule updated successfully.');
    }

    public function destroy(PricingRule $rule): RedirectResponse
    {
        $rule->delete();

        return back()->with('success', 'Pricing rule deleted successfully.');
    }
}
