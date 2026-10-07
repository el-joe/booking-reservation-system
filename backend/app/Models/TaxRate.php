<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'applies_to',
        'is_inclusive',
        'is_active',
    ];

    protected $casts = [
        'applies_to' => 'array',
        'is_inclusive' => 'boolean',
        'is_active' => 'boolean',
    ];
}
