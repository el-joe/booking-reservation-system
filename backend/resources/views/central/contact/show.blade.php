@extends('layouts.central')

@section('title', 'Contact Message')

@section('content')
    <x-page-header title="Contact Message" subtitle="Message from {{ $contactMessage->name }}">
        <a href="{{ route('central.contact.index') }}"
           class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Messages
        </a>
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'Contacts', 'url' => route('central.contact.index')], ['label' => $contactMessage->name]]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Message body --}}
        <div class="lg:col-span-2 rounded-lg bg-white shadow p-6">
            <div class="mb-6 pb-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ $contactMessage->subject }}</h2>
            </div>
            <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ $contactMessage->message }}</div>
        </div>

        {{-- Sidebar: meta + actions --}}
        <div class="space-y-4">

            {{-- Details --}}
            <div class="rounded-lg bg-white shadow p-6 space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Details</h3>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">From</p>
                    <p class="text-sm font-medium text-gray-900">{{ $contactMessage->name }}</p>
                    <a href="mailto:{{ $contactMessage->email }}" class="text-sm text-indigo-600 hover:underline">{{ $contactMessage->email }}</a>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Status</p>
                    @if ($contactMessage->status === 'new')
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Unread</span>
                    @elseif ($contactMessage->status === 'replied')
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Replied</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Read</span>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Received</p>
                    <p class="text-sm text-gray-700">{{ $contactMessage->created_at->format('M j, Y \a\t g:i A') }}</p>
                </div>

                @if ($contactMessage->ip_address)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">IP Address</p>
                        <p class="text-sm font-mono text-gray-600">{{ $contactMessage->ip_address }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="rounded-lg bg-white shadow p-6 space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Actions</h3>

                @if ($contactMessage->status !== 'replied')
                    <form method="POST" action="{{ route('central.contact.mark-replied', $contactMessage) }}">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Mark as Replied
                        </button>
                    </form>
                @endif

                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject) }}"
                   class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    Reply via Email
                </a>

                <form method="POST" action="{{ route('central.contact.destroy', $contactMessage) }}"
                      onsubmit="return confirm('Delete this message permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Delete Message
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
