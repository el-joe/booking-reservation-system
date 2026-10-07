<?php

declare(strict_types=1);

namespace App\Jobs\Tenant;

use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DispatchWebhookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        private readonly Webhook $webhook,
        private readonly string $event,
        private readonly array $payload,
        private readonly string $signature,
    ) {}

    public function handle(): void
    {
        try {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Webhook-Event' => $this->event,
                'X-Webhook-Signature' => $this->signature,
            ])->post($this->webhook->url, $this->payload);

            $this->webhook->update(['last_triggered_at' => now()]);
        } catch (\Throwable $e) {
            Log::warning("Webhook dispatch failed for {$this->webhook->url}: ".$e->getMessage());
            throw $e;
        }
    }
}
