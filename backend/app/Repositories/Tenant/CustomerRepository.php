<?php

declare(strict_types=1);

namespace App\Repositories\Tenant;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomerRepository
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = Customer::query();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_blacklisted']) && $filters['is_blacklisted'] !== '') {
            $query->where('is_blacklisted', (bool) $filters['is_blacklisted']);
        }

        if (! empty($filters['tag'])) {
            $query->whereJsonContains('tags', $filters['tag']);
        }

        if (! empty($filters['has_bookings'])) {
            $query->whereHas('bookings');
        }

        return $query->latest()->paginate(20);
    }

    public function findWithHistory(int $id): Customer
    {
        return Customer::with([
            'bookings.resource',
            'loyaltyTransactions',
            'customerNotes' => fn ($q) => $q->orderByDesc('is_pinned')->latest(),
        ])->findOrFail($id);
    }

    public function getTopSpenders(int $limit = 10): Collection
    {
        return Customer::withSum('bookings as total_spent', 'paid_amount')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();
    }

    public function getSegment(array $criteria): Collection
    {
        $query = Customer::query();

        if (! empty($criteria['min_bookings'])) {
            $query->has('bookings', '>=', (int) $criteria['min_bookings']);
        }

        if (! empty($criteria['min_spent'])) {
            $query->withSum('bookings as total_spent', 'paid_amount')
                ->having('total_spent', '>=', $criteria['min_spent']);
        }

        if (! empty($criteria['tags'])) {
            foreach ($criteria['tags'] as $tag) {
                $query->whereJsonContains('tags', $tag);
            }
        }

        if (isset($criteria['is_blacklisted'])) {
            $query->where('is_blacklisted', $criteria['is_blacklisted']);
        }

        return $query->get();
    }
}
