@extends('layouts.tenant')

@section('title', 'Pricing: ' . $resource->name)

@section('content')
    <x-page-header :title="'Pricing: ' . $resource->name" subtitle="Manage time-based and seasonal pricing rules.">
        <a href="{{ route('tenant.resources.edit', $resource) }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back to Resource
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <h3 class="mb-4 text-base font-semibold text-gray-900">Pricing Rules</h3>

        @if ($pricingRules->isNotEmpty())
            <div class="mb-6 overflow-hidden rounded-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date Range</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Modifier</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Priority</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($pricingRules as $rule)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $rule->name }}</td>
                                <td class="px-4 py-3 capitalize text-gray-600">{{ str_replace('_', ' ', $rule->rule_type) }}</td>
                                <td class="px-4 py-3 text-gray-500 text-xs font-mono">
                                    @if ($rule->applies_from && $rule->applies_to)
                                        {{ $rule->applies_from->format('Y-m-d') }} → {{ $rule->applies_to->format('Y-m-d') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($rule->modifier_type === 'percent')
                                        <span class="{{ $rule->modifier_value >= 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                            {{ $rule->modifier_value >= 0 ? '+' : '' }}{{ $rule->modifier_value }}%
                                        </span>
                                    @else
                                        <span class="{{ $rule->modifier_value >= 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                            {{ $rule->modifier_value >= 0 ? '+$' : '-$' }}{{ abs($rule->modifier_value) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $rule->priority }}</td>
                                <td class="px-4 py-3">
                                    @if ($rule->is_active)
                                        <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('tenant.resources.pricing.destroy', [$resource, $rule]) }}"
                                          class="inline" onsubmit="return confirm('Delete this pricing rule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mb-6 text-sm text-gray-500">No pricing rules yet.</p>
        @endif

        <h4 class="mb-4 text-sm font-semibold text-gray-800 border-t border-gray-200 pt-6">Add New Pricing Rule</h4>
        <form action="{{ route('tenant.resources.pricing.store', $resource) }}" method="POST" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rule Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Summer Peak Rate"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rule Type</label>
                <select name="rule_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="weekday">Weekday</option>
                    <option value="weekend">Weekend</option>
                    <option value="seasonal">Seasonal</option>
                    <option value="dynamic">Dynamic</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Modifier Type</label>
                <select name="modifier_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="percent">Percent (%)</option>
                    <option value="fixed">Fixed ($)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Modifier Value <span class="text-red-500">*</span></label>
                <input type="number" name="modifier_value" step="0.01" required placeholder="20"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Applies From</label>
                <input type="date" name="applies_from"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Applies To</label>
                <input type="date" name="applies_to"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <input type="number" name="priority" min="0" value="0"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="is_active" id="new_rule_active" value="1" checked
                       class="rounded border-gray-300 text-indigo-600">
                <label for="new_rule_active" class="text-sm text-gray-700">Active</label>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Add Rule
                </button>
            </div>
        </form>
    </div>
@endsection
