<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredResources = Resource::query()
            ->where('status', 'active')
            ->withCount(['reviews as published_reviews_count' => fn ($q) => $q->where('status', 'published')])
            ->withAvg(['reviews as rating_average' => fn ($q) => $q->where('status', 'published')], 'rating')
            ->orderByDesc('rating_average')
            ->limit(6)
            ->get();

        $categories = Resource::query()
            ->where('status', 'active')
            ->select('booking_type')
            ->distinct()
            ->pluck('booking_type');

        $recentReviews = Review::query()
            ->published()
            ->with(['customer', 'resource'])
            ->latest()
            ->limit(3)
            ->get();

        return view('tenant.website.home', compact('featuredResources', 'categories', 'recentReviews'));
    }
}
