<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Enums\PlanFeature;
use App\Http\Controllers\Controller;
use App\Http\Requests\Central\StorePlanRequest;
use App\Models\Plan;
use App\Services\Central\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function __construct(private readonly PlanService $planService) {}

    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $plans = Plan::withCount('subscriptions')->get();

            return response()->json(['data' => $plans]);
        }

        $plans = Plan::withCount('subscriptions')->get();

        return view('central.plans.index', compact('plans'));
    }

    public function create(): View
    {
        $features = PlanFeature::cases();

        return view('central.plans.create', compact('features'));
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        $this->planService->create($request->validated());

        return redirect()->route('central.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function show(Plan $plan): View
    {
        $plan->loadCount('subscriptions');

        return view('central.plans.show', compact('plan'));
    }

    public function edit(Plan $plan): View
    {
        $features = PlanFeature::cases();

        return view('central.plans.edit', compact('plan', 'features'));
    }

    public function update(StorePlanRequest $request, Plan $plan): RedirectResponse
    {
        $this->planService->update($plan, $request->validated());

        return redirect()->route('central.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('central.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
