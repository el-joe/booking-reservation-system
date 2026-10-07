<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeBetween('+1 days', '+30 days');
        $checkOut = (clone $checkIn)->modify('+'.$this->faker->numberBetween(1, 7).' days');
        $totalAmount = $this->faker->randomFloat(2, 50, 1000);
        $status = $this->faker->randomElement(BookingStatus::cases());

        return [
            'reference_number' => 'BK-'.date('Y').'-'.strtoupper(Str::random(6)),
            'customer_id' => Customer::factory(),
            'resource_id' => Resource::factory(),
            'booking_type' => $this->faker->randomElement(BookingType::cases()),
            'status' => $status,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests_count' => $this->faker->numberBetween(1, 6),
            'total_amount' => $totalAmount,
            'paid_amount' => in_array($status, [BookingStatus::Confirmed, BookingStatus::Completed]) ? $totalAmount : 0,
            'source' => $this->faker->randomElement(['website', 'walk_in', 'phone', 'ota', 'api']),
            'notes' => $this->faker->optional()->sentence(),
            'confirmed_at' => in_array($status, [BookingStatus::Confirmed, BookingStatus::Completed]) ? now() : null,
            'cancelled_at' => $status === BookingStatus::Cancelled ? now() : null,
            'cancellation_reason' => $status === BookingStatus::Cancelled ? $this->faker->sentence() : null,
        ];
    }
}
