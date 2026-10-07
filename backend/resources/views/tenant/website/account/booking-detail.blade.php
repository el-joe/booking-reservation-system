@extends('layouts.website')

@section('title', 'Booking #' . $booking->reference_number)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tenant.account.bookings') }}" class="text-sm text-indigo-600 hover:underline">← My Bookings</a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Booking Details</h1>
                <p class="text-sm font-mono text-indigo-600">{{ $booking->reference_number }}</p>
            </div>
            @php
                $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-blue-100 text-blue-700'];
                $statusValue = $booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status;
                $color = $statusColors[$statusValue] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $color }} capitalize">
                {{ $booking->status instanceof \BackedEnum ? $booking->status->label() : $booking->status }}
            </span>
        </div>

        @if($booking->resource)
            <div class="flex gap-4">
                @if($booking->resource->cover_image)
                    <img src="{{ $booking->resource->cover_image }}" alt="{{ $booking->resource->name }}" class="h-20 w-28 rounded-lg object-cover shrink-0">
                @endif
                <div>
                    <p class="font-semibold text-gray-900">{{ $booking->resource->name }}</p>
                    <a href="{{ route('tenant.listing', $booking->resource->slug) }}" class="text-xs text-indigo-500 hover:underline">View listing</a>
                </div>
            </div>
            <hr class="border-gray-100">
        @endif

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Check In</p>
                <p class="font-semibold text-gray-900">{{ $booking->check_in?->format('D, M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Check Out</p>
                <p class="font-semibold text-gray-900">{{ $booking->check_out?->format('D, M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Guests</p>
                <p class="font-semibold text-gray-900">{{ $booking->guests_count }}</p>
            </div>
            <div>
                <p class="text-gray-500">Booked At</p>
                <p class="font-semibold text-gray-900">{{ $booking->created_at->format('M j, Y') }}</p>
            </div>
        </div>

        @if($booking->notes)
            <div class="rounded-lg bg-gray-50 p-3">
                <p class="text-xs font-semibold text-gray-600">Special Requests</p>
                <p class="mt-1 text-sm text-gray-700">{{ $booking->notes }}</p>
            </div>
        @endif

        <div class="flex justify-between border-t border-gray-100 pt-4 font-bold text-gray-900">
            <span>Total Amount</span>
            <span>${{ number_format((float) $booking->total_amount, 2) }}</span>
        </div>

        <div class="flex gap-3 flex-wrap">
            @if(in_array($statusValue, ['pending', 'confirmed']))
                <button type="button"
                        x-data
                        @click="$dispatch('open-cancel')"
                        class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                    Cancel Booking
                </button>
            @endif
            @if($statusValue === 'completed' && !$hasReview)
                <button type="button" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Leave a Review
                </button>
            @endif
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div x-data="{ open: false }"
     @open-cancel.window="open = true"
     x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="w-full max-w-md rounded-2xl bg-white p-6" @click.outside="open = false">
        <h3 class="text-lg font-semibold text-gray-900">Cancel Booking</h3>
        <form action="{{ route('tenant.account.cancel-booking', $booking) }}" method="POST" class="mt-4">
            @csrf
            <textarea name="reason" rows="3" placeholder="Reason for cancellation (optional)"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none"></textarea>
            <div class="mt-4 flex gap-3 justify-end">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium">Back</button>
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>
@endsection
