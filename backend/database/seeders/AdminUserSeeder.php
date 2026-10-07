<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CentralAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        CentralAdmin::updateOrCreate(
            ['email' => 'admin@bookings.app'],
            [
                'name' => 'Platform Admin',
                'email' => 'admin@bookings.app',
                'password' => Hash::make('password'),
            ]
        );
    }
}
