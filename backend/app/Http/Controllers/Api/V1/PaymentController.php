<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Services\Tenant\Finance\Gateways\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly StripeService $stripeService) {}

    public function createIntent(Request $request): JsonResponse
    {
        $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->customer_id !== $request->user()->id) {
            abort(403, 'This booking does not belong to you.');
        }

        $intent = $this->stripeService->createPaymentIntent(
            (float) $booking->total_amount,
            $request->currency ?? 'usd',
            ['booking_id' => $booking->id, 'reference_number' => $booking->reference_number]
        );

        return response()->json([
            'client_secret' => $intent['client_secret'],
            'payment_intent_id' => $intent['id'],
        ]);
    }

    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string'],
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->customer_id !== $request->user()->id) {
            abort(403, 'This booking does not belong to you.');
        }

        $result = $this->stripeService->confirmPayment($request->payment_intent_id);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'customer_id' => $request->user()->id,
            'amount' => $booking->total_amount,
            'currency' => 'usd',
            'type' => 'payment',
            'status' => $result['status'] === 'succeeded' ? PaymentStatus::Completed->value : PaymentStatus::Failed->value,
            'gateway' => 'stripe',
            'gateway_transaction_id' => $result['id'],
        ]);

        if ($result['status'] === 'succeeded') {
            $booking->update(['paid_amount' => $booking->total_amount]);
        }

        return response()->json([
            'status' => $result['status'],
            'transaction_id' => $transaction->id,
        ]);
    }
}
