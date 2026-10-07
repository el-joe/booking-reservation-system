<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 29.00,
                'billing_cycle' => 'monthly',
                'max_bookings' => 100,
                'max_resources' => 5,
                'max_staff' => 3,
                'features' => [
                    'has_api' => false,
                    'has_erp' => false,
                    'has_custom_domain' => false,
                    'has_whatsapp' => false,
                    'has_channel_manager' => false,
                ],
                'is_active' => true,
                'tagline' => 'Perfect for getting started',
                'description' => 'Great for small businesses and solo operators just launching their booking presence.',
                'highlight_features' => [
                    'Up to 100 bookings/month',
                    '5 resources',
                    '3 staff accounts',
                    'Email notifications',
                    'Basic analytics',
                ],
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Growth',
                'slug' => 'growth',
                'price' => 79.00,
                'billing_cycle' => 'monthly',
                'max_bookings' => 500,
                'max_resources' => 20,
                'max_staff' => 10,
                'features' => [
                    'has_api' => true,
                    'has_erp' => false,
                    'has_custom_domain' => true,
                    'has_whatsapp' => true,
                    'has_channel_manager' => false,
                ],
                'is_active' => true,
                'tagline' => 'Most popular for growing teams',
                'description' => 'Everything you need to scale your bookings and delight more customers.',
                'highlight_features' => [
                    'Up to 500 bookings/month',
                    '20 resources',
                    '10 staff accounts',
                    'API access',
                    'WhatsApp notifications',
                    'Custom domain',
                    'Priority support',
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 199.00,
                'billing_cycle' => 'monthly',
                'max_bookings' => 0,
                'max_resources' => 0,
                'max_staff' => 0,
                'features' => [
                    'has_api' => true,
                    'has_erp' => true,
                    'has_custom_domain' => true,
                    'has_whatsapp' => true,
                    'has_channel_manager' => true,
                ],
                'is_active' => true,
                'tagline' => 'Unlimited power for large operations',
                'description' => 'Unlimited capacity with full integrations for enterprise-grade booking operations.',
                'highlight_features' => [
                    'Unlimited bookings',
                    'Unlimited resources',
                    'Unlimited staff',
                    'Full API access',
                    'ERP integration',
                    'Channel manager',
                    'Dedicated account manager',
                    'SLA guarantee',
                ],
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
