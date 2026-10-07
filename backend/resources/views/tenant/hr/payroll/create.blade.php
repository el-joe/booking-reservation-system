@extends('layouts.tenant')

@section('title', 'Run Payroll')

@section('content')
    <x-page-header title="Run Payroll" subtitle="Process payroll for a given month and year" />

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('tenant.hr.payroll.store') }}" class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select name="period_month" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @selected($m == now()->month)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <input type="number" name="period_year" value="{{ now()->year }}" min="2020" max="2100"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Active Staff & Current Salaries</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Base Salary</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($staffWithSalaries as $member)
                                @php $salary = $member->salaryStructures->first(); @endphp
                                <tr>
                                    <td class="px-4 py-2">{{ $member->name }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $member->role }}</td>
                                    <td class="px-4 py-2 text-right">
                                        @if ($salary)
                                            {{ $salary->currency }} {{ number_format((float)$salary->base_salary, 2) }}
                                        @else
                                            <span class="text-red-500 text-xs">No salary</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-4 text-center text-gray-400">No active staff found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                    Process Payroll
                </button>
                <a href="{{ route('tenant.hr.payroll.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
