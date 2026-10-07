@extends('tenant.layouts.app')

@section('title', 'Notification Settings')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-semibold mb-6">Notification Settings</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.settings.notifications.update') }}">
            @csrf
            @method('POST')

            {{-- SMTP / Email --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-medium mb-4">Email (SMTP)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mail Driver</label>
                        <select name="mail_driver" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (['smtp', 'mailgun', 'ses', 'postmark'] as $driver)
                                <option value="{{ $driver }}" @selected(($settings['mail_driver'] ?? 'smtp') === $driver)>
                                    {{ strtoupper($driver) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                        <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Encryption</label>
                        <select name="mail_encryption" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">None</option>
                            <option value="tls" @selected(($settings['mail_encryption'] ?? '') === 'tls')>TLS</option>
                            <option value="ssl" @selected(($settings['mail_encryption'] ?? '') === 'ssl')>SSL</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">From Address</label>
                        <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">From Name</label>
                        <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Twilio SMS --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-medium mb-4">SMS (Twilio)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Account SID</label>
                        <input type="text" name="twilio_sid" value="{{ $settings['twilio_sid'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Auth Token</label>
                        <input type="password" name="twilio_auth_token" value="{{ $settings['twilio_auth_token'] ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">From Number</label>
                        <input type="text" name="twilio_from_number" value="{{ $settings['twilio_from_number'] ?? '' }}"
                            placeholder="+1234567890"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Firebase FCM --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-medium mb-4">Push Notifications (Firebase FCM)</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700">FCM Server Key</label>
                    <input type="text" name="fcm_server_key" value="{{ $settings['fcm_server_key'] ?? '' }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection
