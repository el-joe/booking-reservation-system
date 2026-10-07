@extends('layouts.central')

@section('title', 'FAQs')

@section('content')
    <x-page-header title="FAQs" subtitle="Manage frequently asked questions for the public website.">
        <a href="{{ route('central.faq.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add FAQ
        </a>
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'FAQs']]" />

    {{-- Filters --}}
    <form method="GET" class="mb-5 flex flex-wrap items-center gap-3">
        <select name="category" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ Str::title($cat) }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">All statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
        @if(request('category') || request('status'))
            <a href="{{ route('central.faq.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>

    <div class="rounded-lg bg-white shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Question</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden md:table-cell">Category</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden lg:table-cell">Sort Order</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Active</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($faqs as $faq)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 max-w-sm">
                        <p class="text-gray-900 truncate">{{ Str::limit($faq->question, 80) }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-600">{{ Str::title($faq->category) }}</td>
                    <td class="px-4 py-3 hidden lg:table-cell text-gray-500">{{ $faq->sort_order }}</td>
                    <td class="px-4 py-3">
                        @if($faq->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('central.faq.edit', $faq) }}"
                           class="inline-flex items-center rounded bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100 mr-1">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('central.faq.destroy', $faq) }}" class="inline"
                              onsubmit="return confirm('Delete this FAQ?')">
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
                    <td colspan="5" class="px-4 py-16 text-center text-gray-400">
                        No FAQs found.
                        <a href="{{ route('central.faq.create') }}" class="text-indigo-600 hover:underline ml-1">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($faqs->hasPages())
        <div class="border-t border-gray-100 px-4 py-3">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
@endsection
