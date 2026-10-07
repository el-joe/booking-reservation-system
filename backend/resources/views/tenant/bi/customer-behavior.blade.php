@extends('layouts.tenant')

@section('title', 'Customer Behavior')

@section('content')
    <x-page-header title="Customer Behavior" subtitle="Top customers and retention insights">
        <a href="{{ route('tenant.bi.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to BI
        </a>
    </x-page-header>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Retention Rate</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $retentionRate }}%</p>
            <p class="mt-1 text-xs text-gray-400">Customers with 2+ bookings</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Avg Bookings per Customer</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($avgBookingsPerCustomer, 1) }}</p>
            <p class="mt-1 text-xs text-gray-400">All time average</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-4 py-3">
            <h3 class="text-base font-semibold text-gray-900">Top 10 Customers</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Bookings</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Total Spent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($topCustomers as $i => $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($customer->bookings_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">${{ number_format($customer->bookings_sum_total_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-gray-500">No customer data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
