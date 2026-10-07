<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\BlackoutDate;
use App\Models\Booking;
use App\Models\Resource;
use App\Services\Tenant\Resource\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResourceAvailabilityTest extends TestCase
{
    use DatabaseTransactions;

    private AvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AvailabilityService::class);
    }

    #[Test]
    public function test_resource_is_available_when_no_bookings(): void
    {
        $resource = Resource::factory()->create(['capacity' => 2]);

        $from = Carbon::now()->addDays(10);
        $to = Carbon::now()->addDays(13);

        $this->assertTrue($this->service->isAvailable($resource, $from, $to, 1));
    }

    #[Test]
    public function test_resource_is_unavailable_when_fully_booked(): void
    {
        $resource = Resource::factory()->create(['capacity' => 1]);

        $checkIn = Carbon::now()->addDays(5)->toDateString();
        $checkOut = Carbon::now()->addDays(8)->toDateString();

        Booking::factory()->create([
            'resource_id' => $resource->id,
            'status' => BookingStatus::Confirmed,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ]);

        $from = Carbon::parse($checkIn);
        $to = Carbon::parse($checkOut);

        $this->assertFalse($this->service->isAvailable($resource, $from, $to, 1));
    }

    #[Test]
    public function test_blackout_date_blocks_availability(): void
    {
        $resource = Resource::factory()->create(['capacity' => 5]);
        $tomorrow = Carbon::now()->addDay()->toDateString();

        BlackoutDate::create([
            'resource_id' => $resource->id,
            'date' => $tomorrow,
            'reason' => 'Maintenance',
        ]);

        $from = Carbon::parse($tomorrow);
        $to = Carbon::parse($tomorrow)->addDay();

        $this->assertFalse($this->service->isAvailable($resource, $from, $to, 1));
    }

    #[Test]
    public function test_capacity_allows_multiple_bookings(): void
    {
        $resource = Resource::factory()->create(['capacity' => 3]);

        $checkIn = Carbon::now()->addDays(15)->toDateString();
        $checkOut = Carbon::now()->addDays(18)->toDateString();

        // Create 2 confirmed bookings (1 guest each)
        Booking::factory()->count(2)->create([
            'resource_id' => $resource->id,
            'status' => BookingStatus::Confirmed,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests_count' => 1,
        ]);

        $from = Carbon::parse($checkIn);
        $to = Carbon::parse($checkOut);

        // 1 more guest should be available (3 capacity − 2 booked = 1 remaining)
        $this->assertTrue($this->service->isAvailable($resource, $from, $to, 1));

        // 2 more guests should NOT be available (only 1 slot left)
        $this->assertFalse($this->service->isAvailable($resource, $from, $to, 2));
    }
}
