<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Notifications\Tenant\BookingReminderNotification;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'booking:send-reminders';

    protected $description = 'Send reminder notifications for bookings scheduled for tomorrow';

    public function handle(): int
    {
        $bookings = Booking::with(['customer', 'resource'])
            ->where('status', BookingStatus::Confirmed)
            ->whereBetween('check_in', [
                now()->addHours(23),
                now()->addHours(25),
            ])
            ->get();

        $this->info("Found {$bookings->count()} bookings to send reminders for.");

        foreach ($bookings as $booking) {
            if ($booking->customer) {
                $booking->customer->notify(new BookingReminderNotification($booking));
            }
        }

        $this->info('Booking reminders dispatched successfully.');

        return self::SUCCESS;
    }
}
