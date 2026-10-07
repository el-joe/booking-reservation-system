<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payslip->payrollRun->period_label }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0; font-size: 12px; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; background: #f0f0f0; padding: 6px 10px; border-left: 4px solid #333; margin-bottom: 8px; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 40%; font-weight: bold; padding: 4px 0; }
        .info-value { display: table-cell; padding: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background: #333; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; background: #f9f9f9; }
        .net-box { background: #1e40af; color: #fff; padding: 15px; text-align: center; margin: 20px 0; }
        .net-box .label { font-size: 12px; opacity: 0.85; }
        .net-box .amount { font-size: 24px; font-weight: bold; }
        .signatures { display: table; width: 100%; margin-top: 40px; }
        .sig-cell { display: table-cell; width: 50%; text-align: center; padding: 0 20px; }
        .sig-line { border-top: 1px solid #333; padding-top: 5px; margin-top: 40px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PAYSLIP</h1>
        <p>Period: {{ $payslip->payrollRun->period_label }}</p>
        <p>Generated: {{ now()->format('d M Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Employee Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $payslip->staff->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Role:</div>
                <div class="info-value">{{ ucfirst($payslip->staff->role ?? '—') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value">{{ $payslip->staff->department ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Employment Type:</div>
                <div class="info-value">{{ ucwords(str_replace('_', ' ', $payslip->staff->employment_type ?? '')) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $payslip->staff->email }}</div>
            </div>
        </div>
    </div>

    @php
        $earnings = collect($payslip->components)->where('type', 'allowance');
        $deductions = collect($payslip->components)->where('type', 'deduction');
    @endphp

    <div class="section">
        <div class="section-title">Earnings</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($earnings as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-right">{{ number_format((float) $item['amount'], 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Gross</td>
                    <td class="text-right">{{ number_format((float) $payslip->gross_salary, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Deductions</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deductions as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-right">{{ number_format((float) $item['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="color:#999; text-align:center;">No deductions</td></tr>
                @endforelse
                <tr class="total-row">
                    <td>Total Deductions</td>
                    <td class="text-right">{{ number_format((float) $payslip->total_deductions, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="net-box">
        <div class="label">NET SALARY</div>
        <div class="amount">{{ $payslip->payrollRun->period_label[0] ?? '' }}{{ number_format((float) $payslip->net_salary, 2) }}</div>
    </div>

    <div class="signatures">
        <div class="sig-cell">
            <div class="sig-line">Employee Signature</div>
        </div>
        <div class="sig-cell">
            <div class="sig-line">Manager Signature</div>
        </div>
    </div>
</body>
</html>
