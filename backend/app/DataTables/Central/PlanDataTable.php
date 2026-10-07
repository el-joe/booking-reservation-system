<?php

namespace App\DataTables\Central;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PlanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('price_cycle', function (Plan $plan) {
                return '$'.number_format((float) $plan->price, 2).' / '.$plan->billing_cycle;
            })
            ->addColumn('max_bookings', function (Plan $plan) {
                return $plan->max_bookings === 0 || $plan->max_bookings === null
                    ? 'Unlimited'
                    : $plan->max_bookings;
            })
            ->addColumn('is_active', function (Plan $plan) {
                $badge = $plan->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                return $badge;
            })
            ->addColumn('tenants_count', function (Plan $plan) {
                return $plan->subscriptions_count ?? 0;
            })
            ->addColumn('actions', function (Plan $plan) {
                $edit = route('central.plans.edit', $plan);
                $delete = route('central.plans.destroy', $plan);

                return '<a href="'.$edit.'" class="btn btn-sm btn-primary">Edit</a> '
                    .'<form method="POST" action="'.$delete.'" class="d-inline" onsubmit="return confirm(\'Delete this plan?\')">'
                    .'<input type="hidden" name="_method" value="DELETE">'
                    .'<input type="hidden" name="_token" value="'.csrf_token().'">'
                    .'<button class="btn btn-sm btn-danger">Delete</button>'
                    .'</form>';
            })
            ->rawColumns(['is_active', 'actions'])
            ->setRowId('id');
    }

    public function query(Plan $model): QueryBuilder
    {
        return $model->newQuery()->withCount('subscriptions');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('plans-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('reload'),
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::computed('price_cycle')->title('Price / Cycle'),
            Column::computed('max_bookings')->title('Max Bookings'),
            Column::make('max_resources')->title('Max Resources'),
            Column::computed('is_active')->title('Status'),
            Column::computed('tenants_count')->title('Tenants'),
            Column::computed('actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Plans_'.date('YmdHis');
    }
}
