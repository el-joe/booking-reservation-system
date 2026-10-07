@extends('layouts.tenant')

@section('title', 'Notification Templates')

@section('content')
    <x-page-header title="Notification Templates" subtitle="Manage email, SMS, and push notification templates">
        <a href="{{ route('tenant.notifications.templates.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Template
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($templates as $template)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-3 flex items-start justify-between">
                    <h3 class="font-semibold text-gray-900">{{ $template->name }}</h3>
                    <span class="inline-flex items-center rounded-full bg-{{ $template->channel->color() }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $template->channel->color() }}-800">
                        {{ $template->channel->label() }}
                    </span>
                </div>

                <p class="mb-3 text-sm text-gray-500">
                    <span class="font-medium text-gray-700">Trigger:</span>
                    <code class="ml-1 rounded bg-gray-100 px-1.5 py-0.5 text-xs">{{ $template->event_trigger }}</code>
                </p>

                <div class="mb-4 flex items-center gap-2">
                    @if ($template->is_active)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('tenant.notifications.templates.edit', $template) }}"
                        class="text-sm font-medium text-blue-600 hover:underline">Edit</a>

                    <form method="POST" action="{{ route('tenant.notifications.test-send') }}" class="inline">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                        <button type="submit" class="text-sm font-medium text-gray-600 hover:underline">Test Send</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <p class="text-sm text-gray-500">No templates yet. Create your first template.</p>
            </div>
        @endforelse
    </div>
@endsection
