@extends('layouts.tenant')

@section('title', 'Staff Management')

@section('content')
    <x-page-header title="Staff Management" subtitle="Manage your team members">
        <x-slot name="actions">
            <a href="{{ route('tenant.staff.create') }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                <svg class="-ml-0.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Add Staff
            </a>
        </x-slot>
    </x-page-header>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <form method="GET" action="{{ route('tenant.staff.index') }}" class="flex flex-wrap gap-3">
            <select name="role" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="manager" @selected(request('role') === 'manager')>Manager</option>
                <option value="receptionist" @selected(request('role') === 'receptionist')>Receptionist</option>
                <option value="staff" @selected(request('role') === 'staff')>Staff</option>
            </select>

            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                Filter
            </button>
            <a href="{{ route('tenant.staff.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                Clear
            </a>
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Employment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Hire Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($staff as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($member->avatar)
                                        <img src="{{ $member->avatar }}" class="h-8 w-8 rounded-full object-cover" alt="{{ $member->name }}">
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <a href="{{ route('tenant.staff.show', $member) }}" class="font-medium text-blue-600 hover:underline">
                                        {{ $member->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $member->email }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $member->role }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $member->department ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $empColor = match ($member->employment_type) {
                                        'full_time' => 'blue',
                                        'part_time' => 'purple',
                                        'contract' => 'orange',
                                        default => 'gray',
                                    };
                                    $empLabel = match ($member->employment_type) {
                                        'full_time' => 'Full Time',
                                        'part_time' => 'Part Time',
                                        'contract' => 'Contract',
                                        default => $member->employment_type,
                                    };
                                @endphp
                                <x-badge :color="$empColor" :label="$empLabel" />
                            </td>
                            <td class="px-4 py-3">
                                <x-badge :color="$member->status === 'active' ? 'green' : 'red'" :label="ucfirst($member->status)" />
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $member->hire_date?->format('Y-m-d') ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.staff.show', $member) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                    <a href="{{ route('tenant.staff.edit', $member) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                    <a href="{{ route('tenant.staff.schedule', $member) }}" class="text-teal-600 hover:text-teal-800 text-sm">Schedule</a>
                                    <form method="POST" action="{{ route('tenant.staff.destroy', $member) }}" class="inline"
                                          onsubmit="return confirm('Delete this staff member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">No staff members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($staff->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $staff->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
