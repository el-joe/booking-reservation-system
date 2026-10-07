<?php

declare(strict_types=1);

namespace App\Services\Tenant\Finance\Gateways;

class StripeService
{
    public function createPaymentIntent(float $amount, string $currency, array $metadata): array
    {
        // Stub — implement with Stripe SDK
        return [
            'id' => null,
            'client_secret' => null,
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    public function confirmPayment(string $intentId): array
    {
        // Stub — implement with Stripe SDK
        return [
            'id' => $intentId,
            'status' => 'succeeded',
        ];
    }

    public function refund(string $chargeId, float $amount): array
    {
        // Stub — implement with Stripe SDK
        return [
            'id' => null,
            'charge' => $chargeId,
            'amount' => $amount,
            'status' => 'succeeded',
        ];
    }
}
