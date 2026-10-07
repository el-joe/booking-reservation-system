<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('total_bookings', fn (Customer $customer) => $customer->bookings_count)
            ->addColumn('total_spent', fn (Customer $customer) => '$'.number_format((float) ($customer->bookings_sum_paid_amount ?? 0), 2))
            ->addColumn('status', function (Customer $customer): string {
                if ($customer->is_blacklisted) {
                    return '<span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Blacklisted</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>';
            })
            ->addColumn('actions', function (Customer $customer): string {
                $viewUrl = route('tenant.customers.show', $customer);
                $editUrl = route('tenant.customers.edit', $customer);
                $blacklistUrl = route('tenant.customers.blacklist', $customer);
                $removeBlacklistUrl = route('tenant.customers.remove-blacklist', $customer);
                $csrfToken = csrf_token();

                $actions = '<div class="flex items-center gap-2">';
                $actions .= "<a href=\"{$viewUrl}\" class=\"text-xs font-medium text-blue-600 hover:text-blue-800\">View</a>";
                $actions .= "<a href=\"{$editUrl}\" class=\"text-xs font-medium text-gray-600 hover:text-gray-800\">Edit</a>";

                if ($customer->is_blacklisted) {
                    $actions .= "<form method=\"POST\" action=\"{$removeBlacklistUrl}\" class=\"inline\">";
                    $actions .= "<input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">";
                    $actions .= '<button type="submit" class="text-xs font-medium text-green-600 hover:text-green-800">Unblacklist</button>';
                    $actions .= '</form>';
                } else {
                    $actions .= "<a href=\"{$blacklistUrl}\" class=\"text-xs font-medium text-red-600 hover:text-red-800\" onclick=\"return confirm('Blacklist this customer?')\">Blacklist</a>";
                }

                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['status', 'actions'])
            ->filterColumn('name', function (Builder $query, string $keyword): void {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
    }

    public function query(Customer $model): Builder
    {
        return $model->newQuery()
            ->withCount('bookings')
            ->withSum('bookings', 'paid_amount');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customers-table')
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
            Column::computed('total_bookings')->title('Bookings'),
            Column::computed('total_spent')->title('Total Spent'),
            Column::make('loyalty_points')->title('Loyalty Pts'),
            Column::computed('status')->title('Status'),
            Column::make('created_at')->title('Created At'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Customers_'.date('YmdHis');
    }
}
