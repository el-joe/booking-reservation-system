<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Services\Central\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $growthData = $this->dashboardService->getTenantGrowthChart();
        $revenueData = $this->dashboardService->getRevenueChart();
        $recentTenants = $this->dashboardService->getRecentTenants();
        $planDistribution = $this->dashboardService->getPlanDistribution();

        return view('central.dashboard.index', compact(
            'stats',
            'growthData',
            'revenueData',
            'recentTenants',
            'planDistribution',
        ));
    }
}
