@extends('layouts.tenant')

@section('title', 'Payment Settings')

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
                <h2 class="mb-6 text-lg font-semibold text-gray-900">Payment Settings</h2>

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                @php
                    $enabledGateways = json_decode($settings['enabled_gateways'] ?? '[]', true) ?? [];
                @endphp

                <form method="POST" action="{{ route('tenant.settings.payment.update') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <h3 class="mb-3 text-sm font-semibold text-gray-700">Payment Gateways</h3>
                            <div class="space-y-2">
                                @foreach (['stripe' => 'Stripe', 'paypal' => 'PayPal', 'paymob' => 'Paymob', 'cash' => 'Cash on Arrival'] as $gateway => $label)
                                    <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50">
                                        <input type="checkbox" name="enabled_gateways[]" value="{{ $gateway }}"
                                            {{ in_array($gateway, $enabledGateways) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600">
                                        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-data="{ showStripe: false, showPaymob: false }">
                            <h3 class="mb-3 text-sm font-semibold text-gray-700">Stripe Configuration</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="mb-1 block text-sm text-gray-600">Publishable Key</label>
                                    <input type="text" name="stripe_publishable_key" value="{{ old('stripe_publishable_key', $settings['stripe_publishable_key'] ?? '') }}"
                                        class="block w-full rounded-lg border-gray-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500" placeholder="pk_live_...">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm text-gray-600">Secret Key</label>
                                    <div class="relative">
                                        <input :type="showStripe ? 'text' : 'password'" name="stripe_secret_key"
                                            value="{{ old('stripe_secret_key', $settings['stripe_secret_key'] ?? '') }}"
                                            class="block w-full rounded-lg border-gray-300 pr-10 text-sm font-mono focus:border-blue-500 focus:ring-blue-500" placeholder="sk_live_...">
                                        <button type="button" @click="showStripe = !showStripe" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @if (!empty($settings['stripe_secret_key_masked']))
                                        <p class="mt-1 text-xs text-gray-400">Current: {{ $settings['stripe_secret_key_masked'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div x-data="{ showPaymob: false }">
                            <h3 class="mb-3 text-sm font-semibold text-gray-700">Paymob Configuration</h3>
                            <div>
                                <label class="mb-1 block text-sm text-gray-600">API Key</label>
                                <div class="relative">
                                    <input :type="showPaymob ? 'text' : 'password'" name="paymob_api_key"
                                        value="{{ old('paymob_api_key', $settings['paymob_api_key'] ?? '') }}"
                                        class="block w-full rounded-lg border-gray-300 pr-10 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button" @click="showPaymob = !showPaymob" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                                @if (!empty($settings['paymob_api_key_masked']))
                                    <p class="mt-1 text-xs text-gray-400">Current: {{ $settings['paymob_api_key_masked'] }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Default Currency</label>
                                <select name="default_currency" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach (['USD', 'EUR', 'GBP', 'EGP', 'SAR', 'AED'] as $cur)
                                        <option value="{{ $cur }}" {{ old('default_currency', $settings['default_currency'] ?? 'USD') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Deposit Percentage (%)</label>
                                <input type="number" name="deposit_percentage" min="0" max="100" step="0.1"
                                    value="{{ old('deposit_percentage', $settings['deposit_percentage'] ?? 0) }}"
                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Save Payment Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
