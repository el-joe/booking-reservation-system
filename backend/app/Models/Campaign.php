<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'status',
        'audience_filter',
        'subject',
        'body_html',
        'body_text',
        'scheduled_at',
        'sent_at',
        'recipients_count',
        'opens_count',
        'clicks_count',
        'conversions_count',
        'created_by_id',
    ];

    protected $casts = [
        'audience_filter' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'recipients_count' => 'integer',
        'opens_count' => 'integer',
        'clicks_count' => 'integer',
        'conversions_count' => 'integer',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->recipients_count === 0) {
            return 0.0;
        }

        return round(($this->opens_count / $this->recipients_count) * 100, 1);
    }

    public function getClickRateAttribute(): float
    {
        if ($this->recipients_count === 0) {
            return 0.0;
        }

        return round(($this->clicks_count / $this->recipients_count) * 100, 1);
    }

    public function getConversionRateAttribute(): float
    {
        if ($this->recipients_count === 0) {
            return 0.0;
        }

        return round(($this->conversions_count / $this->recipients_count) * 100, 1);
    }
}
