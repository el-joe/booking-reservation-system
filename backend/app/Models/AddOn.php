<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'name',
        'description',
        'price',
        'price_type',
        'is_required',
        'booking_types',
        'is_active',
    ];

    protected $casts = [
        'booking_types' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
