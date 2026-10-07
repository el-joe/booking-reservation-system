<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffSchedule;
use Illuminate\Database\Seeder;

class TenantStaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = [
            [
                'name' => 'Ahmed Mohamed',
                'email' => 'ahmed.mohamed@demo.local',
                'role' => 'receptionist',
                'employment_type' => 'full_time',
                'status' => 'active',
                'hire_date' => now()->subYears(2),
            ],
            [
                'name' => 'Sara Ali',
                'email' => 'sara.ali@demo.local',
                'role' => 'manager',
                'employment_type' => 'full_time',
                'status' => 'active',
                'hire_date' => now()->subYears(3),
            ],
            [
                'name' => 'Mohamed Hassan',
                'email' => 'mohamed.hassan@demo.local',
                'role' => 'staff',
                'employment_type' => 'part_time',
                'status' => 'active',
                'hire_date' => now()->subYear(),
            ],
            [
                'name' => 'Fatima Khalid',
                'email' => 'fatima.khalid@demo.local',
                'role' => 'staff',
                'employment_type' => 'full_time',
                'status' => 'active',
                'hire_date' => now()->subMonths(8),
            ],
            [
                'name' => 'Omar Ibrahim',
                'email' => 'omar.ibrahim@demo.local',
                'role' => 'staff',
                'employment_type' => 'contract',
                'status' => 'inactive',
                'hire_date' => now()->subMonths(6),
            ],
        ];

        foreach ($staffMembers as $data) {
            $staff = Staff::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            if ($staff->status === 'active') {
                $this->seedSchedule($staff);
            }
        }
    }

    private function seedSchedule(Staff $staff): void
    {
        // Mon-Fri (days 1-5), 9am-5pm
        foreach (range(1, 5) as $day) {
            StaffSchedule::updateOrCreate(
                ['staff_id' => $staff->id, 'day_of_week' => $day],
                [
                    'staff_id' => $staff->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]
            );
        }
    }
}
