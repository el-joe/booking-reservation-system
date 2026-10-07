<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Accounting\AccountController as AccountingAccountController;
use App\Http\Controllers\Tenant\Accounting\JournalController;
use App\Http\Controllers\Tenant\Accounting\ReportController as AccountingReportController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\Auth\PasswordResetController;
use App\Http\Controllers\Tenant\BookingController;
use App\Http\Controllers\Tenant\Customer\LeadController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\DocumentController;
use App\Http\Controllers\Tenant\HR\PayrollController;
use App\Http\Controllers\Tenant\HR\PayslipController;
use App\Http\Controllers\Tenant\HR\PerformanceController;
use App\Http\Controllers\Tenant\HR\SalaryController;
use App\Http\Controllers\Tenant\Integration\WebhookController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\Operations\ChecklistController;
use App\Http\Controllers\Tenant\Operations\MaintenanceController;
use App\Http\Controllers\Tenant\Procurement\PurchaseOrderController;
use App\Http\Controllers\Tenant\Procurement\PurchaseRequestController;
use App\Http\Controllers\Tenant\Procurement\SupplierController;
use App\Http\Controllers\Tenant\RefundController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\Resource\AvailabilityController;
use App\Http\Controllers\Tenant\Resource\MediaController;
use App\Http\Controllers\Tenant\Resource\PricingController;
use App\Http\Controllers\Tenant\ResourceController;
use App\Http\Controllers\Tenant\ReviewController;
use App\Http\Controllers\Tenant\Settings\BookingSettingsController;
use App\Http\Controllers\Tenant\Settings\BrandingController;
use App\Http\Controllers\Tenant\Settings\GeneralSettingsController;
use App\Http\Controllers\Tenant\Settings\NotificationSettingsController;
use App\Http\Controllers\Tenant\Settings\PaymentSettingsController;
use App\Http\Controllers\Tenant\Staff\AttendanceController;
use App\Http\Controllers\Tenant\Staff\LeaveController;
use App\Http\Controllers\Tenant\Staff\ScheduleController;
use App\Http\Controllers\Tenant\StaffController;
use App\Http\Controllers\Tenant\TransactionController;
use App\Http\Controllers\Tenant\Website\Auth\CustomerAuthController;
use App\Http\Controllers\Tenant\Website\BookingFlowController;
use App\Http\Controllers\Tenant\Website\CustomerAccountController;
use App\Http\Controllers\Tenant\Website\HomeController;
use App\Http\Controllers\Tenant\Website\ListingController;
use App\Http\Controllers\Tenant\Website\SearchController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function (): void {

    // Public website
    Route::get('/', [HomeController::class, 'index'])->name('tenant.home');
    Route::get('/search', [SearchController::class, 'index'])->name('tenant.search');
    Route::get('/listing/{resource:slug}', [ListingController::class, 'show'])->name('tenant.listing');
    Route::get('/listing/{resource}/price-breakdown', [ListingController::class, 'getPriceBreakdown'])->name('tenant.listing.price');

    // Customer auth
    Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('tenant.customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login']);
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('tenant.customer.logout');
    Route::get('/customer/register', [CustomerAuthController::class, 'showRegisterForm'])->name('tenant.customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register']);

    // Booking flow (requires customer auth)
    Route::middleware('auth:customer')->group(function (): void {
        Route::get('/book/{resource}/step1', [BookingFlowController::class, 'step1'])->name('tenant.book.step1');
        Route::post('/book/step2', [BookingFlowController::class, 'step2'])->name('tenant.book.step2');
        Route::post('/book/step3', [BookingFlowController::class, 'step3'])->name('tenant.book.step3');
        Route::post('/book/step4', [BookingFlowController::class, 'step4'])->name('tenant.book.step4');
        Route::post('/book/step5', [BookingFlowController::class, 'step5'])->name('tenant.book.step5');
        Route::post('/book/confirm', [BookingFlowController::class, 'confirmBooking'])->name('tenant.book.confirm');
        Route::get('/book/confirmation/{booking}', [BookingFlowController::class, 'step6'])->name('tenant.book.step6');

        // Customer account
        Route::get('/account/bookings', [CustomerAccountController::class, 'myBookings'])->name('tenant.account.bookings');
        Route::get('/account/bookings/{booking}', [CustomerAccountController::class, 'bookingDetail'])->name('tenant.account.booking-detail');
        Route::post('/account/bookings/{booking}/cancel', [CustomerAccountController::class, 'cancelBooking'])->name('tenant.account.cancel-booking');
        Route::get('/account/profile', [CustomerAccountController::class, 'profile'])->name('tenant.account.profile');
        Route::post('/account/profile', [CustomerAccountController::class, 'updateProfile'])->name('tenant.account.profile.update');
        Route::get('/account/wishlist', [CustomerAccountController::class, 'wishlist'])->name('tenant.account.wishlist');
        Route::get('/account/reviews', [CustomerAccountController::class, 'myReviews'])->name('tenant.account.reviews');
    });

    // Tenant Auth — Guest
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [LoginController::class, 'login'])->name('tenant.login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('tenant.logout');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('tenant.password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('tenant.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('tenant.password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('tenant.password.update');

    // Tenant Admin — Protected routes
    Route::middleware('auth')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/profile', fn () => 'Profile')->name('tenant.profile');

        // Bookings
        Route::resource('bookings', BookingController::class)->names('tenant.bookings');
        Route::post('bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('tenant.bookings.confirm');
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('tenant.bookings.cancel');
        Route::post('bookings/{booking}/checkin', [BookingController::class, 'checkin'])->name('tenant.bookings.checkin');
        Route::post('bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('tenant.bookings.checkout');
        Route::post('bookings/{booking}/noshow', [BookingController::class, 'noshow'])->name('tenant.bookings.noshow');
        Route::get('bookings-pending', [BookingController::class, 'pending'])->name('tenant.bookings.pending');

        // Resources
        Route::resource('resources', ResourceController::class)->names('tenant.resources');
        Route::prefix('resources/{resource}')->name('tenant.resources.')->group(function (): void {
            Route::get('availability', [AvailabilityController::class, 'index'])->name('availability');
            Route::post('availability', [AvailabilityController::class, 'update'])->name('availability.update');
            Route::post('availability/block', [AvailabilityController::class, 'blockDates'])->name('availability.block');
            Route::get('availability/calendar-data', [AvailabilityController::class, 'getCalendarData'])->name('availability.calendar-data');
            Route::post('media', [MediaController::class, 'store'])->name('media.store');
            Route::post('media/reorder', [MediaController::class, 'reorder'])->name('media.reorder');
            Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
            Route::post('media/{media}/cover', [MediaController::class, 'setCover'])->name('media.cover');
            Route::get('pricing', [PricingController::class, 'index'])->name('pricing');
            Route::post('pricing', [PricingController::class, 'store'])->name('pricing.store');
            Route::put('pricing/{rule}', [PricingController::class, 'update'])->name('pricing.update');
            Route::delete('pricing/{rule}', [PricingController::class, 'destroy'])->name('pricing.destroy');
        });

        // Customers CRM
        Route::resource('customers', CustomerController::class)->names('tenant.customers');
        Route::post('customers/{customer}/blacklist', [CustomerController::class, 'blacklist'])->name('tenant.customers.blacklist');
        Route::post('customers/{customer}/remove-blacklist', [CustomerController::class, 'removeBlacklist'])->name('tenant.customers.remove-blacklist');
        Route::post('customers/{customer}/notes', [CustomerController::class, 'addNote'])->name('tenant.customers.notes.store');
        Route::get('customers-blacklisted', [CustomerController::class, 'blacklistView'])->name('tenant.customers.blacklist.view');

        // Leads
        Route::resource('leads', LeadController::class)->names('tenant.leads');
        Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('tenant.leads.convert');

        // Staff
        Route::resource('staff', StaffController::class)->names('tenant.staff');
        Route::post('staff/{staff}/clock-in', [StaffController::class, 'clockIn'])->name('tenant.staff.clock-in');
        Route::post('staff/{staff}/clock-out', [StaffController::class, 'clockOut'])->name('tenant.staff.clock-out');
        Route::get('staff/{staff}/schedule', [ScheduleController::class, 'index'])->name('tenant.staff.schedule');
        Route::post('staff/{staff}/schedule', [ScheduleController::class, 'update'])->name('tenant.staff.schedule.update');

        // Leave Requests
        Route::get('leave', [LeaveController::class, 'index'])->name('tenant.leave.index');
        Route::post('leave', [LeaveController::class, 'store'])->name('tenant.leave.store');
        Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])->name('tenant.leave.approve');
        Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])->name('tenant.leave.reject');

        // Attendance
        Route::get('attendance', [AttendanceController::class, 'index'])->name('tenant.attendance.index');
        Route::get('staff/{staff}/attendance-report', [AttendanceController::class, 'report'])->name('tenant.staff.attendance-report');

        // Finance — Transactions
        Route::resource('transactions', TransactionController::class)
            ->only(['index', 'show'])
            ->names('tenant.transactions');

        // Finance — Invoices
        Route::resource('invoices', InvoiceController::class)
            ->only(['index', 'show'])
            ->names('tenant.invoices');
        Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('tenant.invoices.send');
        Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('tenant.invoices.download');
        Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('tenant.invoices.mark-paid');

        // Finance — Refunds
        Route::resource('refunds', RefundController::class)
            ->only(['index', 'store'])
            ->names('tenant.refunds');

        // Reports
        Route::get('/reports', fn () => 'Reports')->name('tenant.reports.index');
        Route::prefix('reports')->name('tenant.reports.')->group(function (): void {
            Route::get('bookings', [ReportController::class, 'bookings'])->name('bookings');
            Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('occupancy', [ReportController::class, 'occupancy'])->name('occupancy');
            Route::get('customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('cancellations', [ReportController::class, 'cancellations'])->name('cancellations');
            Route::get('sources', [ReportController::class, 'sources'])->name('sources');
            Route::get('export', [ReportController::class, 'export'])->name('export');
        });

        // Notifications
        Route::prefix('notifications')->name('tenant.notifications.')->group(function (): void {
            Route::get('templates', [NotificationController::class, 'templates'])->name('templates');
            Route::get('templates/create', [NotificationController::class, 'createTemplate'])->name('templates.create');
            Route::post('templates', [NotificationController::class, 'storeTemplate'])->name('templates.store');
            Route::get('templates/{template}/edit', [NotificationController::class, 'editTemplate'])->name('templates.edit');
            Route::put('templates/{template}', [NotificationController::class, 'updateTemplate'])->name('templates.update');
            Route::get('logs', [NotificationController::class, 'logs'])->name('logs');
            Route::post('test-send', [NotificationController::class, 'testSend'])->name('test-send');
            Route::get('settings', [NotificationController::class, 'settings'])->name('settings');
        });

        // Settings
        Route::get('/settings', fn () => 'Settings')->name('tenant.settings.index');

        // HR & Payroll
        Route::prefix('hr')->name('tenant.hr.')->group(function (): void {
            Route::resource('payroll', PayrollController::class)
                ->only(['index', 'create', 'store', 'show'])
                ->names('payroll');
            Route::post('payroll/{run}/mark-paid', [PayrollController::class, 'markPaid'])
                ->name('payroll.mark-paid');
            Route::get('payslips/{payslip}', [PayslipController::class, 'show'])
                ->name('payslips.show');
            Route::get('payslips/{payslip}/download', [PayslipController::class, 'download'])
                ->name('payslips.download');
            Route::get('staff/{staff}/salary', [SalaryController::class, 'index'])
                ->name('salary.index');
            Route::get('staff/{staff}/salary/create', [SalaryController::class, 'create'])
                ->name('salary.create');
            Route::post('staff/{staff}/salary', [SalaryController::class, 'store'])
                ->name('salary.store');
            Route::get('salary/{structure}/edit', [SalaryController::class, 'edit'])
                ->name('salary.edit');
            Route::put('salary/{structure}', [SalaryController::class, 'update'])
                ->name('salary.update');
            Route::resource('performance', PerformanceController::class)
                ->names('performance');
            Route::post('performance/{review}/acknowledge', [PerformanceController::class, 'acknowledge'])
                ->name('performance.acknowledge');
        });

        // Operations
        Route::prefix('operations')->name('tenant.operations.')->group(function (): void {
            Route::get('schedule', [ChecklistController::class, 'masterSchedule'])->name('schedule');
            Route::resource('checklists', ChecklistController::class)->names('checklists');
            Route::resource('maintenance', MaintenanceController::class)->names('maintenance');
            Route::post('maintenance/{schedule}/complete', [MaintenanceController::class, 'markComplete'])->name('maintenance.complete');
        });

        // Procurement
        Route::prefix('procurement')->name('tenant.procurement.')->group(function (): void {
            Route::resource('suppliers', SupplierController::class)->names('suppliers');
            Route::resource('purchase-orders', PurchaseOrderController::class)->names('purchase-orders');
            Route::get('purchase-requests', [PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::post('purchase-requests', [PurchaseRequestController::class, 'store'])->name('purchase-requests.store');
            Route::post('purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
            Route::post('purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
        });

        // Reviews
        Route::prefix('reviews')->name('tenant.reviews.')->group(function (): void {
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            Route::get('{review}', [ReviewController::class, 'show'])->name('show');
            Route::post('{review}/approve', [ReviewController::class, 'approve'])->name('approve');
            Route::post('{review}/reject', [ReviewController::class, 'reject'])->name('reject');
            Route::post('{review}/reply', [ReviewController::class, 'reply'])->name('reply');
            Route::post('{review}/flag', [ReviewController::class, 'flag'])->name('flag');
        });

        // Settings
        Route::prefix('settings')->name('tenant.settings.')->group(function (): void {
            Route::get('/', fn () => redirect()->route('tenant.settings.general'))->name('index');
            Route::get('general', [GeneralSettingsController::class, 'index'])->name('general');
            Route::post('general', [GeneralSettingsController::class, 'update'])->name('general.update');
            Route::get('booking', [BookingSettingsController::class, 'index'])->name('booking');
            Route::post('booking', [BookingSettingsController::class, 'update'])->name('booking.update');
            Route::get('branding', [BrandingController::class, 'index'])->name('branding');
            Route::post('branding', [BrandingController::class, 'update'])->name('branding.update');
            Route::get('payment', [PaymentSettingsController::class, 'index'])->name('payment');
            Route::post('payment', [PaymentSettingsController::class, 'update'])->name('payment.update');
            Route::get('notifications', [NotificationSettingsController::class, 'index'])->name('notifications');
            Route::post('notifications', [NotificationSettingsController::class, 'update'])->name('notifications.update');
        });

        // Integrations
        Route::prefix('integrations')->name('tenant.integrations.')->group(function (): void {
            Route::resource('webhooks', WebhookController::class)->names('webhooks');
        });

        // Documents
        Route::resource('documents', DocumentController::class)
            ->only(['index', 'store', 'show', 'destroy'])
            ->names('tenant.documents');
        Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('tenant.documents.download');

        // Accounting & Finance
        Route::prefix('accounting')->name('tenant.accounting.')->group(function (): void {
            Route::resource('accounts', AccountingAccountController::class)->names('accounts');
            Route::get('journal', [JournalController::class, 'index'])->name('journal.index');
            Route::get('journal/create', [JournalController::class, 'create'])->name('journal.create');
            Route::post('journal', [JournalController::class, 'store'])->name('journal.store');
            Route::get('journal/{entry}', [JournalController::class, 'show'])->name('journal.show');
            Route::post('journal/{entry}/void', [JournalController::class, 'void'])->name('journal.void');
            Route::get('reports/trial-balance', [AccountingReportController::class, 'trialBalance'])->name('reports.trial-balance');
            Route::get('reports/balance-sheet', [AccountingReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
            Route::get('reports/profit-loss', [AccountingReportController::class, 'profitLoss'])->name('reports.profit-loss');
            Route::get('reports/cash-flow', [AccountingReportController::class, 'cashFlow'])->name('reports.cash-flow');
        });

        // Marketing
        Route::prefix('marketing')->name('tenant.marketing.')->group(function (): void {
            Route::resource('campaigns', \App\Http\Controllers\Tenant\Marketing\CampaignController::class)->names('campaigns');
            Route::resource('referrals', \App\Http\Controllers\Tenant\Marketing\ReferralProgramController::class)->names('referrals');
            Route::resource('affiliates', \App\Http\Controllers\Tenant\Marketing\AffiliateController::class)->names('affiliates');
        });

        // Channel Management
        Route::prefix('channels')->name('tenant.channels.')->group(function (): void {
            Route::resource('ota', \App\Http\Controllers\Tenant\Channel\OtaChannelController::class)->names('ota');
            Route::resource('reservations', \App\Http\Controllers\Tenant\Channel\ChannelReservationController::class)
                ->only(['index', 'show'])
                ->names('reservations');
        });

        // Business Intelligence
        Route::prefix('bi')->name('tenant.bi.')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\Tenant\BI\DashboardController::class, 'index'])->name('index');
            Route::get('trends', [\App\Http\Controllers\Tenant\BI\DashboardController::class, 'trends'])->name('trends');
            Route::get('forecast', [\App\Http\Controllers\Tenant\BI\DashboardController::class, 'forecast'])->name('forecast');
            Route::get('customer-behavior', [\App\Http\Controllers\Tenant\BI\DashboardController::class, 'customerBehavior'])->name('customer-behavior');
            Route::get('churn', [\App\Http\Controllers\Tenant\BI\DashboardController::class, 'churnAnalysis'])->name('churn');
        });
    });
});
