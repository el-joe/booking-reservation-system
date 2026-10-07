@extends('layouts.tenant')

@section('title', 'Booking Settings')

@section('content')
    <x-page-header title="Settings" subtitle="Configure your account" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                @include('tenant.settings.partials.sidebar')
            </div>
        </div>
        <div class="lg:col-span-3">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-gray-900">Booking Settings</h2>

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('tenant.settings.booking.update') }}">
                    @csrf

                    <div class="space-y-5">
                        <div x-data="{ enabled: {{ ($settings['auto_confirm_bookings'] ?? '0') === '1' ? 'true' : 'false' }} }">
                            <label class="flex cursor-pointer items-center gap-3">
                                <button type="button" @click="enabled = !enabled"
                                    :class="enabled ? 'bg-blue-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 rounded-full transition-colors duration-200">
                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                        class="inline-block h-5 w-5 translate-x-0.5 transform rounded-full bg-white shadow transition duration-200 mt-0.5 ml-0.5"></span>
                                </button>
                                <input type="hidden" name="auto_confirm_bookings" :value="enabled ? '1' : '0'">
                                <span class="text-sm font-medium text-gray-700">Auto-confirm bookings</span>
                            </label>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Buffer Time (minutes)</label>
                            <input type="number" name="buffer_time_minutes" min="0" value="{{ old('buffer_time_minutes', $settings['buffer_time_minutes'] ?? 0) }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('buffer_time_minutes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Min Advance Booking (hours)</label>
                            <input type="number" name="min_advance_booking_hours" min="0" value="{{ old('min_advance_booking_hours', $settings['min_advance_booking_hours'] ?? 1) }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('min_advance_booking_hours') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Max Advance Booking (days)</label>
                            <input type="number" name="max_advance_booking_days" min="1" value="{{ old('max_advance_booking_days', $settings['max_advance_booking_days'] ?? 365) }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('max_advance_booking_days') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Max Guests</label>
                            <input type="number" name="max_guests" min="1" value="{{ old('max_guests', $settings['max_guests'] ?? 10) }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('max_guests') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
