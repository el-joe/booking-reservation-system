<?php

declare(strict_types=1);

namespace App\Services\Tenant\HR;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\SalaryStructure;
use App\Models\Staff;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PayrollService
{
    public function processPayroll(int $month, int $year): PayrollRun
    {
        $run = PayrollRun::firstOrCreate(
            ['period_month' => $month, 'period_year' => $year],
            ['status' => 'draft', 'total_gross' => 0, 'total_deductions' => 0, 'total_net' => 0]
        );

        $staffList = Staff::where('status', 'active')->get();

        $totalGross = 0.0;
        $totalDeductions = 0.0;
        $totalNet = 0.0;

        foreach ($staffList as $staff) {
            $calc = $this->calculatePayslip($staff, $month, $year);

            Payslip::updateOrCreate(
                ['payroll_run_id' => $run->id, 'staff_id' => $staff->id],
                [
                    'gross_salary' => $calc['gross'],
                    'total_deductions' => $calc['deductions'],
                    'net_salary' => $calc['net'],
                    'components' => $calc['components'],
                    'status' => 'processed',
                ]
            );

            $totalGross += $calc['gross'];
            $totalDeductions += $calc['deductions'];
            $totalNet += $calc['net'];
        }

        $run->update([
            'status' => 'processed',
            'processed_at' => now(),
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
        ]);

        return $run->fresh();
    }

    public function calculatePayslip(Staff $staff, int $month, int $year): array
    {
        $structure = SalaryStructure::where('staff_id', $staff->id)
            ->active()
            ->orderByDesc('effective_from')
            ->first();

        if (! $structure) {
            return [
                'gross' => 0.0,
                'deductions' => 0.0,
                'net' => 0.0,
                'components' => [],
            ];
        }

        $baseSalary = (float) $structure->base_salary;
        $components = [];
        $allowances = 0.0;
        $deductions = 0.0;

        $components[] = ['name' => 'Base Salary', 'type' => 'allowance', 'amount' => $baseSalary];

        foreach ($structure->components as $component) {
            $amount = $component->is_percentage
                ? ($baseSalary * (float) $component->amount / 100)
                : (float) $component->amount;

            $components[] = [
                'name' => $component->name,
                'type' => $component->type,
                'amount' => round($amount, 2),
            ];

            if ($component->type === 'allowance') {
                $allowances += $amount;
            } else {
                $deductions += $amount;
            }
        }

        $gross = $baseSalary + $allowances;
        $net = $gross - $deductions;

        return [
            'gross' => round($gross, 2),
            'deductions' => round($deductions, 2),
            'net' => round($net, 2),
            'components' => $components,
        ];
    }

    public function generatePayslipPdf(Payslip $payslip): string
    {
        $payslip->load(['staff', 'payrollRun']);

        $pdf = Pdf::loadView('pdf.payslip', ['payslip' => $payslip]);

        $dir = 'payslips';
        Storage::makeDirectory($dir);

        $filename = "payslip_{$payslip->payroll_run_id}_{$payslip->staff_id}.pdf";
        $path = "{$dir}/{$filename}";

        Storage::put($path, $pdf->output());

        return $path;
    }

    public function markPayrollPaid(PayrollRun $run): void
    {
        $run->payslips()->update(['status' => 'paid', 'paid_at' => now()]);
        $run->update(['status' => 'paid']);
    }
}
