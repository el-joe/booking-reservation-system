<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staffId = $this->route('staff')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('staff', 'email')->ignore($staffId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'employment_type' => ['nullable', Rule::in(['full_time', 'part_time', 'contract'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'bio' => ['nullable', 'string', 'max:2000'],
            'emergency_contact' => ['nullable', 'array'],
            'emergency_contact.name' => ['nullable', 'string', 'max:255'],
            'emergency_contact.phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact.relationship' => ['nullable', 'string', 'max:100'],
        ];
    }
}
