@extends('layouts.tenant')

@section('title', 'Refunds')

@section('content')
    <x-page-header title="Refunds" subtitle="Track all refund requests." />

    <x-data-table :dataTable="$dataTable" />
@endsection
