@extends('layouts.tenant')

@section('title', 'Churn Analysis')

@section('content')
    <x-page-header title="Churn Analysis" subtitle="Customer churn and retention overview">
        <a href="{{ route('tenant.bi.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to BI
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Customers</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($data['total']) }}</p>
            <p class="mt-1 text-xs text-gray-400">All time</p>
        </div>
        <div class="rounded-xl border border-green-100 bg-green-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-green-600">Active Customers</p>
            <p class="mt-1 text-3xl font-bold text-green-700">{{ number_format($data['active']) }}</p>
            <p class="mt-1 text-xs text-green-500">Booked in last 30 days</p>
        </div>
        <div class="rounded-xl border border-red-100 bg-red-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-red-600">Churned Customers</p>
            <p class="mt-1 text-3xl font-bold text-red-700">{{ number_format($data['churned']) }}</p>
            <p class="mt-1 text-xs text-red-500">No bookings in 30+ days, last booking 90+ days ago</p>
        </div>
        <div class="rounded-xl border {{ $data['churn_rate'] > 20 ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-white' }} p-5 shadow-sm">
            <p class="text-sm font-medium {{ $data['churn_rate'] > 20 ? 'text-red-600' : 'text-gray-500' }}">Churn Rate</p>
            <p class="mt-1 text-3xl font-bold {{ $data['churn_rate'] > 20 ? 'text-red-700' : 'text-gray-900' }}">{{ $data['churn_rate'] }}%</p>
            <p class="mt-1 text-xs {{ $data['churn_rate'] > 20 ? 'text-red-500' : 'text-gray-400' }}">
                {{ $data['churn_rate'] > 20 ? 'Above healthy threshold' : 'Within healthy range' }}
            </p>
        </div>
    </div>

    @if ($data['total'] > 0)
        <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Breakdown</h3>
            <div class="space-y-3">
                @php
                    $activePercent = $data['total'] > 0 ? round(($data['active'] / $data['total']) * 100) : 0;
                    $churnedPercent = $data['churn_rate'];
                    $otherPercent = max(0, 100 - $activePercent - $churnedPercent);
                @endphp
                <div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-gray-600">Active</span>
                        <span class="font-medium text-gray-900">{{ $activePercent }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-2 rounded-full bg-green-500" style="width: {{ $activePercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-gray-600">Churned</span>
                        <span class="font-medium text-gray-900">{{ $churnedPercent }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-2 rounded-full bg-red-500" style="width: {{ $churnedPercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-gray-600">Other (at-risk / occasional)</span>
                        <span class="font-medium text-gray-900">{{ $otherPercent }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-2 rounded-full bg-yellow-400" style="width: {{ $otherPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
