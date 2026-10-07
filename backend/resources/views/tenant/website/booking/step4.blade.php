@extends('layouts.website')

@section('title', 'Booking Summary')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <div class="mb-8 flex items-center justify-center gap-2">
        @foreach(['Dates', 'Add-ons', 'Details', 'Summary', 'Payment', 'Confirm'] as $step)
            <div class="flex items-center {{ !$loop->first ? 'gap-2' : '' }}">
                @if(!$loop->first)<div class="h-px w-6 bg-gray-200"></div>@endif
                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                    {{ $loop->iteration <= 4 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                    {{ $loop->iteration }}
                </div>
            </div>
        @endforeach
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Booking Summary</h1>

    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
        {{-- Resource --}}
        <div class="flex gap-4">
            @if($resource->cover_image)
                <img src="{{ $resource->cover_image }}" alt="{{ $resource->name }}" class="h-20 w-28 rounded-lg object-cover shrink-0">
            @endif
            <div>
                <p class="font-semibold text-gray-900">{{ $resource->name }}</p>
                <p class="text-sm text-indigo-600">{{ $resource->booking_type instanceof \BackedEnum ? $resource->booking_type->label() : ucfirst(str_replace('_', ' ', $resource->booking_type)) }}</p>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- Dates --}}
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Check In</p>
                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($step1['check_in'])->format('D, M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Check Out</p>
                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($step1['check_out'])->format('D, M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Guests</p>
                <p class="font-semibold text-gray-900">{{ $step1['guests'] }}</p>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- Guest Details --}}
        <div class="text-sm">
            <p class="font-semibold text-gray-900">Guest: {{ $guestData['guest_name'] }}</p>
            <p class="text-gray-500">{{ $guestData['guest_email'] }}</p>
            @if(!empty($guestData['guest_phone']))<p class="text-gray-500">{{ $guestData['guest_phone'] }}</p>@endif
            @if(!empty($guestData['special_requests']))<p class="mt-2 text-gray-500 italic">"{{ $guestData['special_requests'] }}"</p>@endif
        </div>

        <hr class="border-gray-100">

        {{-- Pricing --}}
        @php
            $nights = \Carbon\Carbon::parse($step1['check_in'])->diffInDays(\Carbon\Carbon::parse($step1['check_out']));
            $nights = max($nights, 1);
            $baseTotal = (float) $resource->base_price * $nights;
            $tax = round($baseTotal * 0.1, 2);
            $total = $baseTotal + $tax;
        @endphp
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">{{ $resource->formatted_price }} × {{ $nights }} night{{ $nights !== 1 ? 's' : '' }}</span>
                <span>${{ number_format($baseTotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Taxes (10%)</span>
                <span>${{ number_format($tax, 2) }}</span>
            </div>
            <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-gray-900">
                <span>Total</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('tenant.book.step5') }}" method="POST">
        @csrf
        <input type="hidden" name="total_amount" value="{{ $total }}">
        <div class="mt-6 flex justify-between">
            <button type="button" onclick="history.back()" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </button>
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                Proceed to Payment →
            </button>
        </div>
    </form>
</div>
@endsection
