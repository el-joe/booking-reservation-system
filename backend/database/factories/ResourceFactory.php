<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BookingType;
use App\Enums\ResourceStatus;
use App\Enums\ResourceType;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<resource>
 */
class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $bookingType = $this->faker->randomElement(BookingType::cases());
        $name = $this->nameForBookingType($bookingType);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 9999),
            'resource_type' => $this->faker->randomElement(ResourceType::cases()),
            'booking_type' => $bookingType,
            'capacity' => $this->faker->numberBetween(1, 20),
            'description' => $this->faker->optional()->paragraph(),
            'base_price' => $this->faker->randomFloat(2, 20, 500),
            'price_unit' => $this->faker->randomElement(['per_night', 'per_hour', 'per_person', 'per_unit']),
            'status' => $this->faker->randomElement(ResourceStatus::cases()),
            'meta' => null,
        ];
    }

    private function nameForBookingType(BookingType $type): string
    {
        return match ($type->category()) {
            'accommodation' => $this->faker->randomElement(['Deluxe Room', 'Standard Room', 'Suite', 'Ocean View Room', 'Family Room']),
            'travel' => $this->faker->randomElement(['Economy Seat', 'Business Class', 'SUV Rental', 'Compact Car']),
            'dining' => $this->faker->randomElement(['Table for 2', 'Private Dining Room', 'Conference Room A', 'Banquet Hall']),
            'services' => $this->faker->randomElement(['Morning Slot', 'Afternoon Slot', 'Dr. Smith Appointment', 'Massage Session']),
            'entertainment' => $this->faker->randomElement(['Tennis Court A', 'Conference Room B', 'Escape Room Alpha', 'Tour Package']),
            'business' => $this->faker->randomElement(['Meeting Room 1', 'Projector Rental', 'Parking Spot A', 'Storage Unit 5']),
            'online' => $this->faker->randomElement(['Virtual Consultation', 'Webinar Seat', 'Live Stream Session']),
            default => $this->faker->words(2, true),
        };
    }
}
