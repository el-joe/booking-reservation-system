<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php $seo = app(\App\Services\Central\SeoService::class); @endphp
    @yield('seo')

    <title>{{ $seo->getTitle() ?: config('app.name', 'BookEase') }} — @yield('subtitle', 'Find & Book Anything')</title>
    <meta name="description" content="{{ $seo->getDescription() ?: 'Discover and book hotels, restaurants, services and more on the leading SaaS booking platform.' }}">
    @if($seo->getCanonical())
    <link rel="canonical" href="{{ $seo->getCanonical() }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ config('app.name', 'BookEase') }}">
    <meta property="og:type" content="{{ $seo->getOgType() ?: 'website' }}">
    <meta property="og:title" content="{{ $seo->getTitle() ?: config('app.name', 'BookEase') }}">
    <meta property="og:description" content="{{ $seo->getDescription() ?: 'The leading SaaS booking platform.' }}">
    <meta property="og:url" content="{{ $seo->getCanonical() ?: url()->current() }}">
    @if($seo->getOgImage())
    <meta property="og:image" content="{{ $seo->getOgImage() }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->getTitle() ?: config('app.name', 'BookEase') }}">
    <meta name="twitter:description" content="{{ $seo->getDescription() ?: 'The leading SaaS booking platform.' }}">
    @if($seo->getOgImage())
    <meta name="twitter:image" content="{{ $seo->getOgImage() }}">
    @endif

    {{-- JSON-LD Schema --}}
    @if($seo->getSchema())
    <script type="application/ld+json">{!! json_encode($seo->getSchema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Sans:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .font-display { font-family: 'DM Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- ═══ NAVBAR ═══ --}}
    <nav x-data="{ mobileOpen: false, scrolled: false }"
         x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
         :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm' : 'bg-white'"
         class="fixed top-0 inset-x-0 z-50 border-b border-gray-100 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('central.website.home') }}" class="flex items-center gap-2 font-display font-bold text-xl text-indigo-600 tracking-tight flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ config('app.name', 'BookEase') }}
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('central.website.search') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Browse</a>
                    <a href="{{ route('central.website.pricing') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Pricing</a>
                    <a href="{{ route('central.website.blog') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Blog</a>
                    <a href="{{ route('central.website.faq') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">FAQ</a>
                    <a href="{{ route('central.website.page', 'about') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">About</a>
                    <a href="{{ route('central.website.contact') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Contact</a>
                </div>

                {{-- Desktop CTA --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('central.login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Sign In</a>
                    <a href="{{ route('central.website.register') }}" class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        List Your Business
                    </a>
                </div>

                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                    <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1">
            <a href="{{ route('central.website.search') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Browse</a>
            <a href="{{ route('central.website.pricing') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Pricing</a>
            <a href="{{ route('central.website.blog') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Blog</a>
            <a href="{{ route('central.website.faq') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">FAQ</a>
            <a href="{{ route('central.website.page', 'about') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">About</a>
            <a href="{{ route('central.website.contact') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Contact</a>
            <div class="pt-2 border-t border-gray-100 flex flex-col gap-2">
                <a href="{{ route('central.login') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 text-center border border-gray-200 rounded-lg">Sign In</a>
                <a href="{{ route('central.website.register') }}" class="block px-3 py-2 text-sm font-semibold text-white text-center bg-indigo-600 rounded-lg">List Your Business</a>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-20 inset-x-0 z-40 flex justify-center px-4 pointer-events-none">
        <div class="max-w-lg w-full bg-green-50 border border-green-200 rounded-xl shadow-lg px-5 py-4 flex items-center gap-3 pointer-events-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-20 inset-x-0 z-40 flex justify-center px-4 pointer-events-none">
        <div class="max-w-lg w-full bg-red-50 border border-red-200 rounded-xl shadow-lg px-5 py-4 flex items-center gap-3 pointer-events-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Main content --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- ═══ FOOTER ═══ --}}
    <footer class="bg-gray-900 text-gray-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2 text-white font-display font-bold text-lg mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ config('app.name', 'BookEase') }}
                    </div>
                    <p class="text-sm leading-relaxed mb-4">The all-in-one SaaS booking platform for businesses of every kind.</p>
                    {{-- Social links --}}
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="Twitter">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="LinkedIn">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="Facebook">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Platform --}}
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Platform</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('central.website.search') }}" class="hover:text-white transition">Browse Listings</a></li>
                        <li><a href="{{ route('central.website.register') }}" class="hover:text-white transition">List Your Business</a></li>
                        <li><a href="{{ route('central.website.pricing') }}" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="{{ route('central.website.blog') }}" class="hover:text-white transition">Blog</a></li>
                        <li><a href="{{ route('central.website.faq') }}" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Company</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('central.website.page', 'about') }}" class="hover:text-white transition">About Us</a></li>
                        <li><a href="{{ route('central.website.contact') }}" class="hover:text-white transition">Contact</a></li>
                        <li><a href="{{ route('central.website.page', 'help') }}" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="{{ route('central.login') }}" class="hover:text-white transition">Admin Login</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Legal</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('central.website.page', 'terms') }}" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="{{ route('central.website.page', 'privacy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="{{ route('central.website.sitemap') }}" class="hover:text-white transition">Sitemap</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                <p>© {{ date('Y') }} {{ config('app.name', 'BookEase') }}. All rights reserved.</p>
                <p>Built with ❤️ for businesses worldwide</p>
            </div>
        </div>
    </footer>

</body>
</html>
