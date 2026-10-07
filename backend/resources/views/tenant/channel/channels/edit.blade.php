@extends('layouts.tenant')

@section('title', 'Edit Channel')

@section('content')
    <x-page-header title="Edit Channel" :subtitle="$channel->name">
        <a href="{{ route('tenant.channels.ota.show', $channel) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('tenant.channels.ota.update', $channel) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-gray-900">Channel Details</h2>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Channel Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $channel->name) }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="type" class="mb-1 block text-sm font-medium text-gray-700">Channel Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach (['booking_com' => 'Booking.com', 'airbnb' => 'Airbnb', 'expedia' => 'Expedia', 'agoda' => 'Agoda', 'custom' => 'Custom / Other'] as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $channel->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending'] as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $channel->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="channel_property_id" class="mb-1 block text-sm font-medium text-gray-700">Property ID</label>
                        <input type="text" id="channel_property_id" name="channel_property_id"
                            value="{{ old('channel_property_id', $channel->channel_property_id) }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-1 text-sm font-semibold text-gray-900">API Credentials</h2>
                <p class="mb-4 text-xs text-gray-500">Leave blank to keep existing credentials.</p>
                <div class="space-y-4">
                    <div>
                        <label for="api_key" class="mb-1 block text-sm font-medium text-gray-700">API Key</label>
                        <input type="password" id="api_key" name="api_key" placeholder="Leave blank to keep current"
                            class="block w-full rounded-lg border-gray-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="api_secret" class="mb-1 block text-sm font-medium text-gray-700">API Secret</label>
                        <input type="password" id="api_secret" name="api_secret" placeholder="Leave blank to keep current"
                            class="block w-full rounded-lg border-gray-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="hidden" name="sync_enabled" value="0">
                    <input type="checkbox" name="sync_enabled" value="1"
                        {{ old('sync_enabled', $channel->sync_enabled) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Enable automatic sync</p>
                        <p class="text-xs text-gray-500">Automatically push availability updates and pull new reservations.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('tenant.channels.ota.show', $channel) }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
