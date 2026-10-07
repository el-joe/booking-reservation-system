@extends('layouts.tenant')

@section('title', 'Edit Webhook')

@section('content')
    <x-page-header title="Edit Webhook" subtitle="Update webhook configuration">
        <a href="{{ route('tenant.integrations.webhooks.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            {{-- Secret display --}}
            <div class="mb-6 rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-medium text-gray-500 mb-1">Webhook Secret (use to verify payloads)</p>
                <div x-data="{ revealed: false }" class="flex items-center gap-2">
                    <code x-show="revealed" class="text-xs font-mono text-gray-800">{{ $webhook->secret }}</code>
                    <code x-show="!revealed" class="text-xs font-mono text-gray-800">{{ str_repeat('*', strlen($webhook->secret)) }}</code>
                    <button type="button" @click="revealed = !revealed" class="text-xs text-blue-600 hover:underline">
                        <span x-text="revealed ? 'Hide' : 'Reveal'">Reveal</span>
                    </button>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $webhook->secret }}')" class="text-xs text-gray-500 hover:text-gray-700">Copy</button>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.integrations.webhooks.update', $webhook) }}">
                @csrf @method('PUT')

                @include('tenant.integrations.webhooks._form', ['webhook' => $webhook])

                <div class="mt-6">
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Update Webhook
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
