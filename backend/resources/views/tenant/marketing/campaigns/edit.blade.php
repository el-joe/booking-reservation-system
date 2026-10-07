@extends('layouts.tenant')

@section('title', 'Edit Campaign')

@section('content')
    <x-page-header title="Edit Campaign" subtitle="{{ $campaign->name }}">
        <a href="{{ route('tenant.marketing.campaigns.show', $campaign) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
    </x-page-header>

    <form method="POST" action="{{ route('tenant.marketing.campaigns.update', $campaign) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Campaign Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Campaign Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Type <span class="text-red-500">*</span></label>
                    <select name="type" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="email" {{ old('type', $campaign->type) === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('type', $campaign->type) === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="push" {{ old('type', $campaign->type) === 'push' ? 'selected' : '' }}>Push Notification</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at"
                        value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject', $campaign->subject) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Body (HTML)</label>
                    <textarea name="body_html" rows="6"
                        class="block w-full rounded-lg border-gray-300 font-mono text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body_html', $campaign->body_html) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Body (Plain Text)</label>
                    <textarea name="body_text" rows="4"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body_text', $campaign->body_text) }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Audience Filter</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Last Booking From</label>
                    <input type="date" name="audience_filter[last_booking_from]"
                        value="{{ old('audience_filter.last_booking_from', $campaign->audience_filter['last_booking_from'] ?? '') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Last Booking To</label>
                    <input type="date" name="audience_filter[last_booking_to]"
                        value="{{ old('audience_filter.last_booking_to', $campaign->audience_filter['last_booking_to'] ?? '') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Min Total Spent</label>
                    <input type="number" name="audience_filter[min_spent]" min="0" step="0.01"
                        value="{{ old('audience_filter.min_spent', $campaign->audience_filter['min_spent'] ?? '') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Max Total Spent</label>
                    <input type="number" name="audience_filter[max_spent]" min="0" step="0.01"
                        value="{{ old('audience_filter.max_spent', $campaign->audience_filter['max_spent'] ?? '') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tenant.marketing.campaigns.show', $campaign) }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
@endsection
