<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Channel;

use App\Http\Controllers\Controller;
use App\Jobs\Tenant\SyncChannelAvailabilityJob;
use App\Models\OtaChannel;
use App\Models\Resource;
use App\Services\Tenant\Channel\ChannelManagerService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtaChannelController extends Controller
{
    public function __construct(private readonly ChannelManagerService $channelManager) {}

    public function index(): View
    {
        $channels = OtaChannel::withCount('reservations')->latest()->get();

        return view('tenant.channel.channels.index', compact('channels'));
    }

    public function create(): View
    {
        return view('tenant.channel.channels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:booking_com,airbnb,expedia,agoda,custom',
            'api_key' => 'nullable|string|max:500',
            'api_secret' => 'nullable|string|max:500',
            'channel_property_id' => 'nullable|string|max:255',
            'sync_enabled' => 'boolean',
        ]);

        $validated['sync_enabled'] = $request->boolean('sync_enabled');
        $validated['status'] = 'pending';

        $channel = OtaChannel::create($validated);

        return redirect()->route('tenant.channels.ota.show', $channel)
            ->with('success', "Channel \"{$channel->name}\" added successfully.");
    }

    public function show(OtaChannel $ota): View
    {
        $ota->load(['ratePlans.resource', 'reservations' => fn ($q) => $q->latest()->limit(10)]);
        $status = $this->channelManager->getChannelStatus($ota);

        return view('tenant.channel.channels.show', ['channel' => $ota, 'status' => $status]);
    }

    public function edit(OtaChannel $ota): View
    {
        return view('tenant.channel.channels.edit', ['channel' => $ota]);
    }

    public function update(Request $request, OtaChannel $ota): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:booking_com,airbnb,expedia,agoda,custom',
            'api_key' => 'nullable|string|max:500',
            'api_secret' => 'nullable|string|max:500',
            'channel_property_id' => 'nullable|string|max:255',
            'sync_enabled' => 'boolean',
            'status' => 'required|in:active,inactive,pending',
        ]);

        $validated['sync_enabled'] = $request->boolean('sync_enabled');

        $ota->update($validated);

        return redirect()->route('tenant.channels.ota.show', $ota)
            ->with('success', 'Channel updated successfully.');
    }

    public function destroy(OtaChannel $ota): RedirectResponse
    {
        $name = $ota->name;
        $ota->delete();

        return redirect()->route('tenant.channels.ota.index')
            ->with('success', "Channel \"{$name}\" disconnected.");
    }

    public function sync(Request $request, OtaChannel $channel): RedirectResponse
    {
        $resources = Resource::all();

        foreach ($resources as $resource) {
            SyncChannelAvailabilityJob::dispatch(
                $channel,
                $resource,
                Carbon::today(),
                Carbon::today()->addMonths(3),
            );
        }

        return redirect()->back()->with('success', 'Sync queued for all resources.');
    }
}
