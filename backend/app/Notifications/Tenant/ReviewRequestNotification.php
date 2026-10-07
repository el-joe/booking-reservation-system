<?php

declare(strict_types=1);

namespace App\Notifications\Tenant;

use App\Models\Booking;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Booking $booking) {}

    /** @return array<string> */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = NotificationTemplate::where('event_trigger', 'booking.review_request')
            ->where('channel', 'email')
            ->where('is_active', true)
            ->first();

        $variables = $this->buildVariables();

        if ($template) {
            $subject = $template->renderSubject($variables);
            $body = $template->renderBody($variables);

            return (new MailMessage)
                ->subject($subject)
                ->html($body);
        }

        return (new MailMessage)
            ->subject('How was your experience? — '.$this->booking->reference_number)
            ->line('Thank you for your recent booking ('.$this->booking->reference_number.').')
            ->line('We would love to hear about your experience. Please take a moment to leave a review.')
            ->line('Your feedback helps us improve our service.');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'reference_number' => $this->booking->reference_number,
            'status' => $this->booking->status->value,
            'message' => 'Please leave a review for your booking '.$this->booking->reference_number.'.',
        ];
    }

    /** @return array<string, string> */
    private function buildVariables(): array
    {
        return [
            'reference_number' => $this->booking->reference_number,
            'customer_name' => $this->booking->customer?->full_name ?? 'Valued Customer',
            'resource_name' => $this->booking->resource?->name ?? '',
            'check_in' => $this->booking->check_in->format('d M Y, H:i'),
            'check_out' => $this->booking->check_out->format('d M Y, H:i'),
        ];
    }
}
