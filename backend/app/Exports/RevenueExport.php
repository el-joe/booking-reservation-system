<?php

declare(strict_types=1);

namespace App\Exports;

use App\Services\Tenant\Reports\RevenueReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private readonly array $filters = []) {}

    public function collection(): Collection
    {
        $service = app(RevenueReportService::class);
        $byPeriod = $service->getByPeriod($this->filters, $this->filters['group_by'] ?? 'day');

        $rows = collect();
        foreach ($byPeriod['labels'] as $i => $label) {
            $rows->push([
                'period' => $label,
                'revenue' => $byPeriod['data'][$i] ?? 0,
                'count' => $byPeriod['counts'][$i] ?? 0,
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Period', 'Revenue', 'Booking Count'];
    }

    public function map($row): array
    {
        return [
            $row['period'],
            number_format((float) $row['revenue'], 2),
            $row['count'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF059669']],
            ],
        ];
    }
}
