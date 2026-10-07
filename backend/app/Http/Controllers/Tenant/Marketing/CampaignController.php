<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\Tenant\Marketing\CampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(private readonly CampaignService $campaignService) {}

    public function index(): View
    {
        $campaigns = Campaign::with('createdBy')
            ->latest()
            ->paginate(20);

        return view('tenant.marketing.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('tenant.marketing.campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:email,sms,push'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'body_text' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'audience_filter' => ['nullable', 'array'],
            'audience_filter.tags' => ['nullable', 'array'],
            'audience_filter.last_booking_from' => ['nullable', 'date'],
            'audience_filter.last_booking_to' => ['nullable', 'date'],
            'audience_filter.min_spent' => ['nullable', 'numeric', 'min:0'],
            'audience_filter.max_spent' => ['nullable', 'numeric', 'min:0'],
        ]);

        $campaign = $this->campaignService->create($data);

        return redirect()->route('tenant.marketing.campaigns.show', $campaign)
            ->with('success', "Campaign \"{$campaign->name}\" created successfully.");
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load('createdBy');
        $stats = $this->campaignService->getPerformanceStats($campaign);

        return view('tenant.marketing.campaigns.show', compact('campaign', 'stats'));
    }

    public function edit(Campaign $campaign): View
    {
        return view('tenant.marketing.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:email,sms,push'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'body_text' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'audience_filter' => ['nullable', 'array'],
            'audience_filter.tags' => ['nullable', 'array'],
            'audience_filter.last_booking_from' => ['nullable', 'date'],
            'audience_filter.last_booking_to' => ['nullable', 'date'],
            'audience_filter.min_spent' => ['nullable', 'numeric', 'min:0'],
            'audience_filter.max_spent' => ['nullable', 'numeric', 'min:0'],
        ]);

        $campaign->update($data);

        return redirect()->route('tenant.marketing.campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('tenant.marketing.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function send(Campaign $campaign): RedirectResponse
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'This campaign has already been sent.');
        }

        $this->campaignService->send($campaign);

        return back()->with('success', "Campaign \"{$campaign->name}\" is being sent.");
    }
}
