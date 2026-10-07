@extends('layouts.central-website')

@section('title', $bookingType->label())
@section('subtitle', 'Category')

@section('content')
<div class="bg-gradient-to-br from-indigo-600 to-purple-700 py-14 text-white text-center">
    <div class="max-w-2xl mx-auto px-4">
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-white/20 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold">{{ $bookingType->label() }}</h1>
        <p class="text-indigo-100 mt-2 text-sm">Browse all {{ $bookingType->label() }} businesses on our platform.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($tenants->count() > 0)
        <p class="text-sm text-gray-500 mb-6">{{ $tenants->total() }} business{{ $tenants->total() !== 1 ? 'es' : '' }} found</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($tenants as $tenant)
                <x-tenant-card :tenant="$tenant" />
            @endforeach
        </div>
        <div class="mt-8">
            {{ $tenants->links() }}
        </div>
    @else
        <div class="text-center py-20">
            <p class="text-gray-500 text-sm">No businesses listed in this category yet.</p>
            <a href="{{ route('central.website.register') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Be the first to list your business →
            </a>
        </div>
    @endif
</div>
@endsection
