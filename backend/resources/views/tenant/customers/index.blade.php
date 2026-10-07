@extends('layouts.tenant')

@section('title', 'Customers')

@section('content')
    <x-page-header title="Customers" subtitle="Manage your customer profiles and CRM data.">
        <a href="{{ route('tenant.customers.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Customer
        </a>
        <a href="{{ route('tenant.customers.blacklist') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100">
            Blacklisted
        </a>
    </x-page-header>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap items-center gap-3" x-data="{ search: '{{ request('search') }}', blacklisted: '{{ request('blacklisted') }}', tag: '{{ request('tag') }}' }">
        <input
            type="text"
            placeholder="Search name, email, phone…"
            x-model="search"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
        <select x-model="blacklisted" class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Statuses</option>
            <option value="0">Active</option>
            <option value="1">Blacklisted</option>
        </select>
        <input
            type="text"
            placeholder="Filter by tag…"
            x-model="tag"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
    </div>

    <x-data-table
        id="customers-table"
        :columns="[
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Bookings', 'data' => 'total_bookings'],
            ['title' => 'Total Spent', 'data' => 'total_spent'],
            ['title' => 'Loyalty Pts', 'data' => 'loyalty_points'],
            ['title' => 'Status', 'data' => 'status'],
            ['title' => 'Created At', 'data' => 'created_at'],
            ['title' => 'Actions', 'data' => 'actions', 'orderable' => false, 'searchable' => false],
        ]"
        ajaxUrl="{{ route('tenant.customers.index') }}"
    />
@endsection
