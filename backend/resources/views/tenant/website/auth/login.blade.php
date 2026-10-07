@extends('layouts.website')

@section('title', 'Customer Login')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center px-4 py-12 sm:px-6">
    <div class="w-full max-w-md">
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
                <p class="mt-1 text-sm text-gray-500">Sign in to manage your bookings</p>
            </div>

            @if($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 p-3">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.customer.login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-semibold text-gray-700">Password</label>
                        <a href="#" class="text-xs text-indigo-600 hover:underline">Forgot password?</a>
                    </div>
                    <input type="password" name="password" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600">
                    <label for="remember" class="text-sm text-gray-600">Remember me</label>
                </div>

                <button type="submit" class="w-full rounded-xl bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">
                    Sign In
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('tenant.customer.register') }}" class="font-medium text-indigo-600 hover:underline">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection
