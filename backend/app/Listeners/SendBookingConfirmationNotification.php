<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Services\Tenant\Notification\NotificationService;

class SendBookingConfirmationNotification
{
    public function __construct(private readonly NotificationService $notificationService) {}

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking->load(['customer', 'resource']);

        $variables = [
            'reference_number' => $booking->reference_number,
            'customer_name' => $booking->customer?->full_name ?? 'Valued Customer',
            'resource_name' => $booking->resource?->name ?? '',
            'check_in' => $booking->check_in->format('d M Y, H:i'),
            'check_out' => $booking->check_out->format('d M Y, H:i'),
            'total_amount' => number_format((float) $booking->total_amount, 2),
        ];

        if ($booking->customer) {
            $this->notificationService->send($booking->customer, 'booking.created', $variables);
        }
    }
}
