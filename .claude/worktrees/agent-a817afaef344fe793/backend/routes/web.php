<?php

declare(strict_types=1);

use App\Http\Controllers\Central\Auth\LoginController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\PlanController;
use App\Http\Controllers\Central\Settings\GlobalSettingsController;
use App\Http\Controllers\Central\SubscriptionController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\Central\Website\CategoryController;
use App\Http\Controllers\Central\Website\HomeController;
use App\Http\Controllers\Central\Website\ListingController;
use App\Http\Controllers\Central\Website\PageController;
use App\Http\Controllers\Central\Website\RegisterController;
use App\Http\Controllers\Central\Website\SearchController;
use App\Http\Middleware\Central\AuthMiddleware;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Central Public Website Routes (Phase 14 — controllers TBD by Agent 2)
// ---------------------------------------------------------------------------

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/listing/{slug}', [ListingController::class, 'show'])->name('listing.show');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Must be last among public routes to avoid swallowing other named paths.
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');

// ---------------------------------------------------------------------------
// Central Admin — Authentication (guest)
// ---------------------------------------------------------------------------

Route::prefix('central')->name('central.')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ---------------------------------------------------------------------------
// Central Admin — Protected routes
// ---------------------------------------------------------------------------

Route::prefix('central')->name('central.')->middleware(AuthMiddleware::class)->group(function (): void {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tenants — resource + custom member actions
    Route::resource('tenants', TenantController::class);
    Route::post('/tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('/tenants/{tenant}/reactivate', [TenantController::class, 'reactivate'])->name('tenants.reactivate');
    Route::post('/tenants/{tenant}/impersonate', [TenantController::class, 'impersonate'])->name('tenants.impersonate');

    // Plans — full resource
    Route::resource('plans', PlanController::class);

    // Subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function (): void {
        Route::get('/', [GlobalSettingsController::class, 'index'])->name('index');
        Route::put('/', [GlobalSettingsController::class, 'update'])->name('update');

        Route::get('/branding', [GlobalSettingsController::class, 'branding'])->name('branding');
        Route::put('/branding', [GlobalSettingsController::class, 'updateBranding'])->name('branding.update');

        Route::get('/maintenance', [GlobalSettingsController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance/toggle', [GlobalSettingsController::class, 'toggleMaintenance'])->name('maintenance.toggle');

        Route::get('/audit-log', [GlobalSettingsController::class, 'auditLog'])->name('audit-log');
    });
});
