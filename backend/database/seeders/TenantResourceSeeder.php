<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BookingType;
use App\Enums\ResourceStatus;
use App\Enums\ResourceType;
use App\Models\Resource;
use App\Models\TimeSlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            [
                'name' => 'Deluxe Room',
                'booking_type' => BookingType::Hotel,
                'resource_type' => ResourceType::Room,
                'capacity' => 2,
                'base_price' => 150.00,
                'price_unit' => 'per_night',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Suite',
                'booking_type' => BookingType::Hotel,
                'resource_type' => ResourceType::Room,
                'capacity' => 4,
                'base_price' => 300.00,
                'price_unit' => 'per_night',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Standard Room',
                'booking_type' => BookingType::Hotel,
                'resource_type' => ResourceType::Room,
                'capacity' => 2,
                'base_price' => 90.00,
                'price_unit' => 'per_night',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Penthouse Suite',
                'booking_type' => BookingType::Hotel,
                'resource_type' => ResourceType::Room,
                'capacity' => 6,
                'base_price' => 500.00,
                'price_unit' => 'per_night',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Conference Room A',
                'booking_type' => BookingType::MeetingRoom,
                'resource_type' => ResourceType::Court,
                'capacity' => 20,
                'base_price' => 200.00,
                'price_unit' => 'per_hour',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Massage Suite',
                'booking_type' => BookingType::SalonSpa,
                'resource_type' => ResourceType::Slot,
                'capacity' => 1,
                'base_price' => 80.00,
                'price_unit' => 'per_hour',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Tennis Court',
                'booking_type' => BookingType::SportsCourt,
                'resource_type' => ResourceType::Court,
                'capacity' => 4,
                'base_price' => 40.00,
                'price_unit' => 'per_hour',
                'status' => ResourceStatus::Active,
            ],
            [
                'name' => 'Restaurant Table for 2',
                'booking_type' => BookingType::Restaurant,
                'resource_type' => ResourceType::Table,
                'capacity' => 2,
                'base_price' => 0.00,
                'price_unit' => 'per_person',
                'status' => ResourceStatus::Active,
            ],
        ];

        foreach ($resources as $data) {
            $resource = Resource::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                array_merge($data, ['slug' => Str::slug($data['name'])])
            );

            $this->seedTimeSlots($resource);
        }
    }

    private function seedTimeSlots(Resource $resource): void
    {
        // Mon-Fri 9am-5pm (days 1-5)
        foreach (range(1, 5) as $day) {
            TimeSlot::updateOrCreate(
                ['resource_id' => $resource->id, 'day_of_week' => $day, 'start_time' => '09:00:00'],
                [
                    'resource_id' => $resource->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'capacity' => $resource->capacity,
                    'duration_minutes' => 480,
                    'is_active' => true,
                ]
            );

            // Mon-Fri 5pm-10pm
            TimeSlot::updateOrCreate(
                ['resource_id' => $resource->id, 'day_of_week' => $day, 'start_time' => '17:00:00'],
                [
                    'resource_id' => $resource->id,
                    'day_of_week' => $day,
                    'start_time' => '17:00:00',
                    'end_time' => '22:00:00',
                    'capacity' => $resource->capacity,
                    'duration_minutes' => 300,
                    'is_active' => true,
                ]
            );
        }

        // Sat-Sun 9am-10pm (days 6,0)
        foreach ([6, 0] as $day) {
            TimeSlot::updateOrCreate(
                ['resource_id' => $resource->id, 'day_of_week' => $day, 'start_time' => '09:00:00'],
                [
                    'resource_id' => $resource->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '22:00:00',
                    'capacity' => $resource->capacity,
                    'duration_minutes' => 780,
                    'is_active' => true,
                ]
            );
        }
    }
}
