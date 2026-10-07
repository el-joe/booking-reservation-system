<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RefundProcessed;
use App\Models\Account;
use App\Services\Tenant\Accounting\JournalService;

class PostRefundJournal
{
    public function __construct(private JournalService $journalService) {}

    public function handle(RefundProcessed $event): void
    {
        $refund = $event->refund;

        $cash = Account::where('code', '1000')->first();
        $ar = Account::where('code', '1100')->first();

        if (! $cash || ! $ar) {
            return;
        }

        $amount = (float) $refund->amount;

        $this->journalService->post(
            [
                ['account_id' => $ar->id, 'debit' => $amount, 'credit' => 0, 'description' => 'Refund processed'],
                ['account_id' => $cash->id, 'debit' => 0, 'credit' => $amount, 'description' => 'Refund processed'],
            ],
            'Refund Processed: '.($refund->gateway_refund_id ?? $refund->id),
            'RFD-'.$refund->id.'-'.time(),
            $refund,
        );
    }
}
