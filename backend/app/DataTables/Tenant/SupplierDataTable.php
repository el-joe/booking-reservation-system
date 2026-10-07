<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('is_active', function (Supplier $supplier): string {
                if ($supplier->is_active) {
                    return '<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inactive</span>';
            })
            ->addColumn('po_count', fn (Supplier $supplier): int => $supplier->purchase_orders_count)
            ->addColumn('actions', function (Supplier $supplier): string {
                $viewUrl = route('tenant.procurement.suppliers.show', $supplier);
                $editUrl = route('tenant.procurement.suppliers.edit', $supplier);
                $deleteUrl = route('tenant.procurement.suppliers.destroy', $supplier);
                $csrfToken = csrf_token();

                $actions = '<div class="flex items-center gap-2">';
                $actions .= "<a href=\"{$viewUrl}\" class=\"text-xs font-medium text-blue-600 hover:text-blue-800\">View</a>";
                $actions .= "<a href=\"{$editUrl}\" class=\"text-xs font-medium text-gray-600 hover:text-gray-800\">Edit</a>";
                $actions .= "<form method=\"POST\" action=\"{$deleteUrl}\" class=\"inline\">";
                $actions .= "<input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">";
                $actions .= '<input type="hidden" name="_method" value="DELETE">';
                $actions .= '<button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800" onclick="return confirm(\'Delete this supplier?\')">Delete</button>';
                $actions .= '</form>';
                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['is_active', 'actions'])
            ->filterColumn('name', fn (Builder $query, string $keyword) => $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%"));
    }

    public function query(Supplier $model): Builder
    {
        return $model->newQuery()->withCount('purchaseOrders');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('suppliers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->responsive(true)
            ->autoWidth(false);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Phone'),
            Column::computed('is_active')->title('Status'),
            Column::computed('po_count')->title('PO Count'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Suppliers_'.date('YmdHis');
    }
}
