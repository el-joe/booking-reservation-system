@extends('layouts.tenant')

@section('title', 'Edit Resource: ' . $resource->name)

@section('content')
    <x-page-header :title="'Edit: ' . $resource->name" subtitle="Update resource details, availability, pricing, and media.">
        <a href="{{ route('tenant.resources.show', $resource) }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back to Resource
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div x-data="{ activeTab: '{{ request('tab', 'general') }}' }">
        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-6 overflow-x-auto">
                @foreach (['general' => 'General', 'availability' => 'Availability', 'pricing' => 'Pricing', 'media' => 'Media', 'addons' => 'Add-ons'] as $tab => $label)
                    <button @click="activeTab = '{{ $tab }}'"
                            :class="activeTab === '{{ $tab }}'
                                ? 'border-indigo-500 text-indigo-600'
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors">
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Tab: General --}}
        <div x-show="activeTab === 'general'" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <form action="{{ route('tenant.resources.update', $resource) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-form-input name="name" label="Resource Name" :value="old('name', $resource->name)" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Booking Type</label>
                        <select name="booking_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach (\App\Enums\BookingType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('booking_type', $resource->booking_type?->value) === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type</label>
                        <select name="resource_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach (\App\Enums\ResourceType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('resource_type', $resource->resource_type?->value) === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-form-input name="capacity" label="Capacity" type="number" :value="old('capacity', $resource->capacity)" min="1" />
                    </div>
                    <div>
                        <x-form-input name="base_price" label="Base Price" type="number" step="0.01" :value="old('base_price', $resource->base_price)" min="0" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Unit</label>
                        <select name="price_unit" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach (['per_night' => 'Per Night', 'per_hour' => 'Per Hour', 'per_person' => 'Per Person', 'per_unit' => 'Per Unit'] as $value => $label)
                                <option value="{{ $value }}" {{ old('price_unit', $resource->price_unit) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach (\App\Enums\ResourceStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('status', $resource->status?->value) === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <x-form-textarea name="description" label="Description" :value="old('description', $resource->description)" rows="4" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Save General Info</button>
                </div>
            </form>
        </div>

        {{-- Tab: Availability --}}
        <div x-show="activeTab === 'availability'" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-900">Availability Overrides</h3>
                <p class="mt-1 text-sm text-gray-500">Override capacity or close specific dates.</p>
            </div>

            <form action="{{ route('tenant.resources.availability.update', $resource) }}" method="POST" x-data="availabilityForm()">
                @csrf
                <div class="mb-4 flex items-end gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" x-model="newDate" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                        <input type="number" x-model="newCapacity" min="0" placeholder="{{ $resource->capacity }}" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="newClosed" id="is_closed" class="rounded border-gray-300 text-indigo-600">
                        <label for="is_closed" class="text-sm text-gray-700">Closed</label>
                    </div>
                    <button type="button" @click="addDate()" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Add</button>
                </div>

                <div x-show="dates.length > 0" class="mb-4 overflow-hidden rounded-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Capacity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Closed</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(d, i) in dates" :key="i">
                                <tr>
                                    <td class="px-4 py-2 font-mono text-xs" x-text="d.date">
                                        <input type="hidden" :name="'dates[' + i + '][date]'" :value="d.date">
                                    </td>
                                    <td class="px-4 py-2" x-text="d.available_capacity">
                                        <input type="hidden" :name="'dates[' + i + '][available_capacity]'" :value="d.available_capacity">
                                    </td>
                                    <td class="px-4 py-2">
                                        <span x-text="d.is_closed ? 'Yes' : 'No'"></span>
                                        <input type="hidden" :name="'dates[' + i + '][is_closed]'" :value="d.is_closed ? '1' : '0'">
                                    </td>
                                    <td class="px-4 py-2">
                                        <button type="button" @click="dates.splice(i, 1)" class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="submit" x-show="dates.length > 0" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Save Availability</button>
            </form>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Block Dates</h3>
                <form action="{{ route('tenant.resources.availability.block', $resource) }}" method="POST" x-data="blockForm()">
                    @csrf
                    <div class="flex items-end gap-3 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" x-model="newBlockDate" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <button type="button" @click="addBlockDate()" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Add</button>
                    </div>
                    <div x-show="blockDates.length > 0" class="mb-4 flex flex-wrap gap-2">
                        <template x-for="(d, i) in blockDates" :key="i">
                            <span class="inline-flex items-center gap-1 rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                <span x-text="d"></span>
                                <input type="hidden" :name="'dates[]'" :value="d">
                                <button type="button" @click="blockDates.splice(i, 1)" class="ml-1 text-red-500">&times;</button>
                            </span>
                        </template>
                    </div>
                    <div x-show="blockDates.length > 0" class="flex items-end gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <input type="text" name="reason" required placeholder="e.g. Maintenance" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Block Dates</button>
                    </div>
                </form>

                @if ($resource->blackoutDates->isNotEmpty())
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Existing Blocked Dates:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($resource->blackoutDates->sortBy('date') as $blackout)
                                <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                    {{ $blackout->date->format('Y-m-d') }}
                                    @if ($blackout->reason)
                                        — {{ $blackout->reason }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('tenant.resources.availability', $resource) }}"
                   class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25" />
                    </svg>
                    Open Full Calendar View
                </a>
            </div>
        </div>

        {{-- Tab: Pricing --}}
        <div x-show="activeTab === 'pricing'" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Pricing Rules</h3>

            @if ($resource->pricingRules->isNotEmpty())
                <div class="mb-6 overflow-hidden rounded-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Modifier</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Priority</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Active</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($resource->pricingRules as $rule)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $rule->name }}</td>
                                    <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $rule->rule_type) }}</td>
                                    <td class="px-4 py-2">
                                        @if ($rule->modifier_type === 'percent')
                                            {{ $rule->modifier_value > 0 ? '+' : '' }}{{ $rule->modifier_value }}%
                                        @else
                                            {{ $rule->modifier_value > 0 ? '+$' : '-$' }}{{ abs($rule->modifier_value) }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $rule->priority }}</td>
                                    <td class="px-4 py-2">
                                        @if ($rule->is_active)
                                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">Active</span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('tenant.resources.pricing.destroy', [$resource, $rule]) }}" class="inline" onsubmit="return confirm('Delete this rule?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mb-6 text-sm text-gray-500">No pricing rules yet. Add one below.</p>
            @endif

            <h4 class="mb-4 text-sm font-semibold text-gray-800">Add Pricing Rule</h4>
            <form action="{{ route('tenant.resources.pricing.store', $resource) }}" method="POST" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Weekend Rate">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rule Type</label>
                    <select name="rule_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach (['weekday', 'weekend', 'seasonal', 'dynamic'] as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modifier Type</label>
                    <select name="modifier_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="percent">Percent (%)</option>
                        <option value="fixed">Fixed ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modifier Value</label>
                    <input type="number" name="modifier_value" step="0.01" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Applies From</label>
                    <input type="date" name="applies_from" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Applies To</label>
                    <input type="date" name="applies_to" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <input type="number" name="priority" min="0" value="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" id="is_active_rule" value="1" checked class="rounded border-gray-300 text-indigo-600">
                    <label for="is_active_rule" class="text-sm text-gray-700">Active</label>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Add Rule</button>
                </div>
            </form>
        </div>

        {{-- Tab: Media --}}
        <div x-show="activeTab === 'media'" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200" x-data="mediaManager()">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Media</h3>

            {{-- Upload Zone --}}
            <div class="mb-6 rounded-lg border-2 border-dashed border-gray-300 p-6 text-center hover:border-indigo-400 transition-colors">
                <form id="media-upload-form" enctype="multipart/form-data">
                    @csrf
                    <label for="media-file-input" class="cursor-pointer">
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-400 mt-1">Images (JPG, PNG, GIF, WebP) or Videos (MP4, MOV)</p>
                        <input type="file" id="media-file-input" name="file" accept="image/*,video/*" class="hidden" @change="uploadFile($event)">
                    </label>
                </form>
                <div x-show="uploading" class="mt-3 text-sm text-indigo-600">Uploading...</div>
            </div>

            {{-- Media Grid --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4" id="media-grid">
                @foreach ($resource->media as $media)
                    <div class="group relative rounded-lg overflow-hidden border border-gray-200" data-id="{{ $media->id }}">
                        @if ($media->file_type === 'video')
                            <video src="{{ Storage::url($media->file_path) }}" class="h-40 w-full object-cover"></video>
                        @else
                            <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->caption }}" class="h-40 w-full object-cover">
                        @endif

                        @if ($media->is_cover)
                            <span class="absolute top-2 left-2 rounded-md bg-indigo-600 px-2 py-0.5 text-xs font-medium text-white">Cover</span>
                        @endif

                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            @if (! $media->is_cover)
                                <button type="button"
                                        onclick="setCover({{ $media->id }})"
                                        class="rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Set Cover
                                </button>
                            @endif
                            <form method="POST" action="{{ route('tenant.resources.media.destroy', [$resource, $media]) }}"
                                  onsubmit="return confirm('Delete this media?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-500">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tab: Add-ons --}}
        <div x-show="activeTab === 'addons'" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Add-ons</h3>

            @if ($resource->addOns->isNotEmpty())
                <div class="mb-6 overflow-hidden rounded-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Required</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($resource->addOns as $addon)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $addon->name }}</td>
                                    <td class="px-4 py-2">${{ number_format($addon->price, 2) }}</td>
                                    <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $addon->price_type) }}</td>
                                    <td class="px-4 py-2">
                                        @if ($addon->is_required)
                                            <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs text-yellow-700">Required</span>
                                        @else
                                            <span class="text-gray-400 text-xs">Optional</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('tenant.resources.destroy', $addon) }}" class="inline" onsubmit="return confirm('Delete add-on?')">
                                            @csrf
                                            @method('DELETE')
                                            {{-- Note: Add-on deletion route is not yet wired - placeholder --}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mb-6 text-sm text-gray-500">No add-ons yet.</p>
            @endif

            <h4 class="mb-4 text-sm font-semibold text-gray-800">Add New Add-on</h4>
            <form action="#" method="POST" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. Airport Transfer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" name="price" step="0.01" min="0" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Type</label>
                    <select name="price_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="flat">Flat</option>
                        <option value="per_person">Per Person</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_required" id="addon_required" value="1" class="rounded border-gray-300 text-indigo-600">
                    <label for="addon_required" class="text-sm text-gray-700">Required</label>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Add Add-on</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function availabilityForm() {
    return {
        dates: [],
        newDate: '',
        newCapacity: '',
        newClosed: false,
        addDate() {
            if (!this.newDate) return;
            this.dates.push({
                date: this.newDate,
                available_capacity: this.newCapacity || {{ $resource->capacity }},
                is_closed: this.newClosed,
            });
            this.newDate = '';
            this.newCapacity = '';
            this.newClosed = false;
        }
    };
}

function blockForm() {
    return {
        blockDates: [],
        newBlockDate: '',
        addBlockDate() {
            if (!this.newBlockDate || this.blockDates.includes(this.newBlockDate)) return;
            this.blockDates.push(this.newBlockDate);
            this.newBlockDate = '';
        }
    };
}

function mediaManager() {
    return {
        uploading: false,
        uploadFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.uploading = true;
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('{{ route('tenant.resources.media.store', $resource) }}', {
                method: 'POST',
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                this.uploading = false;
                window.location.reload();
            })
            .catch(() => {
                this.uploading = false;
                alert('Upload failed.');
            });
        }
    };
}

function setCover(mediaId) {
    fetch(`{{ url('resources/' . $resource->id . '/media') }}/${mediaId}/cover`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    }).then(() => window.location.reload());
}
</script>
@endpush
