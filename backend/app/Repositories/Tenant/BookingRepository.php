<?php

declare(strict_types=1);

namespace App\Repositories\Tenant;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository
{
    /**
     * Paginate bookings with optional filters.
     *
     * @param  array{
     *     status?: string,
     *     booking_type?: string,
     *     date_from?: string,
     *     date_to?: string,
     *     customer_id?: int,
     *     resource_id?: int,
     *     search?: string,
     * }  $filters
     */
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return Booking::query()
            ->with(['customer', 'resource'])
            ->when(isset($filters['status']), fn (Builder $q) => $q->where('status', $filters['status']))
            ->when(isset($filters['booking_type']), fn (Builder $q) => $q->where('booking_type', $filters['booking_type']))
            ->when(isset($filters['date_from']), fn (Builder $q) => $q->whereDate('check_in', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn (Builder $q) => $q->whereDate('check_in', '<=', $filters['date_to']))
            ->when(isset($filters['customer_id']), fn (Builder $q) => $q->where('customer_id', $filters['customer_id']))
            ->when(isset($filters['resource_id']), fn (Builder $q) => $q->where('resource_id', $filters['resource_id']))
            ->when(isset($filters['search']), function (Builder $q) use ($filters): void {
                $search = $filters['search'];
                $q->where(function (Builder $inner) use ($search): void {
                    $inner->where('reference_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn (Builder $cq) => $cq->where(
                            DB::raw("CONCAT(first_name, ' ', last_name)"),
                            'like',
                            "%{$search}%",
                        ));
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findByReference(string $ref): ?Booking
    {
        return Booking::with(['customer', 'resource', 'items', 'statusHistories'])
            ->where('reference_number', $ref)
            ->first();
    }

    public function pendingApprovals(): Collection
    {
        return Booking::with(['customer', 'resource'])
            ->where('status', BookingStatus::Pending)
            ->orderBy('check_in')
            ->get();
    }

    public function upcomingToday(): Collection
    {
        return Booking::with(['customer', 'resource'])
            ->whereBetween('check_in', [now(), now()->endOfDay()])
            ->orderBy('check_in')
            ->get();
    }
}
