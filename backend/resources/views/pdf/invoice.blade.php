<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 3px solid #2563eb; padding-bottom: 24px; }
        .company-name { font-size: 26px; font-weight: 700; color: #1e40af; }
        .company-sub { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 32px; font-weight: 700; color: #1e40af; letter-spacing: 2px; }
        .invoice-title .number { font-size: 14px; color: #6b7280; margin-top: 4px; }
        .meta-row { display: flex; justify-content: space-between; margin-bottom: 32px; }
        .meta-box { flex: 1; }
        .meta-box + .meta-box { margin-left: 24px; }
        .meta-label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em; margin-bottom: 6px; }
        .meta-value { font-size: 13px; color: #1f2937; line-height: 1.6; }
        .meta-value strong { font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #1e40af; color: #fff; }
        thead th { padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600; }
        thead th:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid #e5e7eb; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 10px 12px; font-size: 13px; vertical-align: top; }
        tbody td:last-child { text-align: right; }
        .totals { margin-left: auto; width: 280px; margin-bottom: 32px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
        .totals-row.total { font-size: 16px; font-weight: 700; color: #1e40af; border-bottom: none; padding-top: 10px; }
        .totals-label { color: #6b7280; }
        .totals-value { font-weight: 500; }
        .footer { border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
        .footer .thank-you { font-size: 15px; font-weight: 600; color: #1e40af; margin-bottom: 8px; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
<div class="container">
    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">{{ tenant('name') ?? config('app.name') }}</div>
            <div class="company-sub">Booking & Reservation System</div>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <div class="number">{{ $invoice->invoice_number }}</div>
            <div style="margin-top: 8px;">
                <span class="status-badge status-{{ $invoice->status->value }}">{{ $invoice->status->label() }}</span>
            </div>
        </div>
    </div>

    {{-- Invoice Meta --}}
    <div class="meta-row">
        <div class="meta-box">
            <div class="meta-label">Invoice Date</div>
            <div class="meta-value">{{ $invoice->created_at->format('d F Y') }}</div>
        </div>
        <div class="meta-box">
            <div class="meta-label">Due Date</div>
            <div class="meta-value">
                @if ($invoice->due_date)
                    {{ $invoice->due_date->format('d F Y') }}
                @else
                    —
                @endif
            </div>
        </div>
        @if ($invoice->booking)
            <div class="meta-box">
                <div class="meta-label">Booking Reference</div>
                <div class="meta-value">{{ $invoice->booking->reference_number }}</div>
            </div>
        @endif
        @if ($invoice->paid_at)
            <div class="meta-box">
                <div class="meta-label">Paid On</div>
                <div class="meta-value">{{ $invoice->paid_at->format('d F Y') }}</div>
            </div>
        @endif
    </div>

    {{-- Customer Info --}}
    @if ($invoice->customer)
        <div style="margin-bottom: 32px; background: #f9fafb; padding: 16px; border-radius: 8px;">
            <div class="meta-label" style="margin-bottom: 8px;">Bill To</div>
            <div class="meta-value">
                <strong>{{ $invoice->customer->full_name }}</strong><br>
                @if ($invoice->customer->email){{ $invoice->customer->email }}<br>@endif
                @if ($invoice->customer->phone){{ $invoice->customer->phone }}@endif
            </div>
        </div>
    @endif

    {{-- Line Items --}}
    <table>
        <thead>
            <tr>
                <th style="width: 50%">Description</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: right">Unit Price</th>
                <th style="text-align: right">Tax %</th>
                <th style="text-align: right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center">{{ $item->quantity }}</td>
                    <td style="text-align: right">${{ number_format((float) $item->unit_price, 2) }}</td>
                    <td style="text-align: right">{{ number_format((float) $item->tax_rate, 1) }}%</td>
                    <td style="text-align: right">${{ number_format((float) $item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="totals-row">
            <span class="totals-label">Subtotal</span>
            <span class="totals-value">${{ number_format((float) $invoice->subtotal, 2) }}</span>
        </div>
        @if ((float) $invoice->tax_amount > 0)
            <div class="totals-row">
                <span class="totals-label">Tax</span>
                <span class="totals-value">${{ number_format((float) $invoice->tax_amount, 2) }}</span>
            </div>
        @endif
        @if ((float) $invoice->discount_amount > 0)
            <div class="totals-row">
                <span class="totals-label">Discount</span>
                <span class="totals-value" style="color: #dc2626;">-${{ number_format((float) $invoice->discount_amount, 2) }}</span>
            </div>
        @endif
        <div class="totals-row total">
            <span>Total</span>
            <span>${{ number_format((float) $invoice->total, 2) }}</span>
        </div>
    </div>

    {{-- Notes --}}
    @if ($invoice->notes)
        <div style="margin-bottom: 24px; padding: 12px; background: #fffbeb; border-left: 3px solid #f59e0b; border-radius: 4px;">
            <div class="meta-label" style="margin-bottom: 4px;">Notes</div>
            <div style="font-size: 12px; color: #374151;">{{ $invoice->notes }}</div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="thank-you">Thank you for your business!</div>
        <div>Please make payment by the due date. For queries, contact us at {{ config('mail.from.address') }}.</div>
        <div style="margin-top: 6px; font-size: 11px;">Payment Terms: Net 7 days &mdash; This invoice was generated electronically and is valid without a signature.</div>
    </div>
</div>
</body>
</html>
