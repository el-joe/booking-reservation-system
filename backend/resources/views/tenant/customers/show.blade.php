@extends('layouts.tenant')

@section('title', $customer->name)

@section('content')
    <x-page-header :title="$customer->name" subtitle="Customer Profile">
        <a href="{{ route('tenant.customers.edit', $customer) }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
            Edit
        </a>
        <a href="{{ route('tenant.customers.index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left: Customer Info --}}
        <div class="space-y-4">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Customer Info</h3>

                @if ($customer->is_blacklisted)
                    <div class="mb-4 rounded-lg bg-red-50 p-3 ring-1 ring-red-200">
                        <p class="text-sm font-semibold text-red-700">⚠ Blacklisted</p>
                        @if ($customer->blacklist_reason)
                            <p class="mt-1 text-xs text-red-600">{{ $customer->blacklist_reason }}</p>
                        @endif
                    </div>
                @endif

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Name</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $customer->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $customer->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Phone</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $customer->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $customer->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Gender</dt>
                        <dd class="mt-0.5 capitalize text-gray-900">{{ $customer->gender ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Address</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $customer->address ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Loyalty Points</dt>
                        <dd class="mt-0.5 font-semibold text-blue-700">{{ number_format($customer->loyalty_points) }} pts</dd>
                    </div>
                    @if ($customer->tags)
                        <div>
                            <dt class="font-medium text-gray-500">Tags</dt>
                            <dd class="mt-1 flex flex-wrap gap-1">
                                @foreach ($customer->tags as $tag)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">{{ $tag }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>

                {{-- Actions --}}
                <div class="mt-6 flex flex-col gap-2">
                    @if ($customer->is_blacklisted)
                        <form method="POST" action="{{ route('tenant.customers.remove-blacklist', $customer) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                Remove from Blacklist
                            </button>
                        </form>
                    @else
                        <button type="button"
                                x-data
                                @click="$dispatch('open-modal', 'blacklist-modal')"
                                class="w-full rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100">
                            Blacklist Customer
                        </button>
                    @endif

                    <button type="button"
                            x-data
                            @click="$dispatch('open-modal', 'add-note-modal')"
                            class="w-full rounded-lg bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-200 hover:bg-gray-100">
                        Add Note
                    </button>
                </div>
            </div>
        </div>

        {{-- Middle: Booking History --}}
        <div class="space-y-4">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Booking History</h3>
                    <a href="{{ route('tenant.bookings.index') }}?customer_id={{ $customer->id }}"
                       class="text-xs font-medium text-blue-600 hover:text-blue-800">View All →</a>
                </div>

                @if ($customer->bookings->isEmpty())
                    <p class="py-4 text-center text-sm text-gray-400">No bookings yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="pb-2 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Date</th>
                                    <th class="pb-2 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Resource</th>
                                    <th class="pb-2 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Status</th>
                                    <th class="pb-2 text-right text-xs font-medium uppercase tracking-wide text-gray-400">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($customer->bookings as $booking)
                                    <tr>
                                        <td class="py-2 text-gray-600">{{ $booking->created_at->format('d M Y') }}</td>
                                        <td class="py-2 text-gray-900">{{ $booking->resource?->name ?? '—' }}</td>
                                        <td class="py-2">
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                                {{ $booking->status }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-right font-medium text-gray-900">${{ number_format($booking->paid_amount ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Notes & Loyalty Transactions --}}
        <div class="space-y-4">
            {{-- Notes --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Notes</h3>

                @if ($customer->customerNotes->isEmpty())
                    <p class="py-4 text-center text-sm text-gray-400">No notes yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($customer->customerNotes as $note)
                            <div class="rounded-lg p-3 {{ $note->is_pinned ? 'bg-yellow-50 ring-1 ring-yellow-200' : 'bg-gray-50' }}">
                                @if ($note->is_pinned)
                                    <span class="mb-1 inline-block text-xs font-semibold text-yellow-600">📌 Pinned</span>
                                @endif
                                <p class="text-sm text-gray-700">{{ $note->note }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Loyalty Transactions --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Loyalty History</h3>

                @if ($customer->loyaltyTransactions->isEmpty())
                    <p class="py-4 text-center text-sm text-gray-400">No transactions yet.</p>
                @else
                    <div class="space-y-2">
                        @foreach ($customer->loyaltyTransactions as $tx)
                            <div class="flex items-center justify-between py-1.5">
                                <div>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $tx->type === 'earn' ? 'bg-green-100 text-green-700' : ($tx->type === 'redeem' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ ucfirst($tx->type) }}
                                    </span>
                                    @if ($tx->note)
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $tx->note }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold {{ $tx->points >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $tx->points >= 0 ? '+' : '' }}{{ $tx->points }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $tx->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Blacklist Modal --}}
    <x-modal name="blacklist-modal" title="Blacklist Customer">
        <form method="POST" action="{{ route('tenant.customers.blacklist', $customer) }}">
            @csrf
            <div class="space-y-4">
                <x-form-textarea name="reason" label="Reason for Blacklisting" required />
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'blacklist-modal')"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Blacklist
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    {{-- Add Note Modal --}}
    <x-modal name="add-note-modal" title="Add Note">
        <form method="POST" action="{{ route('tenant.customers.notes.store', $customer) }}">
            @csrf
            <div class="space-y-4">
                <x-form-textarea name="note" label="Note" required />
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 text-blue-600">
                    <span class="text-gray-700">Pin this note</span>
                </label>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'add-note-modal')"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Save Note
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
@endsection
