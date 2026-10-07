<?php

declare(strict_types=1);

namespace App\Services\Tenant\Notification;

use App\Enums\NotificationChannel;
use App\Jobs\SendNotificationJob;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    public function send(Model $notifiable, string $eventTrigger, array $variables = []): void
    {
        $templates = NotificationTemplate::where('event_trigger', $eventTrigger)
            ->where('is_active', true)
            ->get();

        foreach ($templates as $template) {
            $recipient = $this->resolveRecipient($notifiable, $template->channel);

            if ($recipient === null) {
                continue;
            }

            $subject = $template->renderSubject($variables);
            $body = $template->renderBody($variables);

            SendNotificationJob::dispatch(
                channel: $template->channel->value,
                recipient: $recipient,
                subject: $subject,
                body: $body,
                templateId: $template->id,
                notifiableType: $notifiable->getMorphClass(),
                notifiableId: $notifiable->getKey(),
            );
        }
    }

    public function broadcast(Collection $recipients, NotificationTemplate $template, array $variables = []): void
    {
        foreach ($recipients as $notifiable) {
            $recipient = $this->resolveRecipient($notifiable, $template->channel);

            if ($recipient === null) {
                continue;
            }

            $subject = $template->renderSubject($variables);
            $body = $template->renderBody($variables);

            SendNotificationJob::dispatch(
                channel: $template->channel->value,
                recipient: $recipient,
                subject: $subject,
                body: $body,
                templateId: $template->id,
                notifiableType: $notifiable->getMorphClass(),
                notifiableId: $notifiable->getKey(),
            );
        }
    }

    public function logDelivery(
        string $channel,
        string $recipient,
        ?int $templateId,
        string $status,
        ?string $error = null,
        ?string $notifiableType = null,
        ?int $notifiableId = null,
        ?string $subject = null,
    ): NotificationLog {
        return NotificationLog::create([
            'channel' => $channel,
            'recipient' => $recipient,
            'template_id' => $templateId,
            'status' => $status,
            'error_message' => $error,
            'notifiable_type' => $notifiableType ?? '',
            'notifiable_id' => $notifiableId ?? 0,
            'subject' => $subject,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }

    public function getLog(array $filters = []): LengthAwarePaginator
    {
        $query = NotificationLog::with('template')
            ->latest();

        if (! empty($filters['channel'])) {
            $query->where('channel', $filters['channel']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['notifiable_type'])) {
            $query->where('notifiable_type', $filters['notifiable_type']);
        }

        if (! empty($filters['notifiable_id'])) {
            $query->where('notifiable_id', $filters['notifiable_id']);
        }

        return $query->paginate($filters['per_page'] ?? 25);
    }

    private function resolveRecipient(Model $notifiable, NotificationChannel $channel): ?string
    {
        return match ($channel) {
            NotificationChannel::Email => $notifiable->email ?? null,
            NotificationChannel::SMS => $notifiable->phone ?? null,
            NotificationChannel::Push => $notifiable->device_token ?? null,
            NotificationChannel::WhatsApp => $notifiable->phone ?? null,
            NotificationChannel::Database => (string) $notifiable->getKey(),
        };
    }
}
