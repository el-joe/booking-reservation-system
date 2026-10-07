<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JournalEntryDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('reference_link', function (JournalEntry $entry): string {
                $url = route('tenant.accounting.journal.show', $entry);

                return "<a href=\"{$url}\" class=\"font-medium text-blue-600 hover:underline\">{$entry->reference}</a>";
            })
            ->addColumn('date_formatted', fn (JournalEntry $entry): string => $entry->date->format('d M Y'))
            ->addColumn('source_label', fn (JournalEntry $entry): string => $entry->source_type
                ? class_basename($entry->source_type).' #'.$entry->source_id
                : 'Manual')
            ->addColumn('debit_total', function (JournalEntry $entry): string {
                $total = $entry->lines->sum('debit');

                return '$'.number_format((float) $total, 2);
            })
            ->addColumn('credit_total', function (JournalEntry $entry): string {
                $total = $entry->lines->sum('credit');

                return '$'.number_format((float) $total, 2);
            })
            ->addColumn('actions', function (JournalEntry $entry): string {
                $showUrl = route('tenant.accounting.journal.show', $entry);
                $voidUrl = route('tenant.accounting.journal.void', $entry);
                $actions = "<a href=\"{$showUrl}\" class=\"mr-2 text-sm text-blue-600 hover:underline\">View</a>";
                if (! str_starts_with($entry->reference, 'VOID-')) {
                    $actions .= "<form method=\"POST\" action=\"{$voidUrl}\" class=\"inline\">".csrf_field().'<button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm(\'Void this entry?\')">Void</button></form>';
                }

                return $actions;
            })
            ->rawColumns(['reference_link', 'actions'])
            ->withQuery(fn ($query) => $query->with('lines'));
    }

    public function query(JournalEntry $model): Builder
    {
        return $model->newQuery()->with('lines')->latest('date');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('journal-entries-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('reference_link')->title('Reference')->orderable(false),
            Column::make('date_formatted')->title('Date')->orderable(false),
            Column::make('description')->title('Description'),
            Column::make('source_label')->title('Source')->orderable(false),
            Column::make('debit_total')->title('Debit Total')->orderable(false),
            Column::make('credit_total')->title('Credit Total')->orderable(false),
            Column::make('actions')->title('Actions')->orderable(false)->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'JournalEntries_'.date('YmdHis');
    }
}
