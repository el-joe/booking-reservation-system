@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')
    {{-- Row 1: Stat Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card
            label="Today's Bookings"
            :value="$stats['today_bookings']"
            color="blue"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>'
        />

        <x-stat-card
            label="Monthly Revenue"
            :value="'$' . number_format($stats['monthly_revenue'], 2)"
            color="green"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>'
        />

        <x-stat-card
            label="Occupancy Rate"
            :value="$stats['occupancy_rate'] . '%'"
            color="yellow"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" /></svg>'
        />

        <x-stat-card
            label="Pending Approvals"
            :value="$stats['pending_approvals']"
            color="yellow"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>'
        />
    </div>

    {{-- Row 2: Upcoming Bookings --}}
    <div class="mt-6">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-base font-semibold text-gray-900">Upcoming Bookings (Next 24 hours)</h2>

            @if ($upcomingBookings->isEmpty())
                <p class="py-6 text-center text-sm text-gray-500">No upcoming bookings in the next 24 hours.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Reference</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Customer</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Resource</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Check-In</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($upcomingBookings as $booking)
                                <tr>
                                    <td class="py-3 font-mono text-xs text-gray-600">{{ $booking->reference_number }}</td>
                                    <td class="py-3 font-medium text-gray-900">{{ $booking->customer?->name ?? '—' }}</td>
                                    <td class="py-3 text-gray-600">{{ $booking->resource?->name ?? '—' }}</td>
                                    <td class="py-3 text-gray-600">{{ $booking->check_in->format('M d, H:i') }}</td>
                                    <td class="py-3">
                                        @php $color = $booking->status->color(); @endphp
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            @if ($color === 'yellow') bg-yellow-100 text-yellow-800
                                            @elseif ($color === 'blue') bg-blue-100 text-blue-800
                                            @elseif ($color === 'green') bg-green-100 text-green-800
                                            @elseif ($color === 'red') bg-red-100 text-red-800
                                            @elseif ($color === 'indigo') bg-indigo-100 text-indigo-800
                                            @elseif ($color === 'purple') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $booking->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Row 3: Charts --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Revenue Chart --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-base font-semibold text-gray-900">Revenue (Last 30 Days)</h2>
            <div id="revenueChart"></div>
        </div>

        {{-- Status Breakdown --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-base font-semibold text-gray-900">Booking Status Breakdown</h2>
            <div id="statusChart"></div>
        </div>
    </div>

    {{-- Row 4: Recent Activity --}}
    <div class="mt-6">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-base font-semibold text-gray-900">Recent Activity</h2>

            @if ($recentActivity->isEmpty())
                <p class="py-6 text-center text-sm text-gray-500">No recent activity found.</p>
            @else
                <ol class="relative border-l border-gray-200">
                    @foreach ($recentActivity as $history)
                        <li class="mb-6 ml-4">
                            <div class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full border border-white bg-gray-300"></div>
                            <time class="mb-1 text-xs font-normal leading-none text-gray-400">
                                {{ $history->created_at->diffForHumans() }}
                            </time>
                            <p class="text-sm font-medium text-gray-900">
                                Booking
                                <span class="font-mono text-xs text-gray-600">{{ $history->booking?->reference_number }}</span>
                                @if ($history->booking?->customer)
                                    for <span class="font-medium">{{ $history->booking->customer->name }}</span>
                                @endif
                                changed to
                                <span class="font-semibold text-gray-800">{{ $history->status }}</span>
                                @if ($history->previous_status)
                                    from <span class="text-gray-500">{{ $history->previous_status }}</span>
                                @endif
                            </p>
                            @if ($history->note)
                                <p class="mt-1 text-xs text-gray-500">{{ $history->note }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const revenueData = @json($revenueChart);
        const statusData = @json($statusBreakdown);

        // Revenue line chart
        new ApexCharts(document.getElementById('revenueChart'), {
            chart: { type: 'area', height: 280, toolbar: { show: false }, sparkline: { enabled: false } },
            series: [{ name: 'Revenue', data: revenueData.data }],
            xaxis: { categories: revenueData.labels, tickAmount: 7, labels: { style: { fontSize: '11px' } } },
            yaxis: { labels: { formatter: val => '$' + val.toFixed(0) } },
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
            colors: ['#6366f1'],
            dataLabels: { enabled: false },
            grid: { strokeDashArray: 4 },
            tooltip: { y: { formatter: val => '$' + val.toFixed(2) } },
        }).render();

        // Status donut chart
        new ApexCharts(document.getElementById('statusChart'), {
            chart: { type: 'donut', height: 280 },
            series: statusData.data,
            labels: statusData.labels,
            colors: ['#eab308', '#3b82f6', '#6366f1', '#22c55e', '#ef4444', '#6b7280', '#a855f7'],
            dataLabels: { enabled: false },
            legend: { position: 'bottom', fontSize: '12px' },
            tooltip: { y: { formatter: val => val + ' bookings' } },
            plotOptions: { pie: { donut: { size: '65%' } } },
        }).render();
    </script>
@endpush
