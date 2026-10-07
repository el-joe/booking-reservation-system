@props(['tenant'])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
    {{-- Logo / Avatar --}}
    <div class="h-36 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center relative">
        @if($tenant->logo)
            <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="h-24 w-24 object-contain rounded-xl">
        @else
            <div class="h-16 w-16 rounded-2xl bg-indigo-100 flex items-center justify-center">
                <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr($tenant->name, 0, 1)) }}</span>
            </div>
        @endif

        {{-- Business type badge --}}
        @if($tenant->business_type instanceof \App\Enums\BookingType)
            <span class="absolute top-3 right-3 inline-flex items-center rounded-full bg-white/90 backdrop-blur-sm px-2.5 py-0.5 text-xs font-medium text-indigo-700 border border-indigo-100 shadow-sm">
                {{ $tenant->business_type->label() }}
            </span>
        @endif
    </div>

    <div class="p-4">
        <h3 class="font-semibold text-gray-900 text-sm truncate group-hover:text-indigo-600 transition">{{ $tenant->name }}</h3>

        @if($tenant->notes)
            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $tenant->notes }}</p>
        @endif

        <div class="mt-3 flex items-center justify-between">
            @if($tenant->domain)
                <a href="http://{{ $tenant->domain }}" target="_blank" rel="noopener"
                   class="text-xs text-indigo-500 hover:text-indigo-700 truncate max-w-[120px] transition">
                    {{ $tenant->domain }}
                </a>
            @else
                <span class="text-xs text-gray-400">No domain</span>
            @endif

            <a href="{{ route('central.website.listing', $tenant) }}"
               class="text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-lg transition">
                View
            </a>
        </div>
    </div>
</div>
