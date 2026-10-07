@extends('layouts.central')

@section('title', 'Feature Matrix')

@section('content')
    <x-page-header title="Feature Matrix" subtitle="Toggle features across all plans at a glance.">
        <a href="{{ route('central.plans.index') }}"
           class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Plans
        </a>
    </x-page-header>
    <x-breadcrumb :items="[['label' => 'Plans', 'url' => route('central.plans.index')], ['label' => 'Feature Matrix']]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="sticky left-0 bg-gray-50 px-4 py-3 text-left font-semibold text-gray-600">Feature</th>
                    @foreach ($plans as $plan)
                        <th class="min-w-[120px] px-4 py-3 text-center font-semibold text-gray-700">
                            {{ $plan->name }}
                            <div class="text-xs font-normal text-gray-400">${{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($features as $feature)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="sticky left-0 bg-white px-4 py-3 font-medium text-gray-700">
                            {{ $feature->label() }}
                            <div class="text-xs font-normal text-gray-400">{{ $feature->description() }}</div>
                        </td>
                        @foreach ($plans as $plan)
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('central.plans.update', $plan) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $plan->name }}">
                                    <input type="hidden" name="slug" value="{{ $plan->slug }}">
                                    <input type="hidden" name="price" value="{{ $plan->price }}">
                                    <input type="hidden" name="billing_cycle" value="{{ $plan->billing_cycle }}">
                                    <input type="hidden" name="is_active" value="{{ $plan->is_active ? 1 : 0 }}">
                                    @foreach (\App\Enums\PlanFeature::cases() as $f)
                                        @if ($f !== $feature)
                                            @php $fVal = $plan->features[$f->value] ?? false; @endphp
                                            @if ($fVal)
                                                <input type="hidden" name="features[{{ $f->value }}]" value="1">
                                            @endif
                                        @endif
                                    @endforeach
                                    @php $isEnabled = $plan->features[$feature->value] ?? false; @endphp
                                    @if (! $isEnabled)
                                        <input type="hidden" name="features[{{ $feature->value }}]" value="1">
                                    @endif
                                    <button type="submit"
                                            class="mx-auto flex h-6 w-6 items-center justify-center rounded-full transition-colors {{ $isEnabled ? 'bg-green-500 hover:bg-red-400' : 'bg-gray-200 hover:bg-green-400' }}"
                                            title="{{ $isEnabled ? 'Click to disable' : 'Click to enable' }}">
                                        @if ($isEnabled)
                                            <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <span class="text-xs text-gray-400">−</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
