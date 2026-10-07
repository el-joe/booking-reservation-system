<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst($report_type) }} Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .subtitle { color: #6b7280; font-size: 11px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #1d4ed8; color: #fff; padding: 6px 8px; text-align: left; font-size: 11px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) td { background: #f9fafb; }
    </style>
</head>
<body>
    <h1>{{ ucfirst($report_type) }} Report</h1>
    <p class="subtitle">Generated: {{ now()->format('d M Y H:i') }}</p>

    @if (isset($bookings))
        <table>
            <thead>
                <tr>
                    <th>Reference</th><th>Customer</th><th>Resource</th><th>Status</th><th>Check-in</th><th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $b)
                    <tr>
                        <td>{{ $b->reference_number }}</td>
                        <td>{{ $b->customer ? $b->customer->first_name.' '.$b->customer->last_name : '-' }}</td>
                        <td>{{ $b->resource?->name ?? '-' }}</td>
                        <td>{{ $b->status?->label() }}</td>
                        <td>{{ $b->check_in?->format('d M Y') }}</td>
                        <td>${{ number_format($b->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No data available for this report.</p>
    @endif
</body>
</html>
