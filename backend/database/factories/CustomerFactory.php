<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $isBlacklisted = $this->faker->boolean(5);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->optional()->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->optional()->randomElement(['male', 'female', 'other']),
            'address' => $this->faker->optional()->address(),
            'notes' => $this->faker->optional()->sentence(),
            'loyalty_points' => $this->faker->numberBetween(0, 500),
            'is_blacklisted' => $isBlacklisted,
            'blacklist_reason' => $isBlacklisted ? $this->faker->sentence() : null,
            'tags' => $this->faker->optional()->randomElements(['vip', 'regular', 'new', 'corporate'], 2),
        ];
    }
}
