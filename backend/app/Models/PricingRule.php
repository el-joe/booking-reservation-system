<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'name',
        'rule_type',
        'applies_from',
        'applies_to',
        'modifier_type',
        'modifier_value',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'applies_from' => 'date',
        'applies_to' => 'date',
        'modifier_value' => 'decimal:2',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
