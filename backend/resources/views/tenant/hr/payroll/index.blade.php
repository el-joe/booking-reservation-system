@extends('layouts.tenant')

@section('title', 'Payroll Runs')

@section('content')
    <x-page-header title="Payroll Runs" subtitle="Manage monthly payroll processing">
        <x-slot name="actions">
            <a href="{{ route('tenant.hr.payroll.create') }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                <svg class="-ml-0.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Run Payroll
            </a>
        </x-slot>
    </x-page-header>

    <x-data-table :dataTable="$dataTable" />
@endsection
