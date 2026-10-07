@extends('layouts.tenant')

@section('title', 'Add Customer')

@section('content')
    <x-page-header title="Add Customer" subtitle="Create a new customer profile.">
        <a href="{{ route('tenant.customers.index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <form method="POST" action="{{ route('tenant.customers.store') }}">
                @csrf
                @include('tenant.customers._form')

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('tenant.customers.index') }}"
                       class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                        Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
