@extends('layouts.tenant')

@section('title', 'Balance Sheet')

@section('content')
    <x-page-header title="Balance Sheet" subtitle="Financial position at a point in time." />

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.accounting.reports.balance-sheet') }}" class="flex items-end gap-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">As Of Date</label>
                <input type="date" name="date" value="{{ $asOf->toDateString() }}"
                       class="block rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Apply</button>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Assets --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-800">Assets</h3>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach ($data['assets'] as $account)
                        <tr><td class="px-6 py-3 text-gray-700">{{ $account['code'] }} — {{ $account['name'] }}</td>
                            <td class="px-6 py-3 text-right font-medium text-gray-800">${{ number_format($account['balance'], 2) }}</td></tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr><td class="px-6 py-3 font-semibold text-gray-700">Total Assets</td>
                        <td class="px-6 py-3 text-right font-bold text-gray-900">${{ number_format(collect($data['assets'])->sum('balance'), 2) }}</td></tr>
                </tfoot>
            </table>
        </div>

        {{-- Liabilities + Equity --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="font-semibold text-gray-800">Liabilities</h3>
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($data['liabilities'] as $account)
                            <tr><td class="px-6 py-3 text-gray-700">{{ $account['code'] }} — {{ $account['name'] }}</td>
                                <td class="px-6 py-3 text-right font-medium text-gray-800">${{ number_format($account['balance'], 2) }}</td></tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr><td class="px-6 py-3 font-semibold text-gray-700">Total Liabilities</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-900">${{ number_format(collect($data['liabilities'])->sum('balance'), 2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="font-semibold text-gray-800">Equity</h3>
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($data['equity'] as $account)
                            <tr><td class="px-6 py-3 text-gray-700">{{ $account['code'] }} — {{ $account['name'] }}</td>
                                <td class="px-6 py-3 text-right font-medium text-gray-800">${{ number_format($account['balance'], 2) }}</td></tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr><td class="px-6 py-3 font-semibold text-gray-700">Total Equity</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-900">${{ number_format(collect($data['equity'])->sum('balance'), 2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
