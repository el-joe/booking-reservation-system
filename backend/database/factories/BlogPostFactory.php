<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 9999),
            'excerpt' => fake()->sentences(2, true),
            'body' => implode("\n\n", fake()->paragraphs(4)),
            'author_name' => 'BookEase Team',
            'cover_image' => null,
            'category' => fake()->randomElement(['Business Tips', 'Getting Started', 'Industry Insights', 'Product Updates']),
            'tags' => fake()->randomElements(['booking', 'scheduling', 'saas', 'business', 'growth', 'automation'], 3),
            'status' => 'draft',
            'published_at' => null,
            'seo_title' => null,
            'seo_description' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now()->subDays(fake()->numberBetween(1, 90)),
        ]);
    }

    public function bookingTips(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => '10 Ways to Grow Your Booking Business Online',
            'slug' => '10-ways-to-grow-your-booking-business-online',
            'category' => 'Business Tips',
            'status' => 'published',
            'published_at' => now()->subDays(15),
        ]);
    }

    public function gettingStarted(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'How to Set Up Your First Booking Page in Minutes',
            'slug' => 'how-to-set-up-your-first-booking-page-in-minutes',
            'category' => 'Getting Started',
            'status' => 'published',
            'published_at' => now()->subDays(30),
        ]);
    }

    public function industryInsights(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Why Online Booking Is Essential for Modern Businesses',
            'slug' => 'why-online-booking-is-essential-for-modern-businesses',
            'category' => 'Industry Insights',
            'status' => 'published',
            'published_at' => now()->subDays(45),
        ]);
    }

    public function revenueMaximizing(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Maximizing Revenue with Smart Scheduling',
            'slug' => 'maximizing-revenue-with-smart-scheduling',
            'category' => 'Business Tips',
            'status' => 'published',
            'published_at' => now()->subDays(60),
        ]);
    }

    public function customerExperience(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Customer Experience: How Seamless Booking Wins Loyalty',
            'slug' => 'customer-experience-how-seamless-booking-wins-loyalty',
            'category' => 'Industry Insights',
            'status' => 'published',
            'published_at' => now()->subDays(75),
        ]);
    }

    public function platformUpdate(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Platform Update: New Features for 2025',
            'slug' => 'platform-update-new-features-for-2025',
            'category' => 'Product Updates',
            'status' => 'published',
            'published_at' => now()->subDays(5),
        ]);
    }
}
