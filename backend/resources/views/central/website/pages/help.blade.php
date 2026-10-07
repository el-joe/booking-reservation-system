@extends('layouts.central-website')

@section('title', 'Help Center')
@section('subtitle', 'FAQ')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900">Help Center</h1>
        <p class="mt-3 text-gray-500 text-sm">Frequently asked questions. Can't find your answer? <a href="{{ route('central.website.page', 'contact') }}" class="text-indigo-600 hover:underline">Contact us</a>.</p>
    </div>

    <div class="space-y-4" x-data="{open: null}">
        @foreach([
            ['q' => 'How do I get started listing my business?', 'a' => 'Click "List Your Business" in the top navigation, fill in your business details, choose a subdomain, and submit. Your account will be provisioned automatically and you\'ll receive a confirmation email with next steps.'],
            ['q' => 'Is there a free trial?', 'a' => 'Yes! Every new business account starts with a free trial period. No credit card is required to get started. You can explore all features before deciding on a plan.'],
            ['q' => 'How do customers book with me?', 'a' => 'Once your account is live, you\'ll have a branded booking website at your chosen subdomain. Customers can browse your availability, select a time slot, and complete their booking with instant confirmation.'],
            ['q' => 'What payment methods are supported?', 'a' => 'We support all major credit and debit cards through our integrated payment processor. Funds are transferred directly to your bank account on a regular schedule.'],
            ['q' => 'Can I manage multiple locations or services?', 'a' => 'Absolutely. From your dashboard you can add multiple resources, staff members, services or locations. Each can have its own availability schedule, pricing and booking rules.'],
        ] as $i => $faq)
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden"
             x-data="{ open: false }">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left text-sm font-semibold text-gray-900 hover:bg-gray-50 transition">
                <span>{{ $faq['q'] }}</span>
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 text-gray-400 flex-shrink-0 transition-transform duration-200"
                     :class="{'rotate-180': open}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="px-6 pb-4 text-sm text-gray-600 leading-relaxed">
                {{ $faq['a'] }}
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
