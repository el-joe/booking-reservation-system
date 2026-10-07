@extends('layouts.central-website')

@section('title', 'Privacy Policy')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Privacy Policy</h1>
    <p class="text-sm text-gray-400 mb-10">Last updated: {{ date('F j, Y') }}</p>

    <div class="space-y-8 text-sm text-gray-700 leading-relaxed">
        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">1. Information We Collect</h2>
            <p>We collect information you provide directly (such as name, email, and business details during registration), information generated through your use of the Platform (such as bookings and transactions), and technical information (such as IP addresses, browser type, and usage data).</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">2. How We Use Your Information</h2>
            <p>We use the information we collect to provide, maintain, and improve the Platform; to communicate with you about your account; to process payments; to send service-related notifications; and to comply with legal obligations.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">3. Data Sharing</h2>
            <p>We do not sell your personal information. We may share information with trusted service providers who assist us in operating the Platform (such as payment processors and hosting providers), subject to confidentiality agreements. We may disclose information if required by law.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">4. Cookies</h2>
            <p>We use cookies and similar technologies to keep you logged in, remember your preferences, and analyze Platform usage. You can control cookies through your browser settings, though some features may not function correctly if cookies are disabled.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">5. Data Security</h2>
            <p>We implement industry-standard security measures to protect your data, including encryption in transit and at rest. However, no method of transmission over the internet is 100% secure.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">6. Your Rights</h2>
            <p>Depending on your location, you may have the right to access, correct, or delete your personal data. To exercise these rights, contact us through our contact page.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">7. Data Retention</h2>
            <p>We retain your information for as long as your account is active or as needed to provide services, comply with legal obligations, resolve disputes, and enforce our agreements.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">8. Changes to This Policy</h2>
            <p>We may update this Privacy Policy periodically. We will notify you of material changes by posting a notice on the Platform or by email.</p>
        </section>

        <section>
            <h2 class="text-base font-bold text-gray-900 mb-2">9. Contact</h2>
            <p>If you have questions about this Privacy Policy, please <a href="{{ route('central.website.page', 'contact') }}" class="text-indigo-600 hover:underline">contact us</a>.</p>
        </section>
    </div>
</div>
@endsection
