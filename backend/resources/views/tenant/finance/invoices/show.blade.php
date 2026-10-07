@extends('layouts.tenant')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
    <x-page-header title="{{ $invoice->invoice_number }}" subtitle="Invoice details and payment history.">
        <a href="{{ route('tenant.invoices.download', $invoice) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Download PDF
        </a>
        <form method="POST" action="{{ route('tenant.invoices.send', $invoice) }}" class="inline">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                Send Email
            </button>
        </form>
        @if ($invoice->status->value !== 'paid')
            <form method="POST" action="{{ route('tenant.invoices.mark-paid', $invoice) }}" class="inline">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                    Mark as Paid
                </button>
            </form>
        @endif
        <a href="{{ route('tenant.invoices.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            &larr; Back
        </a>
    </x-page-header>

    {{-- Invoice Preview --}}
    <div class="mx-auto max-w-3xl overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        {{-- Invoice Header --}}
        <div class="flex items-start justify-between border-b-4 border-blue-600 px-8 py-6">
            <div>
                <div class="text-2xl font-bold text-blue-800">{{ tenant('name') ?? config('app.name') }}</div>
                <div class="mt-1 text-sm text-gray-500">Booking & Reservation System</div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold uppercase tracking-widest text-blue-800">Invoice</div>
                <div class="mt-1 text-sm text-gray-500">{{ $invoice->invoice_number }}</div>
                <span
                    class="mt-2 inline-flex items-center rounded-full bg-{{ $invoice->status->color() }}-100 px-2.5 py-0.5 text-xs font-semibold text-{{ $invoice->status->color() }}-800">
                    {{ $invoice->status->label() }}
                </span>
            </div>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-6 border-b border-gray-100 px-8 py-6 sm:grid-cols-4">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Issue Date</div>
                <div class="mt-1 text-sm text-gray-900">{{ $invoice->created_at->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Due Date</div>
                <div class="mt-1 text-sm @if($invoice->isOverdue()) text-red-600 font-semibold @else text-gray-900 @endif">
                    {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '—' }}
                    @if ($invoice->isOverdue())
                        <span class="ml-1 text-xs text-red-500">(Overdue)</span>
                    @endif
                </div>
            </div>
            @if ($invoice->booking)
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Booking Ref</div>
                    <div class="mt-1">
                        <a href="{{ route('tenant.bookings.show', $invoice->booking) }}"
                            class="text-sm text-blue-600 hover:underline">{{ $invoice->booking->reference_number }}</a>
                    </div>
                </div>
            @endif
            @if ($invoice->paid_at)
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Paid On</div>
                    <div class="mt-1 text-sm font-semibold text-green-600">{{ $invoice->paid_at->format('d M Y') }}</div>
                </div>
            @endif
        </div>

        {{-- Customer Info --}}
        @if ($invoice->customer)
            <div class="border-b border-gray-100 bg-gray-50 px-8 py-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Bill To</div>
                <div class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->customer->full_name }}</div>
                @if ($invoice->customer->email)
                    <div class="text-sm text-gray-500">{{ $invoice->customer->email }}</div>
                @endif
                @if ($invoice->customer->phone)
                    <div class="text-sm text-gray-500">{{ $invoice->customer->phone }}</div>
                @endif
            </div>
        @endif

        {{-- Line Items --}}
        <div class="px-8 py-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="pb-3 pr-4">Description</th>
                        <th class="pb-3 pr-4 text-center">Qty</th>
                        <th class="pb-3 pr-4 text-right">Unit Price</th>
                        <th class="pb-3 pr-4 text-right">Tax %</th>
                        <th class="pb-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="py-3 pr-4 text-gray-900">{{ $item->description }}</td>
                            <td class="py-3 pr-4 text-center text-gray-700">{{ $item->quantity }}</td>
                            <td class="py-3 pr-4 text-right text-gray-700">
                                ${{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="py-3 pr-4 text-right text-gray-500">{{ number_format((float) $item->tax_rate, 1) }}%</td>
                            <td class="py-3 text-right font-medium text-gray-900">
                                ${{ number_format((float) $item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="border-t border-gray-100 px-8 py-6">
            <div class="ml-auto max-w-xs space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format((float) $invoice->subtotal, 2) }}</span>
                </div>
                @if ((float) $invoice->tax_amount > 0)
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Tax</span>
                        <span>${{ number_format((float) $invoice->tax_amount, 2) }}</span>
                    </div>
                @endif
                @if ((float) $invoice->discount_amount > 0)
                    <div class="flex justify-between text-sm text-red-600">
                        <span>Discount</span>
                        <span>-${{ number_format((float) $invoice->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between border-t-2 border-gray-200 pt-3 text-lg font-bold text-blue-800">
                    <span>Total</span>
                    <span>${{ number_format((float) $invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if ($invoice->notes)
            <div class="border-t border-gray-100 bg-amber-50 px-8 py-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">Notes</div>
                <div class="mt-1 text-sm text-gray-700">{{ $invoice->notes }}</div>
            </div>
        @endif

        <div class="border-t border-gray-100 px-8 py-6 text-center text-xs text-gray-400">
            <div class="mb-1 text-sm font-semibold text-blue-800">Thank you for your business!</div>
            Payment Terms: Net 7 days &mdash; This invoice was generated electronically.
        </div>
    </div>
@endsection
