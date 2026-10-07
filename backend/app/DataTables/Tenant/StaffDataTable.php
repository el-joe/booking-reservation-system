<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('name', function (Staff $staff) {
                $url = route('tenant.staff.show', $staff);

                return "<a href=\"{$url}\" class=\"font-medium text-blue-600 hover:underline\">{$staff->name}</a>";
            })
            ->editColumn('employment_type', function (Staff $staff) {
                $label = match ($staff->employment_type) {
                    'full_time' => 'Full Time',
                    'part_time' => 'Part Time',
                    'contract' => 'Contract',
                    default => $staff->employment_type,
                };
                $color = match ($staff->employment_type) {
                    'full_time' => 'blue',
                    'part_time' => 'purple',
                    'contract' => 'orange',
                    default => 'gray',
                };

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">{$label}</span>";
            })
            ->editColumn('status', function (Staff $staff) {
                $color = $staff->status === 'active' ? 'green' : 'red';
                $label = ucfirst($staff->status);

                return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">{$label}</span>";
            })
            ->editColumn('hire_date', function (Staff $staff) {
                return $staff->hire_date?->format('Y-m-d') ?? '—';
            })
            ->addColumn('actions', function (Staff $staff) {
                $viewUrl = route('tenant.staff.show', $staff);
                $editUrl = route('tenant.staff.edit', $staff);
                $scheduleUrl = route('tenant.staff.schedule', $staff);
                $leaveUrl = route('tenant.leave.index').'?staff_id='.$staff->id;
                $deleteUrl = route('tenant.staff.destroy', $staff);
                $csrfToken = csrf_token();

                return "<div class=\"flex gap-2 items-center flex-wrap\">
                    <a href=\"{$viewUrl}\" class=\"text-blue-600 hover:text-blue-800 text-sm\">View</a>
                    <a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-800 text-sm\">Edit</a>
                    <a href=\"{$scheduleUrl}\" class=\"text-teal-600 hover:text-teal-800 text-sm\">Schedule</a>
                    <a href=\"{$leaveUrl}\" class=\"text-yellow-600 hover:text-yellow-800 text-sm\">Leave</a>
                    <form method=\"POST\" action=\"{$deleteUrl}\" class=\"inline\" onsubmit=\"return confirm('Delete this staff member?')\">
                        <input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">
                        <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                        <button type=\"submit\" class=\"text-red-600 hover:text-red-800 text-sm\">Delete</button>
                    </form>
                </div>";
            })
            ->rawColumns(['name', 'employment_type', 'status', 'actions']);
    }

    public function query(Staff $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('staff-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('role')->title('Role'),
            Column::make('department')->title('Department'),
            Column::make('employment_type')->title('Employment Type'),
            Column::make('status')->title('Status'),
            Column::make('hire_date')->title('Hire Date'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Staff_'.date('YmdHis');
    }
}
