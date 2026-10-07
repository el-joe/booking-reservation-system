@extends('layouts.tenant')

@section('title', 'Bookings Report')

@section('content')
    <x-page-header title="Bookings Report" subtitle="Analyze booking data across all dimensions.">
    </x-page-header>

    {{-- Filter Bar --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.reports.bookings') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Date From</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Date To</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Status</label>
                <select name="status[]" multiple
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}"
                            {{ in_array($status->value, (array) ($filters['status'] ?? [])) ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Booking Type</label>
                <select name="booking_type[]" multiple
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach ($bookingTypes as $type)
                        <option value="{{ $type->value }}"
                            {{ in_array($type->value, (array) ($filters['booking_type'] ?? [])) ? 'selected' : '' }}>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Resource</label>
                <select name="resource_id" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Resources</option>
                    @foreach ($resources as $resource)
                        <option value="{{ $resource->id }}" {{ ($filters['resource_id'] ?? '') == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Apply Filters
                </button>
                <a href="{{ route('tenant.reports.bookings') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Bookings</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($summary['total_count']) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Confirmed</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($summary['confirmed_count']) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Cancelled</p>
            <p class="mt-1 text-2xl font-bold text-red-600">{{ number_format($summary['cancelled_count']) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">No Shows</p>
            <p class="mt-1 text-2xl font-bold text-gray-600">{{ number_format($summary['no_show_count']) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Revenue</p>
            <p class="mt-1 text-2xl font-bold text-green-600">${{ number_format($summary['total_revenue'], 2) }}</p>
        </div>
    </div>

    {{-- Export Buttons --}}
    <div class="mb-4 flex items-center justify-end gap-2">
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'bookings', 'format' => 'csv'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
            CSV
        </a>
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'bookings', 'format' => 'excel'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-green-300 bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100">
            Excel
        </a>
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'bookings', 'format' => 'pdf'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-100">
            PDF
        </a>
    </div>

    {{-- Data Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Resource</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Check-in</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $booking->reference_number }}</td>
                            <td class="px-4 py-3 text-gray-800">
                                {{ $booking->customer ? $booking->customer->first_name.' '.$booking->customer->last_name : '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $booking->resource?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $booking->booking_type?->label() ?? $booking->booking_type }}</td>
                            <td class="px-4 py-3">
                                @php $color = $booking->status?->color() ?? 'gray'; @endphp
                                <span class="inline-flex items-center rounded-full bg-{{ $color }}-100 px-2 py-0.5 text-xs font-medium text-{{ $color }}-700">
                                    {{ $booking->status?->label() ?? $booking->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $booking->check_in?->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900">${{ number_format($booking->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No bookings found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
