<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Events\BookingConfirmed;
use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Resource;
use App\Services\Tenant\Booking\BookingCreationService;
use App\Services\Tenant\Booking\BookingManagementService;
use App\Services\Tenant\Finance\InvoiceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TenantBookingCreationTest extends TestCase
{
    use DatabaseTransactions;

    private BookingCreationService $creationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creationService = app(BookingCreationService::class);
    }

    /**
     * @return array{
     *     resource_id: int,
     *     customer_id: int,
     *     booking_type: string,
     *     check_in: string,
     *     check_out: string,
     *     guests_count: int,
     *     source: string,
     *     notes: null,
     *     items: array<int, array{description: string, unit_price: float, quantity: int}>,
     * }
     */
    private function validBookingData(Customer $customer, Resource $resource): array
    {
        return [
            'resource_id' => $resource->id,
            'customer_id' => $customer->id,
            'booking_type' => BookingType::Hotel->value,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 2,
            'source' => 'website',
            'notes' => null,
            'items' => [
                ['description' => 'Room charge', 'unit_price' => 150.00, 'quantity' => 3],
            ],
        ];
    }

    #[Test]
    public function test_booking_can_be_created_with_valid_data(): void
    {
        $customer = Customer::factory()->create();
        $resource = Resource::factory()->create();

        $booking = $this->creationService->create($this->validBookingData($customer, $resource));

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertMatchesRegularExpression('/^BK-\d{4}-[A-Z0-9]{6}$/', $booking->reference_number);
        $this->assertEquals($customer->id, $booking->customer_id);
        $this->assertEquals($resource->id, $booking->resource_id);
        $this->assertEquals(BookingStatus::Pending, $booking->status);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'customer_id' => $customer->id,
            'resource_id' => $resource->id,
        ]);

        $this->assertDatabaseHas('booking_items', [
            'booking_id' => $booking->id,
            'description' => 'Room charge',
        ]);

        $this->assertDatabaseHas('booking_status_histories', [
            'booking_id' => $booking->id,
            'status' => BookingStatus::Pending->value,
        ]);
    }

    #[Test]
    public function test_booking_reference_number_is_unique(): void
    {
        $customer = Customer::factory()->create();
        $resource = Resource::factory()->create();

        $data = $this->validBookingData($customer, $resource);

        $booking1 = $this->creationService->create($data);
        $booking2 = $this->creationService->create($data);
        $booking3 = $this->creationService->create($data);

        $referenceNumbers = [$booking1->reference_number, $booking2->reference_number, $booking3->reference_number];

        $this->assertCount(3, array_unique($referenceNumbers));
    }

    #[Test]
    public function test_booking_creation_fires_booking_created_event(): void
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $resource = Resource::factory()->create();

        $booking = $this->creationService->create($this->validBookingData($customer, $resource));

        Event::assertDispatched(BookingCreated::class, function (BookingCreated $event) use ($booking): bool {
            return $event->booking->id === $booking->id;
        });
    }

    #[Test]
    public function test_booking_confirmed_generates_invoice(): void
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $resource = Resource::factory()->create();

        $booking = $this->creationService->create($this->validBookingData($customer, $resource));

        $managementService = app(BookingManagementService::class);
        $managementService->confirm($booking);

        Event::assertDispatched(BookingConfirmed::class, function (BookingConfirmed $event) use ($booking): bool {
            return $event->booking->id === $booking->id;
        });

        $invoiceService = app(InvoiceService::class);
        $invoice = $invoiceService->generateForBooking($booking);

        $this->assertDatabaseHas('invoices', [
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
        ]);

        $this->assertEquals((float) $booking->total_amount, (float) $invoice->total);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
        ]);
    }
}
