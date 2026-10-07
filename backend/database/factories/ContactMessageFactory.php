<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(5),
            'message' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(['new', 'read', 'replied']),
            'ip_address' => fake()->ipv4(),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'new',
        ]);
    }
}
