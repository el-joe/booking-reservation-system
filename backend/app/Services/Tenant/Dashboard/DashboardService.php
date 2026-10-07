<?php

declare(strict_types=1);

namespace App\Services\Tenant\Dashboard;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $todayBookings = Booking::whereDate('check_in', $today)->count();

        $monthlyRevenue = Booking::whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('paid_amount');

        $totalCapacity = Resource::sum('capacity');
        $bookedSlots = Booking::whereIn('status', [
            BookingStatus::Confirmed->value,
            BookingStatus::CheckedIn->value,
        ])->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->count();

        $occupancyRate = $totalCapacity > 0
            ? round(($bookedSlots / $totalCapacity) * 100, 1)
            : 0;

        $pendingApprovals = Booking::where('status', BookingStatus::Pending->value)->count();

        return [
            'today_bookings' => $todayBookings,
            'monthly_revenue' => $monthlyRevenue,
            'occupancy_rate' => $occupancyRate,
            'pending_approvals' => $pendingApprovals,
        ];
    }

    public function getUpcomingBookings(int $limit = 10): Collection
    {
        return Booking::with(['customer', 'resource'])
            ->whereBetween('check_in', [Carbon::now(), Carbon::now()->addHours(24)])
            ->orderBy('check_in')
            ->limit($limit)
            ->get();
    }

    public function getRecentActivity(int $limit = 20): Collection
    {
        return BookingStatusHistory::with(['booking.customer'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRevenueChart(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $data = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(paid_amount) as total')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $values = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('M d');
            $values[] = (float) ($data[$date] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $values,
        ];
    }

    public function getBookingStatusBreakdown(): array
    {
        $counts = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [];
        $data = [];

        foreach (BookingStatus::cases() as $status) {
            $labels[] = $status->label();
            $data[] = $counts[$status->value] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
