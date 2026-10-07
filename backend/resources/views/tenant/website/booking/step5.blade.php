@extends('layouts.website')

@section('title', 'Payment')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-10 sm:px-6">
    <div class="mb-8 flex items-center justify-center gap-2">
        @foreach(['Dates', 'Add-ons', 'Details', 'Summary', 'Payment', 'Confirm'] as $step)
            <div class="flex items-center {{ !$loop->first ? 'gap-2' : '' }}">
                @if(!$loop->first)<div class="h-px w-6 bg-gray-200"></div>@endif
                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                    {{ $loop->iteration <= 5 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                    {{ $loop->iteration }}
                </div>
            </div>
        @endforeach
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Payment</h1>
    <p class="mt-1 text-sm text-gray-500">Complete your booking for <strong>{{ $resource->name }}</strong></p>

    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        {{-- Stripe Payment Element placeholder --}}
        <div id="payment-element" class="min-h-[120px] rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center">
            <p class="text-sm text-gray-400">Payment gateway integration (Stripe Elements)</p>
        </div>

        <div class="mt-6 space-y-3">
            {{-- Pay Now --}}
            <form action="{{ route('tenant.book.confirm') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">
                    Confirm Booking (Pay Later / Free)
                </button>
            </form>
        </div>

        <p class="mt-4 text-center text-xs text-gray-400">
            Your booking details are secured. By completing this booking you agree to the cancellation policy.
        </p>
    </div>

    <div class="mt-4 text-center">
        <button onclick="history.back()" class="text-sm text-gray-500 hover:text-gray-700">← Back to Summary</button>
    </div>
</div>
@endsection
