@extends('layouts.tenant')

@section('title', 'Create Notification Template')

@section('content')
    <x-page-header title="Create Template" subtitle="Set up a new notification template">
        <a href="{{ route('tenant.notifications.templates') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            &larr; Back
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('tenant.notifications.templates.store') }}"
                x-data="{ trigger: '{{ old('event_trigger', '') }}' }">
                @csrf

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Template Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Channel</label>
                            <select name="channel"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->value }}" {{ old('channel') === $channel->value ? 'selected' : '' }}>
                                        {{ $channel->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Event Trigger</label>
                            <select name="event_trigger" x-model="trigger"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                <option value="">Select trigger…</option>
                                @foreach ($eventTriggers as $triggerKey => $vars)
                                    <option value="{{ $triggerKey }}" {{ old('event_trigger') === $triggerKey ? 'selected' : '' }}>
                                        {{ $triggerKey }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Subject (Email)</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="e.g. Your booking {{reference_number}} is confirmed">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Body HTML</label>
                            <textarea name="body_html" rows="15"
                                class="font-mono block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>{{ old('body_html') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Active
                        </label>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Create Template
                    </button>
                </div>
            </form>
        </div>

        {{-- Variables sidebar --}}
        <div x-data="{
            triggers: @js($eventTriggers),
            trigger: '{{ old('event_trigger', '') }}',
            get vars() { return this.triggers[this.trigger] ?? [] }
        }" x-init="$watch('$root.querySelector(\'[name=event_trigger]\').value', v => trigger = v)">
            <div class="sticky top-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 font-semibold text-gray-900">Available Variables</h3>
                <p class="mb-4 text-xs text-gray-500">Click a variable to copy it. Use &#123;&#123;variable&#125;&#125; in templates.</p>
                <template x-if="vars.length > 0">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="v in vars" :key="v">
                            <button type="button"
                                @click="navigator.clipboard.writeText('{{' + v + '}}')"
                                class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-mono font-medium text-blue-700 hover:bg-blue-100"
                                x-text="'{{' + v + '}}'">
                            </button>
                        </template>
                    </div>
                </template>
                <template x-if="vars.length === 0">
                    <p class="text-xs text-gray-400">Select a trigger to see variables.</p>
                </template>
            </div>
        </div>
    </div>
@endsection
