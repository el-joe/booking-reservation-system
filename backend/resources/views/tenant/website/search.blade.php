@extends('layouts.website')

@section('title', 'Search')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex gap-8">

        {{-- Filter Sidebar --}}
        <aside class="hidden w-64 shrink-0 lg:block">
            <div class="sticky top-24 rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="font-semibold text-gray-900">Filters</h2>
                <form method="GET" action="{{ route('tenant.search') }}" id="filter-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    {{-- Booking Type --}}
                    <div class="mt-5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">Type</label>
                        <select name="booking_type" onchange="document.getElementById('filter-form').submit()"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                            <option value="">All Types</option>
                            @foreach($bookingTypes as $type)
                                <option value="{{ $type instanceof \BackedEnum ? $type->value : $type }}"
                                    {{ request('booking_type') == ($type instanceof \BackedEnum ? $type->value : $type) ? 'selected' : '' }}>
                                    {{ $type instanceof \BackedEnum ? $type->label() : ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="mt-5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">Price Range</label>
                        <div class="mt-2 flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                   class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-sm focus:outline-none">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                   class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-sm focus:outline-none">
                        </div>
                    </div>

                    {{-- Capacity --}}
                    <div class="mt-5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">Min Capacity</label>
                        <input type="number" name="capacity" value="{{ request('capacity') }}" placeholder="e.g. 10"
                               class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none">
                    </div>

                    {{-- Min Rating --}}
                    <div class="mt-5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">Minimum Rating</label>
                        <select name="min_rating" onchange="document.getElementById('filter-form').submit()"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                            <option value="">Any Rating</option>
                            @foreach([4, 3, 2] as $r)
                                <option value="{{ $r }}" {{ request('min_rating') == $r ? 'selected' : '' }}>{{ $r }}+ Stars</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="mt-5 w-full rounded-lg bg-indigo-600 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Apply Filters
                    </button>

                    @if(request()->hasAny(['booking_type', 'min_price', 'max_price', 'capacity', 'min_rating']))
                        <a href="{{ route('tenant.search', array_filter(['q' => request('q')])) }}"
                           class="mt-2 block text-center text-xs text-gray-400 hover:text-gray-600">Clear filters</a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- Results --}}
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    {{ $resources->total() }} result{{ $resources->total() !== 1 ? 's' : '' }}
                    @if(request('q')) for "<strong>{{ request('q') }}</strong>" @endif
                </p>
            </div>

            @if($resources->isEmpty())
                <div class="mt-12 flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 py-16">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <p class="mt-3 font-semibold text-gray-700">No results found</p>
                    <p class="mt-1 text-sm text-gray-400">Try adjusting your filters or search terms</p>
                    <a href="{{ route('tenant.search') }}" class="mt-4 rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Clear Search</a>
                </div>
            @else
                <div class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($resources as $resource)
                        <a href="{{ route('tenant.listing', $resource->slug) }}" class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="aspect-video overflow-hidden bg-gray-100">
                                @if($resource->cover_image)
                                    <img src="{{ $resource->cover_image }}" alt="{{ $resource->name }}"
                                         class="h-full w-full object-cover transition group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center bg-indigo-50">
                                        <svg class="h-10 w-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <span class="inline-block rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                    {{ $resource->booking_type instanceof \BackedEnum ? $resource->booking_type->label() : ucfirst(str_replace('_', ' ', $resource->booking_type)) }}
                                </span>
                                <h3 class="mt-2 font-semibold text-gray-900 group-hover:text-indigo-600">{{ $resource->name }}</h3>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="flex items-center gap-1">
                                        @php $avg = (float) ($resource->rating_average ?? 0); @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-3.5 w-3.5 {{ $i <= $avg ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-gray-400">({{ $resource->published_reviews_count ?? 0 }})</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $resource->formatted_price }}</span>
                                </div>
                                @if($resource->capacity)
                                    <p class="mt-1 text-xs text-gray-400">Up to {{ $resource->capacity }} guests</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $resources->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
