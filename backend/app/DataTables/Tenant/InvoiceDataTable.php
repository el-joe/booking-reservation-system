<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('invoice_link', function (Invoice $invoice): string {
                $url = route('tenant.invoices.show', $invoice);

                return "<a href=\"{$url}\" class=\"font-medium text-blue-600 hover:underline\">{$invoice->invoice_number}</a>";
            })
            ->addColumn('customer_name', fn (Invoice $invoice): string => $invoice->customer?->full_name ?? '—')
            ->addColumn('booking_ref', fn (Invoice $invoice): string => $invoice->booking?->reference_number ?? '—')
            ->addColumn('total_formatted', fn (Invoice $invoice): string => '$'.number_format((float) $invoice->total, 2))
            ->addColumn('status_badge', function (Invoice $invoice): string {
                $label = $invoice->status->label();
                $color = $invoice->status->color();

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('due_date_formatted', fn (Invoice $invoice): string => $invoice->due_date ? $invoice->due_date->format('d M Y') : '—')
            ->addColumn('paid_at_formatted', fn (Invoice $invoice): string => $invoice->paid_at ? $invoice->paid_at->format('d M Y') : '—')
            ->addColumn('actions', function (Invoice $invoice): string {
                $showUrl = route('tenant.invoices.show', $invoice);
                $downloadUrl = route('tenant.invoices.download', $invoice);
                $sendUrl = route('tenant.invoices.send', $invoice);
                $markPaidUrl = route('tenant.invoices.mark-paid', $invoice);

                $actions = "<a href=\"{$showUrl}\" class=\"mr-2 text-sm text-blue-600 hover:underline\">View</a>";
                $actions .= "<a href=\"{$downloadUrl}\" class=\"mr-2 text-sm text-gray-600 hover:underline\">PDF</a>";
                $actions .= "<form method=\"POST\" action=\"{$sendUrl}\" class=\"inline\">".csrf_field().'<button class="mr-2 text-sm text-indigo-600 hover:underline">Send</button></form>';

                if ($invoice->status->value !== 'paid') {
                    $actions .= "<form method=\"POST\" action=\"{$markPaidUrl}\" class=\"inline\">".csrf_field().'<button class="text-sm text-green-600 hover:underline">Mark Paid</button></form>';
                }

                return $actions;
            })
            ->rawColumns(['invoice_link', 'status_badge', 'actions'])
            ->filter(function (Builder $query): void {
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                if (request()->filled('date_from')) {
                    $query->whereDate('created_at', '>=', request('date_from'));
                }
                if (request()->filled('date_to')) {
                    $query->whereDate('created_at', '<=', request('date_to'));
                }
            });
    }

    public function query(Invoice $model): Builder
    {
        return $model->newQuery()->with(['customer', 'booking']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoices-table')
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
            Column::make('invoice_link')->title('Invoice #')->name('invoice_number'),
            Column::make('customer_name')->title('Customer')->name('customer.first_name'),
            Column::make('booking_ref')->title('Booking Ref')->name('booking.reference_number'),
            Column::make('total_formatted')->title('Total')->name('total'),
            Column::make('status_badge')->title('Status')->name('status'),
            Column::make('due_date_formatted')->title('Due Date')->name('due_date'),
            Column::make('paid_at_formatted')->title('Paid At')->name('paid_at'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Invoices_'.date('YmdHis');
    }
}
