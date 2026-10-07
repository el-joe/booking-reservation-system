@extends('layouts.central')

@section('title', 'Contact Messages')

@section('content')
    <x-page-header title="Contact Messages" subtitle="Messages submitted via the public contact form.">
        @if ($unreadCount > 0)
            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                {{ $unreadCount }} unread
            </span>
        @endif
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'Contacts']]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="mb-5 flex flex-wrap items-center gap-3">
        <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">All statuses</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Unread</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
        </select>
        <button type="submit" class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
        @if (request('status'))
            <a href="{{ route('central.contact.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>

    <div class="rounded-lg bg-white shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Name</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden sm:table-cell">Email</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Subject</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden md:table-cell">Date</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($messages as $message)
                    <tr class="hover:bg-gray-50 {{ $message->status === 'new' ? 'font-semibold' : '' }}">
                        <td class="px-4 py-3 text-gray-900">{{ $message->name }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell text-gray-600">{{ $message->email }}</td>
                        <td class="px-4 py-3 text-gray-700 max-w-xs truncate">{{ Str::limit($message->subject, 60) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($message->status === 'new')
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Unread</span>
                            @elseif ($message->status === 'replied')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Replied</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Read</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-400 text-xs">{{ $message->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('central.contact.show', $message) }}"
                               class="inline-flex items-center rounded bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100 mr-1">
                                View
                            </a>
                            <form method="POST" action="{{ route('central.contact.destroy', $message) }}" class="inline"
                                  onsubmit="return confirm('Delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-gray-400">No contact messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($messages->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
@endsection
