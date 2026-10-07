@extends('layouts.tenant')

@section('title', 'Transaction #' . $transaction->id)

@section('content')
    <x-page-header title="Transaction #{{ $transaction->id }}" subtitle="Transaction details and refund history.">
        <a href="{{ route('tenant.transactions.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            &larr; Back to Transactions
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Transaction Details --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Transaction Details</h3>
                </div>
                <dl class="divide-y divide-gray-100">
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="col-span-2">
                            <span
                                class="inline-flex items-center rounded-full bg-{{ $transaction->status->color() }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $transaction->status->color() }}-800">
                                {{ $transaction->status->label() }}
                            </span>
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="col-span-2">
                            <span
                                class="inline-flex items-center rounded-full bg-{{ $transaction->type->color() }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $transaction->type->color() }}-800">
                                {{ $transaction->type->label() }}
                            </span>
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="col-span-2 text-sm font-semibold text-gray-900">
                            {{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Gateway</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $transaction->gateway ?? '—' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Gateway ID</dt>
                        <dd class="col-span-2 font-mono text-sm text-gray-900">
                            {{ $transaction->gateway_transaction_id ?? '—' }}</dd>
                    </div>
                    @if ($transaction->booking)
                        <div class="grid grid-cols-3 gap-4 px-6 py-4">
                            <dt class="text-sm font-medium text-gray-500">Booking</dt>
                            <dd class="col-span-2">
                                <a href="{{ route('tenant.bookings.show', $transaction->booking) }}"
                                    class="text-sm text-blue-600 hover:underline">
                                    {{ $transaction->booking->reference_number }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    @if ($transaction->customer)
                        <div class="grid grid-cols-3 gap-4 px-6 py-4">
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="col-span-2">
                                <a href="{{ route('tenant.customers.show', $transaction->customer) }}"
                                    class="text-sm text-blue-600 hover:underline">
                                    {{ $transaction->customer->full_name }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $transaction->notes ?? '—' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $transaction->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Refund Section --}}
        <div class="space-y-4">
            @if (! $transaction->refund && $transaction->status->value === 'paid')
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Issue Refund</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('tenant.refunds.store') }}">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                            <div class="mb-4">
                                <label class="mb-1 block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" name="amount" step="0.01"
                                    value="{{ $transaction->amount }}" max="{{ $transaction->amount }}"
                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="mb-1 block text-sm font-medium text-gray-700">Reason</label>
                                <textarea name="reason" rows="3"
                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Reason for refund..."></textarea>
                            </div>
                            <button type="submit"
                                class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                Issue Refund
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if ($transaction->refund)
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Refund</h3>
                    </div>
                    <dl class="divide-y divide-gray-100">
                        <div class="grid grid-cols-2 gap-4 px-6 py-3">
                            <dt class="text-sm text-gray-500">Amount</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                ${{ number_format((float) $transaction->refund->amount, 2) }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-4 px-6 py-3">
                            <dt class="text-sm text-gray-500">Status</dt>
                            <dd>
                                @php $s = $transaction->refund->status; $c = match($s) { 'processed' => 'green', 'failed' => 'red', default => 'yellow' }; @endphp
                                <span
                                    class="inline-flex items-center rounded-full bg-{{ $c }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $c }}-800">{{ ucfirst($s) }}</span>
                            </dd>
                        </div>
                        <div class="grid grid-cols-2 gap-4 px-6 py-3">
                            <dt class="text-sm text-gray-500">Reason</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->refund->reason ?? '—' }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-4 px-6 py-3">
                            <dt class="text-sm text-gray-500">Processed At</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $transaction->refund->processed_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            @endif
        </div>
    </div>
@endsection
