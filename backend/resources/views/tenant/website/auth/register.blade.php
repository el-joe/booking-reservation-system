@extends('layouts.website')

@section('title', 'Create Account')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center px-4 py-12 sm:px-6">
    <div class="w-full max-w-md">
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">Create an account</h1>
                <p class="mt-1 text-sm text-gray-500">Start booking in seconds</p>
            </div>

            @if($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 p-3">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.customer.register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <button type="submit" class="w-full rounded-xl bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">
                    Create Account
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('tenant.customer.login') }}" class="font-medium text-indigo-600 hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
