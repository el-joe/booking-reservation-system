<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        return view('central.plans.index');
    }

    public function create(): View
    {
        return view('central.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('central.plans.index');
    }

    public function show(string $plan): View
    {
        return view('central.plans.show', compact('plan'));
    }

    public function edit(string $plan): View
    {
        return view('central.plans.edit', compact('plan'));
    }

    public function update(Request $request, string $plan): RedirectResponse
    {
        return redirect()->route('central.plans.index');
    }

    public function destroy(string $plan): RedirectResponse
    {
        return redirect()->route('central.plans.index');
    }
}
