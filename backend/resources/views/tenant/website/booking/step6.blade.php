@extends('layouts.website')

@section('title', 'Booking Confirmed!')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-16 text-center sm:px-6">

    {{-- Checkmark Animation --}}
    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100" style="animation: pop 0.5s ease;">
        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
    </div>

    <h1 class="mt-6 text-3xl font-bold text-gray-900">Booking Confirmed!</h1>
    <p class="mt-2 text-gray-500">Thank you for your booking. A confirmation email has been sent to you.</p>

    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 text-left shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Booking Reference</p>
            <p class="font-mono font-bold text-indigo-600">{{ $booking->reference_number }}</p>
        </div>
        <hr class="my-4 border-gray-100">

        @if($booking->resource)
            <div class="flex gap-4">
                @if($booking->resource->cover_image)
                    <img src="{{ $booking->resource->cover_image }}" alt="{{ $booking->resource->name }}" class="h-16 w-24 rounded-lg object-cover">
                @endif
                <div>
                    <p class="font-semibold text-gray-900">{{ $booking->resource->name }}</p>
                </div>
            </div>
            <hr class="my-4 border-gray-100">
        @endif

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Check In</p>
                <p class="font-semibold">{{ $booking->check_in?->format('D, M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Check Out</p>
                <p class="font-semibold">{{ $booking->check_out?->format('D, M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Guests</p>
                <p class="font-semibold">{{ $booking->guests_count }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status</p>
                <span class="inline-block rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700 capitalize">{{ $booking->status }}</span>
            </div>
        </div>

        <div class="mt-4 flex justify-between border-t border-gray-100 pt-4 text-sm font-bold text-gray-900">
            <span>Total Amount</span>
            <span>${{ number_format((float) $booking->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a href="{{ route('tenant.account.bookings') }}" class="rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white hover:bg-indigo-700">
            View My Bookings
        </a>
        <a href="{{ route('tenant.home') }}" class="rounded-xl border border-gray-300 px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50">
            Return Home
        </a>
    </div>
</div>

<style>
@keyframes pop {
    0% { transform: scale(0.5); opacity: 0; }
    80% { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
}
</style>
@endsection
