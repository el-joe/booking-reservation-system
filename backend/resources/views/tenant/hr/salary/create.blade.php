@extends('layouts.tenant')

@section('title', 'New Salary Structure')

@section('content')
    <x-page-header title="New Salary Structure" subtitle="{{ $staff->name }}" />

    <div class="max-w-2xl" x-data="{
        components: [],
        addComponent() {
            this.components.push({ name: '', type: 'allowance', amount: '', is_percentage: false });
        },
        removeComponent(index) {
            this.components.splice(index, 1);
        }
    }">
        <form method="POST" action="{{ route('tenant.hr.salary.store', $staff) }}" class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Salary</label>
                    <input type="number" name="base_salary" step="0.01" min="0" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <input type="text" name="currency" value="USD" maxlength="3" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Effective From</label>
                <input type="date" name="effective_from" value="{{ date('Y-m-d') }}" required
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Components</h3>
                    <button type="button" @click="addComponent()"
                            class="rounded-lg border border-blue-300 px-3 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50">
                        + Add Component
                    </button>
                </div>
                <template x-for="(comp, index) in components" :key="index">
                    <div class="mb-3 grid grid-cols-12 gap-2 items-end rounded-lg border border-gray-200 p-3">
                        <div class="col-span-4">
                            <label class="block text-xs text-gray-500 mb-1">Name</label>
                            <input type="text" :name="`components[${index}][name]`" x-model="comp.name" required
                                   class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs text-gray-500 mb-1">Type</label>
                            <select :name="`components[${index}][type]`" x-model="comp.type"
                                    class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="allowance">Allowance</option>
                                <option value="deduction">Deduction</option>
                            </select>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs text-gray-500 mb-1">Amount</label>
                            <input type="number" :name="`components[${index}][amount]`" x-model="comp.amount" step="0.01" min="0" required
                                   class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="col-span-1 flex items-center gap-1">
                            <input type="checkbox" :name="`components[${index}][is_percentage]`" x-model="comp.is_percentage" value="1" class="h-4 w-4">
                            <label class="text-xs text-gray-500">%</label>
                        </div>
                        <div class="col-span-1">
                            <button type="button" @click="removeComponent(index)" class="text-red-500 hover:text-red-700 text-xs">✕</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                    Save
                </button>
                <a href="{{ route('tenant.hr.salary.index', $staff) }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
