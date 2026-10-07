@extends('layouts.tenant')

@section('title', $staff->name)

@section('content')
    <x-page-header :title="$staff->name" subtitle="Staff Profile">
        <x-slot name="actions">
            <a href="{{ route('tenant.staff.edit', $staff) }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Edit
            </a>
            <a href="{{ route('tenant.staff.schedule', $staff) }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                Manage Schedule
            </a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left Column --}}
        <div class="space-y-6">
            {{-- Avatar & Basic Info --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 text-center">
                @if ($staff->avatar)
                    <img src="{{ $staff->avatar }}" class="mx-auto h-24 w-24 rounded-full object-cover" alt="{{ $staff->name }}">
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-700">
                        {{ strtoupper(substr($staff->name, 0, 2)) }}
                    </div>
                @endif
                <h2 class="mt-3 text-lg font-semibold text-gray-900">{{ $staff->name }}</h2>
                <p class="text-sm text-gray-500">{{ $staff->role }}@if($staff->department) · {{ $staff->department }}@endif</p>

                <div class="mt-3 flex justify-center gap-2">
                    <x-badge :color="$staff->status === 'active' ? 'green' : 'red'" :label="ucfirst($staff->status)" />
                    @php
                        $empLabel = match ($staff->employment_type) {
                            'full_time' => 'Full Time',
                            'part_time' => 'Part Time',
                            'contract' => 'Contract',
                            default => $staff->employment_type,
                        };
                    @endphp
                    <x-badge color="blue" :label="$empLabel" />
                </div>

                {{-- Clock actions --}}
                <div class="mt-4 flex justify-center gap-3">
                    <form method="POST" action="{{ route('tenant.staff.clock-in', $staff) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                            Clock In
                        </button>
                    </form>
                    <form method="POST" action="{{ route('tenant.staff.clock-out', $staff) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                            Clock Out
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Contact Information</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="text-gray-900">{{ $staff->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Phone</dt>
                        <dd class="text-gray-900">{{ $staff->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Hire Date</dt>
                        <dd class="text-gray-900">{{ $staff->hire_date?->format('d M Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Emergency Contact --}}
            @if ($staff->emergency_contact)
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">Emergency Contact</h3>
                    <dl class="space-y-2 text-sm">
                        <div>
                            <dt class="text-gray-500">Name</dt>
                            <dd class="text-gray-900">{{ $staff->emergency_contact['name'] ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Phone</dt>
                            <dd class="text-gray-900">{{ $staff->emergency_contact['phone'] ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Relationship</dt>
                            <dd class="text-gray-900">{{ $staff->emergency_contact['relationship'] ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            @endif

            {{-- Bio --}}
            @if ($staff->bio)
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="mb-2 text-sm font-semibold text-gray-900">Bio</h3>
                    <p class="text-sm text-gray-600">{{ $staff->bio }}</p>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Weekly Schedule --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Weekly Schedule</h3>
                    <a href="{{ route('tenant.staff.schedule', $staff) }}" class="text-xs text-blue-600 hover:underline">Edit Schedule</a>
                </div>
                <div class="space-y-2">
                    @foreach ($weekSchedule as $day)
                        <div class="flex items-center gap-4 rounded-lg px-3 py-2 {{ $day['is_available'] ? 'bg-green-50' : 'bg-gray-50' }}">
                            <div class="w-28 text-sm font-medium text-gray-700">{{ $day['day_name'] }}</div>
                            @if ($day['is_available'])
                                <div class="text-sm text-gray-600">{{ $day['start_time'] }} — {{ $day['end_time'] }}</div>
                                <x-badge color="green" label="Available" />
                            @else
                                <div class="text-sm text-gray-400">Off</div>
                                <x-badge color="gray" label="Unavailable" />
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Assignments --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Recent Assignments</h3>
                @if ($staff->assignments->isEmpty())
                    <p class="text-sm text-gray-500">No assignments yet.</p>
                @else
                    <div class="space-y-2">
                        @foreach ($staff->assignments->take(5) as $assignment)
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Booking #{{ $assignment->booking?->reference_number ?? $assignment->booking_id }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $assignment->assigned_at->format('d M Y H:i') }}</p>
                                </div>
                                @if ($assignment->notes)
                                    <p class="text-xs text-gray-400">{{ Str::limit($assignment->notes, 40) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Attendance This Month --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Attendance — {{ now()->format('F Y') }}</h3>
                    <span class="text-xs text-gray-500">{{ number_format($totalHoursThisMonth, 1) }} hrs total</span>
                </div>
                @if ($attendanceThisMonth->isEmpty())
                    <p class="text-sm text-gray-500">No attendance logs this month.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium uppercase text-gray-500">
                                    <th class="pb-2">Date</th>
                                    <th class="pb-2">Clock In</th>
                                    <th class="pb-2">Clock Out</th>
                                    <th class="pb-2">Hours</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($attendanceThisMonth->take(10) as $log)
                                    <tr>
                                        <td class="py-1.5 text-gray-700">{{ $log->date->format('d M') }}</td>
                                        <td class="py-1.5 text-gray-600">{{ $log->clock_in ?? '—' }}</td>
                                        <td class="py-1.5 text-gray-600">{{ $log->clock_out ?? '—' }}</td>
                                        <td class="py-1.5 font-medium text-gray-900">{{ $log->hours_worked ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
