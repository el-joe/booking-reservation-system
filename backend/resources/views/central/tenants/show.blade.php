@extends('layouts.central')

@section('title', $tenant->name)

@section('content')
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('central.tenants.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Tenants</a>
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-sm font-medium text-gray-900">{{ $tenant->name }}</span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Left: Tenant info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Tenant Information</h2>
                    <a href="{{ route('central.tenants.edit', $tenant) }}"
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Business Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tenant->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                            <dd class="mt-1">
                                @php $color = $tenant->status?->badgeColor() ?? 'gray'; @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ $tenant->status?->label() ?? 'Unknown' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Contact Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tenant->contact_name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Contact Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tenant->contact_email ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tenant->phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Business Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tenant->business_type?->label() ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Domain</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if ($domain = $tenant->domains()->first()?->domain)
                                    <a href="http://{{ $domain }}" target="_blank" class="text-blue-600 hover:underline">{{ $domain }}</a>
                                @else
                                    —
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tenant->created_at?->format('M d, Y') }}</dd>
                        </div>
                        @if ($tenant->notes)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $tenant->notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Stats --}}
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900">Usage Statistics</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-lg bg-gray-50 p-4 text-center">
                            <dt class="text-xs font-medium text-gray-500">Total Bookings</dt>
                            <dd class="mt-2 text-2xl font-bold text-gray-900">{{ $stats['bookings_count'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Right: Subscription + Quick Actions --}}
        <div class="space-y-6">

            {{-- Subscription card --}}
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900">Subscription</h2>
                </div>
                <div class="p-6">
                    @if ($tenant->subscription)
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Plan</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $tenant->subscription->plan?->name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($tenant->subscription->status) }}</dd>
                            </div>
                            @if ($tenant->subscription->ends_at)
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Renews</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->subscription->ends_at->format('M d, Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    @else
                        <p class="text-sm text-gray-500">No active subscription.</p>
                    @endif
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="rounded-lg bg-white shadow">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if ($tenant->isSuspended())
                        <form method="POST" action="{{ route('central.tenants.reactivate', $tenant) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                Reactivate Tenant
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('central.tenants.suspend', $tenant) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-md bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-400">
                                Suspend Tenant
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('central.tenants.impersonate', $tenant) }}">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Impersonate Tenant
                        </button>
                    </form>

                    <form method="POST" action="{{ route('central.tenants.destroy', $tenant) }}"
                          onsubmit="return confirm('Permanently delete this tenant? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                            Delete Tenant
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
