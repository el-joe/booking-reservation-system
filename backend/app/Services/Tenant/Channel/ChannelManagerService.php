<?php

declare(strict_types=1);

namespace App\Services\Tenant\Channel;

use App\Models\ChannelRatePlan;
use App\Models\ChannelReservation;
use App\Models\OtaChannel;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChannelManagerService
{
    /**
     * Sync availability for a resource on a given channel between two dates.
     *
     * This is a stub implementation. Real OTA integrations require
     * channel-specific API adapters (e.g. iCal, OTA Connect API).
     */
    public function syncAvailability(OtaChannel $channel, Resource $resource, Carbon $from, Carbon $to): void
    {
        if (! $channel->isActive() || ! $channel->sync_enabled) {
            return;
        }

        Log::info('Channel availability sync initiated', [
            'channel_id' => $channel->id,
            'channel_type' => $channel->type,
            'resource_id' => $resource->id,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);

        // TODO: Dispatch to channel-specific adapter based on $channel->type
        // e.g. match($channel->type) {
        //   'booking_com' => $this->bookingComAdapter->pushAvailability(...),
        //   'airbnb' => $this->airbnbAdapter->pushCalendar(...),
        // }

        $channel->update(['last_sync_at' => now()]);
    }

    /**
     * Pull new reservations from a channel and store them locally.
     *
     * @return int Number of reservations imported
     */
    public function importReservations(OtaChannel $channel): int
    {
        if (! $channel->isActive()) {
            return 0;
        }

        Log::info('Channel reservation import initiated', [
            'channel_id' => $channel->id,
            'channel_type' => $channel->type,
        ]);

        // TODO: Fetch from channel-specific adapter and upsert records.
        // Stub returns 0 until adapters are implemented.

        $channel->update(['last_sync_at' => now()]);

        return 0;
    }

    /**
     * Push a rate plan update to the connected channel.
     */
    public function updateRate(ChannelRatePlan $ratePlan): void
    {
        $channel = $ratePlan->channel;

        if (! $channel->isActive() || ! $channel->sync_enabled) {
            return;
        }

        Log::info('Channel rate update initiated', [
            'channel_id' => $channel->id,
            'rate_plan_id' => $ratePlan->id,
            'base_rate' => $ratePlan->base_rate,
        ]);

        // TODO: Dispatch to channel-specific adapter.
    }

    /**
     * Return a status summary for the given channel.
     *
     * @return array{
     *     connected: bool,
     *     status: string,
     *     last_sync_at: string|null,
     *     pending_reservations: int,
     *     active_rate_plans: int,
     * }
     */
    public function getChannelStatus(OtaChannel $channel): array
    {
        return [
            'connected' => $channel->isActive(),
            'status' => $channel->status,
            'last_sync_at' => $channel->last_sync_at?->toIso8601String(),
            'pending_reservations' => ChannelReservation::query()
                ->where('ota_channel_id', $channel->id)
                ->whereNull('booking_id')
                ->count(),
            'active_rate_plans' => $channel->ratePlans()->where('is_active', true)->count(),
        ];
    }
}
