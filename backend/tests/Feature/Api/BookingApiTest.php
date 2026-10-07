<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\ResourceStatus;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Resource;
use PHPUnit\Framework\Attributes\Test;

class BookingApiTest extends ApiTestCase
{
    #[Test]
    public function test_authenticated_customer_can_list_bookings(): void
    {
        $customer = Customer::factory()->create(['password' => bcrypt('password')]);
        Booking::factory()->count(3)->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/bookings');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta']);
    }

    #[Test]
    public function test_customer_can_create_booking(): void
    {
        $customer = Customer::factory()->create(['password' => bcrypt('password')]);
        $resource = Resource::factory()->create([
            'status' => ResourceStatus::Active,
            'booking_type' => BookingType::Hotel,
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/bookings', [
                'resource_id' => $resource->id,
                'check_in' => now()->addDays(2)->toDateString(),
                'check_out' => now()->addDays(5)->toDateString(),
                'guests_count' => 2,
                'notes' => 'Test booking',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'reference_number', 'status']);

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $customer->id,
            'resource_id' => $resource->id,
            'status' => BookingStatus::Pending->value,
        ]);
    }

    #[Test]
    public function test_unauthenticated_returns_401(): void
    {
        $response = $this->getJson('/api/v1/bookings');

        $response->assertStatus(401);
    }
}
