<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliation extends Model
{
    protected $fillable = [
        'bank_account_id',
        'statement_date',
        'statement_balance',
        'reconciled_balance',
        'difference',
        'status',
        'notes',
    ];

    protected $casts = [
        'statement_date' => 'date',
        'status' => 'string',
        'statement_balance' => 'decimal:2',
        'reconciled_balance' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
