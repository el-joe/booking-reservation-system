<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Resource;

use App\Enums\BookingType;
use App\Enums\ResourceStatus;
use App\Enums\ResourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $bookingTypeValues = array_column(BookingType::cases(), 'value');
        $resourceTypeValues = array_column(ResourceType::cases(), 'value');
        $resourceStatusValues = array_column(ResourceStatus::cases(), 'value');

        return [
            'name' => ['required', 'string', 'max:255'],
            'booking_type' => ['required', Rule::in($bookingTypeValues)],
            'resource_type' => ['required', Rule::in($resourceTypeValues)],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'price_unit' => ['nullable', Rule::in(['per_night', 'per_hour', 'per_person', 'per_unit'])],
            'status' => ['nullable', Rule::in($resourceStatusValues)],
            'description' => ['nullable', 'string'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
