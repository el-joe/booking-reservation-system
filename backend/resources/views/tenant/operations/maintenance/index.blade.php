@extends('layouts.tenant')

@section('title', 'Maintenance Schedules')

@section('content')
    <x-page-header title="Maintenance Schedules" subtitle="Track and manage resource maintenance events.">
        <a href="{{ route('tenant.operations.maintenance.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Schedule Maintenance
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($schedules as $schedule)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $schedule->resource?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $schedule->scheduled_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $schedule->duration_hours }}h</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = ['scheduled' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-yellow-100 text-yellow-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                $color = $colors[$schedule->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $color }}">
                                {{ str_replace('_', ' ', ucfirst($schedule->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $schedule->assignedTo?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('tenant.operations.maintenance.edit', $schedule) }}" class="text-gray-600 hover:text-gray-800">Edit</a>
                                @if ($schedule->status !== 'completed')
                                    <form method="POST" action="{{ route('tenant.operations.maintenance.complete', $schedule) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800">Mark Complete</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('tenant.operations.maintenance.destroy', $schedule) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this schedule?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No maintenance schedules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $schedules->links() }}</div>
    </div>
@endsection
