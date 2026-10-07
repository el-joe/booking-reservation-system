<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'iso2',
        'currency_code',
        'currency_symbol',
        'dial_code',
    ];

    /** @return Collection<int, static> */
    public static function forSelect(): Collection
    {
        return static::orderBy('name')->get();
    }
}
