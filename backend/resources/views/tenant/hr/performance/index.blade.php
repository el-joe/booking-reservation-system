@extends('layouts.tenant')

@section('title', 'Performance Reviews')

@section('content')
    <x-page-header title="Performance Reviews" subtitle="Manage staff performance evaluations">
        <x-slot name="actions">
            <a href="{{ route('tenant.hr.performance.create') }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                New Review
            </a>
        </x-slot>
    </x-page-header>

    <form method="GET" action="{{ route('tenant.hr.performance.index') }}" class="mb-4 flex flex-wrap gap-3">
        <select name="staff_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Staff</option>
            @foreach ($staffList as $s)
                <option value="{{ $s->id }}" @selected(request('staff_id') == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
        <input type="text" name="period" value="{{ request('period') }}" placeholder="Period (e.g. Q3 2026)"
               class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Statuses</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
            <option value="acknowledged" @selected(request('status') === 'acknowledged')>Acknowledged</option>
        </select>
        <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filter</button>
        <a href="{{ route('tenant.hr.performance.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Clear</a>
    </form>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reviewer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($reviews as $review)
                    @php $sc = match($review->status) { 'draft'=>'yellow', 'submitted'=>'blue', 'acknowledged'=>'green', default=>'gray' }; @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $review->staff?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $review->reviewer?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $review->period }}</td>
                        <td class="px-4 py-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-800">{{ ucfirst($review->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('tenant.hr.performance.show', $review) }}" class="text-blue-600 hover:underline text-xs">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
