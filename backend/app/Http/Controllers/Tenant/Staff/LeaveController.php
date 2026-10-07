<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Staff;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Staff;
use App\Services\Tenant\Staff\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function __construct(private readonly StaffService $staffService) {}

    public function index(Request $request): View
    {
        $query = LeaveRequest::with('staff');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $leaveRequests = $query->latest()->paginate(20);
        $staffList = Staff::where('status', 'active')->orderBy('name')->get();

        return view('tenant.staff.leave.index', compact('leaveRequests', 'staffList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'staff_id' => ['required', 'exists:staff,id'],
            'leave_type' => ['required', 'in:annual,sick,emergency,unpaid'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        LeaveRequest::create($request->only(['staff_id', 'leave_type', 'start_date', 'end_date', 'reason']));

        return redirect()->route('tenant.leave.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function approve(LeaveRequest $leave, Request $request): RedirectResponse
    {
        $request->validate([
            'response_note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->staffService->approveLeave($leave, $request->response_note);

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(LeaveRequest $leave, Request $request): RedirectResponse
    {
        $request->validate([
            'response_note' => ['required', 'string', 'max:500'],
        ]);

        $this->staffService->rejectLeave($leave, $request->response_note);

        return back()->with('success', 'Leave request rejected.');
    }
}
