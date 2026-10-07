<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ResourceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('name', function (Resource $resource) {
                $url = route('tenant.resources.show', $resource);

                return "<a href=\"{$url}\" class=\"font-medium text-blue-600 hover:text-blue-800\">{$resource->name}</a>";
            })
            ->editColumn('resource_type', function (Resource $resource) {
                $color = $resource->resource_type?->color() ?? 'gray';
                $label = $resource->resource_type?->label() ?? '—';

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">{$label}</span>";
            })
            ->editColumn('booking_type', function (Resource $resource) {
                return $resource->booking_type?->label() ?? '—';
            })
            ->editColumn('base_price', function (Resource $resource) {
                return '$'.number_format((float) $resource->base_price, 2);
            })
            ->editColumn('status', function (Resource $resource) {
                $color = $resource->status?->color() ?? 'gray';
                $label = $resource->status?->label() ?? 'Unknown';

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('media_count', function (Resource $resource) {
                return $resource->media_count ?? 0;
            })
            ->addColumn('actions', function (Resource $resource) {
                $viewUrl = route('tenant.resources.show', $resource);
                $editUrl = route('tenant.resources.edit', $resource);
                $calendarUrl = route('tenant.resources.availability', $resource);
                $pricingUrl = route('tenant.resources.pricing', $resource);
                $deleteUrl = route('tenant.resources.destroy', $resource);
                $csrfToken = csrf_token();

                return "<div class=\"flex gap-2 items-center\">
                    <a href=\"{$viewUrl}\" class=\"text-blue-600 hover:text-blue-800 text-sm\">View</a>
                    <a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-800 text-sm\">Edit</a>
                    <a href=\"{$calendarUrl}\" class=\"text-green-600 hover:text-green-800 text-sm\">Calendar</a>
                    <a href=\"{$pricingUrl}\" class=\"text-yellow-600 hover:text-yellow-800 text-sm\">Pricing</a>
                    <form method=\"POST\" action=\"{$deleteUrl}\" class=\"inline\" onsubmit=\"return confirm('Delete this resource?')\">
                        <input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">
                        <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                        <button type=\"submit\" class=\"text-red-600 hover:text-red-800 text-sm\">Delete</button>
                    </form>
                </div>";
            })
            ->rawColumns(['name', 'resource_type', 'status', 'actions']);
    }

    public function query(Resource $model): QueryBuilder
    {
        return $model->newQuery()->withCount('media');
    }

    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('resources-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('reload'),
            ]);
    }

    /** @return array<int, Column> */
    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::make('name')->title('Name'),
            Column::make('resource_type')->title('Type'),
            Column::make('booking_type')->title('Booking Type'),
            Column::make('capacity')->title('Capacity')->width(90),
            Column::make('base_price')->title('Base Price')->width(110),
            Column::make('status')->title('Status')->width(100),
            Column::make('media_count')->title('Media')->width(80)->orderable(false),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false)->width(220),
        ];
    }

    protected function filename(): string
    {
        return 'Resources_'.date('YmdHis');
    }
}
