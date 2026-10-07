@extends('layouts.tenant')

@section('title', 'New Journal Entry')

@section('content')
    <x-page-header title="New Journal Entry" subtitle="Post a manual double-entry journal." />

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
         x-data="{
             lines: [
                 { account_id: '', description: '', debit: '', credit: '' },
                 { account_id: '', description: '', debit: '', credit: '' },
             ],
             addLine() { this.lines.push({ account_id: '', description: '', debit: '', credit: '' }); },
             removeLine(i) { if (this.lines.length > 2) this.lines.splice(i, 1); },
             get totalDebits() { return this.lines.reduce((s, l) => s + (parseFloat(l.debit) || 0), 0); },
             get totalCredits() { return this.lines.reduce((s, l) => s + (parseFloat(l.credit) || 0), 0); },
             get isBalanced() { return Math.abs(this.totalDebits - this.totalCredits) < 0.001 && this.totalDebits > 0; }
         }">
        <form method="POST" action="{{ route('tenant.accounting.journal.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Date *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Reference *</label>
                    <input type="text" name="reference" value="{{ old('reference', 'JE-'.date('YmdHis')) }}" required
                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('reference')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Description *</label>
                    <input type="text" name="description" value="{{ old('description') }}" required
                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Lines --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">Journal Lines</h3>
                    <button type="button" @click="addLine()"
                            class="rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200">+ Add Line</button>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Account</th>
                                <th class="px-4 py-3 text-left">Description</th>
                                <th class="px-4 py-3 text-right">Debit</th>
                                <th class="px-4 py-3 text-right">Credit</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(line, i) in lines" :key="i">
                                <tr class="border-t border-gray-100">
                                    <td class="px-4 py-2">
                                        <select :name="`lines[${i}][account_id]`" x-model="line.account_id" required
                                                class="block w-full rounded border-gray-300 text-xs focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select account...</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->code }} — {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" :name="`lines[${i}][description]`" x-model="line.description"
                                               class="block w-full rounded border-gray-300 text-xs focus:border-blue-500 focus:ring-blue-500" placeholder="Optional">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" :name="`lines[${i}][debit]`" x-model="line.debit" min="0" step="0.01"
                                               class="block w-full rounded border-gray-300 text-right text-xs focus:border-blue-500 focus:ring-blue-500" placeholder="0.00">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" :name="`lines[${i}][credit]`" x-model="line.credit" min="0" step="0.01"
                                               class="block w-full rounded border-gray-300 text-right text-xs focus:border-blue-500 focus:ring-blue-500" placeholder="0.00">
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" @click="removeLine(i)" x-show="lines.length > 2"
                                                class="text-red-500 hover:text-red-700">✕</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-gray-50 text-sm font-medium">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-gray-600">Totals</td>
                                <td class="px-4 py-3 text-right" x-text="'$' + totalDebits.toFixed(2)"></td>
                                <td class="px-4 py-3 text-right" x-text="'$' + totalCredits.toFixed(2)"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2">
                    <span x-show="isBalanced" class="text-sm font-medium text-green-600">Entry is balanced.</span>
                    <span x-show="!isBalanced && totalDebits > 0" class="text-sm font-medium text-red-600"
                          x-text="'Out of balance by $' + Math.abs(totalDebits - totalCredits).toFixed(2)"></span>
                </div>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="flex gap-3">
                <button type="submit" :disabled="!isBalanced"
                        :class="isBalanced ? 'bg-blue-600 hover:bg-blue-700' : 'cursor-not-allowed bg-gray-300'"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-white">Post Entry</button>
                <a href="{{ route('tenant.accounting.journal.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
