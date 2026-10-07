@extends('layouts.central')

@section('title', 'Dashboard')

@section('content')
    {{-- Row 1: Stat Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card
            label="Total Tenants"
            :value="$stats['total_tenants']"
            color="indigo"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>'
        />

        <x-stat-card
            label="Active Tenants"
            :value="$stats['active_tenants']"
            color="green"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>'
        />

        <x-stat-card
            label="MRR ($)"
            :value="'$' . number_format($stats['mrr'], 2)"
            color="indigo"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>'
        />

        <x-stat-card
            label="Total Plans"
            :value="$stats['total_plans']"
            color="yellow"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>'
        />
    </div>

    {{-- Row 1b: Content Stats --}}
    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <x-stat-card
            label="Blog Posts"
            :value="$stats['blog_posts_count']"
            color="indigo"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" /></svg>'
        />

        <x-stat-card
            label="Unread Messages"
            :value="$stats['unread_contacts']"
            color="red"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>'
        />

        <x-stat-card
            label="Active FAQs"
            :value="$stats['faqs_count']"
            color="green"
            icon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>'
        />
    </div>

    {{-- Row 2: Charts --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Tenant Growth Chart --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">Tenant Growth (Last 6 Months)</h2>
            <div
                x-data
                x-init="
                    new ApexCharts($el.querySelector('#growth-chart'), {
                        chart: { type: 'line', height: 300, toolbar: { show: false }, zoom: { enabled: false } },
                        series: [{ name: 'Tenants', data: @json($growthData['data']) }],
                        xaxis: { categories: @json($growthData['labels']) },
                        stroke: { curve: 'smooth', width: 2 },
                        colors: ['#6366f1'],
                        grid: { borderColor: '#f3f4f6' },
                        tooltip: { theme: 'light' }
                    }).render()
                "
            >
                <div id="growth-chart"></div>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">Revenue (Last 6 Months)</h2>
            <div
                x-data
                x-init="
                    new ApexCharts($el.querySelector('#revenue-chart'), {
                        chart: { type: 'bar', height: 300, toolbar: { show: false } },
                        series: [{ name: 'Revenue ($)', data: @json($revenueData['data']) }],
                        xaxis: { categories: @json($revenueData['labels']) },
                        colors: ['#10b981'],
                        grid: { borderColor: '#f3f4f6' },
                        tooltip: { theme: 'light' }
                    }).render()
                "
            >
                <div id="revenue-chart"></div>
            </div>
        </div>
    </div>

    {{-- Row 3: Plan Distribution + Recent Tenants --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Plan Distribution Pie Chart --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">Plan Distribution</h2>
            @if (count($planDistribution['labels']) > 0)
                <div
                    x-data
                    x-init="
                        new ApexCharts($el.querySelector('#plan-chart'), {
                            chart: { type: 'pie', height: 300 },
                            series: @json($planDistribution['data']),
                            labels: @json($planDistribution['labels']),
                            legend: { position: 'bottom' },
                            tooltip: { theme: 'light' }
                        }).render()
                    "
                >
                    <div id="plan-chart"></div>
                </div>
            @else
                <div class="flex h-48 items-center justify-center text-sm text-gray-400">No active subscriptions yet.</div>
            @endif
        </div>

        {{-- Recent Tenants Table --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">Recent Tenants</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-gray-400">
                            <th class="pb-2 pr-4">Name</th>
                            <th class="pb-2 pr-4">Plan</th>
                            <th class="pb-2 pr-4">Status</th>
                            <th class="pb-2">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($recentTenants as $tenant)
                            <tr>
                                <td class="py-2 pr-4 font-medium text-gray-800">{{ $tenant->name ?? $tenant->id }}</td>
                                <td class="py-2 pr-4 text-gray-500">{{ $tenant->subscription?->plan?->name ?? '—' }}</td>
                                <td class="py-2 pr-4">
                                    @php
                                        $statusColor = match ($tenant->status?->value ?? '') {
                                            'active'    => 'bg-green-100 text-green-700',
                                            'suspended' => 'bg-red-100 text-red-700',
                                            'trial'     => 'bg-yellow-100 text-yellow-700',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColor }}">
                                        {{ $tenant->status?->label() ?? '—' }}
                                    </span>
                                </td>
                                <td class="py-2 text-gray-400">{{ $tenant->created_at?->format('M d, Y') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">No tenants yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection
