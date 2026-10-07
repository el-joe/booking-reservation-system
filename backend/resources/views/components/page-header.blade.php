@props([
    'title' => '',
    'subtitle' => null,
])

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="flex shrink-0 items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
