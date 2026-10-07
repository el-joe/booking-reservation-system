<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Operations;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\Resource;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(): View
    {
        $schedules = MaintenanceSchedule::with(['resource', 'assignedTo'])
            ->latest('scheduled_at')
            ->paginate(20);

        return view('tenant.operations.maintenance.index', compact('schedules'));
    }

    public function create(): View
    {
        $resources = Resource::orderBy('name')->get();
        $staff = Staff::orderBy('name')->get();

        return view('tenant.operations.maintenance.create', compact('resources', 'staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'resource_id' => ['required', 'exists:resources,id'],
            'scheduled_at' => ['required', 'date'],
            'duration_hours' => ['required', 'numeric', 'min:0.5'],
            'description' => ['required', 'string'],
            'assigned_to_id' => ['nullable', 'exists:staff,id'],
        ]);

        MaintenanceSchedule::create($validated);

        return redirect()->route('tenant.operations.maintenance.index')
            ->with('success', 'Maintenance scheduled successfully.');
    }

    public function show(MaintenanceSchedule $maintenance): View
    {
        $maintenance->load(['resource', 'assignedTo']);

        return view('tenant.operations.maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceSchedule $maintenance): View
    {
        $resources = Resource::orderBy('name')->get();
        $staff = Staff::orderBy('name')->get();

        return view('tenant.operations.maintenance.edit', compact('maintenance', 'resources', 'staff'));
    }

    public function update(Request $request, MaintenanceSchedule $maintenance): RedirectResponse
    {
        $validated = $request->validate([
            'resource_id' => ['required', 'exists:resources,id'],
            'scheduled_at' => ['required', 'date'],
            'duration_hours' => ['required', 'numeric', 'min:0.5'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled'],
            'assigned_to_id' => ['nullable', 'exists:staff,id'],
        ]);

        $maintenance->update($validated);

        return redirect()->route('tenant.operations.maintenance.index')
            ->with('success', 'Maintenance schedule updated successfully.');
    }

    public function destroy(MaintenanceSchedule $maintenance): RedirectResponse
    {
        $maintenance->delete();

        return redirect()->route('tenant.operations.maintenance.index')
            ->with('success', 'Maintenance schedule deleted successfully.');
    }

    public function markComplete(MaintenanceSchedule $maintenance): RedirectResponse
    {
        $maintenance->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Maintenance marked as completed.');
    }

    public function calendarEvents(Request $request): JsonResponse
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $maintenances = MaintenanceSchedule::with('resource')
            ->when($start, fn ($q) => $q->where('scheduled_at', '>=', $start))
            ->when($end, fn ($q) => $q->where('scheduled_at', '<=', $end))
            ->get()
            ->map(fn (MaintenanceSchedule $m) => [
                'id' => 'maintenance-'.$m->id,
                'title' => '[Maintenance] '.($m->resource->name ?? 'Resource'),
                'start' => $m->scheduled_at->toIso8601String(),
                'end' => $m->scheduled_at->addHours((float) $m->duration_hours)->toIso8601String(),
                'backgroundColor' => '#f97316',
                'borderColor' => '#ea580c',
                'resourceId' => 'r-'.$m->resource_id,
            ]);

        return response()->json($maintenances);
    }
}
