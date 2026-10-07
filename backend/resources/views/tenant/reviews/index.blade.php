@extends('layouts.tenant')

@section('title', 'Reviews')

@section('content')
    <x-page-header title="Reviews & Ratings" subtitle="Moderate customer reviews">
    </x-page-header>

    {{-- Stats Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Average Rating</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['avg_rating'] }} <span class="text-yellow-400">★</span></p>
            <p class="mt-1 text-xs text-gray-400">From {{ $stats['total'] }} reviews</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Reviews</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="mt-1 text-xs text-green-600">{{ $stats['published'] }} published</p>
        </div>
        <div class="rounded-xl border border-yellow-100 bg-yellow-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-yellow-700">Pending Moderation</p>
            <p class="mt-1 text-3xl font-bold text-yellow-800">{{ $stats['pending'] }}</p>
            <p class="mt-1 text-xs text-yellow-600">Awaiting review</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="mb-4 flex gap-2">
        @foreach (['all' => 'All', 'pending' => 'Pending', 'published' => 'Published', 'rejected' => 'Rejected'] as $value => $label)
            <a href="{{ route('tenant.reviews.index', $value !== 'all' ? ['status' => $value] : []) }}"
               class="rounded-lg px-4 py-2 text-sm font-medium transition
               {{ request('status', 'all') === $value ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <x-data-table id="reviews-table" :ajax="route('tenant.reviews.index')" />
@endsection

@push('scripts')
    {!! $dataTable->scripts() ?? '' !!}
@endpush
