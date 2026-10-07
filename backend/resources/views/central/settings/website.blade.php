@extends('layouts.central')

@section('title', 'Website Settings')

@section('content')
    <x-page-header title="Website Settings" subtitle="Manage your public website configuration." />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('central.settings.website.update') }}" class="max-w-3xl">
        @csrf
        @method('PUT')

        <div
            x-data="{ tab: 'general' }"
            class="space-y-6"
        >
            {{-- Tab navigation --}}
            <div class="flex gap-1 rounded-lg bg-gray-100 p-1 w-fit">
                <button type="button"
                        @click="tab = 'general'"
                        :class="tab === 'general' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="rounded-md px-4 py-2 text-sm font-medium transition-all">
                    General
                </button>
                <button type="button"
                        @click="tab = 'branding'"
                        :class="tab === 'branding' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="rounded-md px-4 py-2 text-sm font-medium transition-all">
                    Branding
                </button>
                <button type="button"
                        @click="tab = 'social'"
                        :class="tab === 'social' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="rounded-md px-4 py-2 text-sm font-medium transition-all">
                    Social
                </button>
                <button type="button"
                        @click="tab = 'analytics'"
                        :class="tab === 'analytics' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="rounded-md px-4 py-2 text-sm font-medium transition-all">
                    Analytics
                </button>
            </div>

            {{-- General tab --}}
            <div x-show="tab === 'general'" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-gray-900">General Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                        <textarea name="site_description" rows="3"
                                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('site_description', $settings['site_description']) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Address</label>
                        <textarea name="contact_address" rows="2"
                                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Branding tab --}}
            <div x-show="tab === 'branding'" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm" x-cloak>
                <h2 class="mb-4 text-base font-semibold text-gray-900">Branding</h2>
                <div class="space-y-6">
                    <div x-data="{ url: '{{ old('logo_url', $settings['logo_url']) }}' }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo URL</label>
                        <input type="url" name="logo_url" x-model="url"
                               placeholder="https://example.com/logo.png"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <div x-show="url" class="mt-3">
                            <p class="text-xs text-gray-500 mb-1">Preview:</p>
                            <img :src="url" alt="Logo preview" class="h-16 max-w-xs rounded border border-gray-200 object-contain p-1">
                        </div>
                    </div>
                    <div x-data="{ url: '{{ old('favicon_url', $settings['favicon_url']) }}' }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Favicon URL</label>
                        <input type="url" name="favicon_url" x-model="url"
                               placeholder="https://example.com/favicon.ico"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <div x-show="url" class="mt-3">
                            <p class="text-xs text-gray-500 mb-1">Preview:</p>
                            <img :src="url" alt="Favicon preview" class="h-8 w-8 rounded border border-gray-200 object-contain p-0.5">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Social tab --}}
            <div x-show="tab === 'social'" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm" x-cloak>
                <h2 class="mb-4 text-base font-semibold text-gray-900">Social Media Links</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Twitter / X</label>
                        <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter']) }}"
                               placeholder="https://twitter.com/yourhandle"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                        <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin']) }}"
                               placeholder="https://linkedin.com/company/yourcompany"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                        <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}"
                               placeholder="https://facebook.com/yourpage"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                        <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}"
                               placeholder="https://instagram.com/yourhandle"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            {{-- Analytics tab --}}
            <div x-show="tab === 'analytics'" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm" x-cloak>
                <h2 class="mb-4 text-base font-semibold text-gray-900">Analytics & SEO</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                        <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id']) }}"
                               placeholder="G-XXXXXXXXXX"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-400">Enter your GA4 Measurement ID (e.g. G-XXXXXXXXXX).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                        <textarea name="meta_keywords" rows="3"
                                  placeholder="booking, reservations, appointments"
                                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_keywords', $settings['meta_keywords']) }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Comma-separated keywords for the default meta tag.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
@endsection
