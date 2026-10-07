@extends('layouts.website')

@section('title', 'Add-ons')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <div class="mb-8 flex items-center justify-center gap-2">
        @foreach(['Dates', 'Add-ons', 'Details', 'Summary', 'Payment', 'Confirm'] as $step)
            <div class="flex items-center {{ !$loop->first ? 'gap-2' : '' }}">
                @if(!$loop->first)<div class="h-px w-6 bg-gray-200"></div>@endif
                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                    {{ $loop->iteration <= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                    {{ $loop->iteration }}
                </div>
            </div>
        @endforeach
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Select Add-ons</h1>
    <p class="mt-1 text-sm text-gray-500">Enhance your booking with optional extras</p>

    <form action="{{ route('tenant.book.step3') }}" method="POST" class="mt-8">
        @csrf

        @if($resource->addOns->isNotEmpty())
            <div class="space-y-3">
                @foreach($resource->addOns->where('is_active', true) as $addOn)
                    <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-indigo-400 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                        <input type="checkbox" name="add_ons[]" value="{{ $addOn->id }}"
                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900">{{ $addOn->name }}</p>
                                <span class="text-sm font-semibold text-indigo-600">+${{ number_format($addOn->price, 2) }}</span>
                            </div>
                            @if($addOn->description)
                                <p class="mt-0.5 text-sm text-gray-500">{{ $addOn->description }}</p>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                <p class="text-sm text-gray-500">No add-ons available for this resource.</p>
            </div>
        @endif

        <div class="mt-6 flex justify-between">
            <a href="{{ route('tenant.book.step1', $data['resource_id'] ?? '') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                Continue to Details →
            </button>
        </div>
    </form>
</div>
@endsection
