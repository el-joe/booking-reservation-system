<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\BI;

use App\Http\Controllers\Controller;
use App\Services\Tenant\BI\BusinessIntelligenceService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly BusinessIntelligenceService $biService) {}

    public function index(): View
    {
        $trends = $this->biService->getBookingTrends(30);
        $churn = $this->biService->getChurnAnalysis();

        return view('tenant.bi.index', compact('trends', 'churn'));
    }

    public function trends(): View
    {
        $trends = $this->biService->getBookingTrends(60);

        return view('tenant.bi.trends', compact('trends'));
    }

    public function forecast(): View
    {
        $forecast = $this->biService->getRevenueForecast(6);

        return view('tenant.bi.forecast', compact('forecast'));
    }

    public function customerBehavior(): View
    {
        $data = $this->biService->getCustomerBehavior();

        return view('tenant.bi.customer-behavior', $data);
    }

    public function churnAnalysis(): View
    {
        $data = $this->biService->getChurnAnalysis();

        return view('tenant.bi.churn', compact('data'));
    }
}
