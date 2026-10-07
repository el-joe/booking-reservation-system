<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Central\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private readonly PlanService $planService) {}

    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $subscriptions = Subscription::with(['plan'])->get();

            return response()->json(['data' => $subscriptions]);
        }

        $subscriptions = Subscription::with(['plan'])->latest()->paginate(25);

        return view('central.subscriptions.index', compact('subscriptions'));
    }

    public function update(Request $request, Subscription $subscription): RedirectResponse
    {
        $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $tenant = $subscription->tenant;

        $this->planService->applyToTenant($tenant, $plan);

        return redirect()->route('central.subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }
}
