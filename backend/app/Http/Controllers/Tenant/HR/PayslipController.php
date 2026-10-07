<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use App\Services\Tenant\HR\PayrollService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PayslipController extends Controller
{
    public function __construct(private readonly PayrollService $payrollService) {}

    public function show(Payslip $payslip): View
    {
        $payslip->load(['staff', 'payrollRun']);

        return view('tenant.hr.payslip.show', compact('payslip'));
    }

    public function download(Payslip $payslip): Response
    {
        $payslip->load(['staff', 'payrollRun']);
        $path = $this->payrollService->generatePayslipPdf($payslip);

        return response(Storage::get($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payslip.pdf"',
        ]);
    }
}
