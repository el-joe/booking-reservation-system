@extends('layouts.central')

@section('title', 'Audit Log')

@section('content')
    <x-page-header title="Audit Log" subtitle="A chronological record of admin actions." />
    <x-breadcrumb :items="[['label' => 'Settings', 'url' => route('central.settings.index')], ['label' => 'Audit Log']]" />

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
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Maintenance
                </a>
                <a href="{{ route('central.settings.audit-log') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium bg-indigo-50 text-indigo-700">
                    Audit Log
                </a>
            </nav>
        </aside>

        <div class="flex-1">
            <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">User</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Target</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">IP Address</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($events as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 font-medium text-gray-900">
                                        {{ $event->user_email ?? $event->user_id ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <x-badge color="indigo">{{ $event->action ?? '—' }}</x-badge>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">{{ $event->target ?? '—' }}</td>
                                    <td class="px-5 py-3 font-mono text-gray-500">{{ $event->ip_address ?? '—' }}</td>
                                    <td class="px-5 py-3 text-gray-500">
                                        {{ isset($event->created_at) ? \Carbon\Carbon::parse($event->created_at)->format('d M Y H:i') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        No audit events recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($events->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4">
                        {{ $events->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
