<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\HR;

use App\DataTables\Tenant\PayrollRunDataTable;
use App\Http\Controllers\Controller;
use App\Models\PayrollRun;
use App\Models\Staff;
use App\Services\Tenant\HR\PayrollService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function __construct(private readonly PayrollService $payrollService) {}

    public function index(PayrollRunDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.hr.payroll.index');
    }

    public function create(): View
    {
        $staffWithSalaries = Staff::where('status', 'active')
            ->with(['salaryStructures' => fn ($q) => $q->active()->latest('effective_from')->limit(1)])
            ->get();

        return view('tenant.hr.payroll.create', compact('staffWithSalaries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2000|max:2100',
        ]);

        $run = $this->payrollService->processPayroll(
            (int) $request->period_month,
            (int) $request->period_year
        );

        return redirect()->route('tenant.hr.payroll.show', $run)
            ->with('success', "Payroll for {$run->period_label} processed successfully.");
    }

    public function show(PayrollRun $run): View
    {
        $run->load('payslips.staff');

        return view('tenant.hr.payroll.show', compact('run'));
    }

    public function markPaid(PayrollRun $run): RedirectResponse
    {
        $this->payrollService->markPayrollPaid($run);

        return redirect()->route('tenant.hr.payroll.show', $run)
            ->with('success', "Payroll for {$run->period_label} marked as paid.");
    }
}
