@extends('layouts.central')

@section('title', 'Maintenance Mode')

@section('content')
    <x-page-header title="Maintenance Mode" subtitle="Control platform and per-tenant maintenance windows." />
    <x-breadcrumb :items="[['label' => 'Settings', 'url' => route('central.settings.index')], ['label' => 'Maintenance']]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex gap-6">
        {{-- Sidebar tabs --}}
        <aside class="w-48 flex-shrink-0">
            <nav class="flex flex-col gap-1">
                <a href="{{ route('central.settings.index') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    General
                </a>
                <a href="{{ route('central.settings.branding') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Branding
                </a>
                <a href="{{ route('central.settings.maintenance') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium bg-indigo-50 text-indigo-700">
                    Maintenance
                </a>
                <a href="{{ route('central.settings.audit-log') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Audit Log
                </a>
            </nav>
        </aside>

        <div class="flex-1 space-y-6">
            {{-- Global toggle --}}
            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Global Maintenance Mode</h2>
                        <p class="mt-1 text-sm text-gray-500">Puts the entire platform under maintenance for all tenants.</p>
                        @if ($globalMaintenance)
                            <x-badge color="red" class="mt-2">Currently ON</x-badge>
                        @else
                            <x-badge color="green" class="mt-2">Currently OFF</x-badge>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('central.settings.maintenance.toggle') }}">
                        @csrf
                        <input type="hidden" name="scope" value="global">
                        <input type="hidden" name="enabled" value="{{ $globalMaintenance ? '0' : '1' }}">
                        <button type="submit"
                            class="{{ $globalMaintenance ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} rounded-lg px-4 py-2 text-sm font-semibold text-white">
                            {{ $globalMaintenance ? 'Disable Global Maintenance' : 'Enable Global Maintenance' }}
                        </button>
                    </form>
                </div>
            </section>

            {{-- Per-tenant DataTable --}}
            <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-900">Per-Tenant Maintenance</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tenant</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($tenants as $tenant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-900">{{ $tenant->name ?? $tenant->id }}</td>
                                    <td class="px-6 py-3">
                                        @if ($tenant->maintenance)
                                            <x-badge color="red">Maintenance ON</x-badge>
                                        @else
                                            <x-badge color="green">Active</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <form method="POST" action="{{ route('central.settings.maintenance.toggle') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="scope" value="tenant">
                                            <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                            <input type="hidden" name="enabled" value="{{ $tenant->maintenance ? '0' : '1' }}">
                                            <button type="submit"
                                                class="{{ $tenant->maintenance ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800' }} text-sm font-medium">
                                                {{ $tenant->maintenance ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">No tenants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
