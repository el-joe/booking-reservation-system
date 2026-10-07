<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case Payment = 'payment';
    case Refund = 'refund';
    case Deposit = 'deposit';
    case Payout = 'payout';

    public function label(): string
    {
        return match ($this) {
            self::Payment => 'Payment',
            self::Refund => 'Refund',
            self::Deposit => 'Deposit',
            self::Payout => 'Payout',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Payment => 'green',
            self::Refund => 'red',
            self::Deposit => 'blue',
            self::Payout => 'purple',
        };
    }
}
