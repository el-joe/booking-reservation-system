@extends('layouts.central-website')

@section('seo')
@endsection

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-b from-indigo-50 to-white pt-14 pb-10">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-3">Our Blog</p>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-gray-900 mb-3" style="text-wrap:balance">Insights for booking businesses</h1>
        <p class="text-gray-500 text-lg">Tips, guides and updates from the {{ config('app.name','BookEase') }} team.</p>

        {{-- Category filter --}}
        @if($categories->count())
        <div class="flex flex-wrap justify-center gap-2 mt-8">
            <a href="{{ route('central.website.blog') }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">All</a>
            @foreach($categories as $cat)
            <a href="{{ route('central.website.blog', ['category' => $cat]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition {{ request('category') === $cat ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">{{ $cat }}</a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-20">

    {{-- Featured post --}}
    @if($featuredPost && !request('category'))
    <a href="{{ route('central.website.blog.show', $featuredPost->slug) }}"
       class="group block mb-12 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md overflow-hidden transition">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="aspect-video lg:aspect-auto bg-gradient-to-br from-indigo-500 to-purple-600 relative overflow-hidden">
                @if($featuredPost->cover_image)
                <img src="{{ $featuredPost->cover_image }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full min-h-[200px]">
                    <svg class="h-20 w-20 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
                @if($featuredPost->category)
                <span class="absolute top-4 left-4 bg-white text-indigo-700 text-xs font-bold px-3 py-1 rounded-full shadow">{{ $featuredPost->category }}</span>
                @endif
            </div>
            <div class="p-8 lg:p-10 flex flex-col justify-center">
                <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-3">Featured Post</span>
                <h2 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-indigo-700 transition leading-snug" style="text-wrap:balance">{{ $featuredPost->title }}</h2>
                @if($featuredPost->excerpt)
                <p class="text-gray-500 mb-5 leading-relaxed">{{ $featuredPost->excerpt }}</p>
                @endif
                <div class="flex items-center gap-4 text-sm text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $featuredPost->author_name }}
                    </span>
                    <span>{{ $featuredPost->published_at?->format('M j, Y') }}</span>
                    <span>{{ $featuredPost->reading_time }}</span>
                </div>
                <div class="mt-6">
                    <span class="inline-flex items-center gap-1.5 text-indigo-600 font-semibold text-sm group-hover:gap-2.5 transition-all">
                        Read article <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </a>
    @endif

    {{-- Posts grid --}}
    @if($posts->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
        @if(!($loop->first && !request('category') && $post->id === $featuredPost?->id))
        <a href="{{ route('central.website.blog.show', $post->slug) }}"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden">
            <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 relative overflow-hidden">
                @if($post->cover_image)
                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="flex items-center justify-center h-full">
                    <svg class="h-10 w-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
                @if($post->category)
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $post->category }}</span>
                @endif
            </div>
            <div class="p-5">
                <h3 class="font-display font-bold text-gray-900 mb-2 leading-snug group-hover:text-indigo-700 transition line-clamp-2">{{ $post->title }}</h3>
                @if($post->excerpt)
                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                @endif
                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>{{ $post->published_at?->format('M j, Y') }}</span>
                    <span class="flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $post->reading_time }}
                    </span>
                </div>
            </div>
        </a>
        @endif
        @endforeach
    </div>

    <div class="mt-10">
        {{ $posts->links() }}
    </div>
    @else
    <div class="text-center py-20">
        <p class="text-gray-400 text-lg">No posts in this category yet.</p>
        <a href="{{ route('central.website.blog') }}" class="text-indigo-600 hover:underline text-sm mt-2 inline-block">View all posts →</a>
    </div>
    @endif

</section>

@endsection
