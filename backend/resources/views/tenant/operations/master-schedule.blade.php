@extends('layouts.tenant')

@section('title', 'Master Schedule')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
@endpush

@section('content')
    <x-page-header title="Master Schedule" subtitle="View all bookings and maintenance events across all resources.">
        <a href="{{ route('tenant.operations.maintenance.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700">
            Schedule Maintenance
        </a>
    </x-page-header>

    <div class="mb-4 flex items-center gap-4 text-sm">
        <span class="flex items-center gap-1.5">
            <span class="h-3 w-3 rounded-full bg-blue-500"></span> Bookings
        </span>
        <span class="flex items-center gap-1.5">
            <span class="h-3 w-3 rounded-full bg-orange-500"></span> Maintenance
        </span>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
        <div id="master-calendar"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource@6.1.10/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('master-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                initialView: 'resourceTimelineWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
                },
                height: 'auto',
                resources: '{{ route('tenant.operations.schedule.resources') }}',
                events: '{{ route('tenant.operations.schedule.events') }}',
                eventContent: function (arg) {
                    return { html: '<div class="px-1 text-xs truncate">' + arg.event.title + '</div>' };
                }
            });
            calendar.render();
        });
    </script>
@endpush
