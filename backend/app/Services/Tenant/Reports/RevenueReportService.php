<?php

declare(strict_types=1);

namespace App\Services\Tenant\Reports;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueReportService
{
    public function generate(array $filters): array
    {
        $dateFrom = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $filters['date_to'] ?? now()->toDateString();
        $groupBy = $filters['group_by'] ?? 'day';

        $baseQuery = Booking::whereNotIn('status', [BookingStatus::Cancelled->value, BookingStatus::Refunded->value])
            ->whereDate('check_in', '>=', $dateFrom)
            ->whereDate('check_in', '<=', $dateTo)
            ->when(! empty($filters['booking_type']), fn ($q) => $q->whereIn('booking_type', (array) $filters['booking_type']))
            ->when(! empty($filters['resource_id']), fn ($q) => $q->where('resource_id', $filters['resource_id']));

        $totalRevenue = (clone $baseQuery)->sum('total_amount');
        $bookingCount = (clone $baseQuery)->count();

        // Previous period for comparison
        $periodDays = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        $prevDateFrom = Carbon::parse($dateFrom)->subDays($periodDays)->toDateString();
        $prevDateTo = Carbon::parse($dateFrom)->subDay()->toDateString();

        $prevRevenue = Booking::whereNotIn('status', [BookingStatus::Cancelled->value, BookingStatus::Refunded->value])
            ->whereDate('check_in', '>=', $prevDateFrom)
            ->whereDate('check_in', '<=', $prevDateTo)
            ->sum('total_amount');

        $growth = $prevRevenue > 0 ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 2) : null;

        // By resource
        $byResource = (clone $baseQuery)
            ->with('resource:id,name')
            ->select('resource_id', DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('resource_id')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->resource?->name ?? 'Unknown' => (float) $row->revenue]);

        // By booking type
        $byBookingType = (clone $baseQuery)
            ->select('booking_type', DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('booking_type')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->booking_type => (float) $row->revenue]);

        return [
            'total_revenue' => (float) $totalRevenue,
            'booking_count' => $bookingCount,
            'average_per_booking' => $bookingCount > 0 ? round((float) $totalRevenue / $bookingCount, 2) : 0,
            'period_comparison' => [
                'current' => (float) $totalRevenue,
                'previous' => (float) $prevRevenue,
                'growth_percent' => $growth,
            ],
            'by_resource' => $byResource->toArray(),
            'by_booking_type' => $byBookingType->toArray(),
            'by_period' => $this->getByPeriod($filters, $groupBy),
        ];
    }

    public function getByPeriod(array $filters, string $groupBy = 'day'): array
    {
        $dateFrom = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $filters['date_to'] ?? now()->toDateString();

        $format = match ($groupBy) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $rows = Booking::whereNotIn('status', [BookingStatus::Cancelled->value, BookingStatus::Refunded->value])
            ->whereDate('check_in', '>=', $dateFrom)
            ->whereDate('check_in', '<=', $dateTo)
            ->when(! empty($filters['booking_type']), fn ($q) => $q->whereIn('booking_type', (array) $filters['booking_type']))
            ->when(! empty($filters['resource_id']), fn ($q) => $q->where('resource_id', $filters['resource_id']))
            ->select(
                DB::raw("DATE_FORMAT(check_in, '{$format}') as period"),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $rows->pluck('period')->toArray(),
            'data' => $rows->pluck('revenue')->map(fn ($v) => (float) $v)->toArray(),
            'counts' => $rows->pluck('count')->toArray(),
        ];
    }
}
