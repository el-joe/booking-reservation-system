<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Resource;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantBookingSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $resources = Resource::all();

        if ($customers->isEmpty() || $resources->isEmpty()) {
            $this->command?->warn('No customers or resources found. Run TenantResourceSeeder and TenantCustomerSeeder first.');

            return;
        }

        $completedBookings = [];

        // 20 completed bookings in past 30 days
        for ($i = 0; $i < 20; $i++) {
            $checkIn = now()->subDays(rand(1, 30));
            $checkOut = (clone $checkIn)->addDays(rand(1, 3));
            $totalAmount = round(rand(5000, 100000) / 100, 2);

            $booking = Booking::create([
                'reference_number' => 'BK-'.date('Y').'-'.strtoupper(Str::random(6)),
                'customer_id' => $customers->random()->id,
                'resource_id' => $resources->random()->id,
                'booking_type' => $resources->random()->booking_type,
                'status' => BookingStatus::Completed,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => rand(1, 4),
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount,
                'source' => collect(['website', 'walk_in', 'phone', 'ota'])->random(),
                'confirmed_at' => $checkIn->copy()->subDays(2),
            ]);

            Transaction::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'amount' => $totalAmount,
                'currency' => 'USD',
                'type' => TransactionType::Payment,
                'status' => PaymentStatus::Paid,
                'gateway' => 'stripe',
                'notes' => 'Payment for booking '.$booking->reference_number,
            ]);

            Invoice::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'invoice_number' => 'INV-'.date('Y').'-'.strtoupper(Str::random(6)),
                'status' => InvoiceStatus::Paid,
                'subtotal' => $totalAmount,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $totalAmount,
                'due_date' => $checkIn->copy()->subDay(),
                'paid_at' => $checkIn->copy()->subDay(),
            ]);

            $completedBookings[] = $booking;
        }

        // 15 confirmed bookings in next 30 days
        for ($i = 0; $i < 15; $i++) {
            $checkIn = now()->addDays(rand(1, 30));
            $checkOut = (clone $checkIn)->addDays(rand(1, 3));
            $totalAmount = round(rand(5000, 100000) / 100, 2);

            Booking::create([
                'reference_number' => 'BK-'.date('Y').'-'.strtoupper(Str::random(6)),
                'customer_id' => $customers->random()->id,
                'resource_id' => $resources->random()->id,
                'booking_type' => $resources->random()->booking_type,
                'status' => BookingStatus::Confirmed,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => rand(1, 4),
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount,
                'source' => collect(['website', 'walk_in', 'phone', 'ota'])->random(),
                'confirmed_at' => now(),
            ]);
        }

        // 10 pending bookings in next 14 days
        for ($i = 0; $i < 10; $i++) {
            $checkIn = now()->addDays(rand(1, 14));
            $checkOut = (clone $checkIn)->addDays(rand(1, 3));
            $totalAmount = round(rand(5000, 100000) / 100, 2);

            Booking::create([
                'reference_number' => 'BK-'.date('Y').'-'.strtoupper(Str::random(6)),
                'customer_id' => $customers->random()->id,
                'resource_id' => $resources->random()->id,
                'booking_type' => $resources->random()->booking_type,
                'status' => BookingStatus::Pending,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => rand(1, 4),
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'source' => collect(['website', 'walk_in', 'phone', 'ota'])->random(),
            ]);
        }

        // 10 cancelled bookings
        for ($i = 0; $i < 10; $i++) {
            $isPast = rand(0, 1) === 1;
            $checkIn = $isPast ? now()->subDays(rand(1, 30)) : now()->addDays(rand(1, 30));
            $checkOut = (clone $checkIn)->addDays(rand(1, 3));
            $totalAmount = round(rand(5000, 100000) / 100, 2);

            Booking::create([
                'reference_number' => 'BK-'.date('Y').'-'.strtoupper(Str::random(6)),
                'customer_id' => $customers->random()->id,
                'resource_id' => $resources->random()->id,
                'booking_type' => $resources->random()->booking_type,
                'status' => BookingStatus::Cancelled,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => rand(1, 4),
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'source' => collect(['website', 'walk_in', 'phone', 'ota'])->random(),
                'cancelled_at' => now(),
                'cancellation_reason' => collect(['Customer request', 'No availability', 'Payment failed', 'Double booking'])->random(),
            ]);
        }

        // 5 no-show bookings in past 7 days
        for ($i = 0; $i < 5; $i++) {
            $checkIn = now()->subDays(rand(1, 7));
            $checkOut = (clone $checkIn)->addDays(rand(1, 3));
            $totalAmount = round(rand(5000, 100000) / 100, 2);

            Booking::create([
                'reference_number' => 'BK-'.date('Y').'-'.strtoupper(Str::random(6)),
                'customer_id' => $customers->random()->id,
                'resource_id' => $resources->random()->id,
                'booking_type' => $resources->random()->booking_type,
                'status' => BookingStatus::NoShow,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => rand(1, 4),
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'source' => collect(['website', 'walk_in', 'phone', 'ota'])->random(),
                'confirmed_at' => $checkIn->copy()->subDays(2),
            ]);
        }

        // Create 10 reviews for completed bookings
        $reviewBookings = array_slice($completedBookings, 0, 10);
        foreach ($reviewBookings as $booking) {
            Review::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'resource_id' => $booking->resource_id,
                'rating' => rand(3, 5),
                'title' => fake()->sentence(6),
                'body' => fake()->paragraph(),
                'status' => 'published',
            ]);
        }
    }
}
