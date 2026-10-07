@extends('layouts.central-website')

@section('seo')
@endsection

@section('content')

<article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-400 mb-8 flex items-center gap-2">
        <a href="{{ route('central.website.home') }}" class="hover:text-gray-600 transition">Home</a>
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('central.website.blog') }}" class="hover:text-gray-600 transition">Blog</a>
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-600 line-clamp-1">{{ $post->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        {{-- Main article --}}
        <div class="lg:col-span-2">
            {{-- Category + meta --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($post->category)
                <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">{{ $post->category }}</span>
                @endif
                <span class="text-xs text-gray-400">{{ $post->published_at?->format('F j, Y') }}</span>
                <span class="text-xs text-gray-400">{{ $post->reading_time }}</span>
            </div>

            <h1 class="font-display text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-4" style="text-wrap:balance">{{ $post->title }}</h1>

            {{-- Author --}}
            <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-100">
                <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                    {{ strtoupper(substr($post->author_name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $post->author_name }}</p>
                    <p class="text-xs text-gray-400">{{ config('app.name','BookEase') }} Team</p>
                </div>
            </div>

            {{-- Cover image --}}
            @if($post->cover_image)
            <div class="aspect-video rounded-2xl overflow-hidden mb-8">
                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
            @endif

            {{-- Body --}}
            <div class="prose prose-gray prose-lg max-w-none">
                {!! nl2br(e($post->body)) !!}
            </div>

            {{-- Tags --}}
            @if($post->tags && count($post->tags))
            <div class="mt-8 pt-8 border-t border-gray-100 flex flex-wrap items-center gap-2">
                <span class="text-xs text-gray-400 font-medium">Tags:</span>
                @foreach($post->tags as $tag)
                <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
            @endif

            {{-- Share --}}
            <div class="mt-8 pt-8 border-t border-gray-100">
                <p class="text-sm font-semibold text-gray-700 mb-3">Share this article</p>
                <div class="flex items-center gap-3" x-data>
                    <button @click="navigator.clipboard.writeText(window.location.href).then(() => $el.textContent = 'Copied!').catch(() => {}); setTimeout(() => $el.textContent = 'Copy link', 2000)"
                            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-4 py-2 rounded-lg transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        Copy link
                    </button>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-4 py-2 rounded-lg transition">
                        Share on X
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-4 py-2 rounded-lg transition">
                        Share on LinkedIn
                    </a>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            {{-- About --}}
            <div class="bg-indigo-50 rounded-2xl p-5">
                <h3 class="font-display font-bold text-gray-900 mb-2">About {{ config('app.name','BookEase') }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">The all-in-one SaaS platform for businesses that take bookings online.</p>
                <a href="{{ route('central.website.register') }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                    List Your Business →
                </a>
            </div>

            {{-- Latest posts --}}
            @php $latest = \App\Models\BlogPost::published()->where('id','!=',$post->id)->latest('published_at')->limit(4)->get(); @endphp
            @if($latest->count())
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-display font-bold text-gray-900 mb-4">More Articles</h3>
                <ul class="space-y-4">
                    @foreach($latest as $lp)
                    <li>
                        <a href="{{ route('central.website.blog.show', $lp->slug) }}" class="group">
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-700 transition line-clamp-2 leading-snug">{{ $lp->title }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $lp->published_at?->format('M j, Y') }} · {{ $lp->reading_time }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </aside>
    </div>

    {{-- Related posts --}}
    @if($related->count())
    <div class="mt-16 pt-12 border-t border-gray-100">
        <h2 class="font-display text-2xl font-bold text-gray-900 mb-6">Related articles</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            @foreach($related as $rp)
            <a href="{{ route('central.website.blog.show', $rp->slug) }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                    <svg class="h-8 w-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <div class="p-4">
                    <h3 class="font-display font-bold text-gray-900 text-sm group-hover:text-indigo-700 transition line-clamp-2">{{ $rp->title }}</h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $rp->published_at?->format('M j, Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</article>

@endsection
