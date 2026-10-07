@extends('layouts.tenant')

@section('title', 'Booking Sources Report')

@section('content')
    <x-page-header title="Booking Sources" subtitle="Understand where your bookings are coming from." />

    {{-- Filters --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.reports.sources') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Apply</button>
                <a href="{{ route('tenant.reports.sources') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Pie chart --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Source Breakdown</h3>
            <div id="sourcePieChart"></div>
        </div>

        {{-- Source table --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Source Details</h3>
                <a href="{{ route('tenant.reports.export', array_merge(request()->query(), ['type' => 'sources', 'format' => 'csv'])) }}"
                    class="text-xs text-blue-600 hover:underline">Export CSV</a>
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="pb-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Source</th>
                        <th class="pb-2 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Bookings</th>
                        <th class="pb-2 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $total = $sourceData->sum('count'); @endphp
                    @forelse ($sourceData as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2.5 font-medium text-gray-800">{{ $row->source ?: 'Direct' }}</td>
                            <td class="py-2.5 text-right text-gray-600">
                                {{ number_format($row->count) }}
                                @if ($total > 0)
                                    <span class="ml-1 text-xs text-gray-400">({{ round(($row->count / $total) * 100, 1) }}%)</span>
                                @endif
                            </td>
                            <td class="py-2.5 text-right font-medium text-green-600">${{ number_format($row->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-gray-400">No data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
<script>
const sourceLabels = @json($sourceData->map(fn($r) => $r->source ?: 'Direct'));
const sourceCounts = @json($sourceData->pluck('count'));

new ApexCharts(document.querySelector('#sourcePieChart'), {
    chart: { type: 'pie', height: 320 },
    series: sourceCounts,
    labels: sourceLabels,
    legend: { position: 'bottom' },
    dataLabels: { enabled: sourceCounts.length > 0 },
}).render();
</script>
@endpush
