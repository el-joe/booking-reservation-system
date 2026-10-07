@extends('layouts.tenant')

@section('title', $campaign->name)

@section('content')
    <x-page-header title="{{ $campaign->name }}" subtitle="Campaign performance & details">
        <div class="flex items-center gap-3">
            @if ($campaign->status === 'draft' || $campaign->status === 'scheduled')
                <form method="POST" action="{{ route('tenant.marketing.campaigns.send', $campaign) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700"
                        onclick="return confirm('Send this campaign now?')">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        Send Now
                    </button>
                </form>
            @endif
            <a href="{{ route('tenant.marketing.campaigns.edit', $campaign) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Edit
            </a>
            <a href="{{ route('tenant.marketing.campaigns.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>
    </x-page-header>

    {{-- Stats Cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Recipients</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['recipients']) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Opens</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['opens']) }}</p>
            <p class="text-xs text-gray-500">{{ $stats['open_rate'] }}% open rate</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Clicks</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['clicks']) }}</p>
            <p class="text-xs text-gray-500">{{ $stats['click_rate'] }}% click rate</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Conversions</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['conversions']) }}</p>
            <p class="text-xs text-gray-500">{{ $stats['conversion_rate'] }}% conversion rate</p>
        </div>
    </div>

    {{-- Campaign Details --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-base font-semibold text-gray-900">Campaign Details</h3>
        <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-medium text-gray-500">Type</dt>
                <dd class="mt-1 text-sm font-medium capitalize text-gray-900">{{ $campaign->type }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-700',
                            'scheduled' => 'bg-blue-100 text-blue-700',
                            'sent' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($campaign->status) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Subject</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $campaign->subject ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Scheduled At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $campaign->scheduled_at?->format('M d, Y H:i') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Sent At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $campaign->sent_at?->format('M d, Y H:i') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Created By</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $campaign->createdBy?->name ?? '—' }}</dd>
            </div>
        </dl>
    </div>
@endsection
