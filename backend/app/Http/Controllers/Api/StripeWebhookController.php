<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request): JsonResponse
    {
        $secret = config('services.stripe.webhook_secret');

        if ($secret) {
            $signature = $request->header('Stripe-Signature');
            if (! $signature) {
                return response()->json(['error' => 'Missing signature'], 400);
            }

            try {
                $payload = $request->getContent();
                $parts = explode(',', $signature);
                $timestamp = null;
                $sigHash = null;

                foreach ($parts as $part) {
                    if (str_starts_with($part, 't=')) {
                        $timestamp = substr($part, 2);
                    }
                    if (str_starts_with($part, 'v1=')) {
                        $sigHash = substr($part, 3);
                    }
                }

                $signedPayload = $timestamp.'.'.$payload;
                $expectedSig = hash_hmac('sha256', $signedPayload, $secret);

                if (! hash_equals($expectedSig, $sigHash ?? '')) {
                    return response()->json(['error' => 'Invalid signature'], 400);
                }
            } catch (\Throwable $e) {
                Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);

                return response()->json(['error' => 'Signature verification failed'], 400);
            }
        }

        $payload = $request->json()->all();
        $event = $payload['type'] ?? null;
        $data = $payload['data']['object'] ?? [];

        Log::info('Stripe webhook received', ['event' => $event]);

        match ($event) {
            'payment_intent.succeeded' => $this->handlePaymentSucceeded($data),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($data),
            'charge.refunded' => $this->handleChargeRefunded($data),
            default => null,
        };

        return response()->json(['received' => true]);
    }

    private function handlePaymentSucceeded(array $data): void
    {
        $intentId = $data['id'] ?? null;
        if (! $intentId) {
            return;
        }

        $transaction = Transaction::where('gateway_transaction_id', $intentId)->first();
        if ($transaction) {
            $transaction->update(['status' => PaymentStatus::Paid]);

            if ($transaction->booking) {
                Log::info('Booking payment confirmed via Stripe', ['booking_id' => $transaction->booking_id]);
            }
        }
    }

    private function handlePaymentFailed(array $data): void
    {
        $intentId = $data['id'] ?? null;
        if (! $intentId) {
            return;
        }

        $transaction = Transaction::where('gateway_transaction_id', $intentId)->first();
        if ($transaction) {
            $transaction->update([
                'status' => PaymentStatus::Failed,
                'gateway_response' => $data,
            ]);
        }
    }

    private function handleChargeRefunded(array $data): void
    {
        $chargeId = $data['id'] ?? null;
        if (! $chargeId) {
            return;
        }

        $refunds = $data['refunds']['data'] ?? [];
        foreach ($refunds as $refundData) {
            $gatewayRefundId = $refundData['id'] ?? null;
            if ($gatewayRefundId) {
                Refund::where('gateway_refund_id', $gatewayRefundId)->update([
                    'status' => 'processed',
                    'processed_at' => now(),
                ]);
            }
        }
    }
}
