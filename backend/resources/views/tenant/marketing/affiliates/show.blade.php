@extends('layouts.tenant')

@section('title', $affiliate->name)

@section('content')
    <x-page-header title="{{ $affiliate->name }}" subtitle="Affiliate partner details">
        <div class="flex items-center gap-2">
            <a href="{{ route('tenant.marketing.affiliates.edit', $affiliate) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Edit
            </a>
            <a href="{{ route('tenant.marketing.affiliates.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Affiliate Info</h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Name</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $affiliate->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Email</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $affiliate->email }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Tracking Code</dt>
                    <dd class="mt-0.5">
                        <code class="rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-800">{{ $affiliate->tracking_code }}</code>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Commission Rate</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $affiliate->commission_rate }}%</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Total Earnings</dt>
                    <dd class="mt-0.5 font-semibold text-gray-900">${{ number_format($affiliate->total_earnings, 2) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="mt-0.5">
                        @if ($affiliate->status === 'active')
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Inactive</span>
                        @endif
                    </dd>
                </div>
                @if ($affiliate->notes)
                    <div>
                        <dt class="font-medium text-gray-500">Notes</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $affiliate->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="text-base font-semibold text-gray-900">Conversions</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Booking ID</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($affiliate->conversions ?? [] as $conversion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-xs text-gray-900">{{ $conversion->booking_id }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">${{ number_format($conversion->amount, 2) }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $conversion->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-12 text-center text-gray-500">No conversions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
