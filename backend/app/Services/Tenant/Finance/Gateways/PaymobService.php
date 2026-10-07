<?php

declare(strict_types=1);

namespace App\Services\Tenant\Finance\Gateways;

class PaymobService
{
    public function createPaymentIntent(float $amount, string $currency, array $metadata): array
    {
        // Stub — implement with Paymob API
        return [
            'id' => null,
            'payment_key' => null,
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    public function confirmPayment(string $orderId): array
    {
        // Stub — implement with Paymob API
        return [
            'id' => $orderId,
            'status' => 'success',
        ];
    }

    public function refund(string $transactionId, float $amount): array
    {
        // Stub — implement with Paymob API
        return [
            'id' => null,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'status' => 'success',
        ];
    }
}
