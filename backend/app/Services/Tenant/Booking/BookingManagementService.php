<?php

declare(strict_types=1);

namespace App\Services\Tenant\Booking;

use App\Enums\BookingStatus;
use App\Events\BookingCancelled;
use App\Events\BookingConfirmed;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingManagementService
{
    public function confirm(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking): Booking {
            $previousStatus = $booking->status->value;

            $booking->update([
                'status' => BookingStatus::Confirmed,
                'confirmed_at' => now(),
            ]);

            $this->recordStatusHistory($booking, BookingStatus::Confirmed->value, $previousStatus, 'Booking confirmed.');

            BookingConfirmed::dispatch($booking);

            return $booking->refresh();
        });
    }

    public function cancel(Booking $booking, string $reason): Booking
    {
        return DB::transaction(function () use ($booking, $reason): Booking {
            $previousStatus = $booking->status->value;

            $booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            $this->recordStatusHistory($booking, BookingStatus::Cancelled->value, $previousStatus, $reason);

            BookingCancelled::dispatch($booking, $reason);

            return $booking->refresh();
        });
    }

    public function markNoShow(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking): Booking {
            $previousStatus = $booking->status->value;

            $booking->update(['status' => BookingStatus::NoShow]);

            $this->recordStatusHistory($booking, BookingStatus::NoShow->value, $previousStatus, 'Marked as no-show.');

            return $booking->refresh();
        });
    }

    public function processCheckin(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking): Booking {
            $previousStatus = $booking->status->value;

            $booking->update(['status' => BookingStatus::CheckedIn]);

            $this->recordStatusHistory($booking, BookingStatus::CheckedIn->value, $previousStatus, 'Customer checked in.');

            return $booking->refresh();
        });
    }

    public function processCheckout(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking): Booking {
            $previousStatus = $booking->status->value;

            $booking->update(['status' => BookingStatus::Completed]);

            $this->recordStatusHistory($booking, BookingStatus::Completed->value, $previousStatus, 'Customer checked out. Booking completed.');

            return $booking->refresh();
        });
    }

    private function recordStatusHistory(
        Booking $booking,
        string $newStatus,
        ?string $previousStatus = null,
        ?string $note = null,
    ): void {
        $booking->statusHistories()->create([
            'status' => $newStatus,
            'previous_status' => $previousStatus,
            'note' => $note,
            'changed_by_id' => auth()->id(),
        ]);
    }
}
