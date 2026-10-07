<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
        return [
            'check_in' => ['sometimes', 'date', 'after:now'],
            'check_out' => ['sometimes', 'date', 'after:check_in'],
            'guests_count' => ['sometimes', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
