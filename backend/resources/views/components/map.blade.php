@props([
    'lat' => null,
    'lng' => null,
    'address' => '',
    'height' => '300px',
    'zoom' => 14,
])

@php
    $mapsKey = config('services.google.maps_key', '');
    $hasKey = ! empty($mapsKey);
@endphp

<div style="height: {{ $height }}; width: 100%;" class="overflow-hidden rounded-xl border border-gray-200">
    @if ($lat && $lng)
        @if ($hasKey)
            <iframe
                width="100%"
                height="100%"
                style="border: 0;"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps/embed/v1/place?key={{ $mapsKey }}&q={{ $lat }},{{ $lng }}&zoom={{ $zoom }}">
            </iframe>
        @else
            <img
                src="https://maps.googleapis.com/maps/api/staticmap?center={{ $lat }},{{ $lng }}&zoom={{ $zoom }}&size=600x300&markers=color:red|{{ $lat }},{{ $lng }}"
                alt="{{ $address }}"
                class="h-full w-full object-cover">
        @endif
    @elseif ($address)
        @if ($hasKey)
            <iframe
                width="100%"
                height="100%"
                style="border: 0;"
                loading="lazy"
                allowfullscreen
                src="https://www.google.com/maps/embed/v1/place?key={{ $mapsKey }}&q={{ urlencode($address) }}&zoom={{ $zoom }}">
            </iframe>
        @else
            <div class="flex h-full items-center justify-center bg-gray-100 text-sm text-gray-500">
                <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                {{ $address }}
            </div>
        @endif
    @else
        <div class="flex h-full items-center justify-center bg-gray-100 text-sm text-gray-400">
            No location set
        </div>
    @endif
</div>
