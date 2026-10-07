<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\PerformanceReview;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function index(): View
    {
        $reviews = PerformanceReview::with(['staff', 'reviewer'])
            ->when(request('staff_id'), fn ($q) => $q->where('staff_id', request('staff_id')))
            ->when(request('period'), fn ($q) => $q->where('period', request('period')))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->latest()
            ->paginate(15);

        $staffList = Staff::where('status', 'active')->orderBy('name')->get();

        return view('tenant.hr.performance.index', compact('reviews', 'staffList'));
    }

    public function create(): View
    {
        $staffList = Staff::where('status', 'active')->orderBy('name')->get();

        return view('tenant.hr.performance.create', compact('staffList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'reviewer_id' => 'required|exists:staff,id',
            'period' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'goals' => 'nullable|string',
            'status' => 'in:draft,submitted',
        ]);

        $review = PerformanceReview::create($data);

        return redirect()->route('tenant.hr.performance.show', $review)
            ->with('success', 'Performance review created successfully.');
    }

    public function show(PerformanceReview $review): View
    {
        $review->load(['staff', 'reviewer']);

        return view('tenant.hr.performance.show', compact('review'));
    }

    public function update(Request $request, PerformanceReview $performance): RedirectResponse
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'reviewer_id' => 'required|exists:staff,id',
            'period' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'goals' => 'nullable|string',
            'status' => 'in:draft,submitted',
        ]);

        $performance->update($data);

        return redirect()->route('tenant.hr.performance.show', $performance)
            ->with('success', 'Performance review updated successfully.');
    }

    public function destroy(PerformanceReview $performance): RedirectResponse
    {
        $performance->delete();

        return redirect()->route('tenant.hr.performance.index')
            ->with('success', 'Performance review deleted.');
    }

    public function acknowledge(PerformanceReview $review): RedirectResponse
    {
        $review->update(['status' => 'acknowledged']);

        return redirect()->route('tenant.hr.performance.show', $review)
            ->with('success', 'Review acknowledged.');
    }
}
