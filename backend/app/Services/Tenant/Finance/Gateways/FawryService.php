<?php

declare(strict_types=1);

namespace App\Services\Tenant\Finance\Gateways;

class FawryService
{
    public function initiatePayment(float $amount, array $customerData): array
    {
        // Stub — implement with Fawry API
        return [
            'merchant_ref_num' => null,
            'fawry_ref_num' => null,
            'amount' => $amount,
            'status' => 'CREATED',
        ];
    }

    public function verifyPayment(string $merchantRefNum): array
    {
        // Stub — implement with Fawry API
        return [
            'merchant_ref_num' => $merchantRefNum,
            'fawry_ref_num' => null,
            'status' => 'PAID',
        ];
    }
}
