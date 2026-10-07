@extends('layouts.tenant')

@section('title', 'Purchase Orders')

@section('content')
    <x-page-header title="Purchase Orders" subtitle="Manage procurement purchase orders.">
        <a href="{{ route('tenant.procurement.purchase-orders.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New PO
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <x-data-table
        id="purchase-orders-table"
        :columns="[
            ['title' => 'PO #', 'data' => 'po_number'],
            ['title' => 'Supplier', 'data' => 'supplier_name', 'orderable' => false],
            ['title' => 'Total', 'data' => 'total'],
            ['title' => 'Status', 'data' => 'status_badge', 'orderable' => false],
            ['title' => 'Delivery Date', 'data' => 'delivery_date'],
            ['title' => 'Actions', 'data' => 'actions', 'orderable' => false, 'searchable' => false],
        ]"
        ajaxUrl="{{ route('tenant.procurement.purchase-orders.index') }}"
    />
@endsection
