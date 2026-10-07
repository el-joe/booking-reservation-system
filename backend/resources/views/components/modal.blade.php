@props([
    'id' => 'modal',
    'maxWidth' => 'lg',
])

@php
    $widthMap = [
        'sm'  => 'max-w-sm',
        'md'  => 'max-w-md',
        'lg'  => 'max-w-lg',
        'xl'  => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $widthClass = $widthMap[$maxWidth] ?? 'max-w-lg';
@endphp

<div x-data="{ open: false }" id="{{ $id }}">

    {{-- Trigger slot --}}
    <div @click="open = true">
        {{ $trigger ?? '' }}
    </div>

    {{-- Backdrop + dialog --}}
    <div
        x-show="open"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="{{ $id }}-title"
        role="dialog"
        aria-modal="true"
        x-cloak
    >
        {{-- Backdrop --}}
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
        ></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all w-full {{ $widthClass }}"
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-start justify-between border-b border-gray-200 px-5 py-4">
                    <h3 id="{{ $id }}-title" class="text-base font-semibold text-gray-900">
                        {{ $title ?? '' }}
                    </h3>
                    <button
                        @click="open = false"
                        class="ml-3 shrink-0 rounded-md p-1 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-5 py-4">
                    {{ $body ?? $slot }}
                </div>

                {{-- Footer --}}
                @if (isset($footer))
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-3">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
