<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Booking;

use App\Enums\BookingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $bookingTypeValues = array_column(BookingType::cases(), 'value');

        return [
            'resource_id' => ['required', 'exists:resources,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'booking_type' => ['required', Rule::in($bookingTypeValues)],
            'check_in' => ['required', 'date', 'after:now'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests_count' => ['required', 'integer', 'min:1'],
            'source' => ['required', Rule::in(['website', 'walk_in', 'phone', 'ota', 'api'])],
            'notes' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.description' => ['required_with:items', 'string'],
            'items.*.unit_price' => ['required_with:items', 'numeric', 'min:0'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
        ];
    }
}
