<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Models\Booking;
use App\Models\Invoice;
use App\Services\Tenant\Finance\InvoiceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceGenerationTest extends TestCase
{
    use DatabaseTransactions;

    private InvoiceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(InvoiceService::class);
    }

    #[Test]
    public function test_invoice_generated_for_booking(): void
    {
        $booking = Booking::factory()->create(['total_amount' => 300.00]);

        $invoice = $this->service->generateForBooking($booking);

        $this->assertDatabaseHas('invoices', [
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
        ]);

        $this->assertEquals((float) $booking->total_amount, (float) $invoice->total);

        $this->assertMatchesRegularExpression('/^INV-\d{4}-\d{5}$/', $invoice->invoice_number);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
        ]);
    }

    #[Test]
    public function test_invoice_number_is_sequential(): void
    {
        // Ensure we start from a clean count by checking current count
        $startCount = Invoice::count();

        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();
        $booking3 = Booking::factory()->create();

        $invoice1 = $this->service->generateForBooking($booking1);
        $invoice2 = $this->service->generateForBooking($booking2);
        $invoice3 = $this->service->generateForBooking($booking3);

        $year = date('Y');
        $pad = fn (int $n): string => str_pad((string) $n, 5, '0', STR_PAD_LEFT);

        $this->assertEquals("INV-{$year}-{$pad($startCount + 1)}", $invoice1->invoice_number);
        $this->assertEquals("INV-{$year}-{$pad($startCount + 2)}", $invoice2->invoice_number);
        $this->assertEquals("INV-{$year}-{$pad($startCount + 3)}", $invoice3->invoice_number);
    }

    #[Test]
    public function test_invoice_can_be_marked_paid(): void
    {
        $booking = Booking::factory()->create();
        $invoice = $this->service->generateForBooking($booking);

        $this->assertEquals(InvoiceStatus::Draft, $invoice->status);

        $paid = $this->service->markAsPaid($invoice);

        $this->assertEquals(InvoiceStatus::Paid, $paid->status);
        $this->assertNotNull($paid->paid_at);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Paid->value,
        ]);
    }
}
