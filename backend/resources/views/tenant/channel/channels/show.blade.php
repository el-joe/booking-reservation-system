@extends('layouts.tenant')

@section('title', $channel->name)

@section('content')
    <x-page-header :title="$channel->name" :subtitle="$channel->typeLabel() . ' — ' . ucfirst($channel->status)">
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('tenant.channels.ota.sync', $channel) }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-white px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Sync Now
                </button>
            </form>
            <a href="{{ route('tenant.channels.ota.edit', $channel) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Edit Channel
            </a>
        </div>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Status</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $channel->statusBadgeClass() }}">
                    {{ ucfirst($status['status']) }}
                </span>
            </p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Last Sync</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">
                {{ $status['last_sync_at'] ? \Carbon\Carbon::parse($status['last_sync_at'])->diffForHumans() : 'Never' }}
            </p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Pending Reservations</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $status['pending_reservations'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Active Rate Plans</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $status['active_rate_plans'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Rate Plans --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">Rate Plans</h2>
            </div>
            @if ($channel->ratePlans->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-gray-500">No rate plans configured.</p>
            @else
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Plan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Resource</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Base Rate</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Active</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($channel->ratePlans as $plan)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $plan->rate_plan_name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ $plan->resource->name ?? '—' }}</td>
                                <td class="px-4 py-2 text-right text-sm font-medium text-gray-900">${{ number_format($plan->base_rate, 2) }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if ($plan->is_active)
                                        <span class="inline-block h-2 w-2 rounded-full bg-green-500"></span>
                                    @else
                                        <span class="inline-block h-2 w-2 rounded-full bg-gray-300"></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Recent Reservations --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">Recent Reservations</h2>
                <a href="{{ route('tenant.channels.reservations.index', ['channel_id' => $channel->id]) }}"
                    class="text-xs text-blue-600 hover:text-blue-700">View all</a>
            </div>
            @if ($channel->reservations->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-gray-500">No reservations imported yet.</p>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach ($channel->reservations as $res)
                        <li class="flex items-center justify-between px-6 py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $res->guest_name }}</p>
                                <p class="text-xs text-gray-500">{{ $res->check_in->format('M d') }} – {{ $res->check_out->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">${{ number_format($res->total_amount, 2) }}</p>
                                @if ($res->isImported())
                                    <span class="text-xs text-green-600">Imported</span>
                                @else
                                    <span class="text-xs text-yellow-600">Pending</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
