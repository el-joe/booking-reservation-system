@extends('layouts.tenant')

@section('title', 'Cancellations Report')

@section('content')
    <x-page-header title="Cancellations Report" subtitle="Analyze cancellation trends, reasons, and impacted resources." />

    {{-- Filters --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.reports.cancellations') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Date From</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? $dateFrom }}"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Date To</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? $dateTo }}"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
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
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Apply</button>
                <a href="{{ route('tenant.reports.cancellations') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-3 gap-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Cancellations</p>
            <p class="mt-1 text-2xl font-bold text-red-600">{{ number_format($totalCancellations) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Cancellation Rate</p>
            <p class="mt-1 text-2xl font-bold text-orange-600">{{ $cancellationRate }}%</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Most Common Reason</p>
            <p class="mt-1 truncate text-xl font-bold text-gray-800">{{ $mostCommonReason ?: 'N/A' }}</p>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Over time --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 lg:col-span-2">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Cancellations Over Time</h3>
            <div id="cancelLineChart"></div>
        </div>
        {{-- By reason pie --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">By Reason</h3>
            <div id="cancelReasonChart"></div>
        </div>
    </div>

    {{-- By resource bar + table --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">By Resource</h3>
            <div id="cancelResourceChart"></div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Recent Cancellations</h3>
                <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'cancellations', 'format' => 'csv'])) }}"
                    class="text-xs text-blue-600 hover:underline">Export CSV</a>
            </div>
            <div class="overflow-y-auto" style="max-height: 300px;">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-2 text-left text-xs font-medium text-gray-500">Ref</th>
                            <th class="pb-2 text-left text-xs font-medium text-gray-500">Customer</th>
                            <th class="pb-2 text-left text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($cancellations as $c)
                            <tr>
                                <td class="py-1.5 font-mono text-xs text-gray-600">{{ $c->reference_number }}</td>
                                <td class="py-1.5 text-gray-800">{{ $c->customer ? $c->customer->first_name.' '.$c->customer->last_name : '-' }}</td>
                                <td class="py-1.5 text-gray-600">{{ $c->cancelled_at?->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-400">No cancellations found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
<script>
const timeLabels = @json($overTime->pluck('date'));
const timeCounts = @json($overTime->pluck('count'));
const reasonLabels = @json($byReason->pluck('cancellation_reason')->map(fn($r) => $r ?: 'Unknown'));
const reasonCounts = @json($byReason->pluck('count'));
const resourceNames = @json($byResource->map(fn($r) => $r->resource?->name ?? 'Unknown'));
const resourceCounts = @json($byResource->pluck('count'));

new ApexCharts(document.querySelector('#cancelLineChart'), {
    chart: { type: 'line', height: 240, toolbar: { show: false } },
    series: [{ name: 'Cancellations', data: timeCounts }],
    xaxis: { categories: timeLabels, labels: { rotate: -45 } },
    colors: ['#dc2626'],
    stroke: { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
}).render();

new ApexCharts(document.querySelector('#cancelReasonChart'), {
    chart: { type: 'pie', height: 240 },
    series: reasonCounts,
    labels: reasonLabels,
    legend: { position: 'bottom' },
}).render();

new ApexCharts(document.querySelector('#cancelResourceChart'), {
    chart: { type: 'bar', height: 240, toolbar: { show: false } },
    series: [{ name: 'Cancellations', data: resourceCounts }],
    xaxis: { categories: resourceNames },
    colors: ['#f97316'],
    dataLabels: { enabled: false },
    plotOptions: { bar: { borderRadius: 4 } },
}).render();
</script>
@endpush
