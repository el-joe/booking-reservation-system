@extends('layouts.tenant')

@section('title', 'Revenue Forecast')

@section('content')
    <x-page-header title="Revenue Forecast" subtitle="Projected revenue for the next 6 months">
        <a href="{{ route('tenant.bi.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to BI
        </a>
    </x-page-header>

    <div class="mb-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
        Forecast is calculated from historical completed booking revenue with a 2% monthly growth assumption.
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Month</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Predicted Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($forecast as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $row['month'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">${{ number_format($row['predicted'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-12 text-center text-gray-500">No forecast data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
