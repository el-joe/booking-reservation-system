@extends('layouts.central-website')

@section('title', config('app.name', 'BookEase'))
@section('subtitle', 'Find & Book Anything')

@section('content')

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight mb-4">
            Book anything,<br>anywhere — instantly.
        </h1>
        <p class="text-lg text-indigo-100 mb-10 max-w-xl mx-auto">
            Discover hotels, restaurants, services, tours and more from thousands of businesses on one platform.
        </p>

        {{-- Search bar --}}
        <form action="{{ route('central.website.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
            <input type="text" name="q" placeholder="Search businesses, services..."
                class="flex-1 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/60 shadow-lg text-sm">
            <button type="submit"
                class="bg-white text-indigo-700 font-semibold px-6 py-3 rounded-xl hover:bg-indigo-50 transition shadow-lg text-sm">
                Search
            </button>
        </form>

        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('central.website.search') }}"
               class="inline-flex items-center gap-1.5 border border-white/30 text-white/80 hover:text-white hover:border-white rounded-full px-4 py-1.5 text-xs font-medium transition">
                Browse All
            </a>
            <a href="{{ route('central.website.register') }}"
               class="inline-flex items-center gap-1.5 bg-white/10 border border-white/30 text-white hover:bg-white/20 rounded-full px-4 py-1.5 text-xs font-medium transition">
                List Your Business →
            </a>
        </div>
    </div>
</section>

{{-- Browse by category --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Browse by Category</h2>
            <p class="text-gray-500 text-sm mt-1">Find the type of business you're looking for.</p>
        </div>
        <a href="{{ route('central.website.search') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View all →</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $type)
        <a href="{{ route('central.website.category', $type->value) }}"
           class="flex flex-col items-center p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 transition text-center group">
            <div class="h-12 w-12 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center mb-3 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition leading-snug">{{ $type->label() }}</span>
            @if(isset($categoryCounts[$type->value]) && $categoryCounts[$type->value] > 0)
            <span class="mt-1 text-[11px] text-gray-400">{{ $categoryCounts[$type->value] }} listed</span>
            @endif
        </a>
        @endforeach
    </div>
</section>

{{-- Featured businesses --}}
@if($featuredTenants->count() > 0)
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Featured Businesses</h2>
                <p class="text-gray-500 text-sm mt-1">Handpicked top-rated businesses on our platform.</p>
            </div>
            <a href="{{ route('central.website.search') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">See all →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($featuredTenants as $tenant)
                <x-tenant-card :tenant="$tenant" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- How it works --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-3">How It Works</h2>
    <p class="text-gray-500 mb-12">Book in three simple steps.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach([
            ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'step' => '1', 'title' => 'Search & Discover', 'desc' => 'Browse thousands of businesses by category, location or keyword.'],
            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'step' => '2', 'title' => 'Pick a Time', 'desc' => 'See real-time availability and choose a slot that works for you.'],
            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'step' => '3', 'title' => 'Book & Confirm', 'desc' => 'Complete your booking instantly and receive a confirmation right away.'],
        ] as $step)
        <div class="flex flex-col items-center">
            <div class="h-14 w-14 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
                </svg>
            </div>
            <div class="text-xs font-semibold text-indigo-500 uppercase tracking-widest mb-1">Step {{ $step['step'] }}</div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ $step['title'] }}</h3>
            <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA band --}}
<section class="bg-indigo-600 text-white py-14">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3">Ready to grow your business?</h2>
        <p class="text-indigo-100 mb-7 text-sm">Join thousands of businesses already on {{ config('app.name', 'BookEase') }}. Set up your page in minutes.</p>
        <a href="{{ route('central.website.register') }}"
           class="inline-block bg-white text-indigo-700 font-semibold px-7 py-3 rounded-xl hover:bg-indigo-50 transition shadow-md">
            Get Started Free →
        </a>
    </div>
</section>

@endsection
