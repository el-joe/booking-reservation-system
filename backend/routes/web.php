<?php

declare(strict_types=1);

use App\Http\Controllers\Central\Auth\LoginController;
use App\Http\Controllers\Central\BlogAdminController;
use App\Http\Controllers\Central\ContactAdminController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\FaqAdminController;
use App\Http\Controllers\Central\PlanController;
use App\Http\Controllers\Central\ProfileController;
use App\Http\Controllers\Central\Settings\GlobalSettingsController;
use App\Http\Controllers\Central\SubscriptionController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\Central\Website\BlogController;
use App\Http\Controllers\Central\Website\CategoryController;
use App\Http\Controllers\Central\Website\ContactController;
use App\Http\Controllers\Central\Website\FaqController;
use App\Http\Controllers\Central\Website\HomeController;
use App\Http\Controllers\Central\Website\ListingController;
use App\Http\Controllers\Central\Website\PageController;
use App\Http\Controllers\Central\Website\PricingController;
use App\Http\Controllers\Central\Website\RegisterController;
use App\Http\Controllers\Central\Website\SearchController;
use App\Http\Controllers\Central\Website\SitemapController;
use App\Http\Controllers\Central\WebsiteSettingsController;
use App\Http\Middleware\Central\AuthMiddleware;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Central Public Website Routes (Phase 14 — controllers TBD by Agent 2)
// ---------------------------------------------------------------------------

Route::get('/', [HomeController::class, 'index'])->name('central.website.home');
Route::get('/search', [SearchController::class, 'index'])->name('central.website.search');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('central.website.category');
Route::get('/listing/{slug}', [ListingController::class, 'show'])->name('central.website.listing');
Route::get('/register', [RegisterController::class, 'index'])->name('central.website.register');
Route::post('/register', [RegisterController::class, 'store'])->name('central.website.register.store');
Route::get('/check-subdomain', [RegisterController::class, 'checkSubdomain'])->name('central.website.check-subdomain');

Route::get('/pricing', [PricingController::class, 'index'])->name('central.website.pricing');
Route::get('/blog', [BlogController::class, 'index'])->name('central.website.blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('central.website.blog.show');
Route::get('/faq', [FaqController::class, 'index'])->name('central.website.faq');
Route::get('/contact', [ContactController::class, 'index'])->name('central.website.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('central.website.contact.store');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('central.website.sitemap');
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nDisallow: /central/\nSitemap: ".url('/sitemap.xml');

    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('central.website.robots');

// Must be last among public routes to avoid swallowing other named paths.
Route::get('/{slug}', [PageController::class, 'show'])->name('central.website.page');

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

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

    // FAQ Admin
    Route::resource('faq', FaqAdminController::class);

    // Blog Admin
    Route::resource('blog', BlogAdminController::class)->names([
        'index' => 'blog.index',
        'create' => 'blog.create',
        'store' => 'blog.store',
        'show' => 'blog.show',
        'edit' => 'blog.edit',
        'update' => 'blog.update',
        'destroy' => 'blog.destroy',
    ]);

    // Contact Messages
    Route::get('contacts', [ContactAdminController::class, 'index'])->name('contact.index');
    Route::get('contacts/{contactMessage}', [ContactAdminController::class, 'show'])->name('contact.show');
    Route::post('contacts/{contactMessage}/replied', [ContactAdminController::class, 'markReplied'])->name('contact.mark-replied');
    Route::delete('contacts/{contactMessage}', [ContactAdminController::class, 'destroy'])->name('contact.destroy');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function (): void {
        Route::get('/', [GlobalSettingsController::class, 'index'])->name('index');
        Route::put('/', [GlobalSettingsController::class, 'update'])->name('update');

        Route::get('/branding', [GlobalSettingsController::class, 'branding'])->name('branding');
        Route::put('/branding', [GlobalSettingsController::class, 'updateBranding'])->name('branding.update');

        Route::get('/maintenance', [GlobalSettingsController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance/toggle', [GlobalSettingsController::class, 'toggleMaintenance'])->name('maintenance.toggle');

        Route::get('/audit-log', [GlobalSettingsController::class, 'auditLog'])->name('audit-log');

        Route::get('/website', [WebsiteSettingsController::class, 'index'])->name('website');
        Route::put('/website', [WebsiteSettingsController::class, 'update'])->name('website.update');
    });
});
