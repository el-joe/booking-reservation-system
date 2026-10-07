@extends('layouts.central-website')

@section('title', 'Search')
@section('subtitle', 'Find Businesses')

@section('content')
<div class="bg-indigo-600 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-white mb-5">Search Businesses</h1>
        <form action="{{ route('central.website.search') }}" method="GET" class="flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or keyword…"
                class="flex-1 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/60">
            <button type="submit" class="bg-white text-indigo-700 font-semibold px-6 py-3 rounded-xl hover:bg-indigo-50 transition text-sm">
                Search
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar filters --}}
        <aside class="w-full lg:w-56 flex-shrink-0">
            <form action="{{ route('central.website.search') }}" method="GET" id="filterForm">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wide">Business Type</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="booking_type" value="" {{ !request('booking_type') ? 'checked' : '' }}
                                class="text-indigo-600" onchange="document.getElementById('filterForm').submit()">
                            <span class="text-sm text-gray-700">All types</span>
                        </label>
                        @foreach($bookingTypes as $type)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="booking_type" value="{{ $type->value }}"
                                {{ request('booking_type') === $type->value ? 'checked' : '' }}
                                class="text-indigo-600" onchange="document.getElementById('filterForm').submit()">
                            <span class="text-sm text-gray-700">{{ $type->label() }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </aside>

        {{-- Results --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    {{ $tenants->total() }} result{{ $tenants->total() !== 1 ? 's' : '' }}
                    @if(request('q')) for "<strong>{{ request('q') }}</strong>" @endif
                </p>
            </div>

            @if($tenants->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($tenants as $tenant)
                        <x-tenant-card :tenant="$tenant" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $tenants->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-gray-500 text-sm">No businesses found. Try a different search.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
