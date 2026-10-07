@extends('layouts.central-website')

@section('seo')
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     SECTION 1: HERO
════════════════════════════════════════════ --}}
<section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white overflow-hidden">
    {{-- Animated background --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay:1s"></div>
        <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
            <defs><pattern id="grid" width="32" height="32" patternUnits="userSpaceOnUse"><path d="M 32 0 L 0 0 0 32" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-center">
        {{-- Pill badge --}}
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-xs font-medium mb-6">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            {{ $stats['tenants'] > 0 ? $stats['tenants'].'+ businesses live on the platform' : 'The smarter way to book' }}
        </div>

        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-6">
            Book anything,<br class="hidden sm:block">anywhere — <span class="text-yellow-300">instantly.</span>
        </h1>
        <p class="text-lg sm:text-xl text-indigo-100 mb-10 max-w-2xl mx-auto leading-relaxed">
            Discover hotels, restaurants, services, tours and more. One platform, {{ $stats['categories'] }}+ categories, thousands of businesses ready to take your booking.
        </p>

        {{-- Search form --}}
        <form action="{{ route('central.website.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto mb-8">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" placeholder="Search businesses, services, locations..."
                    class="w-full pl-11 pr-4 py-3.5 rounded-xl text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-300 shadow-xl">
            </div>
            <button type="submit" class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-8 py-3.5 rounded-xl transition shadow-xl text-sm whitespace-nowrap">
                Search
            </button>
        </form>

        {{-- Quick links --}}
        <div class="flex flex-wrap justify-center gap-2">
            @foreach(array_slice(\App\Enums\BookingType::cases(), 0, 5) as $type)
            <a href="{{ route('central.website.category', $type->value) }}"
               class="inline-flex items-center gap-1.5 border border-white/25 bg-white/10 hover:bg-white/20 text-white/90 hover:text-white rounded-full px-3.5 py-1.5 text-xs font-medium transition">
                {{ $type->label() }}
            </a>
            @endforeach
            <a href="{{ route('central.website.search') }}" class="inline-flex items-center gap-1.5 border border-white/25 bg-white/10 hover:bg-white/20 text-white/90 hover:text-white rounded-full px-3.5 py-1.5 text-xs font-medium transition">
                View all →
            </a>
        </div>

        {{-- Stat counters --}}
        <div class="mt-14 grid grid-cols-3 gap-8 max-w-sm sm:max-w-lg mx-auto border-t border-white/20 pt-10">
            <div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-white">{{ $stats['tenants'] ?: '0' }}+</div>
                <div class="text-indigo-200 text-xs mt-1">Businesses</div>
            </div>
            <div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-white">{{ $stats['categories'] }}+</div>
                <div class="text-indigo-200 text-xs mt-1">Categories</div>
            </div>
            <div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-white">24/7</div>
                <div class="text-indigo-200 text-xs mt-1">Online Booking</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 2: TRUST BAR
════════════════════════════════════════════ --}}
<section class="bg-gray-50 border-y border-gray-100 py-5">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-12">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider whitespace-nowrap">Trusted by businesses in</p>
            @foreach(['Hotels','Restaurants','Clinics','Fitness Centers','Salons & Spas','Co-working Spaces'] as $biz)
            <span class="text-sm font-semibold text-gray-400">{{ $biz }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 3: BROWSE BY CATEGORY
════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-2">Explore</p>
            <h2 class="font-display text-3xl font-bold text-gray-900">Browse by Category</h2>
            <p class="text-gray-500 mt-2">Find exactly what you're looking for from {{ $stats['categories'] }}+ business types.</p>
        </div>
        <a href="{{ route('central.website.search') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
            View all categories <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @php
    $categoryIcons = [
        'accommodation' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'travel' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
        'dining' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
        'services' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
        'entertainment' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
        'business' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'online' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ];
    $categoryColors = [
        'accommodation' => 'bg-blue-50 text-blue-600',
        'travel' => 'bg-cyan-50 text-cyan-600',
        'dining' => 'bg-orange-50 text-orange-600',
        'services' => 'bg-pink-50 text-pink-600',
        'entertainment' => 'bg-purple-50 text-purple-600',
        'business' => 'bg-indigo-50 text-indigo-600',
        'online' => 'bg-teal-50 text-teal-600',
    ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-3">
        @foreach($categories as $type)
        @php $cat = $type->category(); @endphp
        <a href="{{ route('central.website.category', $type->value) }}"
           class="flex flex-col items-center p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 hover:-translate-y-0.5 transition-all duration-200 text-center group">
            <div class="h-11 w-11 rounded-xl {{ $categoryColors[$cat] ?? 'bg-gray-50 text-gray-500' }} flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $categoryIcons[$cat] ?? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16' }}" />
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition leading-snug">{{ $type->label() }}</span>
            @if(isset($categoryCounts[$type->value]) && $categoryCounts[$type->value] > 0)
            <span class="mt-1 text-[10px] text-gray-400 font-medium">{{ $categoryCounts[$type->value] }} listed</span>
            @endif
        </a>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 4: HOW IT WORKS
════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-2">Simple Process</p>
            <h2 class="font-display text-3xl font-bold text-gray-900">Book in three steps</h2>
            <p class="text-gray-500 mt-2">No phone calls, no waiting. Just instant booking.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <div class="hidden md:block absolute top-8 left-1/4 right-1/4 h-px bg-indigo-100"></div>
            @foreach([
                ['icon'=>'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z','step'=>'01','title'=>'Search & Discover','desc'=>'Browse thousands of businesses by category, location or keyword. Filter by ratings, price, and availability.'],
                ['icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','step'=>'02','title'=>'Pick a Time','desc'=>'See real-time availability in a clean calendar view. Choose the slot that works best for you.'],
                ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','step'=>'03','title'=>'Confirm & Go','desc'=>'Complete your booking instantly and receive a confirmation via email and SMS right away.'],
            ] as $step)
            <div class="relative flex flex-col items-center text-center bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 font-display font-bold text-4xl text-indigo-100 select-none">{{ $step['step'] }}</div>
                <div class="h-14 w-14 rounded-2xl bg-indigo-600 flex items-center justify-center mb-5 shadow-md shadow-indigo-200 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-display text-base font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 5: FEATURED BUSINESSES
════════════════════════════════════════════ --}}
@if($featuredTenants->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-2">Handpicked</p>
            <h2 class="font-display text-3xl font-bold text-gray-900">Featured Businesses</h2>
            <p class="text-gray-500 mt-2">Top-rated businesses already on {{ config('app.name', 'BookEase') }}.</p>
        </div>
        <a href="{{ route('central.website.search') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
            See all <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($featuredTenants as $tenant)
            <x-tenant-card :tenant="$tenant" />
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 6: TESTIMONIALS
════════════════════════════════════════════ --}}
<section class="bg-indigo-600 py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-indigo-200 font-semibold text-sm uppercase tracking-wider mb-2">Social Proof</p>
            <h2 class="font-display text-3xl font-bold text-white">What our customers say</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['quote'=>'BookEase transformed how we manage reservations. Our bookings increased 40% in the first month. Setup took less than 30 minutes.','name'=>'Sarah Al-Mansouri','role'=>'Owner, The Palm Restaurant','initials'=>'SA'],
                ['quote'=>'The multi-tenant platform is incredibly reliable. Our clients love the simple booking flow and we love the real-time dashboard insights.','name'=>'James Thornton','role'=>'Director, Velocity Fitness Group','initials'=>'JT'],
                ['quote'=>'Finally a booking platform that works for our type of business. The API integration with our ERP saved our team 10 hours a week.','name'=>'Priya Mehta','role'=>'Operations Manager, MedPoint Clinics','initials'=>'PM'],
            ] as $t)
            <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/20">
                <div class="flex gap-0.5 mb-4">
                    @for($i=0;$i<5;$i++)<svg class="h-4 w-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                </div>
                <p class="text-white/90 text-sm leading-relaxed mb-5">"{{ $t['quote'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm">{{ $t['initials'] }}</div>
                    <div>
                        <div class="text-white text-sm font-semibold">{{ $t['name'] }}</div>
                        <div class="text-indigo-200 text-xs">{{ $t['role'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 7: PRICING TEASER
════════════════════════════════════════════ --}}
@if($featuredPlan)
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-2">Transparent Pricing</p>
    <h2 class="font-display text-3xl font-bold text-gray-900 mb-3">Start for just ${{ number_format($featuredPlan->price, 0) }}/mo</h2>
    <p class="text-gray-500 mb-10 max-w-lg mx-auto">{{ $featuredPlan->tagline ?? 'No setup fees. No hidden charges. Cancel anytime.' }}</p>

    <div class="max-w-sm mx-auto bg-white border-2 border-indigo-600 rounded-3xl p-8 shadow-xl shadow-indigo-100 mb-8">
        <div class="inline-block bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wide">{{ $featuredPlan->name }}</div>
        <div class="flex items-end justify-center gap-1 mb-2">
            <span class="font-display text-5xl font-bold text-gray-900">${{ number_format($featuredPlan->price, 0) }}</span>
            <span class="text-gray-400 text-sm mb-2">/month</span>
        </div>
        <p class="text-gray-500 text-sm mb-6">{{ $featuredPlan->description ?? 'Everything you need to get started.' }}</p>
        @if($featuredPlan->highlight_features)
        <ul class="text-left space-y-3 mb-6">
            @foreach($featuredPlan->highlight_features as $feature)
            <li class="flex items-center gap-2.5 text-sm text-gray-700">
                <svg class="h-4 w-4 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ $feature }}
            </li>
            @endforeach
        </ul>
        @endif
        <a href="{{ route('central.website.register', ['plan_id' => $featuredPlan->id]) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition text-sm">
            Get Started Free →
        </a>
    </div>

    <a href="{{ route('central.website.pricing') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
        Compare all plans →
    </a>
</section>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 8: LATEST BLOG POSTS
════════════════════════════════════════════ --}}
@if($latestPosts->count() > 0)
<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-2">Knowledge Base</p>
                <h2 class="font-display text-3xl font-bold text-gray-900">From our blog</h2>
                <p class="text-gray-500 mt-2">Tips, guides and updates from the BookEase team.</p>
            </div>
            <a href="{{ route('central.website.blog') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                Visit blog <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestPosts as $post)
            <a href="{{ route('central.website.blog.show', $post->slug) }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden">
                <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 relative overflow-hidden">
                    @if($post->cover_image)
                    <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    @else
                    <div class="flex items-center justify-center h-full">
                        <svg class="h-12 w-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    @endif
                    @if($post->category)
                    <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">{{ $post->category }}</span>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-display font-bold text-gray-900 mb-2 leading-snug group-hover:text-indigo-700 transition line-clamp-2">{{ $post->title }}</h3>
                    @if($post->excerpt)
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                        <span>·</span>
                        <span>{{ $post->reading_time }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 9: CTA BAND
════════════════════════════════════════════ --}}
<section class="bg-gray-900 text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-xs font-medium mb-6 text-indigo-300">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            No credit card required
        </div>
        <h2 class="font-display text-3xl sm:text-4xl font-bold mb-4">Ready to grow your business?</h2>
        <p class="text-gray-400 mb-8 text-lg">Join thousands of businesses on {{ config('app.name', 'BookEase') }}. Set up your page in minutes, start taking bookings today.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('central.website.register') }}" class="w-full sm:w-auto inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-8 py-3.5 rounded-xl transition shadow-lg shadow-indigo-900/30 text-sm">
                Get Started Free →
            </a>
            <a href="{{ route('central.website.pricing') }}" class="w-full sm:w-auto inline-block border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white font-semibold px-8 py-3.5 rounded-xl transition text-sm">
                View Pricing
            </a>
        </div>
    </div>
</section>

@endsection
