<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Marketing;

use App\Http\Controllers\Controller;
use App\Models\ReferralProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferralProgramController extends Controller
{
    public function index(): View
    {
        $programs = ReferralProgram::withCount('referrals')->latest()->paginate(20);

        return view('tenant.marketing.referrals.index', compact('programs'));
    }

    public function create(): View
    {
        return view('tenant.marketing.referrals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'reward_type' => ['required', 'in:fixed,percent,points'],
            'reward_value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $program = ReferralProgram::create($data);

        return redirect()->route('tenant.marketing.referrals.index')
            ->with('success', "Referral program \"{$program->name}\" created successfully.");
    }

    public function show(ReferralProgram $referral): View
    {
        $referral->load(['referrals.referrer', 'referrals.referred']);

        return view('tenant.marketing.referrals.show', compact('referral'));
    }

    public function edit(ReferralProgram $referral): View
    {
        return view('tenant.marketing.referrals.edit', compact('referral'));
    }

    public function update(Request $request, ReferralProgram $referral): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'reward_type' => ['required', 'in:fixed,percent,points'],
            'reward_value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $referral->update($data);

        return redirect()->route('tenant.marketing.referrals.index')
            ->with('success', 'Referral program updated successfully.');
    }

    public function destroy(ReferralProgram $referral): RedirectResponse
    {
        $referral->delete();

        return redirect()->route('tenant.marketing.referrals.index')
            ->with('success', 'Referral program deleted successfully.');
    }
}
