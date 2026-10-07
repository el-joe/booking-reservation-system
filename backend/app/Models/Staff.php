<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'role',
        'department',
        'avatar',
        'hire_date',
        'employment_type',
        'status',
        'bio',
        'emergency_contact',
    ];

    protected $casts = [
        'emergency_contact' => 'array',
        'hire_date' => 'date',
        'employment_type' => 'string',
        'status' => 'string',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(StaffAssignment::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function salaryStructures(): HasMany
    {
        return $this->hasMany(SalaryStructure::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function isAvailableOn(Carbon $date): bool
    {
        $dayOfWeek = (int) $date->dayOfWeek;

        $schedule = $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (! $schedule) {
            return false;
        }

        $hasApprovedLeave = $this->leaveRequests()
            ->where('status', 'approved')
            ->where('start_date', '<=', $date->toDateString())
            ->where('end_date', '>=', $date->toDateString())
            ->exists();

        return ! $hasApprovedLeave;
    }
}
