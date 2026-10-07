@extends('layouts.tenant')

@section('title', 'Revenue Report')

@section('content')
    <x-page-header title="Revenue Report" subtitle="Track income trends, breakdowns, and period comparisons." />

    {{-- Filter Bar --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.reports.revenue') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
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
                <label class="mb-1 block text-xs font-medium text-gray-700">Group By</label>
                <select name="group_by" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="day" {{ ($filters['group_by'] ?? 'day') === 'day' ? 'selected' : '' }}>Daily</option>
                    <option value="week" {{ ($filters['group_by'] ?? '') === 'week' ? 'selected' : '' }}>Weekly</option>
                    <option value="month" {{ ($filters['group_by'] ?? '') === 'month' ? 'selected' : '' }}>Monthly</option>
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
                    Apply
                </button>
                <a href="{{ route('tenant.reports.revenue') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Revenue</p>
            <p class="mt-1 text-2xl font-bold text-green-600">${{ number_format($data['total_revenue'], 2) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Avg per Booking</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">${{ number_format($data['average_per_booking'], 2) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Best Period Revenue</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">${{ number_format($bestDayRevenue, 2) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Period Growth</p>
            @php $growth = $data['period_comparison']['growth_percent']; @endphp
            <p class="mt-1 text-2xl font-bold {{ $growth === null ? 'text-gray-400' : ($growth >= 0 ? 'text-green-600' : 'text-red-600') }}">
                {{ $growth === null ? 'N/A' : ($growth >= 0 ? '+' : '').$growth.'%' }}
            </p>
        </div>
    </div>

    {{-- Export Buttons --}}
    <div class="mb-4 flex items-center justify-end gap-2">
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'revenue', 'format' => 'csv'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">CSV</a>
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'revenue', 'format' => 'excel'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-green-300 bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100">Excel</a>
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'revenue', 'format' => 'pdf'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-100">PDF</a>
    </div>

    {{-- Charts --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Revenue over time --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 lg:col-span-2">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Revenue Over Time</h3>
            <div id="revenueLineChart"></div>
        </div>
        {{-- By Booking Type donut --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Revenue by Booking Type</h3>
            <div id="revenueDonutChart"></div>
        </div>
    </div>

    {{-- Revenue by Resource --}}
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <h3 class="mb-3 text-sm font-semibold text-gray-900">Revenue by Resource</h3>
        <div id="revenueBarChart"></div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
<script>
const periodLabels = @json($data['by_period']['labels'] ?? []);
const periodData = @json($data['by_period']['data'] ?? []);
const resourceLabels = @json(array_keys($data['by_resource']));
const resourceData = @json(array_values($data['by_resource']));
const typeLabels = @json(array_keys($data['by_booking_type']));
const typeData = @json(array_values($data['by_booking_type']));

new ApexCharts(document.querySelector('#revenueLineChart'), {
    chart: { type: 'area', height: 280, toolbar: { show: false } },
    series: [{ name: 'Revenue', data: periodData }],
    xaxis: { categories: periodLabels, labels: { rotate: -45 } },
    yaxis: { labels: { formatter: v => '$' + Number(v).toLocaleString() } },
    colors: ['#2563eb'],
    fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
}).render();

new ApexCharts(document.querySelector('#revenueDonutChart'), {
    chart: { type: 'donut', height: 280 },
    series: typeData,
    labels: typeLabels,
    legend: { position: 'bottom' },
    dataLabels: { enabled: typeData.length > 0 },
}).render();

new ApexCharts(document.querySelector('#revenueBarChart'), {
    chart: { type: 'bar', height: 260, toolbar: { show: false } },
    series: [{ name: 'Revenue', data: resourceData }],
    xaxis: { categories: resourceLabels },
    yaxis: { labels: { formatter: v => '$' + Number(v).toLocaleString() } },
    colors: ['#059669'],
    dataLabels: { enabled: false },
    plotOptions: { bar: { borderRadius: 4 } },
}).render();
</script>
@endpush
