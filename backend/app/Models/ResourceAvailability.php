<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceAvailability extends Model
{
    use HasFactory;

    protected $table = 'resource_availability';

    protected $fillable = [
        'resource_id',
        'date',
        'available_capacity',
        'is_closed',
        'note',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'date' => 'date',
        'available_capacity' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
