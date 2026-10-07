<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\ResourceStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ResourceCollection;
use App\Http\Resources\Api\V1\ResourceResource;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $query = Resource::query()->where('status', ResourceStatus::Active);

        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        $resources = $query->with('media')->paginate(15);

        return new ResourceCollection($resources);
    }

    public function show(Resource $resource): JsonResponse
    {
        $resource->load(['media', 'addOns', 'pricingRules']);

        return response()->json(new ResourceResource($resource));
    }

    public function availability(Resource $resource, Request $request): JsonResponse
    {
        $request->validate(['date' => ['required', 'date']]);

        $date = $request->date;

        $isBlackedOut = $resource->blackoutDates()
            ->where('date', $date)
            ->exists();

        $bookedCount = $resource->bookings()
            ->whereDate('check_in', '<=', $date)
            ->whereDate('check_out', '>=', $date)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $available = ! $isBlackedOut && ($resource->capacity === null || $bookedCount < $resource->capacity);

        return response()->json([
            'date' => $date,
            'available' => $available,
            'booked_count' => $bookedCount,
            'capacity' => $resource->capacity,
        ]);
    }

    public function slots(Resource $resource, Request $request): JsonResponse
    {
        $request->validate(['date' => ['required', 'date']]);

        $dayOfWeek = (int) now()->parse($request->date)->dayOfWeek;

        $slots = $resource->timeSlots()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        return response()->json(['slots' => $slots]);
    }
}
