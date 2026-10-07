<?php

declare(strict_types=1);

namespace App\Notifications\Tenant;

use App\Models\Booking;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification implements ShouldQueue
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
        $template = NotificationTemplate::where('event_trigger', 'booking.cancelled')
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
            ->subject('Booking Cancelled: '.$this->booking->reference_number)
            ->line('Your booking '.$this->booking->reference_number.' has been cancelled.')
            ->line('Reason: '.($this->booking->cancellation_reason ?? 'No reason provided.'));
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'reference_number' => $this->booking->reference_number,
            'status' => $this->booking->status->value,
            'cancellation_reason' => $this->booking->cancellation_reason,
            'message' => 'Your booking '.$this->booking->reference_number.' has been cancelled.',
        ];
    }

    /** @return array<string, string> */
    private function buildVariables(): array
    {
        return [
            'reference_number' => $this->booking->reference_number,
            'customer_name' => $this->booking->customer?->full_name ?? 'Valued Customer',
            'cancellation_reason' => $this->booking->cancellation_reason ?? 'No reason provided.',
        ];
    }
}
