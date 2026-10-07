<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        if ($booking->customer_id !== $request->user()->id) {
            abort(403, 'This booking does not belong to you.');
        }

        if ($booking->status !== BookingStatus::Completed) {
            return response()->json(['message' => 'You can only review completed bookings.'], 422);
        }

        $existingReview = Review::where('booking_id', $booking->id)->first();
        if ($existingReview) {
            return response()->json(['message' => 'You have already reviewed this booking.'], 422);
        }

        $review = Review::create([
            'booking_id' => $booking->id,
            'customer_id' => $request->user()->id,
            'resource_id' => $booking->resource_id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
            'status' => 'pending',
        ]);

        $review->load('customer');

        return response()->json(new ReviewResource($review), 201);
    }

    public function index(Resource $resource): JsonResponse
    {
        $reviews = Review::with('customer')
            ->where('resource_id', $resource->id)
            ->where('status', 'published')
            ->latest()
            ->paginate(15);

        return response()->json(ReviewResource::collection($reviews)->response()->getData(true));
    }
}
