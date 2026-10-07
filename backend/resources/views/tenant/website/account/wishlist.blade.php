@extends('layouts.website')

@section('title', 'Wishlist')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">My Wishlist</h1>
    <p class="mt-1 text-sm text-gray-500">Spaces you've saved for later</p>

    @if(empty($wishlist))
        <div class="mt-12 flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 py-16">
            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <p class="mt-3 text-sm text-gray-500">Your wishlist is empty</p>
            <a href="{{ route('tenant.search') }}" class="mt-3 rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Explore Spaces</a>
        </div>
    @else
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($wishlist as $item)
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="aspect-video bg-gray-100">
                        @if(!empty($item['cover_image']))
                            <img src="{{ $item['cover_image'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="font-semibold text-gray-900">{{ $item['name'] ?? 'Resource' }}</p>
                        <a href="{{ $item['url'] ?? '#' }}" class="mt-2 inline-block rounded-lg bg-indigo-600 px-3 py-1.5 text-xs text-white hover:bg-indigo-700">Book Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
