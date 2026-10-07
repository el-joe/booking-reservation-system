<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('customer_name', fn (Transaction $t): string => $t->customer?->full_name ?? '—')
            ->addColumn('booking_ref', fn (Transaction $t): string => $t->booking?->reference_number ?? '—')
            ->addColumn('type_badge', function (Transaction $t): string {
                $label = $t->type->label();
                $color = $t->type->color();

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('amount_formatted', fn (Transaction $t): string => number_format((float) $t->amount, 2).' '.$t->currency)
            ->addColumn('status_badge', function (Transaction $t): string {
                $label = $t->status->label();
                $color = $t->status->color();

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('date_formatted', fn (Transaction $t): string => $t->created_at->format('d M Y, H:i'))
            ->addColumn('actions', function (Transaction $t): string {
                $showUrl = route('tenant.transactions.show', $t);

                return "<a href=\"{$showUrl}\" class=\"text-sm text-blue-600 hover:underline\">View</a>";
            })
            ->filter(function (Builder $query): void {
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                if (request()->filled('type')) {
                    $query->where('type', request('type'));
                }
                if (request()->filled('date_from')) {
                    $query->whereDate('created_at', '>=', request('date_from'));
                }
                if (request()->filled('date_to')) {
                    $query->whereDate('created_at', '<=', request('date_to'));
                }
                if (request()->filled('customer_id')) {
                    $query->where('customer_id', request('customer_id'));
                }
            })
            ->rawColumns(['type_badge', 'status_badge', 'actions']);
    }

    public function query(Transaction $model): Builder
    {
        return $model->newQuery()->with(['customer', 'booking']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('transactions-table')
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
            Column::make('customer_name')->title('Customer')->name('customer.first_name'),
            Column::make('booking_ref')->title('Booking Ref')->name('booking.reference_number'),
            Column::make('type_badge')->title('Type')->name('type'),
            Column::make('amount_formatted')->title('Amount')->name('amount'),
            Column::make('currency')->title('Currency'),
            Column::make('gateway')->title('Gateway'),
            Column::make('status_badge')->title('Status')->name('status'),
            Column::make('date_formatted')->title('Date')->name('created_at'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Transactions_'.date('YmdHis');
    }
}
