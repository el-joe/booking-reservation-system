@extends('layouts.website')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>

    <div class="mt-6 grid gap-6 sm:grid-cols-3">
        {{-- Loyalty Points Card --}}
        <div class="rounded-xl bg-indigo-600 p-5 text-white sm:col-span-1">
            <p class="text-xs font-semibold uppercase tracking-wide text-indigo-200">Loyalty Points</p>
            <p class="mt-2 text-3xl font-bold">{{ number_format($customer->loyalty_points) }}</p>
            <p class="mt-1 text-xs text-indigo-200">Earned from bookings</p>
        </div>

        {{-- Stats --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 sm:col-span-2">
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->bookings()->count() }}</p>
                    <p class="text-xs text-gray-500">Total Bookings</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->reviews()->count() }}</p>
                    <p class="text-xs text-gray-500">Reviews Written</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Edit Profile</h2>

        @if($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 p-3">
                <ul class="list-disc pl-4 text-sm text-red-700">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tenant.account.profile.update') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" value="{{ $customer->email }}" disabled
                       class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-400">Email cannot be changed here</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="pt-2">
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
