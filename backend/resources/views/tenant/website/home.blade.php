@extends('layouts.website')

@section('title', 'Welcome')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-indigo-700 py-24 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-800 to-indigo-500 opacity-90"></div>
        <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                {{ tenant('name') ?? config('app.name') }}
            </h1>
            <p class="mt-4 text-lg text-indigo-200">
                {{ tenant('tagline') ?? 'Find and book the perfect space for your needs' }}
            </p>

            {{-- Hero Search Bar --}}
            <form action="{{ route('tenant.search') }}" method="GET" class="mt-10 rounded-2xl bg-white p-4 shadow-xl sm:flex sm:gap-2">
                <select name="booking_type" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none sm:w-44">
                    <option value="">All Types</option>
                    @foreach($categories as $category)
                        <option value="{{ $category instanceof \BackedEnum ? $category->value : $category }}">
                            {{ $category instanceof \BackedEnum ? $category->label() : ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="check_in" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none sm:w-40">
                <input type="number" name="guests" placeholder="Guests" min="1"
                       class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none sm:w-28">
                <button type="submit" class="mt-2 w-full rounded-lg bg-indigo-600 px-6 py-2.5 font-semibold text-white hover:bg-indigo-700 sm:mt-0 sm:w-auto">
                    Search
                </button>
            </form>
        </div>
    </section>

    {{-- Featured Resources --}}
    @if($featuredResources->isNotEmpty())
    <section class="py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900">Featured Spaces</h2>
            <p class="mt-1 text-gray-500">Top-rated options loved by our customers</p>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredResources as $resource)
                    <a href="{{ route('tenant.listing', $resource->slug) }}" class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="aspect-video overflow-hidden bg-gray-100">
                            @if($resource->cover_image)
                                <img src="{{ $resource->cover_image }}" alt="{{ $resource->name }}"
                                     class="h-full w-full object-cover transition group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center bg-indigo-50">
                                    <svg class="h-12 w-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-indigo-600">
                                {{ $resource->booking_type instanceof \BackedEnum ? $resource->booking_type->label() : ucfirst(str_replace('_', ' ', $resource->booking_type)) }}
                            </p>
                            <h3 class="mt-1 font-semibold text-gray-900 group-hover:text-indigo-600">{{ $resource->name }}</h3>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    @php $avg = (float) ($resource->rating_average ?? 0); @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $avg ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-1 text-xs text-gray-500">({{ $resource->published_reviews_count ?? 0 }})</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $resource->formatted_price }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('tenant.search') }}" class="inline-flex items-center gap-2 rounded-lg border border-indigo-600 px-6 py-2.5 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
                    View All Spaces
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Categories Section --}}
    @if($categories->isNotEmpty())
    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900">Browse by Category</h2>
            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($categories as $category)
                    @php $label = $category instanceof \BackedEnum ? $category->label() : ucfirst(str_replace('_', ' ', $category)); @endphp
                    <a href="{{ route('tenant.search', ['booking_type' => $category instanceof \BackedEnum ? $category->value : $category]) }}"
                       class="flex flex-col items-center gap-3 rounded-xl border border-gray-200 bg-white p-6 text-center transition hover:border-indigo-400 hover:shadow-md">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- How It Works --}}
    <section class="py-16" id="about">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-2xl font-bold text-gray-900">How It Works</h2>
            <div class="mt-12 grid gap-8 sm:grid-cols-3">
                @foreach([['Search', 'Browse our curated spaces by type, date, capacity, and price range.'], ['Book', 'Select your dates, add guests, choose add-ons, and complete your booking in minutes.'], ['Enjoy', 'Arrive and enjoy a seamless experience. Leave a review afterward to help others.']] as [$step, $desc])
                    <div class="text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-600 text-lg font-bold text-white">
                            {{ $loop->iteration }}
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $step }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if($recentReviews->isNotEmpty())
    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900">What Our Customers Say</h2>
            <div class="mt-8 grid gap-6 sm:grid-cols-3">
                @foreach($recentReviews as $review)
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                                {{ $review->avatar_initials }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $review->customer?->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-400">{{ $review->resource?->name }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        @if($review->title)
                            <p class="mt-2 text-sm font-semibold text-gray-800">{{ $review->title }}</p>
                        @endif
                        @if($review->body)
                            <p class="mt-1 text-sm text-gray-600 line-clamp-3">{{ $review->body }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
