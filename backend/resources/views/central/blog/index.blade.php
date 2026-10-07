@extends('layouts.central')

@section('title', 'Blog Posts')

@section('content')
    <x-page-header title="Blog Posts" subtitle="Manage blog posts for the public website.">
        <a href="{{ route('central.blog.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Post
        </a>
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'Blog']]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="mb-5 flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title…"
               class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 w-64">
        <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">All statuses</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <button type="submit" class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('central.blog.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>

    <div class="rounded-lg bg-white shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Title</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden md:table-cell">Category</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden lg:table-cell">Published</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 hidden lg:table-cell">Read time</th>
                    <th class="px-4 py-3 font-semibold text-gray-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50 {{ $post->trashed() ? 'opacity-60' : '' }}">
                    <td class="px-4 py-3 max-w-xs">
                        <p class="font-medium text-gray-900 truncate">{{ $post->title }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $post->slug }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-600">{{ $post->category ?: '—' }}</td>
                    <td class="px-4 py-3">
                        @if($post->trashed())
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Deleted</span>
                        @elseif($post->status === 'published')
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Published</span>
                        @elseif($post->status === 'draft')
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700">Draft</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Archived</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-gray-500 text-xs">
                        {{ $post->published_at?->format('M j, Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-gray-500 text-xs">{{ $post->reading_time }}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        @if(!$post->trashed())
                            <a href="{{ route('central.blog.edit', $post) }}"
                               class="inline-flex items-center rounded bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100 mr-1">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('central.blog.destroy', $post) }}" class="inline"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-16 text-center text-gray-400">
                        No blog posts found.
                        <a href="{{ route('central.blog.create') }}" class="text-indigo-600 hover:underline ml-1">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($posts->hasPages())
        <div class="border-t border-gray-100 px-4 py-3">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
@endsection
