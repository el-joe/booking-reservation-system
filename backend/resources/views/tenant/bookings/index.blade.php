@extends('layouts.tenant')

@section('title', 'Bookings')

@section('content')
    <x-page-header title="Bookings" subtitle="Manage all reservations">
        <a href="{{ route('tenant.bookings.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Booking
        </a>
    </x-page-header>

    {{-- Filter Bar --}}
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591L15 12.75V19.5a.75.75 0 0 1-.348.633l-3 1.875a.75.75 0 0 1-1.152-.633v-7.125L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
            </svg>
            Filters
            <svg class="h-4 w-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        <div x-show="open" x-transition class="mt-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('tenant.bookings.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Status</label>
                    <select name="status"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Booking Type</label>
                    <select name="booking_type"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Types</option>
                        @foreach ($bookingTypes as $type)
                            <option value="{{ $type->value }}" {{ request('booking_type') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs font-medium text-gray-700">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by reference or customer name..."
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-end gap-2 sm:col-span-2">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('tenant.bookings.index') }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- DataTable --}}
    <x-data-table
        id="bookings-table"
        :ajax-url="route('tenant.bookings.index')"
        :columns="[
            ['data' => 'reference_link', 'title' => 'Reference', 'name' => 'reference_number'],
            ['data' => 'customer_name', 'title' => 'Customer', 'name' => 'customer.first_name'],
            ['data' => 'resource_name', 'title' => 'Resource', 'name' => 'resource.name'],
            ['data' => 'type_badge', 'title' => 'Type', 'name' => 'booking_type'],
            ['data' => 'status_badge', 'title' => 'Status', 'name' => 'status'],
            ['data' => 'check_in_formatted', 'title' => 'Check-in', 'name' => 'check_in'],
            ['data' => 'check_out_formatted', 'title' => 'Check-out', 'name' => 'check_out'],
            ['data' => 'total_formatted', 'title' => 'Total', 'name' => 'total_amount'],
            ['data' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ]"
    />

    @push('scripts')
        <script>
            function cancelBooking(bookingId) {
                const reason = prompt('Please enter the cancellation reason:');
                if (!reason) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/bookings/${bookingId}/cancel`;
                form.innerHTML = `@csrf<input name="reason" value="${reason}">`;
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    @endpush
@endsection
