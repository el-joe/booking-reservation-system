@props([
    'icon' => '',
    'label' => '',
    'value' => '',
    'change' => null,
    'color' => 'blue',
    'trend' => 'up',
])

@php
    $colorMap = [
        'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600',   'ring' => 'ring-blue-100'],
        'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-600',  'ring' => 'ring-green-100'],
        'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-600',    'ring' => 'ring-red-100'],
        'yellow' => ['bg' => 'bg-yellow-50', 'icon' => 'text-yellow-600', 'ring' => 'ring-yellow-100'],
    ];
    $colors = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
    <div class="flex items-center justify-between">
        <div class="flex-1 min-w-0">
            <p class="truncate text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $value }}</p>
            @if ($change !== null)
                <div class="mt-2 flex items-center gap-1">
                    @if ($trend === 'up')
                        <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                        <span class="text-xs font-medium text-green-600">{{ $change }}%</span>
                    @else
                        <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                        </svg>
                        <span class="text-xs font-medium text-red-600">{{ $change }}%</span>
                    @endif
                    <span class="text-xs text-gray-400">vs last period</span>
                </div>
            @endif
        </div>
        <div class="ml-4 shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $colors['bg'] }} ring-1 {{ $colors['ring'] }}">
                <span class="{{ $colors['icon'] }}">
                    {!! $icon !!}
                </span>
            </div>
        </div>
    </div>
</div>
