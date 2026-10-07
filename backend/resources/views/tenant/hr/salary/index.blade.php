@extends('layouts.tenant')

@section('title', 'Salary — ' . $staff->name)

@section('content')
    <x-page-header title="Salary Structures" subtitle="{{ $staff->name }}">
        <x-slot name="actions">
            <a href="{{ route('tenant.hr.salary.create', $staff) }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                Update Salary
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-green-200">{{ session('success') }}</div>
    @endif

    @forelse ($structures as $structure)
        <div class="mb-4 rounded-xl bg-white shadow-sm ring-1 ring-{{ $structure->is_active ? 'blue' : 'gray' }}-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div>
                    <span class="text-sm font-semibold text-gray-900">Effective from {{ $structure->effective_from->format('d M Y') }}</span>
                    @if ($structure->is_active)
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Active</span>
                    @endif
                </div>
                <a href="{{ route('tenant.hr.salary.edit', $structure) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
            </div>
            <div class="px-5 py-4">
                <div class="text-xl font-bold text-gray-900">{{ $structure->currency }} {{ number_format((float)$structure->base_salary, 2) }} <span class="text-sm font-normal text-gray-500">Base Salary</span></div>
                @if ($structure->components->isNotEmpty())
                    <div class="mt-3 space-y-1">
                        @foreach ($structure->components as $comp)
                            <div class="flex justify-between text-sm">
                                <span class="{{ $comp->type === 'allowance' ? 'text-green-700' : 'text-red-600' }}">{{ $comp->name }} ({{ ucfirst($comp->type) }})</span>
                                <span>{{ $comp->is_percentage ? $comp->amount.'%' : number_format((float)$comp->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 px-5 py-8 text-center text-gray-400">
            No salary structure defined yet.
        </div>
    @endforelse
@endsection
