@extends('layouts.tenant')

@section('title', 'Edit Booking ' . $booking->reference_number)

@section('content')
    <x-page-header :title="'Edit Booking ' . $booking->reference_number" subtitle="Update booking details">
        <a href="{{ route('tenant.bookings.show', $booking) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <form method="POST" action="{{ route('tenant.bookings.update', $booking) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="check_in" class="mb-1 block text-sm font-medium text-gray-700">Check-in</label>
                    <input type="datetime-local" id="check_in" name="check_in"
                        value="{{ old('check_in', $booking->check_in->format('Y-m-d\TH:i')) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('check_in') border-red-500 @enderror">
                    @error('check_in')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_out" class="mb-1 block text-sm font-medium text-gray-700">Check-out</label>
                    <input type="datetime-local" id="check_out" name="check_out"
                        value="{{ old('check_out', $booking->check_out->format('Y-m-d\TH:i')) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('check_out') border-red-500 @enderror">
                    @error('check_out')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="guests_count" class="mb-1 block text-sm font-medium text-gray-700">Guests Count</label>
                    <input type="number" id="guests_count" name="guests_count" min="1"
                        value="{{ old('guests_count', $booking->guests_count) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('guests_count') border-red-500 @enderror">
                    @error('guests_count')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="mb-1 block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes" rows="4"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                        placeholder="Special requests or notes...">{{ old('notes', $booking->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('tenant.bookings.show', $booking) }}"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
