<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Services\Tenant\Finance\InvoiceService;
use Illuminate\Support\Facades\Log;

class GenerateInvoiceOnBooking
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {}

    public function handle(BookingConfirmed $event): void
    {
        try {
            $invoice = $this->invoiceService->generateForBooking($event->booking);

            Log::info('Invoice generated for booking', [
                'booking_id' => $event->booking->id,
                'reference' => $event->booking->reference_number,
                'invoice_number' => $invoice->invoice_number,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to generate invoice for booking', [
                'booking_id' => $event->booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
