<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('customer_name', fn (Review $review): string => $review->customer?->full_name ?? '—')
            ->addColumn('resource_name', fn (Review $review): string => $review->resource?->name ?? '—')
            ->addColumn('rating_stars', function (Review $review): string {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $filled = $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300';
                    $stars .= "<svg class=\"inline h-4 w-4 {$filled}\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path d=\"M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z\"/></svg>";
                }

                return $stars;
            })
            ->addColumn('title_truncated', fn (Review $review): string => $review->title ? \Str::limit($review->title, 40) : '—')
            ->addColumn('status_badge', function (Review $review): string {
                $colors = ['pending' => 'yellow', 'published' => 'green', 'rejected' => 'red'];
                $color = $colors[$review->status] ?? 'gray';

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">".ucfirst($review->status).'</span>';
            })
            ->addColumn('reply_status', function (Review $review): string {
                if ($review->reply) {
                    return '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Replied</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Pending</span>';
            })
            ->addColumn('submitted_at', fn (Review $review): string => $review->created_at->format('d M Y'))
            ->addColumn('actions', function (Review $review): string {
                $showUrl = route('tenant.reviews.show', $review);
                $approveUrl = route('tenant.reviews.approve', $review);
                $rejectUrl = route('tenant.reviews.reject', $review);
                $flagUrl = route('tenant.reviews.flag', $review);

                $actions = "<a href=\"{$showUrl}\" class=\"mr-2 text-sm text-blue-600 hover:underline\">View</a>";

                if ($review->status === 'pending') {
                    $actions .= "<form method=\"POST\" action=\"{$approveUrl}\" class=\"inline\">".csrf_field().'<button class="mr-2 text-sm text-green-600 hover:underline">Approve</button></form>';
                    $actions .= "<form method=\"POST\" action=\"{$rejectUrl}\" class=\"inline\">".csrf_field().'<button class="mr-2 text-sm text-red-600 hover:underline">Reject</button></form>';
                }

                if ($review->status !== 'rejected') {
                    $actions .= "<form method=\"POST\" action=\"{$flagUrl}\" class=\"inline\">".csrf_field().'<button class="text-sm text-orange-600 hover:underline">Flag</button></form>';
                }

                return $actions;
            })
            ->rawColumns(['rating_stars', 'status_badge', 'reply_status', 'customer_name', 'actions'])
            ->filter(function (Builder $query): void {
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
            });
    }

    public function query(Review $model): Builder
    {
        return $model->newQuery()
            ->with(['customer', 'resource'])
            ->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reviews-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->responsive(true)
            ->autoWidth(false);
    }

    protected function getColumns(): array
    {
        return [
            Column::computed('customer_name')->title('Customer'),
            Column::computed('resource_name')->title('Resource'),
            Column::computed('rating_stars')->title('Rating'),
            Column::computed('title_truncated')->title('Title'),
            Column::computed('status_badge')->title('Status'),
            Column::computed('submitted_at')->title('Submitted'),
            Column::computed('reply_status')->title('Reply'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Reviews_'.date('YmdHis');
    }
}
