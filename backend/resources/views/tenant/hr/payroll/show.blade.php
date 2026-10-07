@extends('layouts.tenant')

@section('title', 'Payroll — ' . $run->period_label)

@section('content')
    <x-page-header title="Payroll: {{ $run->period_label }}" subtitle="Payroll run details and payslips">
        <x-slot name="actions">
            @if ($run->status === 'processed')
                <form method="POST" action="{{ route('tenant.hr.payroll.mark-paid', $run) }}" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Mark entire payroll as paid?')"
                            class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                        Mark as Paid
                    </button>
                </form>
            @endif
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 ring-1 ring-green-200">{{ session('success') }}</div>
    @endif

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 mb-6">
        @php
            $statusColor = match($run->status) { 'draft'=>'yellow', 'processed'=>'blue', 'paid'=>'green', default=>'gray' };
        @endphp
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
            <div class="text-xs font-medium text-gray-500 uppercase">Status</div>
            <div class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">{{ ucfirst($run->status) }}</span>
            </div>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
            <div class="text-xs font-medium text-gray-500 uppercase">Total Gross</div>
            <div class="mt-1 text-xl font-bold text-gray-900">{{ number_format((float)$run->total_gross, 2) }}</div>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
            <div class="text-xs font-medium text-gray-500 uppercase">Total Deductions</div>
            <div class="mt-1 text-xl font-bold text-red-600">{{ number_format((float)$run->total_deductions, 2) }}</div>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-5">
            <div class="text-xs font-medium text-gray-500 uppercase">Total Net</div>
            <div class="mt-1 text-xl font-bold text-green-600">{{ number_format((float)$run->total_net, 2) }}</div>
        </div>
    </div>

    {{-- Payslips --}}
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Payslips ({{ $run->payslips->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gross</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Deductions</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($run->payslips as $payslip)
                        @php $sc = match($payslip->status) { 'draft'=>'yellow', 'processed'=>'blue', 'paid'=>'green', default=>'gray' }; @endphp
                        <tr>
                            <td class="px-4 py-3">{{ $payslip->staff?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float)$payslip->gross_salary, 2) }}</td>
                            <td class="px-4 py-3 text-right text-red-600">{{ number_format((float)$payslip->total_deductions, 2) }}</td>
                            <td class="px-4 py-3 text-right font-medium text-green-700">{{ number_format((float)$payslip->net_salary, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $sc }}-100 text-{{ $sc }}-800">{{ ucfirst($payslip->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('tenant.hr.payslips.show', $payslip) }}" class="text-blue-600 hover:underline text-xs mr-2">View</a>
                                <a href="{{ route('tenant.hr.payslips.download', $payslip) }}" class="text-indigo-600 hover:underline text-xs">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400">No payslips found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
