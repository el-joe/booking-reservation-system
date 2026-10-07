<?php

declare(strict_types=1);

namespace App\Services\Tenant\Integration;

use App\Jobs\Tenant\DispatchWebhookJob;
use App\Models\Webhook;
use Illuminate\Support\Str;

class WebhookDispatchService
{
    public function dispatch(string $event, array $payload): void
    {
        $webhooks = Webhook::where('is_active', true)
            ->get()
            ->filter(fn (Webhook $webhook) => in_array($event, $webhook->events ?? [], true));

        foreach ($webhooks as $webhook) {
            $signature = hash_hmac('sha256', json_encode($payload), $webhook->secret);
            DispatchWebhookJob::dispatch($webhook, $event, $payload, $signature);
        }
    }

    public function generateSecret(): string
    {
        return Str::random(32);
    }
}
