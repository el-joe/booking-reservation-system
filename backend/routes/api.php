<?php

declare(strict_types=1);

use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ResourceController;
use App\Http\Controllers\Api\V1\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Webhooks (no auth)
Route::post('webhooks/stripe', [StripeWebhookController::class, 'handleWebhook'])->name('webhooks.stripe');

Route::prefix('v1')->group(function (): void {
    // Public routes
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('resources', [ResourceController::class, 'index']);
        Route::get('resources/{resource}', [ResourceController::class, 'show']);
        Route::get('resources/{resource}/availability', [ResourceController::class, 'availability']);
        Route::get('resources/{resource}/slots', [ResourceController::class, 'slots']);
        Route::get('resources/{resource}/reviews', [ReviewController::class, 'index']);

        Route::get('bookings', [BookingController::class, 'index']);
        Route::post('bookings', [BookingController::class, 'store']);
        Route::get('bookings/{booking}', [BookingController::class, 'show']);
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);

        Route::get('customer/profile', [CustomerController::class, 'profile']);
        Route::put('customer/profile', [CustomerController::class, 'updateProfile']);
        Route::get('customer/loyalty-points', [CustomerController::class, 'loyaltyPoints']);

        Route::post('payments/intent', [PaymentController::class, 'createIntent']);
        Route::post('payments/confirm', [PaymentController::class, 'confirm']);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markRead']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllRead']);

        Route::post('reviews', [ReviewController::class, 'store']);
    });
});
