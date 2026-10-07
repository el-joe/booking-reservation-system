@extends('layouts.tenant')

@section('title', 'New Booking')

@section('content')
    <x-page-header title="New Booking" subtitle="Create a new reservation" />

    <div
        x-data="{
            step: 1,
            selectedResource: null,
            checkIn: '',
            checkOut: '',
            guestsCount: 1,
            source: 'walk_in',
            bookingType: '',
            customerId: '',
            customerSearch: '',
            notes: '',
            items: [],
            addItem() {
                this.items.push({ description: '', unit_price: 0, quantity: 1 });
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            itemsTotal() {
                return this.items.reduce((sum, i) => sum + (parseFloat(i.unit_price) || 0) * (parseInt(i.quantity) || 0), 0);
            },
        }"
        class="space-y-6"
    >
        {{-- Progress Steps --}}
        <div class="flex items-center gap-0 overflow-x-auto rounded-xl bg-white px-6 py-4 shadow-sm ring-1 ring-gray-200">
            @foreach ([1 => 'Resource', 2 => 'Dates', 3 => 'Customer', 4 => 'Add-ons', 5 => 'Review'] as $num => $label)
                <div class="flex items-center" :class="{ 'flex-1': {{ $num < 5 ? 'true' : 'false' }} }">
                    <div class="flex flex-col items-center">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-medium transition-colors"
                            :class="step >= {{ $num }} ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'"
                        >{{ $num }}</div>
                        <span class="mt-1 whitespace-nowrap text-xs font-medium"
                            :class="step >= {{ $num }} ? 'text-blue-600' : 'text-gray-500'"
                        >{{ $label }}</span>
                    </div>
                    @if ($num < 5)
                        <div class="mx-2 h-0.5 flex-1 transition-colors" :class="step > {{ $num }} ? 'bg-blue-600' : 'bg-gray-200'"></div>
                    @endif
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('tenant.bookings.store') }}">
            @csrf

            {{-- Step 1: Select Resource --}}
            <div x-show="step === 1" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Step 1: Select Resource</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($resources as $resource)
                        <div
                            class="cursor-pointer rounded-lg border-2 p-4 transition-colors"
                            :class="selectedResource === {{ $resource->id }} ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300'"
                            @click="selectedResource = {{ $resource->id }}; bookingType = '{{ $resource->resource_type ?? '' }}'"
                        >
                            <p class="font-medium text-gray-900">{{ $resource->name }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $resource->resource_type ?? 'Resource' }}</p>
                            <p class="mt-1 text-sm text-blue-600">${{ number_format($resource->base_price ?? 0, 2) }}/unit</p>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="resource_id" :value="selectedResource">
                <input type="hidden" name="booking_type" :value="bookingType">
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="if (selectedResource) step = 2"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                        :disabled="!selectedResource">
                        Next: Dates
                    </button>
                </div>
            </div>

            {{-- Step 2: Dates & Details --}}
            <div x-show="step === 2" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Step 2: Dates & Details</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Check-in Date & Time</label>
                        <input type="datetime-local" name="check_in" x-model="checkIn"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Check-out Date & Time</label>
                        <input type="datetime-local" name="check_out" x-model="checkOut"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Number of Guests</label>
                        <input type="number" name="guests_count" x-model="guestsCount" min="1"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Booking Source</label>
                        <select name="source" x-model="source"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="website">Website</option>
                            <option value="walk_in">Walk-in</option>
                            <option value="phone">Phone</option>
                            <option value="ota">OTA</option>
                            <option value="api">API</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Notes (optional)</label>
                        <textarea name="notes" rows="3" x-model="notes"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Any special requests or notes..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="step = 1"
                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </button>
                    <button type="button" @click="if (checkIn && checkOut) step = 3"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Next: Customer
                    </button>
                </div>
            </div>

            {{-- Step 3: Customer --}}
            <div x-show="step === 3" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Step 3: Customer</h3>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Select Customer</label>
                    <select name="customer_id" x-model="customerId"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Select customer —</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="step = 2"
                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </button>
                    <button type="button" @click="if (customerId) step = 4"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Next: Add-ons
                    </button>
                </div>
            </div>

            {{-- Step 4: Add-ons --}}
            <div x-show="step === 4" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Step 4: Add-ons (Optional)</h3>
                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-6">
                                <input type="text" :name="`items[${index}][description]`" x-model="item.description"
                                    placeholder="Description"
                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="col-span-2">
                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1"
                                    placeholder="Qty"
                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="col-span-3">
                                <input type="number" :name="`items[${index}][unit_price]`" x-model="item.unit_price" min="0" step="0.01"
                                    placeholder="Unit price"
                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="col-span-1 flex items-center">
                                <button type="button" @click="removeItem(index)"
                                    class="text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addItem()"
                    class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Item
                </button>
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="step = 3"
                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </button>
                    <button type="button" @click="step = 5"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Next: Review
                    </button>
                </div>
            </div>

            {{-- Step 5: Review --}}
            <div x-show="step === 5" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Step 5: Review & Submit</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Resource</dt>
                        <dd class="mt-1 text-sm text-gray-900" x-text="selectedResource ? 'Resource #' + selectedResource : '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Check-in</dt>
                        <dd class="mt-1 text-sm text-gray-900" x-text="checkIn || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Check-out</dt>
                        <dd class="mt-1 text-sm text-gray-900" x-text="checkOut || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Guests</dt>
                        <dd class="mt-1 text-sm text-gray-900" x-text="guestsCount"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Source</dt>
                        <dd class="mt-1 text-sm text-gray-900" x-text="source"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Add-ons Total</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900" x-text="'$' + itemsTotal().toFixed(2)"></dd>
                    </div>
                </dl>
                <div class="mt-4" x-show="notes">
                    <p class="text-xs font-medium text-gray-500">Notes</p>
                    <p class="mt-1 text-sm text-gray-900" x-text="notes"></p>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="step = 4"
                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        Create Booking
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
