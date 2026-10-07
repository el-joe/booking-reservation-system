@extends('layouts.tenant')

@section('title', 'Schedule — ' . $staff->name)

@section('content')
    <x-page-header :title="$staff->name . ' — Schedule'" subtitle="Manage weekly availability">
        <x-slot name="actions">
            <a href="{{ route('tenant.staff.show', $staff) }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Back to Profile
            </a>
        </x-slot>
    </x-page-header>

    <div class="mx-auto max-w-3xl">
        <form method="POST" action="{{ route('tenant.staff.schedule.update', $staff) }}">
            @csrf

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="space-y-4">
                    @foreach ($days as $dayNum => $dayName)
                        @php $schedule = $schedules->get($dayNum); @endphp
                        <div class="flex items-center gap-4 rounded-lg border border-gray-200 p-4" x-data="{ available: {{ $schedule && $schedule->is_available ? 'true' : 'false' }} }">
                            <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">

                            <div class="w-28">
                                <span class="text-sm font-medium text-gray-700">{{ $dayName }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           name="schedules[{{ $dayNum }}][is_available]"
                                           value="1"
                                           x-model="available"
                                           {{ $schedule && $schedule->is_available ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-600">Available</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-2" x-show="available">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Start Time</label>
                                    <input type="time"
                                           name="schedules[{{ $dayNum }}][start_time]"
                                           value="{{ $schedule?->start_time ?? '09:00' }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <span class="text-gray-400 mt-4">—</span>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">End Time</label>
                                    <input type="time"
                                           name="schedules[{{ $dayNum }}][end_time]"
                                           value="{{ $schedule?->end_time ?? '17:00' }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div x-show="!available" class="text-sm text-gray-400 italic">Day off</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('tenant.staff.show', $staff) }}"
                   class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Save Schedule
                </button>
            </div>
        </form>
    </div>
@endsection
