@extends('layouts.central-website')

@section('title', 'Terms of Service')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Terms of Service</h1>
    <p class="text-sm text-gray-400 mb-10">Last updated: {{ date('F j, Y') }}</p>

    <div class="space-y-8 text-sm text-gray-700 leading-relaxed">
        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">1. Acceptance of Terms</h2>
            <p>By accessing or using {{ config('app.name', 'BookEase') }} ("the Platform"), you agree to be bound by these Terms of Service. If you do not agree, please do not use the Platform.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">2. Description of Service</h2>
            <p>{{ config('app.name', 'BookEase') }} provides a multi-tenant booking and reservation management platform. Businesses ("Tenants") may register to create their own branded booking websites, while end customers ("Users") may use Tenant sites to discover and book services.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">3. Account Registration</h2>
            <p>You must provide accurate, complete information when registering. You are responsible for maintaining the security of your account credentials and for all activities that occur under your account.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">4. Acceptable Use</h2>
            <p>You agree not to use the Platform for any unlawful purpose or in any way that could damage, disable, or impair the Platform. You may not attempt to gain unauthorized access to any part of the Platform.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">5. Payments & Fees</h2>
            <p>Subscription fees are billed in advance. All fees are non-refundable except as required by law. We reserve the right to modify pricing with 30 days' notice.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">6. Termination</h2>
            <p>We may suspend or terminate your account at any time for violation of these Terms. You may cancel your account at any time from your dashboard settings.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">7. Limitation of Liability</h2>
            <p>To the fullest extent permitted by law, {{ config('app.name', 'BookEase') }} shall not be liable for any indirect, incidental, special or consequential damages arising from your use of the Platform.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">8. Changes to Terms</h2>
            <p>We may update these Terms from time to time. Continued use of the Platform after changes constitutes acceptance of the new Terms.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">9. Contact</h2>
            <p>For questions about these Terms, contact us at <a href="{{ route('central.website.page', 'contact') }}" class="text-indigo-600 hover:underline">our contact page</a>.</p>
        </section>
    </div>
</div>
@endsection
