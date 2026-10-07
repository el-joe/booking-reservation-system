@extends('layouts.tenant')

@section('title', 'Business Intelligence')

@section('content')
    <x-page-header title="Business Intelligence" subtitle="Analytics and insights for your business" />

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Bookings (30d)</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($trends->sum('count')) }}</p>
            <p class="mt-1 text-xs text-gray-400">Total over last 30 days</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Revenue (30d)</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">${{ number_format($trends->sum('revenue'), 2) }}</p>
            <p class="mt-1 text-xs text-gray-400">Total over last 30 days</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Churn Rate</p>
            <p class="mt-1 text-2xl font-bold {{ $churn['churn_rate'] > 20 ? 'text-red-600' : 'text-gray-900' }}">{{ $churn['churn_rate'] }}%</p>
            <p class="mt-1 text-xs text-gray-400">{{ $churn['churned'] }} of {{ $churn['total'] }} customers</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Active Customers</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($churn['active']) }}</p>
            <p class="mt-1 text-xs text-gray-400">Active in last 30 days</p>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('tenant.bi.trends') }}"
            class="flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm font-medium text-blue-700 hover:bg-blue-100">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
            </svg>
            Booking Trends
        </a>
        <a href="{{ route('tenant.bi.forecast') }}"
            class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50 p-4 text-sm font-medium text-green-700 hover:bg-green-100">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>
            Revenue Forecast
        </a>
        <a href="{{ route('tenant.bi.customer-behavior') }}"
            class="flex items-center gap-3 rounded-xl border border-purple-100 bg-purple-50 p-4 text-sm font-medium text-purple-700 hover:bg-purple-100">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            Customer Behavior
        </a>
        <a href="{{ route('tenant.bi.churn') }}"
            class="flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 p-4 text-sm font-medium text-red-700 hover:bg-red-100">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>
            Churn Analysis
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-4 py-3">
            <h3 class="text-base font-semibold text-gray-900">Last 10 Days Trend</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Bookings</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($trends->take(10) as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row->count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">${{ number_format($row->revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-gray-500">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
