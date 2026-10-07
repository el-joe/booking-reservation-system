<?php

declare(strict_types=1);

namespace App\Jobs\Tenant;

use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCampaignJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(public readonly Campaign $campaign)
    {
        $this->onQueue('campaigns');
    }

    public function handle(): void
    {
        $campaign = $this->campaign;
        $filter = $campaign->audience_filter ?? [];

        $query = Customer::query();

        if (! empty($filter['tags'])) {
            $query->whereJsonContains('tags', $filter['tags']);
        }

        $recipients = $query->get();

        foreach ($recipients as $customer) {
            try {
                $this->dispatchToRecipient($campaign, $customer);
            } catch (Throwable $e) {
                Log::warning("Campaign [{$campaign->id}] failed for customer [{$customer->id}]: {$e->getMessage()}");
            }
        }
    }

    private function dispatchToRecipient(Campaign $campaign, Customer $customer): void
    {
        match ($campaign->type) {
            'email' => $this->sendEmail($campaign, $customer),
            'sms' => $this->sendSms($campaign, $customer),
            'push' => $this->sendPush($campaign, $customer),
            default => null,
        };
    }

    private function sendEmail(Campaign $campaign, Customer $customer): void
    {
        if (empty($customer->email)) {
            return;
        }

        Mail::html((string) $campaign->body_html, function ($message) use ($campaign, $customer): void {
            $message->to($customer->email, $customer->full_name ?? $customer->email)
                ->subject((string) $campaign->subject);
        });
    }

    private function sendSms(Campaign $campaign, Customer $customer): void
    {
        // SMS integration point — extend with SMS provider (Twilio, Vonage, etc.)
        Log::info("SMS campaign [{$campaign->id}] queued for customer [{$customer->id}]");
    }

    private function sendPush(Campaign $campaign, Customer $customer): void
    {
        // Push notification integration point — extend with push provider (Firebase, etc.)
        Log::info("Push campaign [{$campaign->id}] queued for customer [{$customer->id}]");
    }
}
