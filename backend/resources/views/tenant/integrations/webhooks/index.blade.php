@extends('layouts.tenant')

@section('title', 'Webhooks')

@section('content')
    <x-page-header title="Webhooks" subtitle="Manage webhook integrations">
        <a href="{{ route('tenant.integrations.webhooks.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Webhook
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Events</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Active</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Last Triggered</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($webhooks as $webhook)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 font-mono">
                            {{ \Str::limit($webhook->url, 50) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($webhook->events ?? [] as $event)
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">{{ $event }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if ($webhook->is_active)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $webhook->last_triggered_at?->format('d M Y H:i') ?? 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('tenant.integrations.webhooks.edit', $webhook) }}" class="mr-3 text-sm text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('tenant.integrations.webhooks.destroy', $webhook) }}" class="inline"
                                onsubmit="return confirm('Delete this webhook?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">No webhooks configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
