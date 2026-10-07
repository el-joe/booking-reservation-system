@extends('layouts.tenant')

@section('title', 'Payslip — ' . $payslip->staff?->name)

@section('content')
    <x-page-header title="Payslip" subtitle="{{ $payslip->payrollRun->period_label }} — {{ $payslip->staff?->name }}">
        <x-slot name="actions">
            <a href="{{ route('tenant.hr.payslips.download', $payslip) }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Download PDF
            </a>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-x-1.5 rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Print
            </button>
        </x-slot>
    </x-page-header>

    <div class="max-w-2xl rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        {{-- Header --}}
        <div class="bg-blue-700 text-white px-6 py-5 text-center">
            <h2 class="text-xl font-bold">PAYSLIP</h2>
            <p class="text-blue-200 text-sm mt-1">{{ $payslip->payrollRun->period_label }}</p>
        </div>

        {{-- Staff info --}}
        <div class="px-6 py-4 border-b border-gray-100 grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $payslip->staff?->name }}</span></div>
            <div><span class="text-gray-500">Role:</span> <span class="font-medium">{{ ucfirst($payslip->staff?->role ?? '—') }}</span></div>
            <div><span class="text-gray-500">Department:</span> <span class="font-medium">{{ $payslip->staff?->department ?? '—' }}</span></div>
            <div><span class="text-gray-500">Employment:</span> <span class="font-medium">{{ ucwords(str_replace('_', ' ', $payslip->staff?->employment_type ?? '')) }}</span></div>
        </div>

        @php
            $earnings = collect($payslip->components)->where('type', 'allowance');
            $deductions = collect($payslip->components)->where('type', 'deduction');
        @endphp

        {{-- Earnings --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-3">Earnings</h4>
            @foreach ($earnings as $item)
                <div class="flex justify-between text-sm py-1">
                    <span>{{ $item['name'] }}</span>
                    <span>{{ number_format((float)$item['amount'], 2) }}</span>
                </div>
            @endforeach
            <div class="flex justify-between text-sm font-semibold py-2 border-t border-gray-200 mt-2">
                <span>Total Gross</span>
                <span>{{ number_format((float)$payslip->gross_salary, 2) }}</span>
            </div>
        </div>

        {{-- Deductions --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-3">Deductions</h4>
            @forelse ($deductions as $item)
                <div class="flex justify-between text-sm py-1 text-red-600">
                    <span>{{ $item['name'] }}</span>
                    <span>-{{ number_format((float)$item['amount'], 2) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">No deductions</p>
            @endforelse
            <div class="flex justify-between text-sm font-semibold py-2 border-t border-gray-200 mt-2 text-red-600">
                <span>Total Deductions</span>
                <span>-{{ number_format((float)$payslip->total_deductions, 2) }}</span>
            </div>
        </div>

        {{-- Net --}}
        <div class="px-6 py-5 bg-blue-50 flex justify-between items-center">
            <span class="text-base font-bold text-blue-900">NET SALARY</span>
            <span class="text-2xl font-bold text-blue-700">{{ number_format((float)$payslip->net_salary, 2) }}</span>
        </div>
    </div>
@endsection
