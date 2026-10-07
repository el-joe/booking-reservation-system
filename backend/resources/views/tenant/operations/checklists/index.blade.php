@extends('layouts.tenant')

@section('title', 'Operational Checklists')

@section('content')
    <x-page-header title="Operational Checklists" subtitle="Manage pre/post-booking and maintenance checklists.">
        <a href="{{ route('tenant.operations.checklists.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Checklist
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trigger</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($checklists as $checklist)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $checklist->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst($checklist->trigger)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $checklist->booking_type ?? 'All' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ count($checklist->items ?? []) }} items</td>
                        <td class="px-6 py-4">
                            @if ($checklist->is_active)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('tenant.operations.checklists.edit', $checklist) }}" class="text-gray-600 hover:text-gray-800">Edit</a>
                                <form method="POST" action="{{ route('tenant.operations.checklists.destroy', $checklist) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this checklist?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No checklists found. Create your first checklist.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $checklists->links() }}</div>
    </div>
@endsection
