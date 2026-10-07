<?php

declare(strict_types=1);

namespace App\Notifications\Tenant;

use App\Models\Booking;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Booking $booking,
        public readonly float $amount,
        public readonly string $currency = 'USD',
        public readonly ?string $invoiceNumber = null,
    ) {}

    /** @return array<string> */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = NotificationTemplate::where('event_trigger', 'payment.received')
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
            ->subject('Payment Received for Booking '.$this->booking->reference_number)
            ->line('We have received your payment of '.$this->currency.' '.number_format($this->amount, 2).'.')
            ->line('Booking Reference: '.$this->booking->reference_number);
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'reference_number' => $this->booking->reference_number,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'invoice_number' => $this->invoiceNumber,
            'message' => 'Payment of '.$this->currency.' '.number_format($this->amount, 2).' received for booking '.$this->booking->reference_number.'.',
        ];
    }

    /** @return array<string, string> */
    private function buildVariables(): array
    {
        return [
            'reference_number' => $this->booking->reference_number,
            'amount' => number_format($this->amount, 2),
            'currency' => $this->currency,
            'invoice_number' => $this->invoiceNumber ?? '',
        ];
    }
}
