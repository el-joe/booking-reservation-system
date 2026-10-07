@extends('layouts.tenant')

@section('title', 'Edit Referral Program')

@section('content')
    <x-page-header title="Edit Referral Program" subtitle="Update referral program settings">
        <a href="{{ route('tenant.marketing.referrals.show', $referral) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </x-page-header>

    <form method="POST" action="{{ route('tenant.marketing.referrals.update', $referral) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Program Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Program Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $referral->name) }}" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror">{{ old('description', $referral->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Reward Type <span class="text-red-500">*</span></label>
                    <select name="reward_type" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('reward_type') border-red-300 @enderror">
                        <option value="">Select reward type</option>
                        <option value="fixed" {{ old('reward_type', $referral->reward_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percent" {{ old('reward_type', $referral->reward_type) === 'percent' ? 'selected' : '' }}>Percentage</option>
                        <option value="points" {{ old('reward_type', $referral->reward_type) === 'points' ? 'selected' : '' }}>Points</option>
                    </select>
                    @error('reward_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Reward Value <span class="text-red-500">*</span></label>
                    <input type="number" name="reward_value" value="{{ old('reward_value', $referral->reward_value) }}" required min="0" step="0.01"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('reward_value') border-red-300 @enderror">
                    @error('reward_value')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Starts At</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', $referral->starts_at?->format('Y-m-d')) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('starts_at') border-red-300 @enderror">
                    @error('starts_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Ends At</label>
                    <input type="date" name="ends_at" value="{{ old('ends_at', $referral->ends_at?->format('Y-m-d')) }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('ends_at') border-red-300 @enderror">
                    @error('ends_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $referral->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    @error('is_active')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tenant.marketing.referrals.show', $referral) }}"
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
