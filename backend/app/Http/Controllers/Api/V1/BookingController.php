<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Booking;
use App\Models\Resource;
use App\Services\Tenant\Booking\BookingCreationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private readonly BookingCreationService $bookingCreationService) {}

    public function index(Request $request): BookingCollection
    {
        $query = Booking::query()
            ->where('customer_id', $request->user()->id)
            ->with(['resource']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return new BookingCollection($query->latest()->paginate(15));
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $resource = Resource::findOrFail($request->resource_id);

        $booking = $this->bookingCreationService->create([
            'resource_id' => $resource->id,
            'customer_id' => $request->user()->id,
            'booking_type' => $resource->booking_type->value,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests_count' => $request->guests_count,
            'source' => 'api',
            'notes' => $request->notes,
            'items' => $request->items ?? [],
        ]);

        $booking->load(['resource', 'customer']);

        return response()->json(new BookingResource($booking), 201);
    }

    public function show(Booking $booking): JsonResponse
    {
        if ($booking->customer_id !== request()->user()->id) {
            abort(403, 'This booking does not belong to you.');
        }

        $booking->load(['resource', 'customer']);

        return response()->json(new BookingResource($booking));
    }

    public function cancel(Booking $booking, Request $request): JsonResponse
    {
        if ($booking->customer_id !== $request->user()->id) {
            abort(403, 'This booking does not belong to you.');
        }

        if (! in_array($booking->status, [BookingStatus::Pending, BookingStatus::Confirmed], true)) {
            return response()->json(['message' => 'This booking cannot be cancelled.'], 422);
        }

        $request->validate(['reason' => ['nullable', 'string', 'max:1000']]);

        $booking->update([
            'status' => BookingStatus::Cancelled,
            'cancellation_reason' => $request->reason,
            'cancelled_at' => now(),
        ]);

        return response()->json(new BookingResource($booking->load(['resource', 'customer'])));
    }
}
