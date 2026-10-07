<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
        'is_system',
        'description',
    ];

    protected $casts = [
        'type' => 'string',
        'is_system' => 'boolean',
        'parent_id' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function getBalanceAttribute(): float
    {
        $debits = (float) $this->journalLines()->sum('debit');
        $credits = (float) $this->journalLines()->sum('credit');

        // For asset and expense accounts: balance = debits - credits
        // For liability, equity, revenue: balance = credits - debits
        if (in_array($this->type, ['asset', 'expense'], true)) {
            return $debits - $credits;
        }

        return $credits - $debits;
    }
}
