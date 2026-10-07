@extends('layouts.tenant')

@section('title', 'Cash Flow Statement')

@section('content')
    <x-page-header title="Cash Flow Statement" subtitle="Track inflows and outflows of cash." />

    <div class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-16 shadow-sm">
        <div class="mb-4 rounded-full bg-gray-100 p-6">
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-gray-700">Cash Flow — Coming Soon</h3>
        <p class="text-center text-sm text-gray-500">
            The cash flow statement will show operating, investing, and financing activities.<br>
            This feature is planned for an upcoming release.
        </p>
    </div>
@endsection
