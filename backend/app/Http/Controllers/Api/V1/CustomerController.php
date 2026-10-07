<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        return response()->json(new CustomerResource($request->user()));
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'address' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'gender' => ['sometimes', 'nullable', 'in:male,female,other'],
            'date_of_birth' => ['sometimes', 'nullable', 'date', 'before:today'],
        ]);

        $request->user()->update($validated);

        return response()->json(new CustomerResource($request->user()->fresh()));
    }

    public function loyaltyPoints(Request $request): JsonResponse
    {
        $customer = $request->user();

        $transactions = $customer->loyaltyTransactions()
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'balance' => $customer->loyalty_points,
            'transactions' => $transactions->map(fn ($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'points' => $t->points,
                'balance_after' => $t->balance_after,
                'note' => $t->note,
                'created_at' => $t->created_at?->toISOString(),
            ]),
        ]);
    }
}
