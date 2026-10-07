<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelRatePlan extends Model
{
    protected $fillable = [
        'ota_channel_id',
        'resource_id',
        'rate_plan_name',
        'base_rate',
        'min_stay',
        'max_stay',
        'is_active',
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'min_stay' => 'integer',
        'max_stay' => 'integer',
        'is_active' => 'boolean',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(OtaChannel::class, 'ota_channel_id');
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
