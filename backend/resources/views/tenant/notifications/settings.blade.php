@extends('layouts.tenant')

@section('title', 'Notification Settings')

@section('content')
    <x-page-header title="Notification Settings" subtitle="Configure your notification channels" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Email / SMTP --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Email (SMTP)</h3>
                    <p class="text-xs text-gray-500">Configure your outgoing mail server</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.settings.index') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="_section" value="email">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">SMTP Host</label>
                        <input type="text" name="smtp_host" placeholder="smtp.mailgun.org"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Port</label>
                        <input type="number" name="smtp_port" placeholder="587"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Username</label>
                    <input type="text" name="smtp_username"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Password</label>
                    <input type="password" name="smtp_password"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">From Name</label>
                        <input type="text" name="mail_from_name" placeholder="My Business"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">From Email</label>
                        <input type="email" name="mail_from_address" placeholder="noreply@example.com"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit"
                    class="mt-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Email Settings
                </button>
            </form>
        </div>

        {{-- SMS --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">SMS</h3>
                    <p class="text-xs text-gray-500">Configure Twilio or Vonage for SMS delivery</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.settings.index') }}" class="space-y-3"
                x-data="{ gateway: 'twilio' }">
                @csrf
                <input type="hidden" name="_section" value="sms">

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Gateway</label>
                    <select name="sms_gateway" x-model="gateway"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="twilio">Twilio</option>
                        <option value="vonage">Vonage</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700"
                        x-text="gateway === 'twilio' ? 'Account SID' : 'API Key'"></label>
                    <input type="text" name="sms_api_key"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700"
                        x-text="gateway === 'twilio' ? 'Auth Token' : 'API Secret'"></label>
                    <input type="password" name="sms_api_secret"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">From Number</label>
                    <input type="text" name="sms_from_number" placeholder="+1234567890"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="mt-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                    Save SMS Settings
                </button>
            </form>
        </div>

        {{-- Push / Firebase --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100">
                    <svg class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Push Notifications (Firebase FCM)</h3>
                    <p class="text-xs text-gray-500">Enable mobile push notifications</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.settings.index') }}" class="space-y-3"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_section" value="push">

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Firebase Project ID</label>
                    <input type="text" name="firebase_project_id"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Private Key (JSON file)</label>
                    <input type="file" name="firebase_private_key" accept=".json"
                        class="block w-full rounded-lg border border-gray-300 text-sm text-gray-700 file:mr-3 file:rounded-l-lg file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium hover:file:bg-gray-200">
                </div>

                <button type="submit"
                    class="mt-2 rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700">
                    Save Push Settings
                </button>
            </form>
        </div>

        {{-- WhatsApp --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">WhatsApp Business</h3>
                    <p class="text-xs text-gray-500">Send messages via WhatsApp Business API</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.settings.index') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="_section" value="whatsapp">

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">API Token</label>
                    <input type="password" name="whatsapp_api_token"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Phone Number ID</label>
                    <input type="text" name="whatsapp_phone_number_id"
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="mt-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    Save WhatsApp Settings
                </button>
            </form>
        </div>
    </div>
@endsection
