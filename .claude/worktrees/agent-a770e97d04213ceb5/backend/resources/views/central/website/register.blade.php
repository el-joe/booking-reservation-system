@extends('layouts.central-website')

@section('title', 'List Your Business')
@section('subtitle', 'Get Started')

@section('content')
<div class="bg-gradient-to-br from-indigo-600 to-purple-700 py-16 text-white text-center px-4">
    <h1 class="text-3xl sm:text-4xl font-extrabold mb-3">Grow your business with {{ config('app.name', 'BookEase') }}</h1>
    <p class="text-indigo-100 text-sm max-w-xl mx-auto">Join thousands of businesses taking bookings online. Set up in minutes, no tech skills needed.</p>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

        {{-- Benefits --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-6">Why list on {{ config('app.name', 'BookEase') }}?</h2>
            <ul class="space-y-5">
                @foreach([
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Go live in minutes', 'desc' => 'Create your business page and start accepting bookings the same day.'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Reach more customers', 'desc' => 'Get discovered by thousands of people searching for your services.'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Powerful dashboard', 'desc' => 'Manage bookings, customers, payments and analytics from one place.'],
                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Get paid online', 'desc' => 'Accept payments securely. Funds go directly to your account.'],
                ] as $benefit)
                <li class="flex gap-4">
                    <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $benefit['icon'] }}" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $benefit['title'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $benefit['desc'] }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Registration form --}}
        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Create your business account</h2>

            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('central.website.register.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Business Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Contact Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Business Type <span class="text-red-500">*</span></label>
                    <select name="business_type" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">Select a type…</option>
                        @foreach($bookingTypes as $type)
                            <option value="{{ $type->value }}" {{ old('business_type') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Desired Subdomain <span class="text-red-500">*</span></label>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500">
                        <input type="text" name="subdomain" value="{{ old('subdomain') }}" required pattern="[a-zA-Z0-9\-]+"
                            class="flex-1 px-4 py-2.5 text-sm focus:outline-none bg-white min-w-0">
                        <span class="bg-gray-50 border-l border-gray-200 px-3 py-2.5 text-xs text-gray-500 whitespace-nowrap">
                            .{{ config('app.central_domain', 'bookease.app') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Letters, numbers and hyphens only.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-400">Minimum 8 characters.</p>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition shadow text-sm">
                    Create My Business Account →
                </button>

                <p class="text-center text-xs text-gray-400">
                    By registering you agree to our
                    <a href="{{ route('central.website.page', 'terms') }}" class="text-indigo-500 hover:underline">Terms</a> and
                    <a href="{{ route('central.website.page', 'privacy') }}" class="text-indigo-500 hover:underline">Privacy Policy</a>.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
