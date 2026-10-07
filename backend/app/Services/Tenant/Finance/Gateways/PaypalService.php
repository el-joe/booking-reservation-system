<?php

declare(strict_types=1);

namespace App\Services\Tenant\Finance\Gateways;

class PaypalService
{
    public function createOrder(float $amount, string $currency, array $metadata): array
    {
        // Stub — implement with PayPal SDK
        return [
            'id' => null,
            'status' => 'CREATED',
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    public function captureOrder(string $orderId): array
    {
        // Stub — implement with PayPal SDK
        return [
            'id' => $orderId,
            'status' => 'COMPLETED',
        ];
    }

    public function processRefund(string $captureId, float $amount): array
    {
        // Stub — implement with PayPal SDK
        return [
            'id' => null,
            'capture_id' => $captureId,
            'amount' => $amount,
            'status' => 'COMPLETED',
        ];
    }
}
