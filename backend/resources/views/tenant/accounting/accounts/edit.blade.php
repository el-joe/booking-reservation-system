@extends('layouts.tenant')

@section('title', 'Edit Account')

@section('content')
    <x-page-header title="Edit Account: {{ $account->name }}" subtitle="Update account details." />

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('tenant.accounting.accounts.update', $account) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Code *</label>
                    <input type="text" name="code" value="{{ old('code', $account->code) }}" required
                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $account->name) }}" required
                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                @if (!$account->is_system)
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Type *</label>
                        <select name="type" required class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach (['asset', 'liability', 'equity', 'revenue', 'expense'] as $type)
                                <option value="{{ $type }}" {{ old('type', $account->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Type</label>
                        <input type="text" value="{{ ucfirst($account->type) }}" disabled
                               class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm text-gray-500">
                    </div>
                @endif
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Parent Account</label>
                    <select name="parent_id" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">None</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $account->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} — {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="2"
                          class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $account->description) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Update Account</button>
                <a href="{{ route('tenant.accounting.accounts.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
