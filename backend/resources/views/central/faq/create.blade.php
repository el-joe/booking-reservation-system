@extends('layouts.central')

@section('title', 'Add FAQ')

@section('content')
    <x-page-header title="Add FAQ" subtitle="Create a new frequently asked question." />
    <x-breadcrumb :items="[['label' => 'FAQs', 'url' => route('central.faq.index')], ['label' => 'Add FAQ']]" />

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('central.faq.store') }}" class="rounded-lg bg-white p-6 shadow space-y-6"
              x-data="{ newCategory: false, categoryValue: '' }">
            @csrf

            {{-- Question --}}
            <div>
                <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Question <span class="text-red-500">*</span></label>
                <textarea id="question" name="question" rows="3"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('question') border-red-500 @enderror"
                    required>{{ old('question') }}</textarea>
                @error('question') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Answer --}}
            <div>
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-1">Answer <span class="text-red-500">*</span></label>
                <textarea id="answer" name="answer" rows="6"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('answer') border-red-500 @enderror"
                    required>{{ old('answer') }}</textarea>
                @error('answer') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select
                    @change="newCategory = $event.target.value === '__new__'; if (!newCategory) categoryValue = $event.target.value"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
                    <option value="">Select a category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ Str::title($cat) }}</option>
                    @endforeach
                    <option value="__new__">+ New category…</option>
                </select>

                <div x-show="newCategory" class="mt-2">
                    <input
                        type="text"
                        name="category"
                        placeholder="Enter new category name"
                        value="{{ old('category') }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        :required="newCategory"
                    >
                </div>
                <input type="hidden" name="category" x-show="!newCategory" :value="categoryValue">
                @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                    class="w-32 rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <p class="mt-1 text-xs text-gray-400">Lower numbers appear first.</p>
            </div>

            {{-- Is Active --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on public FAQ page)</label>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Create FAQ
                </button>
                <a href="{{ route('central.faq.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
@endsection
