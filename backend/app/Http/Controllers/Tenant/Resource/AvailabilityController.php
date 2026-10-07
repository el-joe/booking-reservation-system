<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Resource;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Services\Tenant\Resource\AvailabilityService;
use App\Services\Tenant\Resource\ResourceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function __construct(private readonly AvailabilityService $availabilityService) {}

    public function index(Resource $resource): View
    {
        return view('tenant.resources.availability.calendar', compact('resource'));
    }

    public function update(Resource $resource, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dates' => ['required', 'array'],
            'dates.*.date' => ['required', 'date'],
            'dates.*.available_capacity' => ['nullable', 'integer', 'min:0'],
            'dates.*.is_closed' => ['nullable', 'boolean'],
            'dates.*.note' => ['nullable', 'string', 'max:255'],
        ]);

        app(ResourceService::class)
            ->updateAvailability($resource, $validated['dates']);

        return back()->with('success', 'Availability updated successfully.');
    }

    public function blockDates(Resource $resource, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dates' => ['required', 'array'],
            'dates.*' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $this->availabilityService->blockDates($resource, $validated['dates'], $validated['reason']);

        return back()->with('success', 'Dates blocked successfully.');
    }

    public function getCalendarData(Resource $resource, Request $request): JsonResponse
    {
        $month = Carbon::parse($request->input('month', now()->format('Y-m')));
        $events = $this->availabilityService->getCalendarData($resource, $month);

        return response()->json($events);
    }
}
