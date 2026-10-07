<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'booking_type' => $this->booking_type,
            'resource_type' => $this->resource_type,
            'capacity' => $this->capacity,
            'base_price' => $this->base_price,
            'price_unit' => $this->price_unit,
            'status' => $this->status,
            'cover_image_url' => $this->cover_image,
            'description' => $this->description,
            'rating_avg' => $this->rating_average,
        ];
    }
}
