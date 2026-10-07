@extends('layouts.tenant')

@section('title', 'Booking Trends')

@section('content')
    <x-page-header title="Booking Trends" subtitle="Booking and revenue trends over the last 60 days">
        <a href="{{ route('tenant.bi.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to BI
        </a>
    </x-page-header>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Bookings</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($trends as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row->count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">${{ number_format($row->revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-gray-500">No trend data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
