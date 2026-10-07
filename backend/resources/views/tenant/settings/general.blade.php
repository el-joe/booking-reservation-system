@extends('layouts.tenant')

@section('title', 'General Settings')

@section('content')
    <x-page-header title="Settings" subtitle="Configure your account" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                @include('tenant.settings.partials.sidebar')
            </div>
        </div>
        <div class="lg:col-span-3">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-gray-900">General Settings</h2>

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('tenant.settings.general.update') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name', $settings['business_name'] ?? '') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('business_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Timezone</label>
                            <select name="timezone" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach (timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ old('timezone', $settings['timezone'] ?? 'UTC') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                            @error('timezone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Currency</label>
                            <select name="currency" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach (['USD' => 'USD - US Dollar', 'EUR' => 'EUR - Euro', 'GBP' => 'GBP - British Pound', 'EGP' => 'EGP - Egyptian Pound', 'SAR' => 'SAR - Saudi Riyal', 'AED' => 'AED - UAE Dirham'] as $code => $label)
                                    <option value="{{ $code }}" {{ old('currency', $settings['currency'] ?? 'USD') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('currency') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Language</label>
                            <select name="language" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach (['en' => 'English', 'ar' => 'Arabic', 'fr' => 'French', 'es' => 'Spanish'] as $code => $label)
                                    <option value="{{ $code }}" {{ old('language', $settings['language'] ?? 'en') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('language') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Date Format</label>
                            <select name="date_format" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach (['d/m/Y' => 'DD/MM/YYYY', 'm/d/Y' => 'MM/DD/YYYY', 'Y-m-d' => 'YYYY-MM-DD', 'd M Y' => 'DD Mon YYYY'] as $format => $label)
                                    <option value="{{ $format }}" {{ old('date_format', $settings['date_format'] ?? 'd/m/Y') === $format ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('date_format') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
