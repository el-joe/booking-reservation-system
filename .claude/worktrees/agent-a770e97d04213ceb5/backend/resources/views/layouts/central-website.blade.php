<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'BookEase')) — @yield('subtitle', 'Find & Book Anything')</title>
    <meta name="description" content="@yield('meta_description', 'Discover and book hotels, restaurants, services and more.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Fixed Top Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('central.website.home') }}" class="flex items-center gap-2 text-indigo-600 font-extrabold text-xl tracking-tight">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ config('app.name', 'BookEase') }}
                </a>

                {{-- Nav links --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('central.website.search') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Browse</a>
                    <a href="{{ route('central.website.page', 'about') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">About</a>
                    <a href="{{ route('central.website.page', 'contact') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Contact</a>
                </div>

                {{-- CTA --}}
                <a href="{{ route('central.website.register') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    List Your Business
                </a>
            </div>
        </div>
    </nav>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="fixed top-20 inset-x-0 z-40 flex justify-center px-4 pointer-events-none">
        <div class="max-w-lg w-full bg-green-50 border border-green-200 rounded-xl shadow-lg px-5 py-4 flex items-start gap-3 pointer-events-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Main content --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 text-white font-bold text-lg mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ config('app.name', 'BookEase') }}
                    </div>
                    <p class="text-sm leading-relaxed">The all-in-one booking platform for businesses of every kind.</p>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold mb-3 uppercase tracking-wide">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('central.website.search') }}" class="hover:text-white transition">Browse Listings</a></li>
                        <li><a href="{{ route('central.website.register') }}" class="hover:text-white transition">List Your Business</a></li>
                        <li><a href="{{ route('central.website.page', 'about') }}" class="hover:text-white transition">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold mb-3 uppercase tracking-wide">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('central.website.page', 'help') }}" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="{{ route('central.website.page', 'contact') }}" class="hover:text-white transition">Contact</a></li>
                        <li><a href="{{ route('central.website.page', 'terms') }}" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="{{ route('central.website.page', 'privacy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-gray-800 text-center text-xs">
                &copy; {{ date('Y') }} {{ config('app.name', 'BookEase') }}. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
