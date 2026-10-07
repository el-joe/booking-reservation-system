<?php

declare(strict_types=1);

namespace App\Services\Tenant\BI;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BusinessIntelligenceService
{
    public function getBookingTrends(int $days = 30): Collection
    {
        return Booking::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as revenue')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();
    }

    public function getRevenueForecast(int $months = 6): array
    {
        $historical = Booking::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $avgMonthly = $historical->avg('revenue') ?? 0;

        $forecast = [];
        for ($i = 1; $i <= $months; $i++) {
            $forecast[] = [
                'month' => now()->addMonths($i)->format('M Y'),
                'predicted' => round($avgMonthly * (1 + ($i * 0.02)), 2),
            ];
        }

        return $forecast;
    }

    public function getCustomerBehavior(): array
    {
        $topCustomers = Customer::query()
            ->withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        $retentionRate = $this->calculateRetentionRate();
        $avgBookingsPerCustomer = Booking::query()->count() / max(Customer::query()->count(), 1);

        return compact('topCustomers', 'retentionRate', 'avgBookingsPerCustomer');
    }

    public function getChurnAnalysis(): array
    {
        $thirtyDaysAgo = now()->subDays(30);
        $ninetyDaysAgo = now()->subDays(90);

        $activeCustomers = Customer::whereHas('bookings', fn ($q) => $q->where('created_at', '>=', $thirtyDaysAgo))->count();
        $churnedCustomers = Customer::whereHas('bookings', fn ($q) => $q->where('created_at', '<', $ninetyDaysAgo))
            ->whereDoesntHave('bookings', fn ($q) => $q->where('created_at', '>=', $thirtyDaysAgo))
            ->count();
        $totalCustomers = Customer::count();

        return [
            'active' => $activeCustomers,
            'churned' => $churnedCustomers,
            'total' => $totalCustomers,
            'churn_rate' => $totalCustomers > 0 ? round(($churnedCustomers / $totalCustomers) * 100, 2) : 0,
        ];
    }

    private function calculateRetentionRate(): float
    {
        $repeatCustomers = Customer::whereHas('bookings', fn ($q) => $q, '>=', 2)->count();
        $total = Customer::count();

        return $total > 0 ? round(($repeatCustomers / $total) * 100, 2) : 0;
    }
}
