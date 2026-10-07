<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PlanFeature as PlanFeatureEnum;
use App\Enums\TenantStatus;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\Central\TenantManagementService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CentralTenantManagementTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function test_can_create_plan(): void
    {
        $plan = Plan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'price' => 49.99,
            'billing_cycle' => 'monthly',
            'max_bookings' => 100,
            'max_resources' => 10,
            'max_staff' => 5,
            'features' => [],
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('plans', [
            'name' => 'Starter',
            'slug' => 'starter',
        ]);

        $this->assertNotNull($plan->id);
    }

    #[Test]
    public function test_plan_has_feature_flags(): void
    {
        $plan = Plan::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'price' => 99.99,
            'billing_cycle' => 'monthly',
            'max_bookings' => 500,
            'max_resources' => 50,
            'max_staff' => 20,
            'features' => [PlanFeatureEnum::HasApi->value, PlanFeatureEnum::HasErp->value],
            'is_active' => true,
        ]);

        $this->assertNotNull($plan->features);
        $this->assertContains(PlanFeatureEnum::HasApi->value, $plan->features);
        $this->assertContains(PlanFeatureEnum::HasErp->value, $plan->features);
        $this->assertNotContains('non_existent_feature', $plan->features);
    }

    #[Test]
    public function test_tenant_status_transitions(): void
    {
        Queue::fake();

        $tenant = Tenant::create([
            'id' => 'test-tenant-'.uniqid(),
            'name' => 'Test Business',
            'contact_name' => 'John Doe',
            'contact_email' => 'john@testbusiness.com',
            'business_type' => 'hotel',
            'status' => TenantStatus::Active,
        ]);

        $service = app(TenantManagementService::class);

        $service->suspend($tenant);
        $tenant->refresh();

        $this->assertEquals(TenantStatus::Suspended, $tenant->status);
        $this->assertTrue($tenant->isSuspended());

        $service->reactivate($tenant);
        $tenant->refresh();

        $this->assertEquals(TenantStatus::Active, $tenant->status);
        $this->assertTrue($tenant->isActive());
    }
}
