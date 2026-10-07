@extends('layouts.tenant')

@section('title', 'Connect Channel')

@section('content')
    <x-page-header title="Connect Channel" subtitle="Add a new OTA channel connection">
        <a href="{{ route('tenant.channels.ota.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Back to Channels
        </a>
    </x-page-header>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('tenant.channels.ota.store') }}" class="space-y-6">
            @csrf

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-gray-900">Channel Details</h2>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Channel Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            placeholder="e.g. Booking.com Main Property"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="mb-1 block text-sm font-medium text-gray-700">Channel Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-300 @enderror">
                            <option value="">Select a channel type</option>
                            <option value="booking_com" {{ old('type') === 'booking_com' ? 'selected' : '' }}>Booking.com</option>
                            <option value="airbnb" {{ old('type') === 'airbnb' ? 'selected' : '' }}>Airbnb</option>
                            <option value="expedia" {{ old('type') === 'expedia' ? 'selected' : '' }}>Expedia</option>
                            <option value="agoda" {{ old('type') === 'agoda' ? 'selected' : '' }}>Agoda</option>
                            <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>Custom / Other</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="channel_property_id" class="mb-1 block text-sm font-medium text-gray-700">Property ID</label>
                        <input type="text" id="channel_property_id" name="channel_property_id" value="{{ old('channel_property_id') }}"
                            placeholder="Your property ID on the OTA platform"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">The unique identifier assigned to your property by the OTA.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-1 text-sm font-semibold text-gray-900">API Credentials</h2>
                <p class="mb-4 text-xs text-gray-500">Credentials are encrypted at rest. Leave blank if not yet available.</p>

                <div class="space-y-4">
                    <div>
                        <label for="api_key" class="mb-1 block text-sm font-medium text-gray-700">API Key</label>
                        <input type="password" id="api_key" name="api_key" value="{{ old('api_key') }}"
                            placeholder="••••••••"
                            class="block w-full rounded-lg border-gray-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="api_secret" class="mb-1 block text-sm font-medium text-gray-700">API Secret</label>
                        <input type="password" id="api_secret" name="api_secret" value="{{ old('api_secret') }}"
                            placeholder="••••••••"
                            class="block w-full rounded-lg border-gray-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-gray-900">Sync Settings</h2>

                <label class="flex cursor-pointer items-center gap-3">
                    <input type="hidden" name="sync_enabled" value="0">
                    <input type="checkbox" name="sync_enabled" value="1" {{ old('sync_enabled') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Enable automatic sync</p>
                        <p class="text-xs text-gray-500">Automatically push availability updates and pull new reservations.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('tenant.channels.ota.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Connect Channel
                </button>
            </div>
        </form>
    </div>
@endsection
