@extends('layouts.tenant')

@section('title', 'Settings')

@section('content')
    <x-page-header title="Settings" subtitle="Configure your account settings" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                @include('tenant.settings.partials.sidebar')
            </div>
        </div>
        <div class="lg:col-span-3">
            @yield('settings-content')
        </div>
    </div>
@endsection
