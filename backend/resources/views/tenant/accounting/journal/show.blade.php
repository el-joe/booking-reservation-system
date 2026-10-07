@extends('layouts.tenant')

@section('title', 'Journal Entry: ' . $entry->reference)

@section('content')
    <x-page-header title="Journal Entry: {{ $entry->reference }}" subtitle="{{ $entry->description }}">
        <x-slot name="actions">
            @if (!str_starts_with($entry->reference, 'VOID-'))
                <form method="POST" action="{{ route('tenant.accounting.journal.void', $entry) }}" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Void this journal entry? A reversing entry will be posted.')"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Void Entry
                    </button>
                </form>
            @else
                <span class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">Voided Entry</span>
            @endif
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="mb-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div><dt class="text-xs text-gray-500">Reference</dt><dd class="font-mono font-medium text-gray-800">{{ $entry->reference }}</dd></div>
            <div><dt class="text-xs text-gray-500">Date</dt><dd class="text-gray-800">{{ $entry->date->format('d M Y') }}</dd></div>
            <div><dt class="text-xs text-gray-500">Source</dt><dd class="text-gray-700">{{ $entry->source_type ? class_basename($entry->source_type).' #'.$entry->source_id : 'Manual' }}</dd></div>
            <div><dt class="text-xs text-gray-500">Posted</dt><dd class="text-gray-700">{{ $entry->created_at->format('d M Y H:i') }}</dd></div>
        </dl>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">Account</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-right">Debit</th>
                    <th class="px-6 py-3 text-right">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($entry->lines as $line)
                    <tr>
                        <td class="px-6 py-3 font-mono text-gray-700">{{ $line->account->code }} — {{ $line->account->name }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $line->description ?? '—' }}</td>
                        <td class="px-6 py-3 text-right text-gray-800">{{ $line->debit > 0 ? '$'.number_format((float)$line->debit, 2) : '—' }}</td>
                        <td class="px-6 py-3 text-right text-gray-800">{{ $line->credit > 0 ? '$'.number_format((float)$line->credit, 2) : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-semibold">
                <tr>
                    <td colspan="2" class="px-6 py-3 text-right text-gray-700">Totals</td>
                    <td class="px-6 py-3 text-right">${{ number_format($entry->lines->sum('debit'), 2) }}</td>
                    <td class="px-6 py-3 text-right">${{ number_format($entry->lines->sum('credit'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('tenant.accounting.journal.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Journal</a>
    </div>
@endsection
