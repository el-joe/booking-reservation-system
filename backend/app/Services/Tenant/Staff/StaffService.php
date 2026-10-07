<?php

declare(strict_types=1);

namespace App\Services\Tenant\Staff;

use App\Models\AttendanceLog;
use App\Models\Booking;
use App\Models\LeaveRequest;
use App\Models\Staff;
use App\Models\StaffAssignment;
use Carbon\Carbon;

class StaffService
{
    public function create(array $data): Staff
    {
        return Staff::create($data);
    }

    public function update(Staff $staff, array $data): Staff
    {
        $staff->update($data);

        return $staff->fresh();
    }

    public function assignToBooking(Staff $staff, Booking $booking, ?string $notes = null): StaffAssignment
    {
        return StaffAssignment::create([
            'staff_id' => $staff->id,
            'booking_id' => $booking->id,
            'assigned_at' => now(),
            'notes' => $notes,
        ]);
    }

    public function approveLeave(LeaveRequest $request, ?string $note = null): LeaveRequest
    {
        $request->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'response_note' => $note,
        ]);

        return $request->fresh();
    }

    public function rejectLeave(LeaveRequest $request, string $reason): LeaveRequest
    {
        $request->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'response_note' => $reason,
        ]);

        return $request->fresh();
    }

    public function clockIn(Staff $staff): AttendanceLog
    {
        $today = today()->toDateString();

        return AttendanceLog::updateOrCreate(
            ['staff_id' => $staff->id, 'date' => $today],
            ['clock_in' => now()->toTimeString()]
        );
    }

    public function clockOut(Staff $staff): AttendanceLog
    {
        $today = today()->toDateString();

        $log = AttendanceLog::firstOrCreate(
            ['staff_id' => $staff->id, 'date' => $today],
        );

        $clockOut = now()->toTimeString();
        $hoursWorked = null;

        if ($log->clock_in) {
            $clockInTime = Carbon::parse($today.' '.$log->clock_in);
            $clockOutTime = Carbon::parse($today.' '.$clockOut);
            $hoursWorked = round($clockInTime->diffInMinutes($clockOutTime) / 60, 2);
        }

        $log->update([
            'clock_out' => $clockOut,
            'hours_worked' => $hoursWorked,
        ]);

        return $log->fresh();
    }

    public function getScheduleForWeek(Staff $staff, Carbon $weekStart): array
    {
        $schedules = $staff->schedules()->get()->keyBy('day_of_week');
        $result = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $dayOfWeek = (int) $day->dayOfWeek;
            $schedule = $schedules->get($dayOfWeek);

            $result[] = [
                'date' => $day->toDateString(),
                'day_name' => $day->format('l'),
                'day_of_week' => $dayOfWeek,
                'schedule' => $schedule,
                'is_available' => $schedule ? $schedule->is_available : false,
                'start_time' => $schedule?->start_time,
                'end_time' => $schedule?->end_time,
            ];
        }

        return $result;
    }
}
