<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Operations;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\ChecklistCompletion;
use App\Models\OperationalChecklist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    public function index(): View
    {
        $checklists = OperationalChecklist::latest()->paginate(20);

        return view('tenant.operations.checklists.index', compact('checklists'));
    }

    public function create(): View
    {
        $bookingTypes = BookingType::cases();

        return view('tenant.operations.checklists.create', compact('bookingTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'booking_type' => ['nullable', 'string'],
            'trigger' => ['required', 'in:pre_booking,post_booking,daily,maintenance'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        OperationalChecklist::create($validated);

        return redirect()->route('tenant.operations.checklists.index')
            ->with('success', 'Checklist created successfully.');
    }

    public function show(OperationalChecklist $checklist): View
    {
        $checklist->load('completions');

        return view('tenant.operations.checklists.show', compact('checklist'));
    }

    public function edit(OperationalChecklist $checklist): View
    {
        $bookingTypes = BookingType::cases();

        return view('tenant.operations.checklists.edit', compact('checklist', 'bookingTypes'));
    }

    public function update(Request $request, OperationalChecklist $checklist): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'booking_type' => ['nullable', 'string'],
            'trigger' => ['required', 'in:pre_booking,post_booking,daily,maintenance'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $checklist->update($validated);

        return redirect()->route('tenant.operations.checklists.index')
            ->with('success', 'Checklist updated successfully.');
    }

    public function destroy(OperationalChecklist $checklist): RedirectResponse
    {
        $checklist->delete();

        return redirect()->route('tenant.operations.checklists.index')
            ->with('success', 'Checklist deleted successfully.');
    }

    public function complete(Request $request, OperationalChecklist $checklist): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'completed_items' => ['required', 'array'],
        ]);

        ChecklistCompletion::create([
            'checklist_id' => $checklist->id,
            'booking_id' => $validated['booking_id'] ?? null,
            'completed_items' => $validated['completed_items'],
            'completed_by_id' => auth()->id(),
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Checklist completed successfully.');
    }
}
