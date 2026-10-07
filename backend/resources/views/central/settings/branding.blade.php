@extends('layouts.central')

@section('title', 'Branding Settings')

@section('content')
    <x-page-header title="Branding" subtitle="Customize your platform's appearance." />
    <x-breadcrumb :items="[['label' => 'Settings', 'url' => route('central.settings.index')], ['label' => 'Branding']]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex gap-6">
        {{-- Sidebar tabs --}}
        <aside class="w-48 flex-shrink-0">
            <nav class="flex flex-col gap-1">
                <a href="{{ route('central.settings.index') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    General
                </a>
                <a href="{{ route('central.settings.branding') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium bg-indigo-50 text-indigo-700">
                    Branding
                </a>
                <a href="{{ route('central.settings.maintenance') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Maintenance
                </a>
                <a href="{{ route('central.settings.audit-log') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Audit Log
                </a>
            </nav>
        </aside>

        <div class="flex-1">
            <form method="POST" action="{{ route('central.settings.branding.update') }}"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Platform Identity</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form-input name="platform_name" label="Platform Name"
                            :value="$settings['platform_name'] ?? config('app.name')" />
                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Colors</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Primary Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="primary_color"
                                    value="{{ $settings['primary_color'] ?? '#4f46e5' }}"
                                    class="h-10 w-16 cursor-pointer rounded border border-gray-300">
                                <span class="text-sm text-gray-500">{{ $settings['primary_color'] ?? '#4f46e5' }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Secondary Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="secondary_color"
                                    value="{{ $settings['secondary_color'] ?? '#0ea5e9' }}"
                                    class="h-10 w-16 cursor-pointer rounded border border-gray-300">
                                <span class="text-sm text-gray-500">{{ $settings['secondary_color'] ?? '#0ea5e9' }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Logo & Favicon</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Platform Logo</label>
                            @if (!empty($settings['logo_path']))
                                <img src="{{ asset('storage/' . $settings['logo_path']) }}"
                                     alt="Current logo" class="mb-2 h-12 object-contain">
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-400">PNG, SVG recommended. Max 2 MB.</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Favicon</label>
                            @if (!empty($settings['favicon_path']))
                                <img src="{{ asset('storage/' . $settings['favicon_path']) }}"
                                     alt="Current favicon" class="mb-2 h-8 object-contain">
                            @endif
                            <input type="file" name="favicon" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-400">ICO or PNG, 32×32 px. Max 512 KB.</p>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Branding
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
