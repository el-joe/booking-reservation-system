@props([
    'links' => [],
])

<nav class="flex" aria-label="Breadcrumb">
    <ol class="flex items-center gap-1.5 text-sm">
        @foreach ($links as $index => $link)
            @if ($index > 0)
                <li>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </li>
            @endif
            <li>
                @if ($loop->last || empty($link['url']))
                    <span class="font-medium text-gray-700" aria-current="page">{{ $link['label'] }}</span>
                @else
                    <a href="{{ $link['url'] }}" class="text-gray-500 hover:text-gray-700 transition-colors">{{ $link['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
