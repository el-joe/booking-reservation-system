@extends('layouts.tenant')

@section('title', 'Invoices')

@section('content')
    <x-page-header title="Invoices" subtitle="Manage customer invoices.">
    </x-page-header>

    <x-data-table :dataTable="$dataTable" />
@endsection
