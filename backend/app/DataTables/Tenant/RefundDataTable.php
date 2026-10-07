<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Refund;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RefundDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('transaction_link', function (Refund $refund): string {
                if (! $refund->transaction) {
                    return '—';
                }
                $url = route('tenant.transactions.show', $refund->transaction);

                return "<a href=\"{$url}\" class=\"text-sm text-blue-600 hover:underline\">#".$refund->transaction_id.'</a>';
            })
            ->addColumn('amount_formatted', fn (Refund $refund): string => '$'.number_format((float) $refund->amount, 2))
            ->addColumn('status_badge', function (Refund $refund): string {
                $color = match ($refund->status) {
                    'processed' => 'green',
                    'failed' => 'red',
                    default => 'yellow',
                };
                $label = ucfirst($refund->status);

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('processed_at_formatted', fn (Refund $refund): string => $refund->processed_at ? $refund->processed_at->format('d M Y, H:i') : '—')
            ->addColumn('actions', fn (Refund $refund): string => '')
            ->rawColumns(['transaction_link', 'status_badge', 'actions']);
    }

    public function query(Refund $model): Builder
    {
        return $model->newQuery()->with(['transaction']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('refunds-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->responsive(true)
            ->autoWidth(false);
    }

    /**
     * @return Column[]
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('transaction_link')->title('Transaction')->name('transaction_id'),
            Column::make('amount_formatted')->title('Amount')->name('amount'),
            Column::make('reason')->title('Reason'),
            Column::make('status_badge')->title('Status')->name('status'),
            Column::make('processed_at_formatted')->title('Processed At')->name('processed_at'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Refunds_'.date('YmdHis');
    }
}
