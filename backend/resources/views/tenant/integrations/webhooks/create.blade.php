@extends('layouts.tenant')

@section('title', 'Create Webhook')

@section('content')
    <x-page-header title="New Webhook" subtitle="Configure a webhook endpoint">
        <a href="{{ route('tenant.integrations.webhooks.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('tenant.integrations.webhooks.store') }}">
                @csrf

                @include('tenant.integrations.webhooks._form')

                <div class="mt-6">
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Create Webhook
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
