@extends('layouts.central-website')

@section('title', $tenant->name)
@section('subtitle', 'Business Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Back --}}
    <a href="{{ route('central.website.search') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 mb-8 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to search
    </a>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header band --}}
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 px-8 py-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            {{-- Logo --}}
            @if($tenant->logo)
                <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="h-24 w-24 rounded-2xl object-contain border border-gray-200 shadow bg-white">
            @else
                <div class="h-24 w-24 rounded-2xl bg-indigo-100 flex items-center justify-center border border-indigo-200 flex-shrink-0">
                    <span class="text-3xl font-bold text-indigo-600">{{ strtoupper(substr($tenant->name, 0, 1)) }}</span>
                </div>
            @endif

            <div class="flex-1 min-w-0 text-center sm:text-left">
                <h1 class="text-2xl font-extrabold text-gray-900">{{ $tenant->name }}</h1>

                @if($tenant->business_type instanceof \App\Enums\BookingType)
                    <span class="mt-2 inline-flex items-center rounded-full bg-indigo-100 text-indigo-700 px-3 py-0.5 text-xs font-semibold">
                        {{ $tenant->business_type->label() }}
                    </span>
                @endif

                @if($tenant->notes)
                    <p class="mt-3 text-gray-600 text-sm leading-relaxed">{{ $tenant->notes }}</p>
                @endif
            </div>
        </div>

        {{-- Body --}}
        <div class="px-8 py-8 space-y-6">

            @if($tenant->domain)
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Website</p>
                    <a href="http://{{ $tenant->domain }}" target="_blank" rel="noopener"
                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition">
                        {{ $tenant->domain }}
                    </a>
                </div>
            </div>
            @endif

            @if($tenant->contact_email)
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Email</p>
                    <a href="mailto:{{ $tenant->contact_email }}" class="text-gray-700 text-sm hover:text-indigo-600 transition">
                        {{ $tenant->contact_email }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Book Now CTA --}}
            @if($tenant->domain)
            <div class="pt-2">
                <a href="http://{{ $tenant->domain }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Book Now
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
