<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_month',
        'period_year',
        'status',
        'processed_at',
        'total_gross',
        'total_deductions',
        'total_net',
        'notes',
    ];

    protected $casts = [
        'status' => 'string',
        'processed_at' => 'datetime',
    ];

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function getPeriodLabelAttribute(): string
    {
        $date = Carbon::createFromDate($this->period_year, $this->period_month, 1);

        return $date->format('F Y');
    }
}
