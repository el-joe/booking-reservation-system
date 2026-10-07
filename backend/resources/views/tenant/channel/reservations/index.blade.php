@extends('layouts.tenant')

@section('title', 'Channel Reservations')

@section('content')
    <x-page-header title="Channel Reservations" subtitle="Reservations imported from OTA channels">
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('tenant.channels.reservations.import') }}" class="flex items-center gap-2">
                @csrf
                <select name="channel_id" required
                    class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select channel to import</option>
                    @foreach ($channels as $ch)
                        <option value="{{ $ch->id }}">{{ $ch->name }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Import Now
                </button>
            </form>
        </div>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    @if ($channels->isNotEmpty())
        <div class="mb-4 flex items-center gap-3">
            <span class="text-sm text-gray-600">Filter by channel:</span>
            <a href="{{ route('tenant.channels.reservations.index') }}"
                class="rounded-full px-3 py-1 text-xs font-medium {{ ! $channelId ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            @foreach ($channels as $ch)
                <a href="{{ route('tenant.channels.reservations.index', ['channel_id' => $ch->id]) }}"
                    class="rounded-full px-3 py-1 text-xs font-medium {{ (string) $channelId === (string) $ch->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $ch->name }}
                </a>
            @endforeach
        </div>
    @endif

    @if ($reservations->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white py-16 text-center">
            <svg class="mb-3 h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <p class="text-sm font-medium text-gray-900">No reservations found</p>
            <p class="mt-1 text-sm text-gray-500">Import reservations from a connected channel above.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Channel</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Guests</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Imported</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $reservation->guest_name }}</p>
                                @if ($reservation->guest_email)
                                    <p class="text-xs text-gray-400">{{ $reservation->guest_email }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->channel->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $reservation->check_in->format('M d') }} – {{ $reservation->check_out->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->guests_count }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                {{ $reservation->currency }} {{ number_format($reservation->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($reservation->isImported())
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                        Booking Created
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $reservation->imported_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if (! $reservation->isImported())
                                    <a href="{{ route('tenant.bookings.create', ['channel_reservation_id' => $reservation->id]) }}"
                                        class="text-xs font-medium text-blue-600 hover:text-blue-700">
                                        Create Booking →
                                    </a>
                                @else
                                    <a href="{{ route('tenant.bookings.show', $reservation->booking_id) }}"
                                        class="text-xs font-medium text-gray-500 hover:text-gray-700">
                                        View Booking
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    @endif
@endsection
