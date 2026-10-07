<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Central Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full items-center justify-center bg-gray-100 px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo / branding --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center gap-2">
                <span class="text-2xl font-bold text-gray-900">BookingApp</span>
                <span class="rounded bg-indigo-600 px-1.5 py-0.5 text-xs font-semibold text-white uppercase tracking-widest">Admin</span>
            </div>
            <p class="mt-2 text-sm text-gray-500">Sign in to the central admin panel</p>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200">
            <h1 class="mb-6 text-lg font-semibold text-gray-900">Welcome back</h1>

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('central.login.post') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="space-y-1">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 bg-red-50 @enderror"
                        placeholder="admin@example.com"
                    >
                    @error('email')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-400 bg-red-50 @enderror"
                        placeholder="Password"
                    >
                    @error('password')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Sign in
                </button>
            </form>
        </div>
    </div>

</body>
</html>
