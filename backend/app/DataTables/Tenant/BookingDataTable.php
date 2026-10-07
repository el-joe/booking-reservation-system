<?php

declare(strict_types=1);

namespace App\DataTables\Tenant;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BookingDataTable extends DataTable
{
    public function dataTable(mixed $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('reference_link', function (Booking $booking): string {
                $url = route('tenant.bookings.show', $booking);

                return "<a href=\"{$url}\" class=\"font-medium text-blue-600 hover:underline\">{$booking->reference_number}</a>";
            })
            ->addColumn('customer_name', fn (Booking $booking): string => $booking->customer?->full_name ?? '—')
            ->addColumn('resource_name', fn (Booking $booking): string => $booking->resource?->name ?? '—')
            ->addColumn('type_badge', function (Booking $booking): string {
                $label = $booking->booking_type->label();
                $color = $booking->booking_type->color();

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('status_badge', function (Booking $booking): string {
                $label = $booking->status->label();
                $color = $booking->status->color();

                return "<span class=\"inline-flex items-center rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800\">{$label}</span>";
            })
            ->addColumn('check_in_formatted', fn (Booking $booking): string => $booking->check_in->format('d M Y, H:i'))
            ->addColumn('check_out_formatted', fn (Booking $booking): string => $booking->check_out->format('d M Y, H:i'))
            ->addColumn('total_formatted', fn (Booking $booking): string => '$'.number_format((float) $booking->total_amount, 2))
            ->addColumn('actions', function (Booking $booking): string {
                $showUrl = route('tenant.bookings.show', $booking);
                $editUrl = route('tenant.bookings.edit', $booking);
                $cancelUrl = route('tenant.bookings.cancel', $booking);
                $confirmUrl = route('tenant.bookings.confirm', $booking);
                $checkinUrl = route('tenant.bookings.checkin', $booking);

                $actions = "<a href=\"{$showUrl}\" class=\"mr-2 text-sm text-blue-600 hover:underline\">View</a>";
                $actions .= "<a href=\"{$editUrl}\" class=\"mr-2 text-sm text-gray-600 hover:underline\">Edit</a>";

                if ($booking->status === BookingStatus::Pending) {
                    $actions .= "<form method=\"POST\" action=\"{$confirmUrl}\" class=\"inline\">".csrf_field().'<button class="mr-2 text-sm text-green-600 hover:underline">Confirm</button></form>';
                }

                if ($booking->status === BookingStatus::Confirmed) {
                    $actions .= "<form method=\"POST\" action=\"{$checkinUrl}\" class=\"inline\">".csrf_field().'<button class="mr-2 text-sm text-indigo-600 hover:underline">Check-in</button></form>';
                }

                if (! in_array($booking->status, [BookingStatus::Cancelled, BookingStatus::Completed, BookingStatus::NoShow], true)) {
                    $actions .= "<button onclick=\"cancelBooking({$booking->id})\" class=\"text-sm text-red-600 hover:underline\">Cancel</button>";
                }

                return $actions;
            })
            ->filter(function (Builder $query): void {
                if (request()->filled('status')) {
                    $query->where('status', request('status'));
                }
                if (request()->filled('booking_type')) {
                    $query->where('booking_type', request('booking_type'));
                }
                if (request()->filled('date_from')) {
                    $query->whereDate('check_in', '>=', request('date_from'));
                }
                if (request()->filled('date_to')) {
                    $query->whereDate('check_in', '<=', request('date_to'));
                }
                if (request()->filled('search_value')) {
                    $search = request('search_value');
                    $query->where(function (Builder $q) use ($search): void {
                        $q->where('reference_number', 'like', "%{$search}%")
                            ->orWhereHas('customer', fn (Builder $cq) => $cq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%"));
                    });
                }
            })
            ->rawColumns(['reference_link', 'type_badge', 'status_badge', 'actions']);
    }

    public function query(Booking $model): Builder
    {
        return $model->newQuery()->with(['customer', 'resource']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('bookings-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->responsive(true)
            ->autoWidth(false);
    }

    /**
     * @return Column[]
     */
    protected function getColumns(): array
    {
        return [
            Column::make('reference_link')->title('Reference')->name('reference_number'),
            Column::make('customer_name')->title('Customer')->name('customer.first_name'),
            Column::make('resource_name')->title('Resource')->name('resource.name'),
            Column::make('type_badge')->title('Type')->name('booking_type'),
            Column::make('status_badge')->title('Status')->name('status'),
            Column::make('check_in_formatted')->title('Check-in')->name('check_in'),
            Column::make('check_out_formatted')->title('Check-out')->name('check_out'),
            Column::make('total_formatted')->title('Total')->name('total_amount'),
            Column::computed('actions')->title('Actions')->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Bookings_'.date('YmdHis');
    }
}
