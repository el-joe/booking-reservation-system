<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Support\Facades\Log;

class UpdateResourceAvailability
{
    /**
     * Handle the event.
     * Availability logic will be built in AGENT-07.
     */
    public function handle(BookingCreated $event): void
    {
        Log::info('Resource availability update stub', [
            'booking_id' => $event->booking->id,
            'resource_id' => $event->booking->resource_id,
            'check_in' => $event->booking->check_in,
            'check_out' => $event->booking->check_out,
        ]);
    }
}
