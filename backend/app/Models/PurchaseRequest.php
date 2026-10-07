<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_by_id',
        'title',
        'items',
        'total_estimated',
        'status',
        'approved_by_id',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'status' => 'string',
        'total_estimated' => 'decimal:2',
    ];

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'requested_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by_id');
    }
}
