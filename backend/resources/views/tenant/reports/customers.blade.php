@extends('layouts.tenant')

@section('title', 'Customers Report')

@section('content')
    <x-page-header title="Customers Report" subtitle="Understand acquisition, retention, and spending behavior." />

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Customers</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">New This Month</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($newThisMonth) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Returning Rate</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ $returningRate }}%</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Avg Bookings / Customer</p>
            <p class="mt-1 text-2xl font-bold text-purple-600">{{ $avgBookings }}</p>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Monthly Acquisition --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Monthly Acquisition (Last 12 Months)</h3>
            <div id="acquisitionChart"></div>
        </div>

        {{-- Top Spenders --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Top 10 Spenders</h3>
                <a href="{{ route('tenant.reports.export', ['type' => 'customers', 'format' => 'excel']) }}"
                    class="text-xs text-blue-600 hover:underline">Export</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-2 text-left text-xs font-medium text-gray-500">Customer</th>
                            <th class="pb-2 text-right text-xs font-medium text-gray-500">Bookings</th>
                            <th class="pb-2 text-right text-xs font-medium text-gray-500">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($topSpenders as $customer)
                            <tr>
                                <td class="py-2 text-gray-800">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td class="py-2 text-right text-gray-600">{{ $customer->bookings_count }}</td>
                                <td class="py-2 text-right font-medium text-green-600">${{ number_format($customer->bookings_sum_total_amount ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-400">No data available.</td></tr>
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
const acqLabels = @json($monthlyAcquisition->pluck('month'));
const acqData = @json($monthlyAcquisition->pluck('count'));

new ApexCharts(document.querySelector('#acquisitionChart'), {
    chart: { type: 'line', height: 260, toolbar: { show: false } },
    series: [{ name: 'New Customers', data: acqData }],
    xaxis: { categories: acqLabels, labels: { rotate: -45 } },
    colors: ['#7c3aed'],
    stroke: { curve: 'smooth', width: 2 },
    markers: { size: 4 },
    dataLabels: { enabled: false },
}).render();
</script>
@endpush
