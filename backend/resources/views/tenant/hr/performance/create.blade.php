@extends('layouts.tenant')

@section('title', 'New Performance Review')

@section('content')
    <x-page-header title="New Performance Review" subtitle="Evaluate a staff member's performance" />

    <div class="max-w-2xl" x-data="{ rating: 0 }">
        <form method="POST" action="{{ route('tenant.hr.performance.store') }}" class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Staff Member</label>
                    <select name="staff_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select staff...</option>
                        @foreach ($staffList as $s)
                            <option value="{{ $s->id }}" @selected(old('staff_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reviewer</label>
                    <select name="reviewer_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select reviewer...</option>
                        @foreach ($staffList as $s)
                            <option value="{{ $s->id }}" @selected(old('reviewer_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                    <input type="text" name="period" value="{{ old('period') }}" placeholder="e.g. Q3 2026" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <div class="flex gap-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}"
                                :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                class="text-3xl focus:outline-none transition-colors">★</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" :value="rating" x-bind:value="rating">
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Strengths</label>
                <textarea name="strengths" rows="3"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('strengths') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Areas for Improvement</label>
                <textarea name="improvements" rows="3"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('improvements') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Goals</label>
                <textarea name="goals" rows="3"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('goals') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-500">Save Review</button>
                <a href="{{ route('tenant.hr.performance.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
