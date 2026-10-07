@extends('layouts.tenant')

@section('title', 'Channel Manager')

@section('content')
    <x-page-header title="Channel Manager" subtitle="Manage OTA connections and sync availability">
        <a href="{{ route('tenant.channels.ota.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Connect Channel
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($channels->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white py-16 text-center">
            <svg class="mb-3 h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
            </svg>
            <p class="text-sm font-medium text-gray-900">No channels connected</p>
            <p class="mt-1 text-sm text-gray-500">Connect Booking.com, Airbnb, or other OTAs to sync availability and reservations.</p>
            <a href="{{ route('tenant.channels.ota.create') }}"
                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Connect your first channel
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Channel</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Last Sync</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Reservations</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($channels as $channel)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.channels.ota.show', $channel) }}"
                                    class="font-medium text-gray-900 hover:text-blue-600">{{ $channel->name }}</a>
                                @if ($channel->channel_property_id)
                                    <p class="text-xs text-gray-400">ID: {{ $channel->channel_property_id }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $channel->typeLabel() }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $channel->statusBadgeClass() }}">
                                    {{ ucfirst($channel->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $channel->last_sync_at ? $channel->last_sync_at->diffForHumans() : 'Never' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($channel->reservations_count) }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('tenant.channels.ota.sync', $channel) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-md border border-blue-300 bg-white px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                            Sync Now
                                        </button>
                                    </form>
                                    <a href="{{ route('tenant.channels.ota.edit', $channel) }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('tenant.channels.ota.destroy', $channel) }}"
                                        onsubmit="return confirm('Disconnect this channel? All rate plans will be removed.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-md border border-red-300 bg-white px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                            Disconnect
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
            <p>{{ $channels->count() }} channel(s) connected</p>
            <a href="{{ route('tenant.channels.reservations.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                View all imported reservations →
            </a>
        </div>
    @endif
@endsection
