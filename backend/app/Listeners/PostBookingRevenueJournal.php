<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Models\Account;
use App\Services\Tenant\Accounting\JournalService;

class PostBookingRevenueJournal
{
    public function __construct(private JournalService $journalService) {}

    public function handle(BookingConfirmed $event): void
    {
        $booking = $event->booking;

        $ar = Account::where('code', '1100')->first();
        $revenue = Account::where('code', '4000')->first();

        if (! $ar || ! $revenue) {
            return;
        }

        $amount = (float) $booking->total_amount;

        $this->journalService->post(
            [
                ['account_id' => $ar->id, 'debit' => $amount, 'credit' => 0, 'description' => 'Booking #'.$booking->reference_number],
                ['account_id' => $revenue->id, 'debit' => 0, 'credit' => $amount, 'description' => 'Booking #'.$booking->reference_number],
            ],
            'Booking Revenue: '.$booking->reference_number,
            'BKG-'.$booking->id.'-'.time(),
            $booking,
        );
    }
}
