@extends('layouts.website')

@section('title', 'My Reviews')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">My Reviews</h1>

    @if($reviews->isEmpty())
        <div class="mt-12 flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 py-16">
            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            <p class="mt-3 text-sm text-gray-500">You haven't written any reviews yet</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach($reviews as $review)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->resource?->name }}</p>
                            <div class="mt-1 flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        @php
                            $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'published' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                            $color = $statusColors[$review->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }} capitalize">{{ $review->status }}</span>
                    </div>
                    @if($review->title)
                        <p class="mt-2 text-sm font-medium text-gray-800">{{ $review->title }}</p>
                    @endif
                    @if($review->body)
                        <p class="mt-1 text-sm text-gray-600">{{ $review->body }}</p>
                    @endif
                    <p class="mt-2 text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
