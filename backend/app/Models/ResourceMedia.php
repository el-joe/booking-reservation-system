<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceMedia extends Model
{
    use HasFactory;

    protected $table = 'resource_media';

    protected $fillable = [
        'resource_id',
        'file_path',
        'file_type',
        'sort_order',
        'is_cover',
        'caption',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function scopeIsCover(Builder $query): Builder
    {
        return $query->where('is_cover', true);
    }
}
