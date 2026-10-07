<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Staff $staff): View
    {
        $schedules = $staff->schedules()->get()->keyBy('day_of_week');

        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('tenant.staff.schedule', compact('staff', 'schedules', 'days'));
    }

    public function update(Staff $staff, Request $request): RedirectResponse
    {
        $request->validate([
            'schedules' => ['required', 'array'],
            'schedules.*.day_of_week' => ['required', 'integer', 'min:0', 'max:6'],
            'schedules.*.start_time' => ['required', 'date_format:H:i'],
            'schedules.*.end_time' => ['required', 'date_format:H:i', 'after:schedules.*.start_time'],
            'schedules.*.is_available' => ['boolean'],
        ]);

        foreach ($request->schedules as $scheduleData) {
            StaffSchedule::updateOrCreate(
                ['staff_id' => $staff->id, 'day_of_week' => $scheduleData['day_of_week']],
                [
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                    'is_available' => (bool) ($scheduleData['is_available'] ?? false),
                ]
            );
        }

        return redirect()->route('tenant.staff.schedule', $staff)
            ->with('success', 'Schedule updated successfully.');
    }
}
