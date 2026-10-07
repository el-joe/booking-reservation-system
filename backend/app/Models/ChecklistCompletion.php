<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'checklist_id',
        'completed_items',
        'completed_by_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_items' => 'array',
        'completed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(OperationalChecklist::class, 'checklist_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'completed_by_id');
    }
}
