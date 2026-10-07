@extends('layouts.tenant')

@section('title', $resource->name)

@section('content')
    <x-page-header :title="$resource->name" :subtitle="$resource->booking_type?->label() . ' · ' . $resource->resource_type?->label()">
        <a href="{{ route('tenant.resources.edit', $resource) }}"
           class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            Edit Resource
        </a>
        <a href="{{ route('tenant.resources.index') }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left Column: Cover Image + Quick Stats --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Cover Image --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                @if ($resource->cover_image)
                    <img src="{{ Storage::url($resource->cover_image) }}" alt="{{ $resource->name }}" class="h-64 w-full object-cover">
                @else
                    <div class="flex h-64 w-full items-center justify-center bg-gray-100 text-gray-400">
                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Details</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Status</dt>
                        <dd>
                            @php $color = $resource->status?->color() ?? 'gray'; @endphp
                            <span class="rounded-full bg-{{ $color }}-100 px-2 py-0.5 text-xs font-medium text-{{ $color }}-800">
                                {{ $resource->status?->label() ?? 'Unknown' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Capacity</dt>
                        <dd class="font-medium text-gray-900">{{ $resource->capacity }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Base Price</dt>
                        <dd class="font-medium text-gray-900">{{ $resource->formatted_price }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Media</dt>
                        <dd class="font-medium text-gray-900">{{ $resource->media->count() }} files</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Pricing Rules</dt>
                        <dd class="font-medium text-gray-900">{{ $resource->pricingRules->count() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Quick Actions --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 space-y-2">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Actions</h3>
                <a href="{{ route('tenant.resources.availability', $resource) }}"
                   class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25" /></svg>
                    View Availability Calendar
                </a>
                <a href="{{ route('tenant.resources.pricing', $resource) }}"
                   class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Manage Pricing Rules
                </a>
                <a href="{{ route('tenant.resources.edit', $resource) }}#media"
                   class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" /></svg>
                    Manage Media
                </a>
            </div>
        </div>

        {{-- Right Column: Description + Media Grid + Time Slots --}}
        <div class="lg:col-span-2 space-y-6">
            @if ($resource->description)
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="mb-3 text-base font-semibold text-gray-900">Description</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $resource->description }}</p>
                </div>
            @endif

            @if ($resource->media->isNotEmpty())
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="mb-4 text-base font-semibold text-gray-900">Media ({{ $resource->media->count() }})</h3>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ($resource->media->take(6) as $media)
                            <div class="relative overflow-hidden rounded-lg">
                                @if ($media->file_type === 'video')
                                    <video src="{{ Storage::url($media->file_path) }}" class="h-32 w-full object-cover"></video>
                                @else
                                    <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->caption }}" class="h-32 w-full object-cover">
                                @endif
                                @if ($media->is_cover)
                                    <span class="absolute top-1 left-1 rounded-sm bg-indigo-600 px-1.5 py-0.5 text-xs text-white">Cover</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($resource->addOns->isNotEmpty())
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="mb-4 text-base font-semibold text-gray-900">Add-ons</h3>
                    <div class="space-y-2">
                        @foreach ($resource->addOns as $addon)
                            <div class="flex items-center justify-between rounded-md border border-gray-200 px-4 py-3 text-sm">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $addon->name }}</span>
                                    @if ($addon->description)
                                        <span class="ml-2 text-gray-500">— {{ $addon->description }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">${{ number_format($addon->price, 2) }}</span>
                                    <span class="text-xs text-gray-400">{{ $addon->price_type === 'per_person' ? '/person' : 'flat' }}</span>
                                    @if ($addon->is_required)
                                        <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs text-yellow-700">Required</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
