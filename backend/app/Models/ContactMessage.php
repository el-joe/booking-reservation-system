<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
    ];

    /** @param Builder<ContactMessage> $query */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }
}
