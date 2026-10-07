<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome') — {{ tenant('name') ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-white" x-data="{ mobileMenuOpen: false }">

    {{-- Flash Toast --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-4 right-4 z-50 rounded-lg bg-green-600 px-4 py-3 text-sm text-white shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-4 right-4 z-50 rounded-lg bg-red-600 px-4 py-3 text-sm text-white shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-4">

                {{-- Logo / Name --}}
                <a href="{{ route('tenant.home') }}" class="flex shrink-0 items-center gap-2">
                    @if(tenant('logo_url'))
                        <img src="{{ tenant('logo_url') }}" alt="{{ tenant('name') }}" class="h-9 w-9 rounded-md object-cover">
                    @else
                        <span class="flex h-9 w-9 items-center justify-center rounded-md bg-indigo-600 text-xs font-bold text-white">
                            {{ strtoupper(substr(tenant('name') ?? 'T', 0, 2)) }}
                        </span>
                    @endif
                    <span class="hidden text-lg font-bold text-gray-900 sm:block">{{ tenant('name') ?? config('app.name') }}</span>
                </a>

                {{-- Search bar (center) --}}
                <div class="hidden flex-1 max-w-lg md:block">
                    <form action="{{ route('tenant.search') }}" method="GET" class="flex">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Search spaces, venues, equipment..."
                               class="w-full rounded-l-lg border border-r-0 border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <button type="submit" class="rounded-r-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Nav Links --}}
                <nav class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('tenant.search') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Explore</a>
                    <a href="#about" class="text-sm font-medium text-gray-600 hover:text-indigo-600">About</a>
                    <a href="#contact" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Contact</a>
                </nav>

                {{-- Customer Auth --}}
                <div class="flex items-center gap-3">
                    @auth('customer')
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">
                                    {{ strtoupper(substr(auth('customer')->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:block">{{ auth('customer')->user()->name }}</span>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 rounded-lg bg-white py-1 shadow-lg ring-1 ring-gray-200">
                                <a href="{{ route('tenant.account.bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Bookings</a>
                                <a href="{{ route('tenant.account.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                                <a href="{{ route('tenant.account.reviews') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Reviews</a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('tenant.customer.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-50">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('tenant.customer.login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Login</a>
                        <a href="{{ route('tenant.customer.register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Register</a>
                    @endauth

                    {{-- Mobile menu button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="rounded-md p-2 text-gray-600 hover:bg-gray-100 md:hidden">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenuOpen" x-cloak class="border-t border-gray-100 bg-white px-4 py-3 md:hidden">
            <form action="{{ route('tenant.search') }}" method="GET" class="mb-3 flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..."
                       class="w-full rounded-l-lg border border-r-0 border-gray-300 px-3 py-2 text-sm focus:outline-none">
                <button type="submit" class="rounded-r-lg bg-indigo-600 px-3 py-2 text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </form>
            <div class="space-y-1">
                <a href="{{ route('tenant.search') }}" class="block rounded px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Explore</a>
                <a href="#about" class="block rounded px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">About</a>
                <a href="#contact" class="block rounded px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Contact</a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 bg-gray-900 text-gray-300" id="contact">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div>
                    <h3 class="text-base font-semibold text-white">{{ tenant('name') ?? config('app.name') }}</h3>
                    @if(tenant('address'))
                        <p class="mt-2 text-sm">{{ tenant('address') }}</p>
                    @endif
                    @if(tenant('phone'))
                        <p class="mt-1 text-sm">{{ tenant('phone') }}</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Quick Links</h3>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li><a href="{{ route('tenant.home') }}" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('tenant.search') }}" class="hover:text-white">Explore</a></li>
                        <li><a href="#about" class="hover:text-white">About</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Account</h3>
                    <ul class="mt-2 space-y-1 text-sm">
                        @auth('customer')
                            <li><a href="{{ route('tenant.account.bookings') }}" class="hover:text-white">My Bookings</a></li>
                            <li><a href="{{ route('tenant.account.profile') }}" class="hover:text-white">Profile</a></li>
                        @else
                            <li><a href="{{ route('tenant.customer.login') }}" class="hover:text-white">Login</a></li>
                            <li><a href="{{ route('tenant.customer.register') }}" class="hover:text-white">Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-6 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ tenant('name') ?? config('app.name') }}. All rights reserved.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
