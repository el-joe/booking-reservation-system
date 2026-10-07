@extends('layouts.central')

@section('title', 'Global Settings')

@section('content')
    <x-page-header title="Global Settings" subtitle="Manage platform-wide configuration." />
    <x-breadcrumb :items="[['label' => 'Settings']]" />

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
                   class="rounded-lg px-3 py-2 text-sm font-medium bg-indigo-50 text-indigo-700">
                    General
                </a>
                <a href="{{ route('central.settings.branding') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
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

        {{-- Content --}}
        <div class="flex-1">
            <form method="POST" action="{{ route('central.settings.update') }}" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Email Gateway --}}
                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Email Gateway</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form-select name="email_driver" label="Driver"
                            :value="$settings['email_driver'] ?? ''"
                            :options="['smtp' => 'SMTP', 'mailgun' => 'Mailgun', 'ses' => 'Amazon SES', 'log' => 'Log (dev)']" />
                        <x-form-input name="email_host" label="SMTP Host"
                            :value="$settings['email_host'] ?? ''" />
                        <x-form-input name="email_port" label="SMTP Port" type="number"
                            :value="$settings['email_port'] ?? '587'" />
                        <x-form-input name="email_username" label="Username"
                            :value="$settings['email_username'] ?? ''" />
                        <x-form-input name="email_password" label="Password" type="password"
                            :value="$settings['email_password'] ?? ''" />
                        <x-form-select name="email_encryption" label="Encryption"
                            :value="$settings['email_encryption'] ?? 'tls'"
                            :options="['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None']" />
                    </div>
                </section>

                {{-- SMS Gateway --}}
                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">SMS Gateway</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form-select name="sms_driver" label="Provider"
                            :value="$settings['sms_driver'] ?? ''"
                            :options="['twilio' => 'Twilio', 'vonage' => 'Vonage', 'africas_talking' => "Africa's Talking", 'log' => 'Log (dev)']" />
                        <x-form-input name="sms_api_key" label="API Key"
                            :value="$settings['sms_api_key'] ?? ''" />
                        <x-form-input name="sms_sender_id" label="Sender ID"
                            :value="$settings['sms_sender_id'] ?? ''" />
                    </div>
                </section>

                {{-- Booking Type Matrix --}}
                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Booking Type Matrix</h2>
                    <p class="mb-3 text-sm text-gray-500">Select which booking types are available across the platform.</p>
                    @php
                        $enabledTypes = json_decode($settings['booking_types'] ?? '[]', true) ?? [];
                        $allTypes = ['hourly' => 'Hourly', 'daily' => 'Daily', 'session' => 'Session', 'recurring' => 'Recurring', 'overnight' => 'Overnight'];
                    @endphp
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach ($allTypes as $value => $label)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50">
                                <input type="checkbox" name="booking_types[]" value="{{ $value }}"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600"
                                    @if (in_array($value, $enabledTypes)) checked @endif>
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
