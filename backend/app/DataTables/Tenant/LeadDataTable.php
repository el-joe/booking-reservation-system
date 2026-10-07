<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LeadDataTable extends DataTable
{
    private const SOURCE_COLORS = [
        'website' => 'blue',
        'referral' => 'purple',
        'walk_in' => 'green',
        'phone' => 'yellow',
        'social' => 'pink',
        'other' => 'gray',
    ];

    private const STATUS_COLORS = [
        'new' => 'blue',
        'contacted' => 'yellow',
        'qualified' => 'purple',
        'converted' => 'green',
        'lost' => 'red',
    ];

    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('type_badge', function (Lead $lead): string {
                if (! $lead->booking_type) {
                    return '<span class="text-gray-400 text-xs">—</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">'.e($lead->booking_type->label()).'</span>';
            })
            ->addColumn('source_badge', function (Lead $lead): string {
                $color = self::SOURCE_COLORS[$lead->source] ?? 'gray';
                $label = ucwords(str_replace('_', ' ', $lead->source));

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2 py-0.5 text-xs font-medium text-{$color}-700\">{$label}</span>";
            })
            ->addColumn('status_badge', function (Lead $lead): string {
                $color = self::STATUS_COLORS[$lead->status] ?? 'gray';
                $label = ucfirst($lead->status);

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2 py-0.5 text-xs font-medium text-{$color}-700\">{$label}</span>";
            })
            ->addColumn('assigned_to_name', fn (Lead $lead) => $lead->assigned_to ?? '—')
            ->addColumn('actions', function (Lead $lead): string {
                $editUrl = route('tenant.leads.edit', $lead);
                $convertUrl = route('tenant.leads.convert', $lead);
                $deleteUrl = route('tenant.leads.destroy', $lead);
                $csrfToken = csrf_token();

                $actions = '<div class="flex items-center gap-2">';
                $actions .= "<a href=\"{$editUrl}\" class=\"text-xs font-medium text-gray-600 hover:text-gray-800\">Edit</a>";

                if ($lead->status !== 'converted') {
                    $actions .= "<form method=\"POST\" action=\"{$convertUrl}\" class=\"inline\">";
                    $actions .= "<input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">";
                    $actions .= '<button type="submit" class="text-xs font-medium text-green-600 hover:text-green-800">Convert</button>';
                    $actions .= '</form>';
                }

                $actions .= "<form method=\"POST\" action=\"{$deleteUrl}\" class=\"inline\">";
                $actions .= "<input type=\"hidden\" name=\"_token\" value=\"{$csrfToken}\">";
                $actions .= '<input type="hidden" name="_method" value="DELETE">';
                $actions .= '<button type="submit" onclick="return confirm(\'Delete this lead?\')" class="text-xs font-medium text-red-600 hover:text-red-800">Delete</button>';
                $actions .= '</form>';

                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['type_badge', 'source_badge', 'status_badge', 'actions']);
    }

    public function query(Lead $model): Builder
    {
        return $model->newQuery()->withTrashed(false);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('leads-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(7, 'desc')
            ->responsive(true)
            ->autoWidth(false);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Phone'),
            Column::computed('type_badge')->title('Type'),
            Column::computed('source_badge')->title('Source'),
            Column::computed('status_badge')->title('Status'),
            Column::computed('assigned_to_name')->title('Assigned To'),
            Column::make('created_at')->title('Created At'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Leads_'.date('YmdHis');
    }
}
