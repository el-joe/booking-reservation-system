<?php

declare(strict_types=1);

namespace App\Exports;

use App\Services\Tenant\Reports\BookingReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private readonly array $filters = []) {}

    public function collection(): Collection
    {
        return app(BookingReportService::class)->generate($this->filters);
    }

    public function headings(): array
    {
        return [
            'Reference',
            'Customer',
            'Resource',
            'Type',
            'Status',
            'Check-in',
            'Check-out',
            'Guests',
            'Total Amount',
            'Paid Amount',
            'Source',
            'Created At',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->reference_number,
            $booking->customer ? $booking->customer->first_name.' '.$booking->customer->last_name : '-',
            $booking->resource?->name ?? '-',
            $booking->booking_type?->label() ?? $booking->booking_type,
            $booking->status?->label() ?? $booking->status,
            $booking->check_in?->format('Y-m-d H:i'),
            $booking->check_out?->format('Y-m-d H:i'),
            $booking->guests_count ?? 1,
            number_format((float) $booking->total_amount, 2),
            number_format((float) $booking->paid_amount, 2),
            $booking->source ?? '-',
            $booking->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1D4ED8']],
            ],
        ];
    }
}
