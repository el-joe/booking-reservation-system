<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Payslip;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayslipDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('staff.name', fn (Payslip $payslip) => $payslip->staff?->name ?? '—')
            ->editColumn('gross_salary', fn (Payslip $payslip) => number_format((float) $payslip->gross_salary, 2))
            ->editColumn('total_deductions', fn (Payslip $payslip) => number_format((float) $payslip->total_deductions, 2))
            ->editColumn('net_salary', fn (Payslip $payslip) => number_format((float) $payslip->net_salary, 2))
            ->editColumn('status', function (Payslip $payslip) {
                $color = match ($payslip->status) {
                    'draft' => 'yellow',
                    'processed' => 'blue',
                    'paid' => 'green',
                    default => 'gray',
                };

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">".ucfirst($payslip->status).'</span>';
            })
            ->editColumn('paid_at', fn (Payslip $payslip) => $payslip->paid_at?->format('Y-m-d') ?? '—')
            ->addColumn('actions', function (Payslip $payslip) {
                $viewUrl = route('tenant.hr.payslips.show', $payslip);
                $downloadUrl = route('tenant.hr.payslips.download', $payslip);

                return "<div class=\"flex gap-2\"><a href=\"{$viewUrl}\" class=\"text-blue-600 hover:text-blue-800 text-sm\">View</a><a href=\"{$downloadUrl}\" class=\"text-indigo-600 hover:text-indigo-800 text-sm\">PDF</a></div>";
            })
            ->rawColumns(['status', 'actions']);
    }

    public function query(Payslip $model): QueryBuilder
    {
        return $model->newQuery()->with('staff');
    }

    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('payslips-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('staff.name')->title('Staff'),
            Column::make('gross_salary')->title('Gross'),
            Column::make('total_deductions')->title('Deductions'),
            Column::make('net_salary')->title('Net'),
            Column::make('status')->title('Status'),
            Column::make('paid_at')->title('Paid At'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Payslips_'.date('YmdHis');
    }
}
