<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelReservation extends Model
{
    protected $fillable = [
        'ota_channel_id',
        'booking_id',
        'external_reservation_id',
        'external_status',
        'guest_name',
        'guest_email',
        'check_in',
        'check_out',
        'guests_count',
        'total_amount',
        'currency',
        'raw_data',
        'imported_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_amount' => 'decimal:2',
        'raw_data' => 'array',
        'imported_at' => 'datetime',
        'guests_count' => 'integer',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(OtaChannel::class, 'ota_channel_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isImported(): bool
    {
        return $this->booking_id !== null;
    }
}
