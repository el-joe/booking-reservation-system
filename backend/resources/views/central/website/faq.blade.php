@extends('layouts.central-website')

@section('title', 'FAQ')

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-indigo-700 to-indigo-500 py-20 text-white">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">Frequently Asked Questions</h1>
        <p class="mt-4 text-lg text-indigo-100">Find answers to common questions about our booking platform, billing, and technical setup.</p>
    </div>
</section>

{{-- FAQ Body --}}
<section class="py-16">
    <div
        class="mx-auto max-w-4xl px-4"
        x-data="{
            search: '',
            active: null,
            activeTab: '{{ $grouped->keys()->first() }}',
            matchesSearch(question, answer) {
                if (this.search.trim() === '') return true;
                const q = this.search.toLowerCase();
                return question.toLowerCase().includes(q) || answer.toLowerCase().includes(q);
            }
        }"
    >
        {{-- Search --}}
        <div class="mb-8">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803 7.5 7.5 0 0 0 15.803 15.803Z" />
                </svg>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search questions…"
                    class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-10 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
            </div>
        </div>

        {{-- Category Tabs (hidden during search) --}}
        <div x-show="search.trim() === ''" class="mb-8 flex flex-wrap gap-2">
            @foreach($grouped->keys() as $category)
            <button
                @click="activeTab = '{{ $category }}'"
                :class="activeTab === '{{ $category }}' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition-colors"
            >
                {{ Str::title($category) }}
            </button>
            @endforeach
        </div>

        {{-- FAQ Groups --}}
        @foreach($grouped as $category => $items)
        <div
            x-show="search.trim() !== '' || activeTab === '{{ $category }}'"
            class="mb-10"
        >
            <h2
                x-show="search.trim() !== ''"
                class="mb-4 text-lg font-semibold text-gray-800"
            >
                {{ Str::title($category) }}
            </h2>

            <div class="divide-y divide-gray-100 rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                @foreach($items as $faq)
                <div
                    x-show="matchesSearch('{{ addslashes($faq->question) }}', '{{ addslashes(strip_tags($faq->answer)) }}')"
                    x-data="{ open: false }"
                    class="px-6"
                >
                    <button
                        @click="open = !open"
                        class="flex w-full items-center justify-between py-5 text-left"
                    >
                        <span class="text-sm font-medium text-gray-900 pr-4">{{ $faq->question }}</span>
                        <svg
                            :class="open ? 'rotate-180' : ''"
                            class="h-5 w-5 shrink-0 text-gray-400 transition-transform duration-200"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pb-5">
                        <div class="prose prose-sm max-w-none text-gray-600">{!! nl2br(e($faq->answer)) !!}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Empty state --}}
        <div
            x-show="search.trim() !== '' && {{ $grouped->map(fn($items) => 'true')->values()->implode(' && ') ?: 'true' }} === true"
            x-cloak
            class="py-16 text-center text-gray-400"
        >
            <svg class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803 7.5 7.5 0 0 0 15.803 15.803Z" />
            </svg>
            <p class="text-sm">No questions match "<span x-text="search" class="font-medium text-gray-600"></span>".</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-gray-50 py-16">
    <div class="mx-auto max-w-xl px-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Still have questions?</h2>
        <p class="mt-3 text-gray-500">Can't find what you're looking for? Our team is happy to help.</p>
        <a href="/contact"
           class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition-colors">
            Contact Us
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
            </svg>
        </a>
    </div>
</section>
@endsection
