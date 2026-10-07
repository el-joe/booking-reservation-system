@extends('layouts.website')

@section('title', $resource->name)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="lg:flex lg:gap-8">

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">

            {{-- Gallery --}}
            <div x-data="{ activeImage: '{{ $resource->cover_image }}' }">
                <div class="overflow-hidden rounded-2xl bg-gray-100 aspect-video">
                    <img :src="activeImage || ''" alt="{{ $resource->name }}" class="h-full w-full object-cover">
                </div>
                @if($resource->media->count() > 1)
                    <div class="mt-3 flex gap-2 overflow-x-auto">
                        @foreach($resource->media as $media)
                            <button @click="activeImage = '{{ $media->file_path }}'"
                                    class="h-16 w-24 shrink-0 overflow-hidden rounded-lg border-2 border-transparent hover:border-indigo-500 focus:outline-none"
                                    :class="activeImage === '{{ $media->file_path }}' ? 'border-indigo-500' : ''">
                                <img src="{{ $media->file_path }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Title & Details --}}
            <div class="mt-6">
                <span class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                    {{ $resource->booking_type instanceof \BackedEnum ? $resource->booking_type->label() : ucfirst(str_replace('_', ' ', $resource->booking_type)) }}
                </span>
                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $resource->name }}</h1>

                <div class="mt-2 flex flex-wrap items-center gap-4">
                    @php $totalReviews = $reviews->count(); $avg = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0; @endphp
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-5 w-5 {{ $i <= $avg ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                        <span class="ml-1 text-sm text-gray-500">{{ $avg }} ({{ $totalReviews }} review{{ $totalReviews !== 1 ? 's' : '' }})</span>
                    </div>
                    @if($resource->capacity)
                        <span class="text-sm text-gray-500">&#x2022; Up to {{ $resource->capacity }} guests</span>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            @if($resource->description)
            <div class="mt-6" x-data="{ expanded: false }">
                <h2 class="text-lg font-semibold text-gray-900">About this space</h2>
                <div class="mt-2 text-sm leading-relaxed text-gray-600" :class="expanded ? '' : 'line-clamp-4'">
                    {!! nl2br(e($resource->description)) !!}
                </div>
                <button @click="expanded = !expanded" class="mt-2 text-sm font-medium text-indigo-600 hover:underline" x-text="expanded ? 'Show less' : 'Show more'"></button>
            </div>
            @endif

            {{-- Add-ons --}}
            @if($resource->addOns->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900">Available Add-ons</h2>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    @foreach($resource->addOns->where('is_active', true) as $addOn)
                        <div class="rounded-lg border border-gray-200 p-3">
                            <div class="flex items-start justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ $addOn->name }}</p>
                                <span class="text-sm font-semibold text-indigo-600">+${{ number_format($addOn->price, 2) }}</span>
                            </div>
                            @if($addOn->description)
                                <p class="mt-1 text-xs text-gray-500">{{ $addOn->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Reviews --}}
            <div class="mt-10">
                <h2 class="text-lg font-semibold text-gray-900">Reviews</h2>

                @if($totalReviews > 0)
                    {{-- Rating Distribution --}}
                    <div class="mt-4 space-y-2">
                        @foreach($ratingDistribution as $stars => $data)
                            <div class="flex items-center gap-3">
                                <span class="w-4 text-xs text-gray-500">{{ $stars }}</span>
                                <div class="flex-1 overflow-hidden rounded-full bg-gray-200 h-2">
                                    <div class="h-2 rounded-full bg-amber-400" style="width: {{ $data['percent'] }}%"></div>
                                </div>
                                <span class="w-6 text-xs text-gray-500">{{ $data['count'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-5">
                        @foreach($reviews as $review)
                            <div class="border-b border-gray-100 pb-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                                        {{ $review->avatar_initials }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $review->customer?->name ?? 'Guest' }}</p>
                                        <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                @if($review->title)
                                    <p class="mt-1 text-sm font-medium text-gray-800">{{ $review->title }}</p>
                                @endif
                                @if($review->body)
                                    <p class="mt-1 text-sm text-gray-600">{{ $review->body }}</p>
                                @endif
                                @if($review->reply)
                                    <div class="mt-3 rounded-lg bg-gray-50 p-3">
                                        <p class="text-xs font-semibold text-gray-700">Response from host:</p>
                                        <p class="mt-1 text-sm text-gray-600">{{ $review->reply }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-3 text-sm text-gray-500">No reviews yet. Be the first to book and leave a review!</p>
                @endif
            </div>

            {{-- Similar Resources --}}
            @if($similarResources->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-lg font-semibold text-gray-900">Similar Spaces</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    @foreach($similarResources as $similar)
                        <a href="{{ route('tenant.listing', $similar->slug) }}" class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="aspect-video overflow-hidden bg-gray-100">
                                @if($similar->cover_image)
                                    <img src="{{ $similar->cover_image }}" alt="{{ $similar->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center bg-indigo-50">
                                        <svg class="h-8 w-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21" /></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600">{{ $similar->name }}</p>
                                <p class="text-xs font-semibold text-indigo-600">{{ $similar->formatted_price }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sticky Pricing Card --}}
        <aside class="mt-8 lg:mt-0 lg:w-80 lg:shrink-0">
            <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="text-center">
                    <span class="text-3xl font-bold text-gray-900">${{ number_format((float) $resource->base_price, 2) }}</span>
                    <span class="text-gray-500"> / {{ str_replace('per_', '', $resource->price_unit ?? 'day') }}</span>
                </div>

                <form action="{{ route('tenant.book.step1', $resource) }}" method="GET" class="mt-5 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Check In</label>
                        <input type="date" name="check_in" value="{{ request('check_in') }}" min="{{ date('Y-m-d') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Check Out</label>
                        <input type="date" name="check_out" value="{{ request('check_out') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Guests</label>
                        <input type="number" name="guests" value="{{ request('guests', 1) }}" min="1" max="{{ $resource->capacity ?? 100 }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">
                        Book Now
                    </button>
                </form>

                @if($totalReviews > 0)
                    <p class="mt-4 text-center text-xs text-gray-400">
                        &#9733; {{ $avg }} rating &bull; {{ $totalReviews }} review{{ $totalReviews !== 1 ? 's' : '' }}
                    </p>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
