<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\PayrollRun;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayrollRunDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('period', fn (PayrollRun $run) => $run->period_label)
            ->editColumn('status', function (PayrollRun $run) {
                $color = match ($run->status) {
                    'draft' => 'yellow',
                    'processed' => 'blue',
                    'paid' => 'green',
                    default => 'gray',
                };

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">".ucfirst($run->status).'</span>';
            })
            ->editColumn('total_gross', fn (PayrollRun $run) => number_format((float) $run->total_gross, 2))
            ->editColumn('total_net', fn (PayrollRun $run) => number_format((float) $run->total_net, 2))
            ->editColumn('processed_at', fn (PayrollRun $run) => $run->processed_at?->format('Y-m-d H:i') ?? '—')
            ->addColumn('actions', function (PayrollRun $run) {
                $viewUrl = route('tenant.hr.payroll.show', $run);
                $markPaidUrl = route('tenant.hr.payroll.mark-paid', $run);
                $csrf = csrf_token();
                $html = "<div class=\"flex gap-2\"><a href=\"{$viewUrl}\" class=\"text-blue-600 hover:text-blue-800 text-sm\">View</a>";
                if ($run->status === 'processed') {
                    $html .= "<form method=\"POST\" action=\"{$markPaidUrl}\" class=\"inline\"><input type=\"hidden\" name=\"_token\" value=\"{$csrf}\"><button type=\"submit\" class=\"text-green-600 hover:text-green-800 text-sm\" onclick=\"return confirm('Mark as paid?')\">Mark Paid</button></form>";
                }
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['status', 'actions']);
    }

    public function query(PayrollRun $model): QueryBuilder
    {
        return $model->newQuery()->orderByDesc('period_year')->orderByDesc('period_month');
    }

    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('payroll-runs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    protected function getColumns(): array
    {
        return [
            Column::computed('period')->title('Period'),
            Column::make('status')->title('Status'),
            Column::make('total_gross')->title('Total Gross'),
            Column::make('total_net')->title('Total Net'),
            Column::make('processed_at')->title('Processed At'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'PayrollRuns_'.date('YmdHis');
    }
}
