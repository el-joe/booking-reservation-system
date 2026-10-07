<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaryController extends Controller
{
    public function index(Staff $staff): View
    {
        $structures = SalaryStructure::where('staff_id', $staff->id)
            ->with('components')
            ->orderByDesc('effective_from')
            ->get();

        return view('tenant.hr.salary.index', compact('staff', 'structures'));
    }

    public function create(Staff $staff): View
    {
        return view('tenant.hr.salary.create', compact('staff'));
    }

    public function store(Staff $staff, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'effective_from' => 'required|date',
            'components' => 'nullable|array',
            'components.*.name' => 'required|string|max:255',
            'components.*.type' => 'required|in:allowance,deduction',
            'components.*.amount' => 'required|numeric|min:0',
            'components.*.is_percentage' => 'boolean',
        ]);

        // Deactivate previous structures
        SalaryStructure::where('staff_id', $staff->id)->update(['is_active' => false]);

        $structure = SalaryStructure::create([
            'staff_id' => $staff->id,
            'base_salary' => $data['base_salary'],
            'currency' => $data['currency'],
            'effective_from' => $data['effective_from'],
            'is_active' => true,
        ]);

        foreach ($data['components'] ?? [] as $comp) {
            $structure->components()->create([
                'name' => $comp['name'],
                'type' => $comp['type'],
                'amount' => $comp['amount'],
                'is_percentage' => (bool) ($comp['is_percentage'] ?? false),
                'percentage_of' => ($comp['is_percentage'] ?? false) ? 'base_salary' : null,
            ]);
        }

        return redirect()->route('tenant.hr.salary.index', $staff)
            ->with('success', 'Salary structure created successfully.');
    }

    public function edit(SalaryStructure $structure): View
    {
        $structure->load(['staff', 'components']);

        return view('tenant.hr.salary.edit', compact('structure'));
    }

    public function update(SalaryStructure $structure, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'effective_from' => 'required|date',
            'components' => 'nullable|array',
            'components.*.name' => 'required|string|max:255',
            'components.*.type' => 'required|in:allowance,deduction',
            'components.*.amount' => 'required|numeric|min:0',
            'components.*.is_percentage' => 'boolean',
        ]);

        $structure->update([
            'base_salary' => $data['base_salary'],
            'currency' => $data['currency'],
            'effective_from' => $data['effective_from'],
        ]);

        $structure->components()->delete();

        foreach ($data['components'] ?? [] as $comp) {
            $structure->components()->create([
                'name' => $comp['name'],
                'type' => $comp['type'],
                'amount' => $comp['amount'],
                'is_percentage' => (bool) ($comp['is_percentage'] ?? false),
                'percentage_of' => ($comp['is_percentage'] ?? false) ? 'base_salary' : null,
            ]);
        }

        return redirect()->route('tenant.hr.salary.index', $structure->staff_id)
            ->with('success', 'Salary structure updated successfully.');
    }
}
