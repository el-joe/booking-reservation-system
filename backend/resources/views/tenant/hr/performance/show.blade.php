@extends('layouts.tenant')

@section('title', 'Performance Review')

@section('content')
    <x-page-header title="Performance Review" subtitle="{{ $review->staff?->name }} — {{ $review->period }}">
        <x-slot name="actions">
            @if ($review->status === 'submitted')
                <form method="POST" action="{{ route('tenant.hr.performance.acknowledge', $review) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                        Acknowledge
                    </button>
                </form>
            @endif
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-green-200">{{ session('success') }}</div>
    @endif

    <div class="max-w-2xl rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Staff:</span> <span class="font-medium">{{ $review->staff?->name }}</span></div>
            <div><span class="text-gray-500">Reviewer:</span> <span class="font-medium">{{ $review->reviewer?->name }}</span></div>
            <div><span class="text-gray-500">Period:</span> <span class="font-medium">{{ $review->period }}</span></div>
            <div>
                <span class="text-gray-500">Status:</span>
                @php $sc = match($review->status) { 'draft'=>'yellow', 'submitted'=>'blue', 'acknowledged'=>'green', default=>'gray' }; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-800 ml-1">{{ ucfirst($review->status) }}</span>
            </div>
        </div>

        <div class="px-6 py-4 border-b border-gray-100">
            <div class="text-sm text-gray-500 mb-1">Rating</div>
            <div class="flex gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="text-2xl {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                @endfor
                <span class="ml-2 text-sm text-gray-500 self-center">{{ $review->rating }}/5</span>
            </div>
        </div>

        @foreach (['strengths' => 'Strengths', 'improvements' => 'Areas for Improvement', 'goals' => 'Goals'] as $field => $label)
            @if ($review->$field)
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="text-xs font-semibold uppercase text-gray-500 mb-2">{{ $label }}</div>
                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $review->$field }}</p>
                </div>
            @endif
        @endforeach
    </div>
@endsection
