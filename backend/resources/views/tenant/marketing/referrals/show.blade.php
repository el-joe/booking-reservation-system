@extends('layouts.tenant')

@section('title', $referral->name)

@section('content')
    <x-page-header title="{{ $referral->name }}" subtitle="Referral program details">
        <div class="flex items-center gap-2">
            <a href="{{ route('tenant.marketing.referrals.edit', $referral) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Edit
            </a>
            <a href="{{ route('tenant.marketing.referrals.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Program Info</h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Name</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $referral->name }}</dd>
                </div>
                @if ($referral->description)
                    <div>
                        <dt class="font-medium text-gray-500">Description</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $referral->description }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="font-medium text-gray-500">Reward Type</dt>
                    <dd class="mt-0.5 capitalize text-gray-900">{{ $referral->reward_type }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Reward Value</dt>
                    <dd class="mt-0.5 text-gray-900">
                        @if ($referral->reward_type === 'percent')
                            {{ $referral->reward_value }}%
                        @elseif ($referral->reward_type === 'points')
                            {{ number_format($referral->reward_value) }} pts
                        @else
                            ${{ number_format($referral->reward_value, 2) }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="mt-0.5">
                        @if ($referral->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Inactive</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Starts At</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $referral->starts_at?->format('M d, Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Ends At</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $referral->ends_at?->format('M d, Y') ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="text-base font-semibold text-gray-900">Referrals</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Referrer</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Referred Customer</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Created At</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Reward Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($referral->referrals ?? [] as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $item->referrer?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->referredCustomer?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $rewardColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'paid' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $rewardColors[$item->reward_status ?? 'pending'] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($item->reward_status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-500">No referrals yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
