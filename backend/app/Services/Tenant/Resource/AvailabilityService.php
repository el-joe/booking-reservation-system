<?php

declare(strict_types=1);

namespace App\Services\Tenant\Resource;

use App\Enums\BookingStatus;
use App\Models\BlackoutDate;
use App\Models\Resource;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    public function isAvailable(Resource $resource, Carbon $from, Carbon $to, int $guests = 1): bool
    {
        // 1. Check for blackout dates in range
        $hasBlackout = BlackoutDate::where('resource_id', $resource->id)
            ->where(function ($query) use ($from, $to): void {
                $query->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
            })
            ->exists();

        if ($hasBlackout) {
            return false;
        }

        // 2. Check ResourceAvailability overrides
        $overrides = $resource->availability()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get();

        foreach ($overrides as $override) {
            if ($override->is_closed) {
                return false;
            }

            if ($override->available_capacity < $guests) {
                return false;
            }
        }

        // 3. Check existing bookings do not exceed capacity
        $bookedCount = $resource->bookings()
            ->whereIn('status', [
                BookingStatus::Confirmed->value,
                BookingStatus::CheckedIn->value,
            ])
            ->where(function ($query) use ($from, $to): void {
                $query->where('check_in', '<', $to)->where('check_out', '>', $from);
            })
            ->count();

        return ($resource->capacity - $bookedCount) >= $guests;
    }

    public function getAvailableSlots(Resource $resource, Carbon $date): Collection
    {
        $dayOfWeek = $date->dayOfWeek; // 0 = Sunday

        $hasBlackout = BlackoutDate::where('resource_id', $resource->id)
            ->whereDate('date', $date)
            ->exists();

        if ($hasBlackout) {
            return collect();
        }

        return TimeSlot::where('resource_id', $resource->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * @param  array<string>  $dates
     */
    public function blockDates(Resource $resource, array $dates, string $reason): void
    {
        foreach ($dates as $date) {
            BlackoutDate::firstOrCreate(
                ['resource_id' => $resource->id, 'date' => $date],
                ['reason' => $reason]
            );
        }
    }

    /**
     * Returns calendar data for a given month suitable for FullCalendar.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getCalendarData(Resource $resource, Carbon $month): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $blackouts = BlackoutDate::where('resource_id', $resource->id)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->pluck('reason', 'date')
            ->map(fn ($reason, $date) => $date)
            ->values()
            ->toArray();

        $availabilityOverrides = $resource->availability()
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->keyBy(fn ($item) => $item->date->toDateString());

        $bookedCounts = $resource->bookings()
            ->whereIn('status', [
                BookingStatus::Confirmed->value,
                BookingStatus::CheckedIn->value,
            ])
            ->where('check_in', '<=', $endOfMonth)
            ->where('check_out', '>=', $startOfMonth)
            ->get()
            ->groupBy(fn ($booking) => Carbon::parse($booking->check_in)->toDateString())
            ->map(fn ($bookings) => $bookings->count());

        $events = [];
        $current = $startOfMonth->copy();

        while ($current->lte($endOfMonth)) {
            $dateStr = $current->toDateString();
            $booked = $bookedCounts[$dateStr] ?? 0;

            if (in_array($dateStr, $blackouts)) {
                $events[] = [
                    'date' => $dateStr,
                    'status' => 'blocked',
                    'color' => '#ef4444',
                    'title' => 'Blocked',
                    'available' => 0,
                    'capacity' => $resource->capacity,
                ];
            } elseif (isset($availabilityOverrides[$dateStr])) {
                $override = $availabilityOverrides[$dateStr];

                if ($override->is_closed) {
                    $events[] = [
                        'date' => $dateStr,
                        'status' => 'closed',
                        'color' => '#ef4444',
                        'title' => 'Closed',
                        'available' => 0,
                        'capacity' => $resource->capacity,
                    ];
                } else {
                    $available = $override->available_capacity - $booked;
                    $status = $available <= 0 ? 'full' : ($available < $resource->capacity ? 'partial' : 'available');
                    $color = match ($status) {
                        'full' => '#ef4444',
                        'partial' => '#f59e0b',
                        default => '#22c55e',
                    };

                    $events[] = [
                        'date' => $dateStr,
                        'status' => $status,
                        'color' => $color,
                        'title' => "{$available} available",
                        'available' => max(0, $available),
                        'capacity' => $override->available_capacity,
                    ];
                }
            } else {
                $available = $resource->capacity - $booked;
                $status = $available <= 0 ? 'full' : ($booked > 0 ? 'partial' : 'available');
                $color = match ($status) {
                    'full' => '#ef4444',
                    'partial' => '#f59e0b',
                    default => '#22c55e',
                };

                $events[] = [
                    'date' => $dateStr,
                    'status' => $status,
                    'color' => $color,
                    'title' => "{$available} available",
                    'available' => max(0, $available),
                    'capacity' => $resource->capacity,
                ];
            }

            $current->addDay();
        }

        return $events;
    }
}
