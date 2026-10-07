<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'booking_type' => $this->booking_type,
            'check_in' => $this->check_in?->toISOString(),
            'check_out' => $this->check_out?->toISOString(),
            'guests_count' => $this->guests_count,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'notes' => $this->notes,
            'cancellation_reason' => $this->cancellation_reason,
            'resource' => new ResourceResource($this->whenLoaded('resource')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
