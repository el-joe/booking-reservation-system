@extends('layouts.tenant')

@section('title', 'Documents')

@section('content')
    <x-page-header title="Documents" subtitle="Manage uploaded files and track expiry dates.">
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        <select name="entity_type" class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Entity Types</option>
            <option value="App\Models\Customer" {{ request('entity_type') === 'App\Models\Customer' ? 'selected' : '' }}>Customers</option>
            <option value="App\Models\Staff" {{ request('entity_type') === 'App\Models\Staff' ? 'selected' : '' }}>Staff</option>
            <option value="App\Models\Booking" {{ request('entity_type') === 'App\Models\Booking' ? 'selected' : '' }}>Bookings</option>
            <option value="App\Models\Supplier" {{ request('entity_type') === 'App\Models\Supplier' ? 'selected' : '' }}>Suppliers</option>
        </select>
        <input type="text" name="category" value="{{ request('category') }}" placeholder="Category…"
               class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="expiring_soon" value="1" {{ request('expiring_soon') ? 'checked' : '' }} class="rounded border-gray-300">
            Expiring in 30 days
        </label>
        <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
        <a href="{{ route('tenant.documents.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
    </form>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($documents as $document)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $document->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ class_basename($document->documentable_type) }} #{{ $document->documentable_id }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($document->category)
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">{{ $document->category }}</span>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if ($document->expires_at)
                                @if ($document->isExpired())
                                    <span class="font-medium text-red-600">{{ $document->expires_at->format('M d, Y') }} (Expired)</span>
                                @elseif ($document->isExpiringSoon())
                                    <span class="font-medium text-yellow-600">{{ $document->expires_at->format('M d, Y') }} (Soon)</span>
                                @else
                                    <span class="text-gray-600">{{ $document->expires_at->format('M d, Y') }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $document->uploadedBy?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('tenant.documents.download', $document) }}" class="text-blue-600 hover:text-blue-800">Download</a>
                                <form method="POST" action="{{ route('tenant.documents.destroy', $document) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this document?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $documents->links() }}</div>
    </div>
@endsection
