@extends('layouts.website')

@section('title', 'My Bookings')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>

    {{-- Tabs --}}
    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex gap-6">
            @foreach(['upcoming' => 'Upcoming', 'past' => 'Past', 'cancelled' => 'Cancelled'] as $key => $label)
                <a href="?tab={{ $key }}"
                   class="pb-3 text-sm font-medium border-b-2 transition
                       {{ $tab === $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="mt-6">
        @php $bookings = $$tab; @endphp

        @if($bookings->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 py-16">
                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <p class="mt-3 text-sm text-gray-500">No {{ $tab }} bookings</p>
                @if($tab === 'upcoming')
                    <a href="{{ route('tenant.search') }}" class="mt-3 rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Find a Space</a>
                @endif
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="flex gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        @if($booking->resource?->cover_image)
                            <img src="{{ $booking->resource->cover_image }}" alt="{{ $booking->resource->name }}" class="h-20 w-28 shrink-0 rounded-lg object-cover">
                        @else
                            <div class="h-20 w-28 shrink-0 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <svg class="h-8 w-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18" /></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->resource?->name ?? 'Resource' }}</p>
                                    <p class="text-xs font-mono text-gray-400">{{ $booking->reference_number }}</p>
                                </div>
                                @php
                                    $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-blue-100 text-blue-700'];
                                    $color = $statusColors[$booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }} capitalize">
                                    {{ $booking->status instanceof \BackedEnum ? $booking->status->label() : $booking->status }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $booking->check_in?->format('M j') }} – {{ $booking->check_out?->format('M j, Y') }}
                                &bull; {{ $booking->guests_count }} guest{{ $booking->guests_count !== 1 ? 's' : '' }}
                            </p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="font-semibold text-gray-900">${{ number_format((float) $booking->total_amount, 2) }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('tenant.account.booking-detail', $booking) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">View Details</a>
                                    @if(in_array($booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status, ['pending', 'confirmed']))
                                        <button type="button"
                                                x-data
                                                @click="$dispatch('open-cancel', { id: {{ $booking->id }} })"
                                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Cancel</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
<div x-data="{ open: false, bookingId: null }"
     @open-cancel.window="open = true; bookingId = $event.detail.id"
     x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="w-full max-w-md rounded-2xl bg-white p-6" @click.outside="open = false">
        <h3 class="text-lg font-semibold text-gray-900">Cancel Booking</h3>
        <p class="mt-1 text-sm text-gray-500">Are you sure you want to cancel this booking?</p>
        <form :action="`/account/bookings/${bookingId}/cancel`" method="POST" class="mt-4">
            @csrf
            <textarea name="reason" rows="3" placeholder="Reason for cancellation (optional)"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none"></textarea>
            <div class="mt-4 flex gap-3 justify-end">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium">Keep Booking</button>
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Cancel Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection
