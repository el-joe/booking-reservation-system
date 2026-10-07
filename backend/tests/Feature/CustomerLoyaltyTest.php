<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Customer;
use App\Services\Tenant\Customer\CustomerService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomerLoyaltyTest extends TestCase
{
    use DatabaseTransactions;

    private CustomerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CustomerService::class);
    }

    #[Test]
    public function test_loyalty_points_can_be_added(): void
    {
        $customer = Customer::factory()->create(['loyalty_points' => 0]);

        $this->service->addLoyaltyPoints($customer, 100);
        $customer->refresh();

        $this->assertEquals(100, $customer->loyalty_points);

        $this->assertDatabaseHas('loyalty_transactions', [
            'customer_id' => $customer->id,
            'type' => 'earn',
            'points' => 100,
        ]);
    }

    #[Test]
    public function test_loyalty_points_can_be_redeemed(): void
    {
        $customer = Customer::factory()->create(['loyalty_points' => 500]);

        $result = $this->service->redeemLoyaltyPoints($customer, 200);
        $customer->refresh();

        $this->assertTrue($result);
        $this->assertEquals(300, $customer->loyalty_points);

        $this->assertDatabaseHas('loyalty_transactions', [
            'customer_id' => $customer->id,
            'type' => 'redeem',
            'points' => -200,
        ]);
    }

    #[Test]
    public function test_cannot_redeem_more_than_balance(): void
    {
        $customer = Customer::factory()->create(['loyalty_points' => 50]);

        $result = $this->service->redeemLoyaltyPoints($customer, 100);
        $customer->refresh();

        $this->assertFalse($result);
        $this->assertEquals(50, $customer->loyalty_points);
    }
}
