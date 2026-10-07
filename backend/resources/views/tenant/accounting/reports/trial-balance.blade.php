@extends('layouts.tenant')

@section('title', 'Trial Balance')

@section('content')
    <x-page-header title="Trial Balance" subtitle="Account balances as of a specific date." />

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('tenant.accounting.reports.trial-balance') }}" class="flex items-end gap-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-700">As Of Date</label>
                <input type="date" name="date" value="{{ $asOf->toDateString() }}"
                       class="block rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Apply</button>
        </form>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Account Name</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-right">Debit</th>
                    <th class="px-6 py-3 text-right">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($rows as $row)
                    <tr>
                        <td class="px-6 py-3 font-mono text-gray-600">{{ $row['code'] }}</td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
                        <td class="px-6 py-3 capitalize text-gray-600">{{ $row['type'] }}</td>
                        <td class="px-6 py-3 text-right text-gray-800">{{ $row['debit'] > 0 ? '$'.number_format($row['debit'], 2) : '—' }}</td>
                        <td class="px-6 py-3 text-right text-gray-800">{{ $row['credit'] > 0 ? '$'.number_format($row['credit'], 2) : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No transactions found for this period.</td></tr>
                @endforelse
            </tbody>
            @if (count($rows))
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-gray-700">Totals</td>
                        <td class="px-6 py-3 text-right">${{ number_format(collect($rows)->sum('debit'), 2) }}</td>
                        <td class="px-6 py-3 text-right">${{ number_format(collect($rows)->sum('credit'), 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
@endsection
