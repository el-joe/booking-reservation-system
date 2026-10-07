@extends('layouts.tenant')

@section('title', 'Create Purchase Order')

@section('content')
    <x-page-header title="Create Purchase Order" subtitle="Issue a new purchase order.">
        <a href="{{ route('tenant.procurement.purchase-orders.index') }}"
           class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
            &larr; Back
        </a>
    </x-page-header>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200"
         x-data="{
             items: [{ description: '', quantity: 1, unit_price: 0, total: 0 }],
             tax: 0,
             get subtotal() { return this.items.reduce((s, i) => s + (parseFloat(i.quantity) * parseFloat(i.unit_price) || 0), 0); },
             get grandTotal() { return this.subtotal + (parseFloat(this.tax) || 0); },
             updateTotal(i) { this.items[i].total = (parseFloat(this.items[i].quantity) * parseFloat(this.items[i].unit_price) || 0).toFixed(2); },
             addItem() { this.items.push({ description: '', quantity: 1, unit_price: 0, total: 0 }); },
             removeItem(i) { this.items.splice(i, 1); }
         }">
        <form action="{{ route('tenant.procurement.purchase-orders.store') }}" method="POST" @submit.prevent="
            document.getElementById('subtotal-input').value = subtotal.toFixed(2);
            document.getElementById('total-input').value = grandTotal.toFixed(2);
            $el.submit();
        ">
            @csrf
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Request (optional)</label>
                    <select name="purchase_request_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">None</option>
                        @foreach ($purchaseRequests as $pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_request_id') == $pr->id ? 'selected' : '' }}>#{{ $pr->id }} - {{ $pr->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                    <input type="date" name="delivery_date" value="{{ old('delivery_date') }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Line Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase border-b">
                                <th class="pb-2 pr-4">Description</th>
                                <th class="pb-2 pr-4 w-24">Qty</th>
                                <th class="pb-2 pr-4 w-28">Unit Price</th>
                                <th class="pb-2 pr-4 w-28">Total</th>
                                <th class="pb-2 w-8"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b">
                                    <td class="py-2 pr-4">
                                        <input type="text" :name="`items[${index}][description]`" x-model="item.description" required
                                               class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </td>
                                    <td class="py-2 pr-4">
                                        <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" required @input="updateTotal(index)"
                                               class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </td>
                                    <td class="py-2 pr-4">
                                        <input type="number" :name="`items[${index}][unit_price]`" x-model="item.unit_price" min="0" step="0.01" required @input="updateTotal(index)"
                                               class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </td>
                                    <td class="py-2 pr-4 text-gray-600" x-text="'$' + item.total"></td>
                                    <td class="py-2">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="addItem()" class="mt-2 text-sm text-blue-600 hover:text-blue-800">+ Add Line Item</button>
            </div>

            <div class="flex justify-end mb-6">
                <div class="w-64 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span x-text="'$' + subtotal.toFixed(2)"></span></div>
                    <div class="flex justify-between items-center">
                        <label class="text-gray-600">Tax</label>
                        <input type="number" name="tax" x-model="tax" min="0" step="0.01" class="w-28 rounded-md border-gray-300 shadow-sm sm:text-sm text-right">
                    </div>
                    <div class="flex justify-between font-semibold border-t pt-2"><span>Total</span><span x-text="'$' + grandTotal.toFixed(2)"></span></div>
                </div>
            </div>

            <input type="hidden" id="subtotal-input" name="subtotal" value="0">
            <input type="hidden" id="total-input" name="total" value="0">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('tenant.procurement.purchase-orders.index') }}"
                   class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create Purchase Order</button>
            </div>
        </form>
    </div>
@endsection
