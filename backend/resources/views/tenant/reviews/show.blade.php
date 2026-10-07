@extends('layouts.tenant')

@section('title', 'Review Detail')

@section('content')
    <x-page-header title="Review Detail" subtitle="Review moderation">
        <a href="{{ route('tenant.reviews.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Back to Reviews
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Review Card --}}
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700">
                        {{ strtoupper(substr($review->customer?->first_name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">{{ $review->customer?->full_name ?? 'Unknown' }}</h3>
                            <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="mt-1 flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-2 text-sm text-gray-500">{{ $review->rating }}/5</span>
                        </div>
                        @if ($review->title)
                            <h4 class="mt-2 font-medium text-gray-800">{{ $review->title }}</h4>
                        @endif
                        @if ($review->body)
                            <p class="mt-2 text-gray-700">{{ $review->body }}</p>
                        @endif
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="mt-4 flex items-center gap-2">
                    @php
                        $colors = ['pending' => 'yellow', 'published' => 'green', 'rejected' => 'red'];
                        $color = $colors[$review->status] ?? 'gray';
                    @endphp
                    <span class="inline-flex items-center rounded-full bg-{{ $color }}-100 px-3 py-1 text-sm font-medium text-{{ $color }}-800">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>

                {{-- Moderation Actions --}}
                @if ($review->status === 'pending')
                    <div class="mt-4 flex gap-3 border-t border-gray-100 pt-4">
                        <form method="POST" action="{{ route('tenant.reviews.approve', $review) }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                ✓ Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('tenant.reviews.reject', $review) }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                ✗ Reject
                            </button>
                        </form>
                        <form method="POST" action="{{ route('tenant.reviews.flag', $review) }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
                                ⚑ Flag
                            </button>
                        </form>
                    </div>
                @elseif ($review->status === 'published')
                    <div class="mt-4 flex gap-3 border-t border-gray-100 pt-4">
                        <form method="POST" action="{{ route('tenant.reviews.flag', $review) }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
                                ⚑ Flag & Hide
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Reply Section --}}
            <div class="mt-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-semibold text-gray-900">
                    {{ $review->reply ? 'Your Reply' : 'Reply to Review' }}
                </h3>

                @if ($review->reply)
                    <div class="rounded-lg bg-blue-50 p-4">
                        <p class="text-sm text-gray-700">{{ $review->reply }}</p>
                        <p class="mt-2 text-xs text-gray-400">Replied {{ $review->replied_at?->format('d M Y') }}</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('tenant.reviews.reply', $review) }}">
                        @csrf
                        <textarea name="reply" rows="4"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Write a public reply to this review...">{{ old('reply') }}</textarea>
                        @error('reply') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <div class="mt-3">
                            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Post Reply
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 font-semibold text-gray-900">Resource</h3>
                <p class="text-sm text-gray-700">{{ $review->resource?->name ?? '—' }}</p>
            </div>

            @if ($review->booking)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 font-semibold text-gray-900">Booking Reference</h3>
                    <a href="{{ route('tenant.bookings.show', $review->booking) }}"
                        class="text-sm text-blue-600 hover:underline">
                        {{ $review->booking->reference_number }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
