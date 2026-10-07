@extends('layouts.tenant')

@section('title', 'Attendance Logs')

@section('content')
    <x-page-header title="Attendance Logs" subtitle="Track staff clock-in and clock-out times" />

    {{-- Summary Card --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Hours Logged</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalHours, 1) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('tenant.attendance.index') }}" class="flex flex-wrap gap-3">
            <select name="staff_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Staff</option>
                @foreach ($staffList as $member)
                    <option value="{{ $member->id }}" @selected(request('staff_id') == $member->id)>{{ $member->name }}</option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="flex items-center text-gray-400 text-sm">to</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
            <a href="{{ route('tenant.attendance.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Clear</a>
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Staff</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Clock In</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Clock Out</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Hours Worked</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <a href="{{ route('tenant.staff.show', $log->staff_id) }}" class="text-blue-600 hover:underline">
                                    {{ $log->staff?->name ?? '—' }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $log->date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @if ($log->clock_in)
                                    <span class="inline-flex items-center gap-1 text-green-700">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v4.59L7.3 9.24a.75.75 0 0 0-1.1 1.02l3.25 3.5a.75.75 0 0 0 1.1 0l3.25-3.5a.75.75 0 1 0-1.1-1.02l-1.95 2.1V6.75Z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $log->clock_in }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                @if ($log->clock_out)
                                    <span class="inline-flex items-center gap-1 text-red-700">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-7.834V13.5a.75.75 0 0 1-1.5 0V10.166l-1.95-2.1a.75.75 0 1 0-1.1 1.02l3.25 3.5a.75.75 0 0 0 1.1 0l3.25-3.5a.75.75 0 0 0-1.1-1.02l-1.95 2.1Z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $log->clock_out }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($log->hours_worked)
                                    <span class="font-semibold text-gray-900">{{ $log->hours_worked }} hrs</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $log->notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-500">No attendance logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
