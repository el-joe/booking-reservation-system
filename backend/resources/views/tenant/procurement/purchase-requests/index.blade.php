@extends('layouts.tenant')

@section('title', 'Purchase Requests')

@section('content')
    <x-page-header title="Purchase Requests" subtitle="Review and approve purchase requests.">
        <button onclick="document.getElementById('new-request-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            New Request
        </button>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Tabs --}}
    <div x-data="{ tab: 'pending' }" class="space-y-4">
        <div class="flex border-b border-gray-200">
            <button @click="tab = 'pending'" :class="tab === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                Pending Approval <span class="ml-1 rounded-full bg-yellow-100 px-1.5 text-xs text-yellow-700">{{ $pending->count() }}</span>
            </button>
            <button @click="tab = 'approved'" :class="tab === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                Approved <span class="ml-1 rounded-full bg-green-100 px-1.5 text-xs text-green-700">{{ $approved->count() }}</span>
            </button>
            <button @click="tab = 'rejected'" :class="tab === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                Rejected <span class="ml-1 rounded-full bg-red-100 px-1.5 text-xs text-red-700">{{ $rejected->count() }}</span>
            </button>
        </div>

        @foreach (['pending' => $pending, 'approved' => $approved, 'rejected' => $rejected] as $tabKey => $requests)
            <div x-show="tab === '{{ $tabKey }}'">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Est. Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                @if ($tabKey === 'pending')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($requests as $request)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $request->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $request->requestedBy?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">${{ number_format((float) $request->total_estimated, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                                    @if ($tabKey === 'pending')
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <form method="POST" action="{{ route('tenant.procurement.purchase-requests.approve', $request) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('tenant.procurement.purchase-requests.reject', $request) }}" class="inline"
                                                      x-data @submit.prevent="
                                                          const r = prompt('Reason for rejection:');
                                                          if (r) { $el.querySelector('[name=reason]').value = r; $el.submit(); }
                                                      ">
                                                    @csrf
                                                    <input type="hidden" name="reason" value="">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Reject</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No {{ $tabKey }} requests.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    {{-- New Request Modal --}}
    <div id="new-request-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         x-data="{ items: [{ description: '', quantity: 1, estimated_price: 0 }] }">
        <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl mx-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">New Purchase Request</h2>
            <form action="{{ route('tenant.procurement.purchase-requests.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Items</label>
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="text" :name="`items[${index}][description]`" x-model="item.description" placeholder="Description" required
                                       class="flex-1 rounded-md border-gray-300 shadow-sm sm:text-sm">
                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" placeholder="Qty" required
                                       class="w-20 rounded-md border-gray-300 shadow-sm sm:text-sm">
                                <input type="number" :name="`items[${index}][estimated_price]`" x-model="item.estimated_price" min="0" step="0.01" placeholder="Price" required
                                       class="w-28 rounded-md border-gray-300 shadow-sm sm:text-sm">
                                <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="text-red-500 hover:text-red-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="items.push({ description: '', quantity: 1, estimated_price: 0 })" class="text-sm text-blue-600 hover:text-blue-800">+ Add Item</button>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"></textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('new-request-modal').classList.add('hidden')"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
@endsection
