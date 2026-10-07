@extends('layouts.tenant')

@section('title', 'Journal Entries')

@section('content')
    <x-page-header title="Journal Entries" subtitle="Double-entry bookkeeping ledger.">
        <x-slot name="actions">
            <a href="{{ route('tenant.accounting.journal.create') }}"
               class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                + New Journal Entry
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        {{ $dataTable->table(['class' => 'w-full text-sm']) }}
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
