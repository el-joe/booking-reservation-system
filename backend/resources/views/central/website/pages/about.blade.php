@extends('layouts.central-website')

@section('title', 'About')
@section('subtitle', config('app.name', 'BookEase'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">About {{ config('app.name', 'BookEase') }}</h1>
        <p class="mt-3 text-gray-500 text-sm max-w-xl mx-auto">Making bookings simple for businesses and customers alike.</p>
    </div>

    <div class="prose prose-indigo max-w-none text-gray-700 space-y-6 text-sm leading-relaxed">
        <p>
            {{ config('app.name', 'BookEase') }} is a multi-tenant SaaS booking platform built to help businesses of all kinds — from hotels and restaurants to clinics and fitness studios — accept reservations, manage appointments, and delight their customers.
        </p>

        <h2 class="text-lg font-bold text-gray-900 mt-8">Our Mission</h2>
        <p>
            We believe every business, big or small, deserves powerful, affordable tools to grow online. Our mission is to remove the technical and financial barriers to booking software so that any business owner can set up a professional booking system in minutes.
        </p>

        <h2 class="text-lg font-bold text-gray-900 mt-8">What We Offer</h2>
        <ul class="list-disc list-inside space-y-2 text-gray-600">
            <li>A dedicated booking website for your business on your own subdomain.</li>
            <li>Real-time availability and instant booking confirmation.</li>
            <li>Secure online payments integrated directly into your booking flow.</li>
            <li>Customer management, reviews and loyalty programs.</li>
            <li>Full analytics dashboard to track performance and revenue.</li>
        </ul>

        <h2 class="text-lg font-bold text-gray-900 mt-8">Our Story</h2>
        <p>
            Founded to solve the problem of fragmented, expensive booking tools, {{ config('app.name', 'BookEase') }} was built by a team of developers and entrepreneurs who wanted to create one unified platform for any booking vertical — and make it accessible to all.
        </p>

        <div class="mt-10 text-center">
            <a href="{{ route('central.website.register') }}"
               class="inline-block bg-indigo-600 text-white font-semibold px-7 py-3 rounded-xl hover:bg-indigo-700 transition shadow">
                Get Started Free →
            </a>
        </div>
    </div>
</div>
@endsection
