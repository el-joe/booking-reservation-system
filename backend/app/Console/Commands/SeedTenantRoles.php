<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedTenantRoles extends Command
{
    protected $signature = 'tenant:seed-roles';

    protected $description = 'Seed default roles and permissions for a tenant database';

    public function handle(): int
    {
        $permissions = [
            'manage-bookings',
            'view-bookings',
            'manage-customers',
            'manage-resources',
            'manage-staff',
            'view-reports',
            'manage-finance',
            'manage-settings',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $roles = [
            'admin' => $permissions,
            'manager' => [
                'manage-bookings',
                'view-bookings',
                'manage-customers',
                'manage-resources',
                'manage-staff',
                'view-reports',
            ],
            'receptionist' => [
                'manage-bookings',
                'view-bookings',
                'manage-customers',
            ],
            'staff' => [
                'view-bookings',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
            $this->info("Role [{$roleName}] seeded with ".count($rolePermissions).' permissions.');
        }

        $this->info('Tenant roles and permissions seeded successfully.');

        return self::SUCCESS;
    }
}
