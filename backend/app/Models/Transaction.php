<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'amount',
        'currency',
        'type',
        'status',
        'gateway',
        'gateway_transaction_id',
        'gateway_response',
        'notes',
    ];

    protected $casts = [
        'type' => TransactionType::class,
        'status' => PaymentStatus::class,
        'gateway_response' => 'array',
        'amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }
}
