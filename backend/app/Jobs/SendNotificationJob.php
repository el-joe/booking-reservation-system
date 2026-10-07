<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\NotificationChannel;
use App\Services\Tenant\Notification\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SendNotificationJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public readonly string $channel,
        public readonly string $recipient,
        public readonly string $subject,
        public readonly string $body,
        public readonly ?int $templateId,
        public readonly string $notifiableType,
        public readonly int|string $notifiableId,
    ) {
        $this->onQueue('notifications');
    }

    public function handle(NotificationService $notificationService): void
    {
        $channelEnum = NotificationChannel::from($this->channel);

        match ($channelEnum) {
            NotificationChannel::Email => $this->sendEmail(),
            NotificationChannel::SMS => $this->sendSms(),
            NotificationChannel::Push => $this->sendPush(),
            NotificationChannel::WhatsApp => $this->sendWhatsApp(),
            NotificationChannel::Database => $this->sendDatabase(),
        };

        $notificationService->logDelivery(
            channel: $this->channel,
            recipient: $this->recipient,
            templateId: $this->templateId,
            status: 'sent',
            notifiableType: $this->notifiableType,
            notifiableId: (int) $this->notifiableId,
            subject: $this->subject,
        );
    }

    public function failed(Throwable $exception): void
    {
        $notificationService = app(NotificationService::class);

        $notificationService->logDelivery(
            channel: $this->channel,
            recipient: $this->recipient,
            templateId: $this->templateId,
            status: 'failed',
            error: $exception->getMessage(),
            notifiableType: $this->notifiableType,
            notifiableId: (int) $this->notifiableId,
            subject: $this->subject,
        );
    }

    private function sendEmail(): void
    {
        $body = $this->body;
        $subject = $this->subject;

        Mail::html($body, function ($message) use ($subject): void {
            $message->to($this->recipient)->subject($subject);
        });
    }

    private function sendSms(): void
    {
        Log::info('SMS notification stub', [
            'to' => $this->recipient,
            'body' => $this->body,
        ]);
    }

    private function sendPush(): void
    {
        Log::info('Push notification stub', [
            'token' => $this->recipient,
            'subject' => $this->subject,
            'body' => $this->body,
        ]);
    }

    private function sendWhatsApp(): void
    {
        Log::info('WhatsApp notification stub', [
            'to' => $this->recipient,
            'body' => $this->body,
        ]);
    }

    private function sendDatabase(): void
    {
        DatabaseNotification::create([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\GenericNotification',
            'notifiable_type' => $this->notifiableType,
            'notifiable_id' => $this->notifiableId,
            'data' => json_encode([
                'subject' => $this->subject,
                'body' => $this->body,
                'channel' => $this->channel,
            ]),
            'read_at' => null,
        ]);
    }
}
