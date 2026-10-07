<?php

declare(strict_types=1);

namespace App\Http\Requests\Central;

use App\Enums\BookingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $bookingTypeValues = array_column(BookingType::cases(), 'value');
        $tenantId = $this->route('tenant')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('tenants', 'name')->ignore($tenantId, 'id')],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'business_type' => ['required', Rule::in($bookingTypeValues)],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'logo' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
