<?php

declare(strict_types=1);

namespace App\Jobs\Tenant;

use App\Models\OtaChannel;
use App\Models\Resource;
use App\Services\Tenant\Channel\ChannelManagerService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncChannelAvailabilityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly OtaChannel $channel,
        private readonly Resource $resource,
        private readonly Carbon $from,
        private readonly Carbon $to,
    ) {}

    public function handle(ChannelManagerService $service): void
    {
        $service->syncAvailability($this->channel, $this->resource, $this->from, $this->to);
    }
}
