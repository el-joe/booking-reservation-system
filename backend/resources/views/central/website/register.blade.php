@extends('layouts.central-website')

@section('seo')
@endsection

@section('content')

{{-- Page hero --}}
<section class="bg-gradient-to-b from-indigo-50 to-white pt-12 pb-4">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-2">Get Started Free</p>
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-gray-900 mb-2">List your business on {{ config('app.name','BookEase') }}</h1>
        <p class="text-gray-500">30-day free trial · No credit card required · Cancel anytime</p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ══ LEFT SIDEBAR ══ --}}
        <div class="hidden lg:block">
            <div class="sticky top-24 space-y-6">
                <div>
                    <h3 class="font-display font-bold text-gray-900 mb-4">Why join {{ config('app.name','BookEase') }}?</h3>
                    <ul class="space-y-4">
                        @foreach([
                            ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z','title'=>'Go live in minutes','desc'=>'Set up your booking page faster than making coffee.'],
                            ['icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','title'=>'Real-time analytics','desc'=>'See who is booking, when, and from where.'],
                            ['icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9','title'=>'Automated notifications','desc'=>'Customers get instant email/SMS confirmation.'],
                            ['icon'=>'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z','title'=>'Secure & reliable','desc'=>'99.9% uptime SLA with enterprise-grade security.'],
                            ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','title'=>'24/7 support','desc'=>'Our team is here when you need us.'],
                        ] as $b)
                        <li class="flex gap-3">
                            <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $b['icon'] }}"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $b['title'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $b['desc'] }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Trust badges --}}
                <div class="bg-indigo-50 rounded-2xl p-5 space-y-2.5">
                    @foreach(['No credit card required','30-day free trial','Cancel anytime','GDPR compliant'] as $trust)
                    <div class="flex items-center gap-2 text-sm text-indigo-800">
                        <svg class="h-4 w-4 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $trust }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══ FORM ══ --}}
        <div class="lg:col-span-2">
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('central.website.register.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- ── GROUP 1: Business ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    <h2 class="font-display font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                        Your Business
                    </h2>
                    <p class="text-sm text-gray-500 mb-5 ml-8">Basic information about your business.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="business_name">Business Name <span class="text-red-500">*</span></label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required
                                class="w-full border {{ $errors->has('business_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                placeholder="e.g. The Grand Hotel">
                            @error('business_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="business_type">Business Type <span class="text-red-500">*</span></label>
                                <select id="business_type" name="business_type" required
                                    class="w-full border {{ $errors->has('business_type') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                    <option value="">Select type…</option>
                                    @php
                                    $grouped = collect(\App\Enums\BookingType::cases())->groupBy(fn($t) => ucfirst($t->category()));
                                    @endphp
                                    @foreach($grouped as $groupName => $types)
                                    <optgroup label="{{ $groupName }}">
                                        @foreach($types as $type)
                                        <option value="{{ $type->value }}" {{ old('business_type') === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                                @error('business_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="phone">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                    class="w-full border {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="+1 555 000 0000">
                                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── GROUP 2: Account ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    <h2 class="font-display font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                        Your Account
                    </h2>
                    <p class="text-sm text-gray-500 mb-5 ml-8">Your personal login credentials.</p>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="contact_name">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required
                                    class="w-full border {{ $errors->has('contact_name') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="John Smith">
                                @error('contact_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="email">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="john@yourbusiness.com">
                                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ showPw: false, showPw2: false }">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password">Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input :type="showPw ? 'text' : 'password'" id="password" name="password" required minlength="8"
                                        class="w-full border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Min. 8 characters">
                                    <button type="button" @click="showPw=!showPw" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPw" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showPw" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password_confirmation">Confirm Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input :type="showPw2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Repeat password">
                                    <button type="button" @click="showPw2=!showPw2" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPw2" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showPw2" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── GROUP 3: Plan Selection ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8" x-data="{ selectedPlan: {{ $selectedPlanId ?: ($plans->where('is_featured',true)->first()?->id ?? $plans->first()?->id ?? 0) }} }">
                    <h2 class="font-display font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">3</span>
                        Choose a Plan
                    </h2>
                    <p class="text-sm text-gray-500 mb-5 ml-8">You can change this anytime. All plans start with a 30-day free trial.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($plans as $plan)
                        <label :class="selectedPlan === {{ $plan->id }} ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500' : 'border-gray-200 hover:border-gray-300'"
                               class="relative flex flex-col cursor-pointer rounded-2xl border-2 p-4 transition-all">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" x-model.number="selectedPlan" class="sr-only" {{ (old('plan_id', $selectedPlanId ?: '') == $plan->id || (!old('plan_id') && !$selectedPlanId && $plan->is_featured)) ? 'checked' : '' }}>
                            @if($plan->is_featured)
                            <span class="absolute -top-2.5 right-3 bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Popular</span>
                            @endif
                            <span class="font-bold text-sm text-gray-900 mb-1">{{ $plan->name }}</span>
                            <span class="font-display text-xl font-bold text-indigo-600">${{ number_format($plan->price, 0) }}<span class="text-gray-400 text-xs font-normal">/mo</span></span>
                            @if($plan->tagline)<span class="text-xs text-gray-400 mt-1">{{ $plan->tagline }}</span>@endif
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ── GROUP 4: Location & Currency ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    <h2 class="font-display font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">4</span>
                        Location & Currency
                    </h2>
                    <p class="text-sm text-gray-500 mb-5 ml-8">Currency is automatically set based on your country.</p>

                    @php
                    $countriesJson = $countries->mapWithKeys(fn($c) => [$c->iso2 => ['name'=>$c->name,'currency_code'=>$c->currency_code,'currency_symbol'=>$c->currency_symbol]])->toJson();
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                         x-data="{ countries: {{ $countriesJson }}, selectedIso: '{{ old('country_iso2','') }}', currencyCode: '{{ old('currency_code','') }}', currencySymbol: '', updateCurrency() { const c = this.countries[this.selectedIso]; if(c){ this.currencyCode = c.currency_code; this.currencySymbol = c.currency_symbol; } else { this.currencyCode = ''; this.currencySymbol = ''; } } }"
                         x-init="updateCurrency()">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="country_iso2">Country <span class="text-red-500">*</span></label>
                            <select id="country_iso2" name="country_iso2" required
                                x-model="selectedIso" @change="updateCurrency()"
                                class="w-full border {{ $errors->has('country_iso2') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="">Select your country…</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->iso2 }}" {{ old('country_iso2') === $country->iso2 ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_iso2')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Currency</label>
                            <div class="relative">
                                <span x-show="currencySymbol" x-text="currencySymbol" class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-medium"></span>
                                <input type="text" name="currency_code" :value="currencyCode" readonly
                                    :placeholder="selectedIso ? '' : 'Select country first'"
                                    :class="selectedIso ? 'bg-gray-50 text-gray-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                    class="w-full border border-gray-200 rounded-xl py-2.5 text-sm focus:outline-none pl-10 pr-4 font-medium">
                                <div x-show="!selectedIso" class="absolute inset-0 rounded-xl bg-gray-100/50 flex items-center justify-center cursor-not-allowed">
                                    <span class="text-xs text-gray-400">Select country first</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Auto-filled from your country selection</p>
                        </div>
                    </div>
                </div>

                {{-- ── GROUP 5: Domain ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    <h2 class="font-display font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">5</span>
                        Your Booking URL
                    </h2>
                    <p class="text-sm text-gray-500 mb-5 ml-8">Choose your unique subdomain. This will be your booking site address.</p>

                    @php $centralDomain = config('tenancy.central_domains.0', 'bookings.app'); @endphp

                    <div x-data="{ subdomain: '{{ old('subdomain','') }}', status: '', checking: false, timer: null,
                        check() {
                            clearTimeout(this.timer);
                            if(this.subdomain.length < 4) { this.status = ''; return; }
                            this.timer = setTimeout(() => {
                                this.checking = true;
                                fetch('/check-subdomain?subdomain=' + encodeURIComponent(this.subdomain))
                                    .then(r => r.json())
                                    .then(d => { this.status = d.available ? 'available' : 'taken'; this.checking = false; });
                            }, 500);
                        }
                    }">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="subdomain">Subdomain <span class="text-red-500">*</span></label>
                        <div class="flex rounded-xl border {{ $errors->has('subdomain') ? 'border-red-400' : 'border-gray-300' }} overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent">
                            <input type="text" id="subdomain" name="subdomain"
                                x-model="subdomain" @input="check()"
                                value="{{ old('subdomain') }}" required
                                class="flex-1 px-4 py-2.5 text-sm focus:outline-none bg-white min-w-0"
                                placeholder="your-business" pattern="[a-z0-9][a-z0-9\-]{1,30}[a-z0-9]">
                            <span class="flex items-center bg-gray-50 px-4 text-sm text-gray-500 border-l border-gray-300 whitespace-nowrap">.{{ $centralDomain }}</span>
                        </div>
                        <div class="mt-1.5 h-4 flex items-center gap-1.5">
                            <template x-if="checking"><span class="text-xs text-gray-400">Checking…</span></template>
                            <template x-if="!checking && status === 'available'">
                                <span class="text-xs text-green-600 font-medium flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Available
                                </span>
                            </template>
                            <template x-if="!checking && status === 'taken'">
                                <span class="text-xs text-red-600 font-medium flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Already taken, try another
                                </span>
                            </template>
                        </div>
                        @error('subdomain')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-400">Use only lowercase letters, numbers, and hyphens. Min 4 characters.</p>
                    </div>
                </div>

                {{-- ── GROUP 6: Legal ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-start gap-3 mb-6">
                        <input type="checkbox" id="agreed_terms" name="agreed_terms" value="1" required
                            class="mt-0.5 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                        <label for="agreed_terms" class="text-sm text-gray-600 cursor-pointer">
                            I agree to the
                            <a href="{{ route('central.website.page', 'terms') }}" class="text-indigo-600 hover:underline" target="_blank">Terms of Service</a>
                            and
                            <a href="{{ route('central.website.page', 'privacy') }}" class="text-indigo-600 hover:underline" target="_blank">Privacy Policy</a>.
                        </label>
                    </div>
                    @error('agreed_terms')<p class="-mt-4 mb-4 text-xs text-red-600">{{ $message }}</p>@enderror

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl transition shadow-md shadow-indigo-200 text-base">
                        Create My Account — Start Free Trial →
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">No credit card required · Cancel anytime · 30-day free trial</p>
                </div>

            </form>
        </div>
    </div>
</section>

@endsection
