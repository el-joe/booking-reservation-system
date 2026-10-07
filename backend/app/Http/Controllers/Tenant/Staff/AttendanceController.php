<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Staff;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = AttendanceLog::with('staff');

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $logs = $query->orderByDesc('date')->paginate(50);
        $staffList = Staff::where('status', 'active')->orderBy('name')->get();

        $totalHours = $query->sum('hours_worked');

        return view('tenant.staff.attendance.index', compact('logs', 'staffList', 'totalHours'));
    }

    public function report(Staff $staff): View
    {
        $month = now()->month;
        $year = now()->year;

        $logs = $staff->attendanceLogs()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        $totalHours = $logs->sum('hours_worked');
        $daysWorked = $logs->whereNotNull('clock_in')->count();

        return view('tenant.staff.attendance.report', compact('staff', 'logs', 'totalHours', 'daysWorked', 'month', 'year'));
    }
}
