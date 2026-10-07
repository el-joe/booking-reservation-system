<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Dashboard\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $upcomingBookings = $this->dashboardService->getUpcomingBookings(10);
        $recentActivity = $this->dashboardService->getRecentActivity(20);
        $revenueChart = $this->dashboardService->getRevenueChart(30);
        $statusBreakdown = $this->dashboardService->getBookingStatusBreakdown();

        return view('tenant.dashboard.index', compact(
            'stats',
            'upcomingBookings',
            'recentActivity',
            'revenueChart',
            'statusBreakdown',
        ));
    }
}
