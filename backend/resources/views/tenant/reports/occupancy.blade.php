@extends('layouts.tenant')

@section('title', 'Occupancy Report')

@section('content')
    <x-page-header title="Occupancy Report" subtitle="Understand utilization rates across resources and time." />

    {{-- Filters --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.reports.occupancy') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
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
                <a href="{{ route('tenant.reports.occupancy') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Overall Rate --}}
    <div class="mb-6 flex items-center justify-center">
        <div class="rounded-xl bg-white p-8 text-center shadow-sm ring-1 ring-gray-200">
            <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Overall Utilization Rate</p>
            <p class="mt-2 text-6xl font-bold text-blue-600">{{ $data['overall_rate'] }}%</p>
            <p class="mt-1 text-xs text-gray-400">{{ $data['date_from'] }} — {{ $data['date_to'] }}</p>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Per-resource chart --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Utilization by Resource</h3>
            <div id="occupancyBarChart"></div>
        </div>

        {{-- Heatmap --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Occupancy Heatmap</h3>
            <div class="flex flex-wrap gap-1">
                @foreach ($data['heatmap_data'] as $date => $rate)
                    @php
                        $opacity = max(0.1, $rate / 100);
                        $label = $date.' ('.$rate.'%)';
                    @endphp
                    <div title="{{ $label }}"
                        class="h-5 w-5 rounded-sm border border-gray-100"
                        style="background-color: rgba(37, 99, 235, {{ $opacity }})">
                    </div>
                @endforeach
                @if (empty($data['heatmap_data']))
                    <p class="text-sm text-gray-400">No data for selected period.</p>
                @endif
            </div>
            <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                <span>Low</span>
                <div class="flex gap-0.5">
                    @foreach ([0.1, 0.3, 0.5, 0.7, 0.9] as $op)
                        <div class="h-3 w-5 rounded-sm" style="background-color: rgba(37,99,235,{{ $op }})"></div>
                    @endforeach
                </div>
                <span>High</span>
            </div>
        </div>
    </div>

    {{-- Export --}}
    <div class="flex justify-end gap-2">
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'occupancy', 'format' => 'csv'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">CSV</a>
        <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'occupancy', 'format' => 'excel'])) }}"
            class="inline-flex items-center gap-1 rounded-lg border border-green-300 bg-green-50 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100">Excel</a>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
<script>
const resourceNames = @json(array_keys($data['by_resource']));
const resourceRates = @json(array_values($data['by_resource']));

new ApexCharts(document.querySelector('#occupancyBarChart'), {
    chart: { type: 'bar', height: 280, toolbar: { show: false } },
    series: [{ name: 'Occupancy %', data: resourceRates }],
    xaxis: { categories: resourceNames },
    yaxis: { min: 0, max: 100, labels: { formatter: v => v + '%' } },
    colors: ['#2563eb'],
    dataLabels: { enabled: false },
    plotOptions: { bar: { borderRadius: 4 } },
}).render();
</script>
@endpush
