<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Models\Account;
use App\Services\Tenant\Accounting\JournalService;

class PostPaymentJournal
{
    public function __construct(private JournalService $journalService) {}

    public function handle(PaymentReceived $event): void
    {
        $transaction = $event->transaction;

        $cash = Account::where('code', '1000')->first();
        $ar = Account::where('code', '1100')->first();

        if (! $cash || ! $ar) {
            return;
        }

        $amount = (float) $transaction->amount;

        $this->journalService->post(
            [
                ['account_id' => $cash->id, 'debit' => $amount, 'credit' => 0, 'description' => 'Payment received'],
                ['account_id' => $ar->id, 'debit' => 0, 'credit' => $amount, 'description' => 'Payment received'],
            ],
            'Payment Received: '.($transaction->gateway_transaction_id ?? $transaction->id),
            'PMT-'.$transaction->id.'-'.time(),
            $transaction,
        );
    }
}
