@extends('layouts.tenant')

@section('title', $lead->name)

@section('content')
    <x-page-header :title="$lead->name" subtitle="Lead Details">
        <a href="{{ route('tenant.leads.edit', $lead) }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
            Edit
        </a>
        <a href="{{ route('tenant.leads.index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
            ← Back
        </a>
    </x-page-header>

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Name</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $lead->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Email</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $lead->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Phone</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $lead->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Source</dt>
                    <dd class="mt-0.5 capitalize text-gray-900">{{ str_replace('_', ' ', $lead->source) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="mt-0.5 capitalize text-gray-900">{{ $lead->status }}</dd>
                </div>
                @if ($lead->notes)
                    <div>
                        <dt class="font-medium text-gray-500">Notes</dt>
                        <dd class="mt-0.5 text-gray-900">{{ $lead->notes }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="font-medium text-gray-500">Created</dt>
                    <dd class="mt-0.5 text-gray-900">{{ $lead->created_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>

            @if ($lead->status !== 'converted')
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <form method="POST" action="{{ route('tenant.leads.convert', $lead) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700">
                            Convert to Customer
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
