@extends('layouts.central')

@section('title', 'Subscription Plans')

@section('content')
    <x-page-header title="Subscription Plans" subtitle="Manage the plans tenants can subscribe to.">
        <a href="{{ route('central.plans.create') }}"
           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + New Plan
        </a>
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'Plans']]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($plans as $plan)
            <div class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h2>
                        <span class="font-mono text-xs text-gray-400">{{ $plan->slug }}</span>
                    </div>
                    <span class="rounded-full px-2 py-1 text-xs font-medium {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($plan->price, 2) }}</span>
                    <span class="text-sm text-gray-500"> / {{ $plan->billing_cycle }}</span>
                </div>

                <ul class="mb-6 flex-1 space-y-2 text-sm text-gray-600">
                    <li class="flex justify-between">
                        <span>Bookings</span>
                        <span class="font-medium">{{ $plan->max_bookings === 0 || $plan->max_bookings === null ? 'Unlimited' : $plan->max_bookings }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Resources</span>
                        <span class="font-medium">{{ $plan->max_resources === null ? 'Unlimited' : $plan->max_resources }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Staff</span>
                        <span class="font-medium">{{ $plan->max_staff === null ? 'Unlimited' : $plan->max_staff }}</span>
                    </li>
                </ul>

                @if ($plan->features)
                    <div class="mb-4 flex flex-wrap gap-1">
                        @foreach ($plan->features as $key => $val)
                            @if ($val)
                                <span class="rounded bg-indigo-50 px-2 py-0.5 text-xs text-indigo-700">
                                    {{ \App\Enums\PlanFeature::from($key)?->label() ?? $key }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="mb-4 text-xs text-gray-400">
                    {{ $plan->subscriptions_count }} tenant(s) subscribed
                </div>

                <div class="mt-auto flex gap-2">
                    <a href="{{ route('central.plans.edit', $plan) }}"
                       class="flex-1 rounded-lg bg-indigo-50 py-2 text-center text-sm font-medium text-indigo-700 hover:bg-indigo-100">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('central.plans.destroy', $plan) }}"
                          onsubmit="return confirm('Delete {{ $plan->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-16 text-center text-gray-400">
                <p class="text-lg">No plans yet.</p>
                <a href="{{ route('central.plans.create') }}" class="mt-2 inline-block text-indigo-600 hover:underline">
                    Create your first plan
                </a>
            </div>
        @endforelse
    </div>
@endsection
