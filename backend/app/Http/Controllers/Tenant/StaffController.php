<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Staff\StoreStaffRequest;
use App\Models\Staff;
use App\Services\Tenant\Staff\StaffService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function __construct(private readonly StaffService $staffService) {}

    public function index(Request $request): View
    {
        $query = Staff::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query->latest()->paginate(20);

        return view('tenant.staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('tenant.staff.create');
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $staff = $this->staffService->create($request->validated());

        return redirect()->route('tenant.staff.show', $staff)
            ->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff): View
    {
        $staff->load(['schedules', 'assignments.booking', 'leaveRequests']);

        $weekSchedule = $this->staffService->getScheduleForWeek($staff, Carbon::now()->startOfWeek());

        $attendanceThisMonth = $staff->attendanceLogs()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $totalHoursThisMonth = $attendanceThisMonth->sum('hours_worked');

        return view('tenant.staff.show', compact('staff', 'weekSchedule', 'attendanceThisMonth', 'totalHoursThisMonth'));
    }

    public function edit(Staff $staff): View
    {
        return view('tenant.staff.edit', compact('staff'));
    }

    public function update(StoreStaffRequest $request, Staff $staff): RedirectResponse
    {
        $this->staffService->update($staff, $request->validated());

        return redirect()->route('tenant.staff.show', $staff)
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();

        return redirect()->route('tenant.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    public function clockIn(Staff $staff): RedirectResponse
    {
        $this->staffService->clockIn($staff);

        return back()->with('success', "Clock-in recorded for {$staff->name}.");
    }

    public function clockOut(Staff $staff): RedirectResponse
    {
        $this->staffService->clockOut($staff);

        return back()->with('success', "Clock-out recorded for {$staff->name}.");
    }
}
