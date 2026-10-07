@extends('layouts.tenant')

@section('title', 'Availability Calendar: ' . $resource->name)

@section('content')
    <x-page-header :title="'Availability: ' . $resource->name" subtitle="View and manage resource availability across the calendar.">
        <a href="{{ route('tenant.resources.edit', $resource) }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back to Resource
        </a>
    </x-page-header>

    {{-- Legend --}}
    <div class="mb-4 flex items-center gap-4 text-sm">
        <div class="flex items-center gap-1.5">
            <span class="inline-block h-3 w-3 rounded-sm bg-green-500"></span>
            <span class="text-gray-600">Available</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="inline-block h-3 w-3 rounded-sm bg-yellow-400"></span>
            <span class="text-gray-600">Partial</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="inline-block h-3 w-3 rounded-sm bg-red-500"></span>
            <span class="text-gray-600">Full / Blocked</span>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <div id="availability-calendar"></div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('availability-calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth',
        },
        events: function (info, successCallback, failureCallback) {
            const month = info.start.toISOString().substring(0, 7);

            fetch('{{ route('tenant.resources.availability.calendar-data', $resource) }}?month=' + month)
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    const events = data.map(function (d) {
                        return {
                            title: d.title,
                            start: d.date,
                            allDay: true,
                            backgroundColor: d.color,
                            borderColor: d.color,
                            textColor: '#fff',
                            extendedProps: {
                                status: d.status,
                                available: d.available,
                                capacity: d.capacity,
                            },
                        };
                    });
                    successCallback(events);
                })
                .catch(function () { failureCallback(); });
        },
        eventClick: function (info) {
            const props = info.event.extendedProps;
            alert(
                info.event.start.toDateString() + '\n' +
                'Status: ' + props.status + '\n' +
                'Available: ' + props.available + ' / ' + props.capacity
            );
        },
        eventTimeFormat: { hour: 'numeric', minute: '2-digit' },
        height: 'auto',
    });

    calendar.render();
});
</script>
@endpush
