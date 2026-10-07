<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\TenantBookingSeeder;
use Database\Seeders\TenantCustomerSeeder;
use Database\Seeders\TenantNotificationTemplateSeeder;
use Database\Seeders\TenantResourceSeeder;
use Database\Seeders\TenantStaffSeeder;
use Illuminate\Console\Command;

class DemoSeed extends Command
{
    protected $signature = 'demo:seed';

    protected $description = 'Seed demo data for the current tenant database';

    public function handle(): int
    {
        $this->info('Seeding tenant resources...');
        (new TenantResourceSeeder)->run();

        $this->info('Seeding customers...');
        (new TenantCustomerSeeder)->run();

        $this->info('Seeding bookings...');
        (new TenantBookingSeeder)->run();

        $this->info('Seeding staff...');
        (new TenantStaffSeeder)->run();

        $this->info('Seeding notification templates...');
        (new TenantNotificationTemplateSeeder)->run();

        $this->info('Done! Demo data seeded successfully.');

        return self::SUCCESS;
    }
}
