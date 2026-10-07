<?php

declare(strict_types=1);

namespace App\Services\Tenant\Finance;

use App\Enums\InvoiceStatus;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateForBooking(Booking $booking): Invoice
    {
        $count = Invoice::count();
        $invoiceNumber = 'INV-'.date('Y').'-'.str_pad((string) ($count + 1), 5, '0', STR_PAD_LEFT);

        $subtotal = (float) $booking->total_amount;
        $total = $subtotal;

        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'invoice_number' => $invoiceNumber,
            'status' => InvoiceStatus::Draft,
            'subtotal' => $subtotal,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total' => $total,
            'due_date' => now()->addDays(7),
            'notes' => null,
        ]);

        // Create base price line item
        $invoice->items()->create([
            'description' => "Booking #{$booking->reference_number}",
            'quantity' => 1,
            'unit_price' => $subtotal,
            'total_price' => $subtotal,
            'tax_rate' => 0,
        ]);

        // Create items from booking items
        foreach ($booking->items as $item) {
            $invoice->items()->create([
                'description' => $item->description ?? $item->name ?? 'Item',
                'quantity' => $item->quantity ?? 1,
                'unit_price' => $item->unit_price ?? $item->price ?? 0,
                'total_price' => $item->total_price ?? $item->price ?? 0,
                'tax_rate' => 0,
            ]);
        }

        return $invoice->fresh(['items', 'booking', 'customer']);
    }

    public function sendByEmail(Invoice $invoice): void
    {
        // Stub — will use InvoiceMailNotification in the notification system phase
        // Notification::send($invoice->customer, new InvoiceMailNotification($invoice));
    }

    public function generatePdf(Invoice $invoice): string
    {
        $invoice->load(['items', 'booking', 'customer']);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        $filename = "invoices/{$invoice->invoice_number}.pdf";
        Storage::put($filename, $pdf->output());

        return Storage::path($filename);
    }

    public function markAsPaid(Invoice $invoice, ?Transaction $transaction = null): Invoice
    {
        $invoice->update([
            'status' => InvoiceStatus::Paid,
            'paid_at' => now(),
        ]);

        return $invoice->fresh();
    }
}
