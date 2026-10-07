<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function show(Resource $resource): View
    {
        $resource->load(['media', 'addOns', 'timeSlots', 'pricingRules']);

        $reviews = Review::query()
            ->published()
            ->where('resource_id', $resource->id)
            ->with('customer')
            ->latest()
            ->get();

        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percent' => $reviews->count() > 0 ? round($count / $reviews->count() * 100) : 0,
            ];
        }

        $similarResources = Resource::query()
            ->where('status', 'active')
            ->where('booking_type', $resource->booking_type)
            ->where('id', '!=', $resource->id)
            ->with('media')
            ->withAvg(['reviews as rating_average' => fn ($q) => $q->where('status', 'published')], 'rating')
            ->limit(3)
            ->get();

        return view('tenant.website.listing', compact('resource', 'reviews', 'ratingDistribution', 'similarResources'));
    }

    public function getPriceBreakdown(Resource $resource, Request $request): JsonResponse
    {
        $request->validate([
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1'],
        ]);

        $checkIn = new \DateTimeImmutable($request->input('check_in'));
        $checkOut = new \DateTimeImmutable($request->input('check_out'));
        $days = (int) $checkIn->diff($checkOut)->days;

        $baseTotal = (float) $resource->base_price * max($days, 1);

        return response()->json([
            'base_price' => $resource->base_price,
            'price_unit' => $resource->price_unit,
            'nights' => $days,
            'base_total' => $baseTotal,
            'taxes' => round($baseTotal * 0.1, 2),
            'total' => round($baseTotal * 1.1, 2),
        ]);
    }
}
