@extends('layouts.tenant')

@section('title', 'Edit Checklist')

@section('content')
    <x-page-header title="Edit Checklist" subtitle="Update this operational checklist.">
        <a href="{{ route('tenant.operations.checklists.index') }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back
        </a>
    </x-page-header>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <form action="{{ route('tenant.operations.checklists.update', $checklist) }}" method="POST"
              x-data="{ items: {{ json_encode(old('items', $checklist->items ?? [''])) }} }">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $checklist->name) }}" required
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trigger <span class="text-red-500">*</span></label>
                    <select name="trigger" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach (['pre_booking' => 'Pre-Booking', 'post_booking' => 'Post-Booking', 'daily' => 'Daily', 'maintenance' => 'Maintenance'] as $val => $label)
                            <option value="{{ $val }}" {{ old('trigger', $checklist->trigger) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Type</label>
                    <select name="booking_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Types</option>
                        @foreach ($bookingTypes as $type)
                            <option value="{{ $type->value }}" {{ old('booking_type', $checklist->booking_type) === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Checklist Items <span class="text-red-500">*</span></label>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="text" :name="`items[${index}]`" x-model="items[index]" required placeholder="Task description"
                                   class="block flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1"
                                    class="text-red-500 hover:text-red-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="items.push('')"
                            class="mt-1 text-sm text-blue-600 hover:text-blue-800">+ Add Item</button>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $checklist->is_active ? '1' : '0') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('tenant.operations.checklists.index') }}"
                   class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Update Checklist</button>
            </div>
        </form>
    </div>
@endsection
