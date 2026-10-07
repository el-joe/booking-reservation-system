<?php

declare(strict_types=1);

namespace App\Services\Tenant\Customer;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\LoyaltyTransaction;

class CustomerService
{
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer->refresh();
    }

    public function blacklist(Customer $customer, string $reason): Customer
    {
        $customer->update([
            'is_blacklisted' => true,
            'blacklist_reason' => $reason,
        ]);

        $this->addNote($customer, "Customer blacklisted. Reason: {$reason}");

        return $customer->refresh();
    }

    public function removeFromBlacklist(Customer $customer): Customer
    {
        $customer->update([
            'is_blacklisted' => false,
            'blacklist_reason' => null,
        ]);

        $this->addNote($customer, 'Customer removed from blacklist.');

        return $customer->refresh();
    }

    public function addLoyaltyPoints(
        Customer $customer,
        int $points,
        ?Booking $booking = null,
        string $note = '',
    ): LoyaltyTransaction {
        $customer->addLoyaltyPoints($points);
        $customer->refresh();

        return LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'booking_id' => $booking?->id,
            'type' => 'earn',
            'points' => $points,
            'balance_after' => $customer->loyalty_points,
            'note' => $note ?: null,
        ]);
    }

    public function redeemLoyaltyPoints(
        Customer $customer,
        int $points,
        ?Booking $booking = null,
    ): bool {
        if ($customer->loyalty_points < $points) {
            return false;
        }

        $customer->deductLoyaltyPoints($points);
        $customer->refresh();

        LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'booking_id' => $booking?->id,
            'type' => 'redeem',
            'points' => -$points,
            'balance_after' => $customer->loyalty_points,
        ]);

        return true;
    }

    public function addNote(Customer $customer, string $note, bool $isPinned = false): CustomerNote
    {
        return CustomerNote::create([
            'customer_id' => $customer->id,
            'staff_id' => auth()->id(),
            'note' => $note,
            'is_pinned' => $isPinned,
        ]);
    }
}
