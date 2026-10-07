@extends('layouts.tenant')

@section('title', 'Profit & Loss')

@section('content')
    <x-page-header title="Profit & Loss" subtitle="Income and expenses for a period." />

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.accounting.reports.profit-loss') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">From</label>
                <input type="date" name="date_from" value="{{ $from->toDateString() }}"
                       class="block rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">To</label>
                <input type="date" name="date_to" value="{{ $to->toDateString() }}"
                       class="block rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Apply</button>
        </form>
    </div>

    <div class="space-y-4">
        {{-- Revenue --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-green-50 px-6 py-4">
                <h3 class="font-semibold text-green-800">Revenue</h3>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach ($data['revenue'] as $account)
                        <tr><td class="px-6 py-3 text-gray-700">{{ $account['code'] }} — {{ $account['name'] }}</td>
                            <td class="px-6 py-3 text-right font-medium text-gray-800">${{ number_format($account['balance'], 2) }}</td></tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr><td class="px-6 py-3 font-semibold text-gray-700">Total Revenue</td>
                        <td class="px-6 py-3 text-right font-bold text-green-700">${{ number_format(collect($data['revenue'])->sum('balance'), 2) }}</td></tr>
                </tfoot>
            </table>
        </div>

        {{-- Expenses --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-red-50 px-6 py-4">
                <h3 class="font-semibold text-red-800">Expenses</h3>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach ($data['expenses'] as $account)
                        <tr><td class="px-6 py-3 text-gray-700">{{ $account['code'] }} — {{ $account['name'] }}</td>
                            <td class="px-6 py-3 text-right font-medium text-gray-800">${{ number_format($account['balance'], 2) }}</td></tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr><td class="px-6 py-3 font-semibold text-gray-700">Total Expenses</td>
                        <td class="px-6 py-3 text-right font-bold text-red-700">${{ number_format(collect($data['expenses'])->sum('balance'), 2) }}</td></tr>
                </tfoot>
            </table>
        </div>

        {{-- Net Income --}}
        <div class="rounded-xl border {{ $data['net'] >= 0 ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} p-6">
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold {{ $data['net'] >= 0 ? 'text-green-800' : 'text-red-800' }}">Net Income</span>
                <span class="text-2xl font-bold {{ $data['net'] >= 0 ? 'text-green-700' : 'text-red-700' }}">${{ number_format($data['net'], 2) }}</span>
            </div>
        </div>
    </div>
@endsection
