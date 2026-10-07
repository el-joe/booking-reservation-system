<?php

declare(strict_types=1);

namespace App\Services\Tenant\Reports;

use App\Exports\BookingExport;
use App\Exports\CustomerExport;
use App\Exports\RevenueExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    public function __construct(
        private readonly BookingReportService $bookingReportService,
        private readonly RevenueReportService $revenueReportService,
    ) {}

    public function toExcel(string $reportType, array $filters): BinaryFileResponse
    {
        $export = match ($reportType) {
            'revenue' => new RevenueExport($filters),
            'customers' => new CustomerExport($filters),
            default => new BookingExport($filters),
        };

        return Excel::download($export, "{$reportType}-report-".now()->format('Y-m-d').'.xlsx');
    }

    public function toCsv(string $reportType, array $filters): BinaryFileResponse
    {
        $export = match ($reportType) {
            'revenue' => new RevenueExport($filters),
            'customers' => new CustomerExport($filters),
            default => new BookingExport($filters),
        };

        return Excel::download($export, "{$reportType}-report-".now()->format('Y-m-d').'.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function toPdf(string $reportType, array $filters): Response
    {
        $data = match ($reportType) {
            'revenue' => $this->revenueReportService->generate($filters),
            'bookings' => [
                'bookings' => $this->bookingReportService->generate($filters),
                'summary' => $this->bookingReportService->getSummary($filters),
            ],
            default => [],
        };

        $view = "tenant.reports.pdf.{$reportType}";
        $fallbackView = 'tenant.reports.pdf.generic';

        $pdfView = view()->exists($view) ? $view : $fallbackView;

        $pdf = Pdf::loadView($pdfView, array_merge($data, ['report_type' => $reportType, 'filters' => $filters]));

        return $pdf->download("{$reportType}-report-".now()->format('Y-m-d').'.pdf');
    }
}
