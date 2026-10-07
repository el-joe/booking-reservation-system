@extends('layouts.website')

@section('title', 'Guest Details')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <div class="mb-8 flex items-center justify-center gap-2">
        @foreach(['Dates', 'Add-ons', 'Details', 'Summary', 'Payment', 'Confirm'] as $step)
            <div class="flex items-center {{ !$loop->first ? 'gap-2' : '' }}">
                @if(!$loop->first)<div class="h-px w-6 bg-gray-200"></div>@endif
                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                    {{ $loop->iteration <= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                    {{ $loop->iteration }}
                </div>
            </div>
        @endforeach
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Guest Details</h1>

    <form action="{{ route('tenant.book.step4') }}" method="POST" class="mt-8">
        @csrf

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', auth('customer')->user()->name) }}" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
                    @error('guest_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="guest_email" value="{{ old('guest_email', auth('customer')->user()->email) }}" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
                    @error('guest_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Phone</label>
                <input type="text" name="guest_phone" value="{{ old('guest_phone', auth('customer')->user()->phone) }}"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Special Requests</label>
                <textarea name="special_requests" rows="3" placeholder="Any special requirements or requests..."
                          class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">{{ old('special_requests') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button type="button" onclick="history.back()" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </button>
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                Review Summary →
            </button>
        </div>
    </form>
</div>
@endsection
