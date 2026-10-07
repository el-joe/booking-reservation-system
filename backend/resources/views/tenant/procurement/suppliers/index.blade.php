@extends('layouts.tenant')

@section('title', 'Suppliers')

@section('content')
    <x-page-header title="Suppliers" subtitle="Manage your procurement suppliers.">
        <a href="{{ route('tenant.procurement.suppliers.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Supplier
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <x-data-table
        id="suppliers-table"
        :columns="[
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Status', 'data' => 'is_active', 'orderable' => false],
            ['title' => 'PO Count', 'data' => 'po_count', 'orderable' => false],
            ['title' => 'Actions', 'data' => 'actions', 'orderable' => false, 'searchable' => false],
        ]"
        ajaxUrl="{{ route('tenant.procurement.suppliers.index') }}"
    />
@endsection
