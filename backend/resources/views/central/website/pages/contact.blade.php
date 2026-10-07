@extends('layouts.central-website')

@section('title', 'Contact')
@section('subtitle', 'Get in Touch')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900">Contact Us</h1>
        <p class="mt-3 text-gray-500 text-sm">We'd love to hear from you. Fill out the form and we'll get back to you within 24 hours.</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
        <form action="mailto:support@{{ config('app.central_domain', 'bookease.app') }}" method="POST" enctype="text/plain" class="space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Your Name</label>
                <input type="text" name="name" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email Address</label>
                <input type="email" name="email" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Message</label>
                <textarea name="message" rows="5" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition shadow text-sm">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection
