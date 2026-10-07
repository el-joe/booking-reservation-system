<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Resource;
use App\Services\Tenant\Reports\BookingReportService;
use App\Services\Tenant\Reports\ExportService;
use App\Services\Tenant\Reports\OccupancyReportService;
use App\Services\Tenant\Reports\RevenueReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private readonly BookingReportService $bookingReportService,
        private readonly RevenueReportService $revenueReportService,
        private readonly OccupancyReportService $occupancyReportService,
        private readonly ExportService $exportService,
    ) {}

    public function bookings(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'booking_type', 'status', 'resource_id', 'customer_id', 'search']);
        $bookings = $this->bookingReportService->generate($filters);
        $summary = $this->bookingReportService->getSummary($filters);
        $statuses = BookingStatus::cases();
        $bookingTypes = BookingType::cases();
        $resources = Resource::orderBy('name')->get(['id', 'name']);

        return view('tenant.reports.bookings', compact('bookings', 'summary', 'statuses', 'bookingTypes', 'resources', 'filters'));
    }

    public function revenue(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'booking_type', 'resource_id', 'group_by']);
        $data = $this->revenueReportService->generate($filters);
        $bookingTypes = BookingType::cases();
        $resources = Resource::orderBy('name')->get(['id', 'name']);

        // Best day revenue
        $byPeriod = $data['by_period'];
        $bestDayRevenue = ! empty($byPeriod['data']) ? max($byPeriod['data']) : 0;

        return view('tenant.reports.revenue', compact('data', 'bookingTypes', 'resources', 'filters', 'bestDayRevenue'));
    }

    public function occupancy(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'resource_id']);
        $data = $this->occupancyReportService->generate($filters);
        $resources = Resource::orderBy('name')->get(['id', 'name']);

        return view('tenant.reports.occupancy', compact('data', 'resources', 'filters'));
    }

    public function customers(Request $request): View
    {
        $currentMonth = now()->startOfMonth();

        $totalCustomers = Customer::count();
        $newThisMonth = Customer::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        $returningCount = Customer::has('bookings', '>=', 2)->count();
        $returningRate = $totalCustomers > 0 ? round(($returningCount / $totalCustomers) * 100, 1) : 0;

        $avgBookings = $totalCustomers > 0
            ? round(Booking::count() / $totalCustomers, 1)
            : 0;

        // Monthly acquisition (last 12 months)
        $monthlyAcquisition = Customer::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 10 spenders
        $topSpenders = Customer::withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->orderByDesc('bookings_sum_total_amount')
            ->limit(10)
            ->get();

        return view('tenant.reports.customers', compact(
            'totalCustomers',
            'newThisMonth',
            'returningRate',
            'avgBookings',
            'monthlyAcquisition',
            'topSpenders',
        ));
    }

    public function cancellations(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'resource_id']);
        $dateFrom = $filters['date_from'] ?? now()->subMonths(3)->toDateString();
        $dateTo = $filters['date_to'] ?? now()->toDateString();

        $totalCancellations = Booking::where('status', BookingStatus::Cancelled)
            ->whereDate('cancelled_at', '>=', $dateFrom)
            ->whereDate('cancelled_at', '<=', $dateTo)
            ->count();

        $totalBookings = Booking::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->count();

        $cancellationRate = $totalBookings > 0 ? round(($totalCancellations / $totalBookings) * 100, 1) : 0;

        // By reason
        $byReason = Booking::where('status', BookingStatus::Cancelled)
            ->whereDate('cancelled_at', '>=', $dateFrom)
            ->whereDate('cancelled_at', '<=', $dateTo)
            ->select('cancellation_reason', DB::raw('COUNT(*) as count'))
            ->groupBy('cancellation_reason')
            ->orderByDesc('count')
            ->get();

        $mostCommonReason = $byReason->first()?->cancellation_reason ?? 'N/A';

        // By resource
        $byResource = Booking::with('resource:id,name')
            ->where('status', BookingStatus::Cancelled)
            ->whereDate('cancelled_at', '>=', $dateFrom)
            ->whereDate('cancelled_at', '<=', $dateTo)
            ->select('resource_id', DB::raw('COUNT(*) as count'))
            ->groupBy('resource_id')
            ->orderByDesc('count')
            ->get();

        // Over time (monthly)
        $overTime = Booking::where('status', BookingStatus::Cancelled)
            ->whereDate('cancelled_at', '>=', $dateFrom)
            ->whereDate('cancelled_at', '<=', $dateTo)
            ->select(DB::raw("DATE_FORMAT(cancelled_at, '%Y-%m-%d') as date"), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Cancellations table
        $cancellations = Booking::with(['customer', 'resource'])
            ->where('status', BookingStatus::Cancelled)
            ->whereDate('cancelled_at', '>=', $dateFrom)
            ->whereDate('cancelled_at', '<=', $dateTo)
            ->orderByDesc('cancelled_at')
            ->limit(100)
            ->get();

        $resources = Resource::orderBy('name')->get(['id', 'name']);

        return view('tenant.reports.cancellations', compact(
            'totalCancellations',
            'cancellationRate',
            'mostCommonReason',
            'byReason',
            'byResource',
            'overTime',
            'cancellations',
            'resources',
            'filters',
            'dateFrom',
            'dateTo',
        ));
    }

    public function sources(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to']);
        $dateFrom = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $filters['date_to'] ?? now()->toDateString();

        $sourceData = Booking::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->select(
                'source',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('source')
            ->orderByDesc('count')
            ->get();

        return view('tenant.reports.sources', compact('sourceData', 'filters', 'dateFrom', 'dateTo'));
    }

    public function export(Request $request): mixed
    {
        $validated = $request->validate([
            'type' => 'required|in:bookings,revenue,customers,cancellations,sources,occupancy',
            'format' => 'required|in:csv,excel,pdf',
        ]);

        $filters = $request->except(['type', 'format']);

        return match ($validated['format']) {
            'excel' => $this->exportService->toExcel($validated['type'], $filters),
            'csv' => $this->exportService->toCsv($validated['type'], $filters),
            'pdf' => $this->exportService->toPdf($validated['type'], $filters),
        };
    }
}
