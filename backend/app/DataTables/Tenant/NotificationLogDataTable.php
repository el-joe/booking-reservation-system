<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\NotificationLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NotificationLogDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('channel_badge', function (NotificationLog $log): string {
                $colors = [
                    'email' => 'blue',
                    'sms' => 'green',
                    'push' => 'orange',
                    'whatsapp' => 'emerald',
                    'database' => 'gray',
                ];
                $color = $colors[$log->channel] ?? 'gray';
                $label = ucfirst($log->channel);

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('template_name', fn (NotificationLog $log): string => $log->template?->name ?? '—')
            ->addColumn('status_badge', function (NotificationLog $log): string {
                $config = match ($log->status) {
                    'sent' => ['green', 'Sent'],
                    'failed' => ['red', 'Failed'],
                    default => ['yellow', 'Pending'],
                };

                return "<span class=\"inline-flex items-center rounded-full bg-{$config[0]}-100 px-2.5 py-0.5 text-xs font-medium text-{$config[0]}-800\">{$config[1]}</span>";
            })
            ->addColumn('sent_at_formatted', fn (NotificationLog $log): string => $log->sent_at?->format('d M Y, H:i') ?? '—')
            ->addColumn('error_truncated', fn (NotificationLog $log): string => $log->error_message ? Str::limit($log->error_message, 80) : '—')
            ->rawColumns(['channel_badge', 'status_badge']);
    }

    public function query(NotificationLog $model): Builder
    {
        return $model->newQuery()
            ->with('template')
            ->when(request('channel'), fn ($q) => $q->where('channel', request('channel')))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->when(request('date_from'), fn ($q) => $q->whereDate('created_at', '>=', request('date_from')))
            ->when(request('date_to'), fn ($q) => $q->whereDate('created_at', '<=', request('date_to')))
            ->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('notification-logs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle();
    }

    /** @return array<Column> */
    protected function getColumns(): array
    {
        return [
            Column::make('channel_badge')->title('Channel')->name('channel'),
            Column::make('recipient')->title('Recipient'),
            Column::make('template_name')->title('Template')->orderable(false),
            Column::make('subject')->title('Subject'),
            Column::make('status_badge')->title('Status')->name('status'),
            Column::make('sent_at_formatted')->title('Sent At')->name('sent_at'),
            Column::make('error_truncated')->title('Error')->name('error_message')->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'NotificationLogs_'.date('YmdHis');
    }
}
