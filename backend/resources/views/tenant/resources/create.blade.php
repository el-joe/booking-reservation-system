@extends('layouts.tenant')

@section('title', 'Create Resource')

@section('content')
    <x-page-header title="Create Resource" subtitle="Add a new bookable resource to your business.">
        <a href="{{ route('tenant.resources.index') }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back to Resources
        </a>
    </x-page-header>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <form action="{{ route('tenant.resources.store') }}" method="POST" x-data="resourceForm()">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-form-input
                        name="name"
                        label="Resource Name"
                        :value="old('name')"
                        required
                        placeholder="e.g. Deluxe Room 101"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Type <span class="text-red-500">*</span></label>
                    <select name="booking_type" x-model="bookingType" @change="updateResourceTypes()"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select booking type</option>
                        @foreach (\App\Enums\BookingType::cases() as $type)
                            <option value="{{ $type->value }}" {{ old('booking_type') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type <span class="text-red-500">*</span></label>
                    <select name="resource_type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select resource type</option>
                        @foreach (\App\Enums\ResourceType::cases() as $type)
                            <option value="{{ $type->value }}" {{ old('resource_type') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-form-input
                        name="capacity"
                        label="Capacity"
                        type="number"
                        :value="old('capacity', 1)"
                        min="1"
                    />
                </div>

                <div>
                    <x-form-input
                        name="base_price"
                        label="Base Price"
                        type="number"
                        step="0.01"
                        :value="old('base_price', '0.00')"
                        min="0"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Unit</label>
                    <select name="price_unit"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach (['per_night' => 'Per Night', 'per_hour' => 'Per Hour', 'per_person' => 'Per Person', 'per_unit' => 'Per Unit'] as $value => $label)
                            <option value="{{ $value }}" {{ old('price_unit', 'per_unit') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach (\App\Enums\ResourceStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status', 'active') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <x-form-textarea
                        name="description"
                        label="Description"
                        :value="old('description')"
                        rows="4"
                        placeholder="Describe this resource..."
                    />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('tenant.resources.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Create Resource
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function resourceForm() {
    return {
        bookingType: '{{ old('booking_type') }}',
        updateResourceTypes() {
            // Future: dynamically suggest resource types based on booking type
        }
    };
}
</script>
@endpush
