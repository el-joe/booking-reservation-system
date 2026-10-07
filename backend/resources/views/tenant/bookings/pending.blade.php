@extends('layouts.tenant')

@section('title', 'Pending Approvals')

@section('content')
    <x-page-header title="Pending Approvals" subtitle="Review and approve booking requests">
        <a href="{{ route('tenant.bookings.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            All Bookings
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 ring-1 ring-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($pendingBookings->isEmpty())
        <div class="rounded-xl bg-white p-12 text-center shadow-sm ring-1 ring-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p class="mt-3 text-sm font-medium text-gray-900">No pending approvals</p>
            <p class="mt-1 text-sm text-gray-500">All booking requests have been reviewed.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($pendingBookings as $booking)
                <div
                    x-data="{ rejectOpen: false }"
                    class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('tenant.bookings.show', $booking) }}"
                                    class="text-base font-semibold text-blue-600 hover:underline">
                                    {{ $booking->reference_number }}
                                </a>
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                    Pending
                                </span>
                            </div>

                            <div class="mt-2 grid grid-cols-2 gap-x-6 gap-y-1 text-sm text-gray-600">
                                <div><span class="font-medium">Customer:</span> {{ $booking->customer?->full_name ?? '—' }}</div>
                                <div><span class="font-medium">Resource:</span> {{ $booking->resource?->name ?? '—' }}</div>
                                <div><span class="font-medium">Check-in:</span> {{ $booking->check_in->format('d M Y, H:i') }}</div>
                                <div><span class="font-medium">Check-out:</span> {{ $booking->check_out->format('d M Y, H:i') }}</div>
                                <div><span class="font-medium">Guests:</span> {{ $booking->guests_count }}</div>
                                <div><span class="font-medium">Total:</span> ${{ number_format($booking->total_amount, 2) }}</div>
                            </div>
                        </div>

                        <div class="flex shrink-0 gap-2">
                            <form method="POST" action="{{ route('tenant.bookings.confirm', $booking) }}">
                                @csrf
                                <button type="submit"
                                    class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                    Confirm
                                </button>
                            </form>

                            <button type="button" @click="rejectOpen = true"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                Reject
                            </button>
                        </div>
                    </div>

                    {{-- Rejection Modal --}}
                    <div x-show="rejectOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                            <h4 class="mb-3 text-base font-semibold text-gray-900">Reject Booking</h4>
                            <p class="mb-3 text-sm text-gray-600">
                                Rejecting booking <strong>{{ $booking->reference_number }}</strong> for
                                {{ $booking->customer?->full_name }}.
                            </p>
                            <form method="POST" action="{{ route('tenant.bookings.cancel', $booking) }}">
                                @csrf
                                <textarea name="reason" rows="3" required
                                    placeholder="Please provide a rejection reason..."
                                    class="mb-4 block w-full rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"></textarea>
                                <div class="flex gap-3">
                                    <button type="button" @click="rejectOpen = false"
                                        class="flex-1 rounded-lg border border-gray-300 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Back
                                    </button>
                                    <button type="submit"
                                        class="flex-1 rounded-lg bg-red-600 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                        Reject Booking
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
