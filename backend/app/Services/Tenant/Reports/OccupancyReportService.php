<?php

declare(strict_types=1);

namespace App\Services\Tenant\Reports;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Resource;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OccupancyReportService
{
    public function generate(array $filters): array
    {
        $dateFrom = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $filters['date_to'] ?? now()->toDateString();

        $totalDays = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;

        $resourceQuery = Resource::query()
            ->when(! empty($filters['resource_id']), fn ($q) => $q->where('id', $filters['resource_id']));

        $resources = $resourceQuery->get();

        $bookedCountsByResource = Booking::whereNotIn('status', [
            BookingStatus::Cancelled->value,
            BookingStatus::Refunded->value,
        ])
            ->whereDate('check_in', '>=', $dateFrom)
            ->whereDate('check_in', '<=', $dateTo)
            ->when(! empty($filters['resource_id']), fn ($q) => $q->where('resource_id', $filters['resource_id']))
            ->selectRaw('resource_id, COUNT(DISTINCT DATE(check_in)) as booked_days')
            ->groupBy('resource_id')
            ->get()
            ->keyBy('resource_id');

        $byResource = [];
        $totalBooked = 0;
        $totalSlots = 0;

        foreach ($resources as $resource) {
            $bookedDays = (int) ($bookedCountsByResource[$resource->id]->booked_days ?? 0);
            $rate = $this->calculateRate($bookedDays, $totalDays);
            $byResource[$resource->name] = $rate;
            $totalBooked += $bookedDays;
            $totalSlots += $totalDays;
        }

        $overallRate = $totalSlots > 0 ? $this->calculateRate($totalBooked, $totalSlots) : 0.0;

        // Heatmap: date => occupancy %
        $heatmapData = $this->buildHeatmap($dateFrom, $dateTo, $filters);

        return [
            'overall_rate' => $overallRate,
            'by_resource' => $byResource,
            'heatmap_data' => $heatmapData,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    public function calculateRate(int $bookedSlots, int $totalSlots): float
    {
        if ($totalSlots === 0) {
            return 0.0;
        }

        return round(($bookedSlots / $totalSlots) * 100, 2);
    }

    private function buildHeatmap(string $dateFrom, string $dateTo, array $filters): array
    {
        $resourceCount = Resource::query()
            ->when(! empty($filters['resource_id']), fn ($q) => $q->where('id', $filters['resource_id']))
            ->count();

        if ($resourceCount === 0) {
            return [];
        }

        $bookingsByDate = Booking::whereNotIn('status', [
            BookingStatus::Cancelled->value,
            BookingStatus::Refunded->value,
        ])
            ->whereDate('check_in', '>=', $dateFrom)
            ->whereDate('check_in', '<=', $dateTo)
            ->when(! empty($filters['resource_id']), fn ($q) => $q->where('resource_id', $filters['resource_id']))
            ->selectRaw('DATE(check_in) as booking_date, COUNT(DISTINCT resource_id) as booked_resources')
            ->groupBy('booking_date')
            ->get()
            ->keyBy('booking_date');

        $heatmap = [];
        $period = CarbonPeriod::create($dateFrom, $dateTo);

        foreach ($period as $date) {
            $dateStr = $date->toDateString();
            $booked = (int) ($bookingsByDate[$dateStr]->booked_resources ?? 0);
            $heatmap[$dateStr] = $this->calculateRate($booked, $resourceCount);
        }

        return $heatmap;
    }
}
