<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Customer;
use PHPUnit\Framework\Attributes\Test;

class AuthApiTest extends ApiTestCase
{
    #[Test]
    public function test_customer_can_register(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'customer' => ['id', 'name', 'email', 'phone'],
            ]);

        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
    }

    #[Test]
    public function test_customer_can_login(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'customer' => ['id', 'name', 'email'],
            ]);
    }

    #[Test]
    public function test_invalid_credentials_return_422(): void
    {
        Customer::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}
