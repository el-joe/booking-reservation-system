@extends('layouts.tenant')

@section('title', 'Chart of Accounts')

@section('content')
    <x-page-header title="Chart of Accounts" subtitle="Manage your double-entry accounting structure.">
        <x-slot name="actions">
            <a href="{{ route('tenant.accounting.accounts.create') }}"
               class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                + Add Account
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    @foreach (['asset' => 'Assets', 'liability' => 'Liabilities', 'equity' => 'Equity', 'revenue' => 'Revenue', 'expense' => 'Expenses'] as $type => $label)
        <div x-data="{ open: true }" class="mb-4 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <button @click="open = !open"
                    class="flex w-full items-center justify-between px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50">
                <span>{{ $label }}</span>
                <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition>
                <table class="w-full text-sm">
                    <thead class="border-t border-gray-100 bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Code</th>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-right">Balance</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($accounts->get($type, collect()) as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-mono text-gray-600">{{ $account->code }}</td>
                                <td class="px-6 py-3 font-medium text-gray-800">
                                    {{ $account->name }}
                                    @if ($account->is_system)
                                        <span class="ml-1 rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-500">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right text-gray-700">${{ number_format($account->balance, 2) }}</td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('tenant.accounting.accounts.create', ['parent_id' => $account->id]) }}"
                                       class="mr-2 text-xs text-blue-600 hover:underline">+ Sub-account</a>
                                    <a href="{{ route('tenant.accounting.accounts.edit', $account) }}"
                                       class="mr-2 text-xs text-gray-600 hover:underline">Edit</a>
                                    @if (!$account->is_system)
                                        <form method="POST" action="{{ route('tenant.accounting.accounts.destroy', $account) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:underline"
                                                    onclick="return confirm('Delete this account?')">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @foreach ($account->children as $child)
                                <tr class="bg-gray-50 hover:bg-gray-100">
                                    <td class="px-6 py-2 pl-10 font-mono text-gray-500">{{ $child->code }}</td>
                                    <td class="px-6 py-2 pl-10 text-gray-700">{{ $child->name }}</td>
                                    <td class="px-6 py-2 text-right text-gray-600">${{ number_format($child->balance, 2) }}</td>
                                    <td class="px-6 py-2 text-right">
                                        <a href="{{ route('tenant.accounting.accounts.edit', $child) }}" class="text-xs text-gray-600 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">No accounts in this category.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
