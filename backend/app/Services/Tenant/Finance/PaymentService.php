<?php

declare(strict_types=1);

namespace App\Services\Tenant\Finance;

use App\Enums\PaymentStatus;
use App\Models\Refund;
use App\Models\Transaction;

class PaymentService
{
    public function record(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function processRefund(Transaction $transaction, float $amount, string $reason): Refund
    {
        $refund = Refund::create([
            'transaction_id' => $transaction->id,
            'booking_id' => $transaction->booking_id,
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'pending',
        ]);

        $transaction->update(['status' => PaymentStatus::Refunded]);

        return $refund;
    }
}
