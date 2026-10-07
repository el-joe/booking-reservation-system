<?php

declare(strict_types=1);

namespace App\Services\Tenant\Reports;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Collection;

class BookingReportService
{
    public function generate(array $filters): Collection
    {
        $query = Booking::with(['customer', 'resource'])
            ->when(
                ! empty($filters['date_from']),
                fn ($q) => $q->whereDate('check_in', '>=', $filters['date_from'])
            )
            ->when(
                ! empty($filters['date_to']),
                fn ($q) => $q->whereDate('check_in', '<=', $filters['date_to'])
            )
            ->when(
                ! empty($filters['booking_type']),
                fn ($q) => $q->whereIn('booking_type', (array) $filters['booking_type'])
            )
            ->when(
                ! empty($filters['status']),
                fn ($q) => $q->whereIn('status', (array) $filters['status'])
            )
            ->when(
                ! empty($filters['resource_id']),
                fn ($q) => $q->where('resource_id', $filters['resource_id'])
            )
            ->when(
                ! empty($filters['customer_id']),
                fn ($q) => $q->where('customer_id', $filters['customer_id'])
            )
            ->orderByDesc('created_at');

        return $query->get();
    }

    public function getSummary(array $filters): array
    {
        $bookings = $this->generate($filters);

        return [
            'total_count' => $bookings->count(),
            'confirmed_count' => $bookings->where('status', BookingStatus::Confirmed)->count()
                + $bookings->where('status', BookingStatus::CheckedIn)->count()
                + $bookings->where('status', BookingStatus::Completed)->count(),
            'cancelled_count' => $bookings->where('status', BookingStatus::Cancelled)->count(),
            'no_show_count' => $bookings->where('status', BookingStatus::NoShow)->count(),
            'total_revenue' => $bookings->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Refunded])
                ->sum('total_amount'),
        ];
    }
}
