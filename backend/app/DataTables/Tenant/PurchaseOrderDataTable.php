<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PurchaseOrderDataTable extends DataTable
{
    private const STATUS_COLORS = [
        'draft' => 'bg-gray-100 text-gray-700',
        'sent' => 'bg-blue-100 text-blue-700',
        'received' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];

    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('supplier_name', fn (PurchaseOrder $po): string => $po->supplier?->name ?? '-')
            ->addColumn('status_badge', function (PurchaseOrder $po): string {
                $color = self::STATUS_COLORS[$po->status] ?? 'bg-gray-100 text-gray-700';

                return "<span class=\"inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {$color}\">".ucfirst((string) $po->status).'</span>';
            })
            ->addColumn('actions', function (PurchaseOrder $po): string {
                $viewUrl = route('tenant.procurement.purchase-orders.show', $po);
                $editUrl = route('tenant.procurement.purchase-orders.edit', $po);
                $deleteUrl = route('tenant.procurement.purchase-orders.destroy', $po);
                $csrfToken = csrf_token();

                $actions = '<div class="flex items-center gap-2">';
                $actions .= "<a href=\"{$viewUrl}\" class=\"text-xs font-medium text-blue-600 hover:text-blue-800\">View</a>";
                $actions .= "<a href=\"{$editUrl}\" class=\"text-xs font-medium text-gray-600 hover:text-gray-800\">Edit</a>";
                $actions .= "<form method=\"POST\" action=\"{$deleteUrl}\" class=\"inline\">";
                $actions .= "<input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">";
                $actions .= '<input type="hidden" name="_method" value="DELETE">';
                $actions .= '<button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800" onclick="return confirm(\'Delete this PO?\')">Delete</button>';
                $actions .= '</form>';
                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['status_badge', 'actions']);
    }

    public function query(PurchaseOrder $model): Builder
    {
        return $model->newQuery()->with('supplier');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('purchase-orders-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->responsive(true)
            ->autoWidth(false);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('po_number')->title('PO #'),
            Column::computed('supplier_name')->title('Supplier'),
            Column::make('total')->title('Total'),
            Column::computed('status_badge')->title('Status'),
            Column::make('delivery_date')->title('Delivery Date'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'PurchaseOrders_'.date('YmdHis');
    }
}
