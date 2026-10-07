@extends('layouts.tenant')

@section('title', 'Blacklisted Customers')

@section('content')
    <x-page-header title="Blacklisted Customers" subtitle="Customers blocked from making bookings.">
        <a href="{{ route('tenant.customers.index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
            ← All Customers
        </a>
    </x-page-header>

    <x-data-table
        id="blacklisted-table"
        :columns="[
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Reason', 'data' => 'blacklist_reason'],
            ['title' => 'Actions', 'data' => 'actions', 'orderable' => false, 'searchable' => false],
        ]"
        ajaxUrl="{{ route('tenant.customers.index') }}?is_blacklisted=1"
    />
@endsection
