<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private readonly array $filters = []) {}

    public function collection(): Collection
    {
        return Customer::withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->orderByDesc('bookings_sum_total_amount')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Total Bookings',
            'Total Spent',
            'Loyalty Points',
            'Created At',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->first_name.' '.$customer->last_name,
            $customer->email ?? '-',
            $customer->phone ?? '-',
            $customer->bookings_count,
            number_format((float) ($customer->bookings_sum_total_amount ?? 0), 2),
            $customer->loyalty_points ?? 0,
            $customer->created_at?->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF7C3AED']],
            ],
        ];
    }
}
