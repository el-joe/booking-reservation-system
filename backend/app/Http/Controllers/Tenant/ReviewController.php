<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\ReviewDataTable;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(ReviewDataTable $dataTable): mixed
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        $stats = $this->getStats();

        return $dataTable->render('tenant.reviews.index', compact('stats'));
    }

    public function show(Review $review): View
    {
        $review->load(['customer', 'resource', 'booking']);

        return view('tenant.reviews.show', compact('review'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'published']);

        return redirect()->route('tenant.reviews.show', $review)
            ->with('success', 'Review approved and published.');
    }

    public function reject(Review $review, Request $request): RedirectResponse
    {
        $review->update(['status' => 'rejected']);

        return redirect()->route('tenant.reviews.show', $review)
            ->with('success', 'Review rejected.');
    }

    public function reply(Review $review, Request $request): RedirectResponse
    {
        $request->validate(['reply' => 'required|string|max:2000']);

        $review->update([
            'reply' => $request->reply,
            'replied_at' => now(),
            'replied_by_id' => auth()->id(),
        ]);

        return redirect()->route('tenant.reviews.show', $review)
            ->with('success', 'Reply posted successfully.');
    }

    public function flag(Review $review): RedirectResponse
    {
        $review->update(['status' => 'rejected']);

        return redirect()->route('tenant.reviews.index')
            ->with('success', 'Review flagged and hidden.');
    }

    public function stats(): JsonResponse
    {
        return response()->json($this->getStats());
    }

    private function getStats(): array
    {
        $reviews = Review::all();
        $total = $reviews->count();
        $avgRating = $total > 0 ? round($reviews->avg('rating'), 1) : 0;
        $byStar = [];
        for ($i = 1; $i <= 5; $i++) {
            $byStar[$i] = $reviews->where('rating', $i)->count();
        }

        return [
            'avg_rating' => $avgRating,
            'total' => $total,
            'pending' => $reviews->where('status', 'pending')->count(),
            'published' => $reviews->where('status', 'published')->count(),
            'by_star' => $byStar,
        ];
    }
}
