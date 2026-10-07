<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;
        $uniqueRule = $customerId
            ? "unique:customers,email,{$customerId}"
            : 'unique:customers,email';

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', $uniqueRule],
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:1000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert comma-separated tags string to array if needed
        if ($this->has('tags') && is_string($this->input('tags'))) {
            $this->merge([
                'tags' => array_filter(array_map('trim', explode(',', $this->input('tags')))),
            ]);
        }
    }
}
