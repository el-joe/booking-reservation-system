@extends('layouts.tenant')

@section('title', 'Leave Requests')

@section('content')
    <x-page-header title="Leave Requests" subtitle="Manage staff leave requests">
        <x-slot name="actions">
            <button type="button"
                    onclick="document.getElementById('new-leave-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-x-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                New Leave Request
            </button>
        </x-slot>
    </x-page-header>

    {{-- Tabs --}}
    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', '' => 'All'] as $tabStatus => $tabLabel)
                <a href="{{ route('tenant.leave.index', ['status' => $tabStatus]) }}"
                   class="whitespace-nowrap py-2 px-1 text-sm font-medium border-b-2 {{ request('status', '') === $tabStatus ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $tabLabel }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Filters --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('tenant.leave.index') }}" class="flex flex-wrap gap-3">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <select name="staff_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Staff</option>
                @foreach ($staffList as $member)
                    <option value="{{ $member->id }}" @selected(request('staff_id') == $member->id)>{{ $member->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Staff</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Dates</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Days</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($leaveRequests as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $leave->staff?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $typeColor = match ($leave->leave_type) {
                                        'annual' => 'blue',
                                        'sick' => 'red',
                                        'emergency' => 'orange',
                                        'unpaid' => 'gray',
                                        default => 'gray',
                                    };
                                @endphp
                                <x-badge :color="$typeColor" :label="ucfirst($leave->leave_type)" />
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $leave->start_date->format('d M Y') }} — {{ $leave->end_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $leave->duration_days }}</td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $leave->reason ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColor = match ($leave->status) {
                                        'pending' => 'yellow',
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                        default => 'gray',
                                    };
                                @endphp
                                <x-badge :color="$statusColor" :label="ucfirst($leave->status)" />
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($leave->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('tenant.leave.approve', $leave) }}">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('tenant.leave.reject', $leave) }}">
                                            @csrf
                                            <input type="hidden" name="response_note" value="Rejected by manager">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                    onclick="return confirm('Reject this leave request?')">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">{{ $leave->response_note ? Str::limit($leave->response_note, 30) : '—' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500">No leave requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($leaveRequests->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $leaveRequests->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- New Leave Request Modal --}}
    <div id="new-leave-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-base font-semibold text-gray-900">New Leave Request</h2>

            <form method="POST" action="{{ route('tenant.leave.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Staff Member</label>
                    <select name="staff_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Staff</option>
                        @foreach ($staffList as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                    <select name="leave_type" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="annual">Annual</option>
                        <option value="sick">Sick</option>
                        <option value="emergency">Emergency</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-form-input name="start_date" label="Start Date" type="date" />
                    <x-form-input name="end_date" label="End Date" type="date" />
                </div>

                <x-form-textarea name="reason" label="Reason" rows="3" />

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button"
                            onclick="document.getElementById('new-leave-modal').classList.add('hidden')"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
