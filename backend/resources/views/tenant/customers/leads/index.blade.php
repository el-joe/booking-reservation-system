@extends('layouts.tenant')

@section('title', 'Leads')

@section('content')
    <x-page-header title="Leads" subtitle="Manage pre-booking inquiries and convert them to customers.">
        <a href="{{ route('tenant.leads.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Lead
        </a>
    </x-page-header>

    <x-data-table
        id="leads-table"
        :columns="[
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Type', 'data' => 'type_badge'],
            ['title' => 'Source', 'data' => 'source_badge'],
            ['title' => 'Status', 'data' => 'status_badge'],
            ['title' => 'Assigned To', 'data' => 'assigned_to_name'],
            ['title' => 'Created At', 'data' => 'created_at'],
            ['title' => 'Actions', 'data' => 'actions', 'orderable' => false, 'searchable' => false],
        ]"
        ajaxUrl="{{ route('tenant.leads.index') }}"
    />
@endsection
