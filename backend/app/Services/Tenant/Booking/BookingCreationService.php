<?php

declare(strict_types=1);

namespace App\Services\Tenant\Booking;

use App\Enums\BookingStatus;
use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Support\Facades\DB;

class BookingCreationService
{
    /**
     * Create a new booking with items and initial status history.
     *
     * @param  array{
     *     resource_id: int,
     *     customer_id: int,
     *     booking_type: string,
     *     check_in: string,
     *     check_out: string,
     *     guests_count: int,
     *     source: string,
     *     notes: ?string,
     *     items: ?array<int, array{description: string, unit_price: float, quantity: int}>,
     * } $data
     */
    public function create(array $data): Booking
    {
        return DB::transaction(function () use ($data): Booking {
            // Stub: AvailabilityService will be built in AGENT-07
            // $this->availability->check($data['resource_id'], $data['check_in'], $data['check_out']);

            $itemsTotal = $this->calculateItemsTotal($data['items'] ?? []);
            $basePrice = 0; // Base price will be calculated by PricingService in a future phase

            $booking = Booking::create([
                'reference_number' => Booking::generateReferenceNumber(),
                'resource_id' => $data['resource_id'],
                'customer_id' => $data['customer_id'],
                'booking_type' => $data['booking_type'],
                'status' => BookingStatus::Pending,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guests_count' => $data['guests_count'],
                'total_amount' => $basePrice + $itemsTotal,
                'paid_amount' => 0,
                'source' => $data['source'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] ?? [] as $item) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'item_type' => $item['item_type'] ?? 'custom',
                    'item_id' => $item['item_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['unit_price'] * $item['quantity'],
                ]);
            }

            $booking->statusHistories()->create([
                'status' => BookingStatus::Pending->value,
                'previous_status' => null,
                'note' => 'Booking created.',
                'changed_by_id' => auth()->id(),
            ]);

            BookingCreated::dispatch($booking);

            return $booking->load(['customer', 'resource', 'items', 'statusHistories']);
        });
    }

    /**
     * @param  array<int, array{unit_price: float, quantity: int}>  $items
     */
    private function calculateItemsTotal(array $items): float
    {
        return array_reduce($items, static fn (float $carry, array $item): float => $carry + ($item['unit_price'] * $item['quantity']), 0.0);
    }
}
