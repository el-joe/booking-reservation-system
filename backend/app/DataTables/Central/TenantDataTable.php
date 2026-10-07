<?php

declare(strict_types=1);

namespace App\DataTables\Central;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenantDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('status', function (Tenant $tenant) {
                $color = match ($tenant->status?->value) {
                    'active' => 'green',
                    'suspended' => 'red',
                    'trial' => 'yellow',
                    default => 'gray',
                };
                $label = $tenant->status?->label() ?? 'Unknown';

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">{$label}</span>";
            })
            ->editColumn('plan', function (Tenant $tenant) {
                return $tenant->subscription?->plan?->name ?? '—';
            })
            ->editColumn('domain', function (Tenant $tenant) {
                $domain = $tenant->domains()->first()?->domain ?? '—';

                return "<a href=\"http://{$domain}\" target=\"_blank\" class=\"text-blue-600 hover:underline\">{$domain}</a>";
            })
            ->addColumn('bookings_count', function () {
                return 0;
            })
            ->editColumn('created_at', function (Tenant $tenant) {
                return $tenant->created_at?->format('Y-m-d');
            })
            ->addColumn('actions', function (Tenant $tenant) {
                $viewUrl = route('central.tenants.show', $tenant);
                $editUrl = route('central.tenants.edit', $tenant);
                $suspendUrl = route('central.tenants.suspend', $tenant);
                $reactivateUrl = route('central.tenants.reactivate', $tenant);
                $deleteUrl = route('central.tenants.destroy', $tenant);
                $csrfToken = csrf_token();

                $statusAction = $tenant->isSuspended()
                    ? "<form method=\"POST\" action=\"{$reactivateUrl}\" class=\"inline\">
                        <input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">
                        <button type=\"submit\" class=\"text-green-600 hover:text-green-800 text-sm\">Activate</button>
                       </form>"
                    : "<form method=\"POST\" action=\"{$suspendUrl}\" class=\"inline\">
                        <input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">
                        <button type=\"submit\" class=\"text-yellow-600 hover:text-yellow-800 text-sm\">Suspend</button>
                       </form>";

                return "<div class=\"flex gap-2 items-center\">
                    <a href=\"{$viewUrl}\" class=\"text-blue-600 hover:text-blue-800 text-sm\">View</a>
                    <a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-800 text-sm\">Edit</a>
                    {$statusAction}
                    <form method=\"POST\" action=\"{$deleteUrl}\" class=\"inline\" onsubmit=\"return confirm('Delete this tenant?')\">
                        <input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">
                        <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                        <button type=\"submit\" class=\"text-red-600 hover:text-red-800 text-sm\">Delete</button>
                    </form>
                </div>";
            })
            ->rawColumns(['status', 'domain', 'actions']);
    }

    public function query(Tenant $model): QueryBuilder
    {
        return $model->newQuery()->with('subscription.plan');
    }

    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('tenants-table')
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
            Column::make('id')->title('ID')->width(80),
            Column::make('name')->title('Name'),
            Column::make('domain')->title('Domain'),
            Column::make('plan')->title('Plan')->orderable(false),
            Column::make('status')->title('Status'),
            Column::make('bookings_count')->title('Bookings')->orderable(false),
            Column::make('created_at')->title('Created'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false)->width(160),
        ];
    }

    protected function filename(): string
    {
        return 'Tenants_'.date('YmdHis');
    }
}
