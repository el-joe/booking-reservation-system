@extends('layouts.central')

@section('title', $plan->name)

@section('content')
    <x-page-header title="{{ $plan->name }}" subtitle="Plan details and subscribers.">
        <a href="{{ route('central.plans.edit', $plan) }}"
           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            Edit
        </a>
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'Plans', 'url' => route('central.plans.index')], ['label' => $plan->name]]" />

    <div class="max-w-2xl space-y-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="text-base font-semibold text-gray-900">Details</h2>
                <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">Slug</dt>
                    <dd class="font-mono font-medium text-gray-800">{{ $plan->slug }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Price</dt>
                    <dd class="font-medium text-gray-800">${{ number_format($plan->price, 2) }} / {{ $plan->billing_cycle }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Max Bookings</dt>
                    <dd class="font-medium text-gray-800">{{ $plan->max_bookings === 0 || $plan->max_bookings === null ? 'Unlimited' : $plan->max_bookings }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Max Resources</dt>
                    <dd class="font-medium text-gray-800">{{ $plan->max_resources ?? 'Unlimited' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Max Staff</dt>
                    <dd class="font-medium text-gray-800">{{ $plan->max_staff ?? 'Unlimited' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Subscribers</dt>
                    <dd class="font-medium text-gray-800">{{ $plan->subscriptions_count }}</dd>
                </div>
            </dl>

            @if ($plan->features)
                <div class="mt-4">
                    <h3 class="mb-2 text-sm font-semibold text-gray-700">Features</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($plan->features as $key => $val)
                            @if ($val)
                                <span class="rounded bg-indigo-50 px-2 py-0.5 text-xs text-indigo-700">
                                    {{ \App\Enums\PlanFeature::from($key)?->label() ?? $key }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
