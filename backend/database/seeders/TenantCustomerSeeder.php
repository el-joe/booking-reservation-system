<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class TenantCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::factory(30)->create();

        // Blacklist 3 customers
        $blacklistReasons = [
            'Multiple no-show incidents',
            'Payment fraud attempted',
            'Property damage reported',
        ];

        $customers->take(3)->each(function (Customer $customer, int $index) use ($blacklistReasons): void {
            $customer->update([
                'is_blacklisted' => true,
                'blacklist_reason' => $blacklistReasons[$index],
            ]);
        });

        // Give loyalty points to 10 customers
        $customers->slice(3, 10)->each(function (Customer $customer): void {
            $customer->update([
                'loyalty_points' => rand(100, 1000),
            ]);
        });
    }
}
