@extends('layouts.central')

@section('title', 'Edit Tenant')

@section('content')
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('central.tenants.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Tenants</a>
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <a href="{{ route('central.tenants.show', $tenant) }}" class="text-sm text-gray-500 hover:text-gray-700">{{ $tenant->name }}</a>
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-sm font-medium text-gray-900">Edit</span>
    </div>

    <div class="rounded-lg bg-white shadow">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Edit Tenant</h2>
        </div>
        <form method="POST" action="{{ route('central.tenants.update', $tenant) }}" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Business Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror"
                           required>
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Domain</label>
                    <p class="mt-1 text-sm text-gray-600 bg-gray-50 rounded-md border border-gray-200 px-3 py-2">
                        {{ $tenant->domains()->first()?->domain ?? '—' }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Domain cannot be changed after creation.</p>
                </div>

                <div>
                    <label for="contact_name" class="block text-sm font-medium text-gray-700">Contact Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $tenant->contact_name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('contact_name') border-red-500 @enderror"
                           required>
                    @error('contact_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $tenant->contact_email) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('contact_email') border-red-500 @enderror"
                           required>
                    @error('contact_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type <span class="text-red-500">*</span></label>
                    <select name="business_type" id="business_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('business_type') border-red-500 @enderror"
                            required>
                        <option value="">— Select type —</option>
                        @foreach (\App\Enums\BookingType::cases() as $type)
                            <option value="{{ $type->value }}"
                                {{ old('business_type', $tenant->business_type?->value) === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700">Plan <span class="text-red-500">*</span></label>
                    <select name="plan_id" id="plan_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('plan_id') border-red-500 @enderror"
                            required>
                        <option value="">— Select plan —</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}"
                                {{ old('plan_id', $tenant->subscription?->plan_id) == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} ({{ $plan->billing_cycle }})
                            </option>
                        @endforeach
                    </select>
                    @error('plan_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo URL</label>
                    <input type="text" name="logo" id="logo" value="{{ old('logo', $tenant->logo) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="https://...">
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes', $tenant->notes) }}</textarea>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('central.tenants.show', $tenant) }}"
                   class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
