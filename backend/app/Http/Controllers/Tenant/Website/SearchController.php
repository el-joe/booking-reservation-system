<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Resource::query()
            ->where('status', 'active')
            ->with(['media'])
            ->withAvg(['reviews as rating_average' => fn ($q) => $q->where('status', 'published')], 'rating')
            ->withCount(['reviews as published_reviews_count' => fn ($q) => $q->where('status', 'published')]);

        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->input('booking_type'));
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->input('max_price'));
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->input('capacity'));
        }

        if ($request->filled('min_rating')) {
            $query->having('rating_average', '>=', $request->input('min_rating'));
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request->input('q').'%')
                    ->orWhere('description', 'like', '%'.$request->input('q').'%');
            });
        }

        $resources = $query->paginate(12)->withQueryString();

        $bookingTypes = Resource::query()
            ->where('status', 'active')
            ->select('booking_type')
            ->distinct()
            ->pluck('booking_type');

        return view('tenant.website.search', compact('resources', 'bookingTypes'));
    }
}
