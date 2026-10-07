<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Channel;

use App\Http\Controllers\Controller;
use App\Models\ChannelReservation;
use App\Models\OtaChannel;
use App\Services\Tenant\Channel\ChannelManagerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChannelReservationController extends Controller
{
    public function __construct(private readonly ChannelManagerService $channelManager) {}

    public function index(Request $request): View
    {
        $channelId = $request->query('channel_id');

        $reservations = ChannelReservation::with('channel')
            ->when($channelId, fn ($q) => $q->where('ota_channel_id', $channelId))
            ->latest('imported_at')
            ->paginate(25);

        $channels = OtaChannel::orderBy('name')->get();

        return view('tenant.channel.reservations.index', compact('reservations', 'channels', 'channelId'));
    }

    public function show(ChannelReservation $reservation): View
    {
        $reservation->load(['channel', 'booking']);

        return view('tenant.channel.reservations.show', compact('reservation'));
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'channel_id' => 'required|exists:ota_channels,id',
        ]);

        $channel = OtaChannel::findOrFail($validated['channel_id']);
        $count = $this->channelManager->importReservations($channel);

        return redirect()->route('tenant.channels.reservations.index')
            ->with('success', "Import complete. {$count} new reservation(s) pulled.");
    }
}
