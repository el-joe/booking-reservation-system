@extends('layouts.tenant')

@section('title', 'Create Campaign')

@section('content')
    <x-page-header title="Create Campaign" subtitle="Set up a new marketing campaign">
        <a href="{{ route('tenant.marketing.campaigns.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </x-page-header>

    <form method="POST" action="{{ route('tenant.marketing.campaigns.store') }}" class="space-y-6">
        @csrf

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Campaign Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Campaign Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                        placeholder="e.g. Summer Promo 2026">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Type <span class="text-red-500">*</span></label>
                    <select name="type" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select type</option>
                        <option value="email" {{ old('type') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('type') === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="push" {{ old('type') === 'push' ? 'selected' : '' }}>Push Notification</option>
                    </select>
                    @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('scheduled_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Email subject line">
                    @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Body (HTML)</label>
                    <textarea name="body_html" rows="6"
                        class="block w-full rounded-lg border-gray-300 font-mono text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="<p>Hello {{customer_name}},</p>...">{{ old('body_html') }}</textarea>
                    @error('body_html')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Body (Plain Text)</label>
                    <textarea name="body_text" rows="4"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Plain text fallback...">{{ old('body_text') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Audience Filter</h3>
            <p class="mb-4 text-sm text-gray-500">Leave all filters blank to target all customers.</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Last Booking From</label>
                    <input type="date" name="audience_filter[last_booking_from]"
                        value="{{ old('audience_filter.last_booking_from') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Last Booking To</label>
                    <input type="date" name="audience_filter[last_booking_to]"
                        value="{{ old('audience_filter.last_booking_to') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Min Total Spent</label>
                    <input type="number" name="audience_filter[min_spent]" min="0" step="0.01"
                        value="{{ old('audience_filter.min_spent') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Max Total Spent</label>
                    <input type="number" name="audience_filter[max_spent]" min="0" step="0.01"
                        value="{{ old('audience_filter.max_spent') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tenant.marketing.campaigns.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Create Campaign
            </button>
        </div>
    </form>
@endsection
