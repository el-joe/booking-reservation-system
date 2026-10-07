<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Operations;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MaintenanceSchedule;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $resources = Resource::orderBy('name')->get();

        return view('tenant.operations.master-schedule', compact('resources'));
    }

    public function events(Request $request): JsonResponse
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $bookings = Booking::with(['resource', 'customer'])
            ->when($start, fn ($q) => $q->where('check_in', '>=', $start))
            ->when($end, fn ($q) => $q->where('check_out', '<=', $end))
            ->get()
            ->map(fn (Booking $b) => [
                'id' => 'booking-'.$b->id,
                'title' => $b->customer?->name ?? $b->reference_number,
                'start' => $b->check_in?->toIso8601String(),
                'end' => $b->check_out?->toIso8601String(),
                'backgroundColor' => '#3b82f6',
                'borderColor' => '#2563eb',
                'resourceId' => 'r-'.$b->resource_id,
            ]);

        $maintenances = MaintenanceSchedule::with('resource')
            ->when($start, fn ($q) => $q->where('scheduled_at', '>=', $start))
            ->when($end, fn ($q) => $q->where('scheduled_at', '<=', $end))
            ->get()
            ->map(fn (MaintenanceSchedule $m) => [
                'id' => 'maintenance-'.$m->id,
                'title' => '[Maintenance] '.($m->resource->name ?? ''),
                'start' => $m->scheduled_at->toIso8601String(),
                'end' => $m->scheduled_at->addHours((float) $m->duration_hours)->toIso8601String(),
                'backgroundColor' => '#f97316',
                'borderColor' => '#ea580c',
                'resourceId' => 'r-'.$m->resource_id,
            ]);

        return response()->json($bookings->merge($maintenances)->values());
    }

    public function resources(): JsonResponse
    {
        $resources = Resource::orderBy('name')
            ->get()
            ->map(fn (Resource $r) => [
                'id' => 'r-'.$r->id,
                'title' => $r->name,
            ]);

        return response()->json($resources);
    }
}
