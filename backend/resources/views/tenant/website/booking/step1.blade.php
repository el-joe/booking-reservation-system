@extends('layouts.website')

@section('title', 'Book: ' . $resource->name)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    {{-- Progress Steps --}}
    <div class="mb-8 flex items-center justify-center gap-2">
        @foreach(['Dates', 'Add-ons', 'Details', 'Summary', 'Payment', 'Confirm'] as $step)
            <div class="flex items-center {{ !$loop->first ? 'gap-2' : '' }}">
                @if(!$loop->first)
                    <div class="h-px w-6 bg-gray-200"></div>
                @endif
                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                    {{ $loop->iteration === 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                    {{ $loop->iteration }}
                </div>
            </div>
        @endforeach
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Select Dates & Guests</h1>
    <p class="mt-1 text-sm text-gray-500">Booking: <strong>{{ $resource->name }}</strong></p>

    <form action="{{ route('tenant.book.step2') }}" method="POST" class="mt-8">
        @csrf
        <input type="hidden" name="resource_id" value="{{ $resource->id }}">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Check In <span class="text-red-500">*</span></label>
                    <input type="date" name="check_in" value="{{ request('check_in', old('check_in')) }}"
                           min="{{ date('Y-m-d') }}" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('check_in')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Check Out <span class="text-red-500">*</span></label>
                    <input type="date" name="check_out" value="{{ request('check_out', old('check_out')) }}"
                           required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('check_out')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Number of Guests <span class="text-red-500">*</span></label>
                <input type="number" name="guests" value="{{ request('guests', old('guests', 1)) }}"
                       min="1" max="{{ $resource->capacity ?? 100 }}" required
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @if($resource->capacity)
                    <p class="mt-1 text-xs text-gray-400">Maximum {{ $resource->capacity }} guests</p>
                @endif
                @error('guests')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Pricing Preview --}}
            <div class="rounded-lg bg-indigo-50 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Base price</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $resource->formatted_price }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('tenant.listing', $resource->slug) }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                Continue to Add-ons →
            </button>
        </div>
    </form>
</div>
@endsection
