<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    public function index(): View
    {
        $affiliates = Affiliate::withCount('conversions')->latest()->paginate(20);

        return view('tenant.marketing.affiliates.index', compact('affiliates'));
    }

    public function create(): View
    {
        return view('tenant.marketing.affiliates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:affiliates,email'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['tracking_code'] = strtoupper(Str::random(8));
        $data['total_earnings'] = 0;

        $affiliate = Affiliate::create($data);

        return redirect()->route('tenant.marketing.affiliates.show', $affiliate)
            ->with('success', "Affiliate \"{$affiliate->name}\" created successfully.");
    }

    public function show(Affiliate $affiliate): View
    {
        $affiliate->load(['conversions.booking']);

        return view('tenant.marketing.affiliates.show', compact('affiliate'));
    }

    public function edit(Affiliate $affiliate): View
    {
        return view('tenant.marketing.affiliates.edit', compact('affiliate'));
    }

    public function update(Request $request, Affiliate $affiliate): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:affiliates,email,{$affiliate->id}"],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $affiliate->update($data);

        return redirect()->route('tenant.marketing.affiliates.show', $affiliate)
            ->with('success', 'Affiliate updated successfully.');
    }

    public function destroy(Affiliate $affiliate): RedirectResponse
    {
        $affiliate->delete();

        return redirect()->route('tenant.marketing.affiliates.index')
            ->with('success', 'Affiliate deleted successfully.');
    }
}
