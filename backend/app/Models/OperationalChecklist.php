<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperationalChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'booking_type',
        'trigger',
        'items',
        'is_active',
    ];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
    ];

    public function getBookingTypeEnumAttribute(): ?BookingType
    {
        return $this->booking_type ? BookingType::tryFrom($this->booking_type) : null;
    }

    public function completions(): HasMany
    {
        return $this->hasMany(ChecklistCompletion::class, 'checklist_id');
    }
}
