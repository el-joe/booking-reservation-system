@extends('layouts.tenant')

@section('title', 'Booking ' . $booking->reference_number)

@section('content')
    <x-page-header :title="'Booking ' . $booking->reference_number" subtitle="Booking details and status">
        <a href="{{ route('tenant.bookings.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            ← Back to Bookings
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 ring-1 ring-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left Column: Booking Details --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Booking Info Card --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Booking Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Reference</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->reference_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full bg-{{ $booking->status->color() }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $booking->status->color() }}-800">
                                {{ $booking->status->label() }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->customer?->full_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Resource</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->resource?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Check-in</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->check_in->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Check-out</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->check_out->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Guests</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->guests_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Source</dt>
                        <dd class="mt-1 text-sm capitalize text-gray-900">{{ str_replace('_', ' ', $booking->source) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Amount</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">${{ number_format($booking->total_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Paid Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($booking->paid_amount, 2) }}</dd>
                    </div>
                    @if ($booking->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-700">{{ $booking->notes }}</dd>
                        </div>
                    @endif
                    @if ($booking->cancellation_reason)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Cancellation Reason</dt>
                            <dd class="mt-1 text-sm text-red-600">{{ $booking->cancellation_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Booking Items Table --}}
            @if ($booking->items->isNotEmpty())
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="mb-4 text-base font-semibold text-gray-900">Add-on Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Description</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Qty</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Unit Price</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($booking->items as $item)
                                    <tr>
                                        <td class="py-3 text-gray-900">{{ $item->description }}</td>
                                        <td class="py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                                        <td class="py-3 text-right text-gray-700">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-3 text-right font-medium text-gray-900">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Status & History --}}
        <div class="space-y-6">
            {{-- Actions Card --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('tenant.bookings.edit', $booking) }}"
                        class="block w-full rounded-lg border border-gray-300 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Edit Booking
                    </a>

                    @if ($booking->isPending())
                        <form method="POST" action="{{ route('tenant.bookings.confirm', $booking) }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-green-600 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                Confirm Booking
                            </button>
                        </form>
                    @endif

                    @if ($booking->isConfirmed())
                        <form method="POST" action="{{ route('tenant.bookings.checkin', $booking) }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                Process Check-in
                            </button>
                        </form>
                    @endif

                    @if ($booking->status === \App\Enums\BookingStatus::CheckedIn)
                        <form method="POST" action="{{ route('tenant.bookings.checkout', $booking) }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-blue-600 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Process Check-out
                            </button>
                        </form>
                        <form method="POST" action="{{ route('tenant.bookings.noshow', $booking) }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-gray-600 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Mark No-Show
                            </button>
                        </form>
                    @endif

                    @if (! in_array($booking->status, [\App\Enums\BookingStatus::Cancelled, \App\Enums\BookingStatus::Completed, \App\Enums\BookingStatus::NoShow]))
                        <div x-data="{ open: false }">
                            <button type="button" @click="open = true"
                                class="w-full rounded-lg bg-red-600 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                Cancel Booking
                            </button>
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                                    <h4 class="mb-3 text-base font-semibold text-gray-900">Cancel Booking</h4>
                                    <form method="POST" action="{{ route('tenant.bookings.cancel', $booking) }}">
                                        @csrf
                                        <textarea name="reason" rows="3" required
                                            placeholder="Please provide a cancellation reason..."
                                            class="mb-4 block w-full rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"></textarea>
                                        <div class="flex gap-3">
                                            <button type="button" @click="open = false"
                                                class="flex-1 rounded-lg border border-gray-300 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                Back
                                            </button>
                                            <button type="submit"
                                                class="flex-1 rounded-lg bg-red-600 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                Confirm Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status History Timeline --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Status History</h3>
                <div class="space-y-4">
                    @forelse ($booking->statusHistories->sortByDesc('created_at') as $history)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-blue-600 ring-2 ring-blue-100"></div>
                                @if (! $loop->last)
                                    <div class="mt-1 h-full w-0.5 bg-gray-200"></div>
                                @endif
                            </div>
                            <div class="pb-4">
                                <p class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $history->status)) }}</p>
                                <p class="text-xs text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                @if ($history->note)
                                    <p class="mt-1 text-xs text-gray-600">{{ $history->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No history yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
