@extends('layouts.tenant')

@section('title', 'Edit Notification Template')

@section('content')
    <x-page-header title="Edit Template" subtitle="{{ $template->name }}">
        <a href="{{ route('tenant.notifications.templates') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            &larr; Back
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('tenant.notifications.templates.update', $template) }}"
                x-data="{ trigger: '{{ $template->event_trigger }}' }">
                @csrf
                @method('PUT')

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Template Name</label>
                            <input type="text" name="name" value="{{ old('name', $template->name) }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Channel</label>
                            <select name="channel"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->value }}"
                                        {{ old('channel', $template->channel->value) === $channel->value ? 'selected' : '' }}>
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
                                @foreach ($eventTriggers as $triggerKey => $vars)
                                    <option value="{{ $triggerKey }}"
                                        {{ old('event_trigger', $template->event_trigger) === $triggerKey ? 'selected' : '' }}>
                                        {{ $triggerKey }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Subject (Email)</label>
                            <input type="text" name="subject" value="{{ old('subject', $template->subject) }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Body HTML</label>
                            <textarea name="body_html" rows="15"
                                class="font-mono block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>{{ old('body_html', $template->body_html) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1"
                                {{ $template->is_active ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Active
                        </label>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Update Template
                    </button>
                </div>
            </form>
        </div>

        <div x-data="{
            triggers: @js($eventTriggers),
            trigger: '{{ $template->event_trigger }}',
            get vars() { return this.triggers[this.trigger] ?? [] }
        }">
            <div class="sticky top-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 font-semibold text-gray-900">Available Variables</h3>
                <p class="mb-4 text-xs text-gray-500">Click a variable to copy it. Use &#123;&#123;variable&#125;&#125; in templates.</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="v in vars" :key="v">
                        <button type="button"
                            @click="navigator.clipboard.writeText('{{' + v + '}}')"
                            class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-mono font-medium text-blue-700 hover:bg-blue-100"
                            x-text="'{{' + v + '}}'">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection
