# Sub-Agent Prompts — Booking Reservation System

> These prompts are designed to be run as independent sub-agents.
> Each prompt is self-contained and references `instructions.md` + `tasks.md` for context.
> Working directory: `/var/www/booking-reservation-system/backend`
> Run prompts in order within each phase, or in parallel where marked **[PARALLEL OK]**.

---

## AGENT-00: Foundation Setup

```
You are working on a Laravel 11 (PHP 8.3) SaaS multi-tenant booking reservation platform.
Read instructions.md in the project root for full architecture context.

Task: Complete Phase 0 — Foundation & Infrastructure from tasks.md.

Steps to execute:
1. Install stancl/tenancy: `composer require stancl/tenancy`
2. Run `php artisan tenancy:install` and publish config
3. Configure tenancy for domain-based isolation in config/tenancy.php
4. Create central database migrations:
   - plans (id, name, slug, price, billing_cycle, max_bookings, max_resources, max_staff, features JSON, is_active)
   - subscriptions (id, tenant_id, plan_id, status, starts_at, ends_at, trial_ends_at)
   - plan_features (id, plan_id, feature_key, feature_value)
5. Create all Enum classes in app/Enums/:
   - BookingStatus (Pending, Confirmed, CheckedIn, Completed, Cancelled, NoShow, Refunded)
   - BookingType (Hotel, Apartment, Hostel, Villa, Resort, Camping, Flight, Train, CarRental, Yacht, Cruise, AirportTransfer, Restaurant, EventHall, Catering, FoodPreorder, Medical, SalonSpa, HomeService, Fitness, Tutoring, Photography, EventTicket, Tour, EscapeRoom, Coworking, SportsCourt, MeetingRoom, EquipmentRental, Parking, StorageUnit, OnlineConsultation, Webinar, LiveStreaming)
   - PaymentStatus (Pending, Paid, PartiallyPaid, Refunded, Failed, Cancelled)
   - ResourceStatus (Active, Inactive, Maintenance, Blocked)
   - ResourceType (Room, Table, Vehicle, Slot, Court, Equipment, Staff, Package)
   - NotificationChannel (Email, SMS, Push, WhatsApp, Database)
   - InvoiceStatus (Draft, Sent, Paid, Overdue, Cancelled)
   - TransactionType (Payment, Refund, Deposit, Payout)
6. Install spatie/laravel-permission: `composer require spatie/laravel-permission`
7. Publish and run permission migrations
8. Configure Redis queue and cache drivers in config/queue.php and config/cache.php
9. Install Yajra DataTables: `composer require yajra/laravel-datatables-oracle`
10. Run `vendor/bin/pint --dirty` to format all PHP files

Follow all conventions in backend/CLAUDE.md. Use `php artisan make:` for all file generation.
```

---

## AGENT-01: Base UI Layout & Authentication [PARALLEL OK after AGENT-00]

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for full architecture context.

Task: Build the base Blade UI layouts, shared components, and authentication for both Central Admin and Tenant Admin.

1. Create resources/views/layouts/central.blade.php
   - Fixed sidebar (left, 240px), fixed topbar (top, 64px), main content area
   - TailwindCSS dark sidebar with navigation items: Dashboard, Tenants, Plans, Settings
   - Topbar with: page title, user avatar dropdown (profile, logout)
   - Alpine.js sidebar collapse on mobile
   - Flash message toast component slot

2. Create resources/views/layouts/tenant.blade.php
   - Same structure but tenant-specific nav:
     Dashboard, Bookings, Resources, Customers, Staff, Finance, Reports, Settings
   - Tenant logo/name in sidebar header

3. Create shared Blade components in resources/views/components/:
   - stat-card.blade.php (icon, label, value, change percentage, color)
   - data-table.blade.php (wraps Yajra DataTable with consistent styling)
   - modal.blade.php (Alpine.js controlled, slot for title + body + footer)
   - toast.blade.php (Alpine.js auto-dismiss, types: success/error/warning/info)
   - breadcrumb.blade.php (array of [label, url] pairs)
   - page-header.blade.php (title, subtitle, action button slot)
   - form-input.blade.php (label, input, error message)
   - form-select.blade.php
   - form-textarea.blade.php
   - badge.blade.php (status badge with color map per Enum)

4. Central Admin authentication:
   - php artisan make:controller Central/Auth/LoginController
   - Login view at resources/views/central/auth/login.blade.php
   - Routes: GET /central/login, POST /central/login, POST /central/logout
   - Guard: 'central' (configure in config/auth.php)
   - Middleware: 'central.auth'

5. Tenant Admin authentication:
   - php artisan make:controller Tenant/Auth/LoginController
   - php artisan make:controller Tenant/Auth/PasswordResetController
   - Views: login, forgot-password, reset-password
   - Routes in routes/tenant.php (under tenancy middleware)
   - Guard: 'tenant' 

6. Run `vendor/bin/pint --dirty`

Use TailwindCSS utility classes only. No custom CSS files.
```

---

## AGENT-02: Tenant Management (Central Panel)

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the complete Tenant Management section of the Central Admin Panel (Phase 1.1 from tasks.md).

1. Create the Tenant model (already created by stancl/tenancy) — extend with custom fields:
   - Add migration: contact_name, contact_email, phone, business_type (BookingType enum), status (active/suspended/trial), logo, notes

2. php artisan make:service Central/TenantManagementService
   - Methods: create(array $data), suspend(Tenant $tenant), reactivate(Tenant $tenant), delete(Tenant $tenant), impersonate(Tenant $tenant)

3. php artisan make:repository Central/TenantRepository
   - Methods: paginate(array $filters), findByDomain(string $domain), withSubscription()

4. php artisan make:controller Central/TenantController --resource
   - index(): DataTable response + view
   - create(): form view
   - store(): StoreTenantsRequest → TenantManagementService::create()
   - show(): tenant detail view
   - edit(): edit form view
   - update(): UpdateTenantRequest → service
   - destroy(): soft delete

5. php artisan make:request Central/StoreTenantRequest
   - Validate: name (required, unique), domain (required, unique, regex), email, plan_id, contact_name, business_type

6. Create DataTable class: php artisan make:datatable Central/TenantDataTable
   - Columns: ID, Name, Domain, Plan, Status (badge), Bookings Count, Created At, Actions

7. Create Job: php artisan make:job ProvisionTenantDatabase
   - Creates tenant DB, runs migrations, seeds default roles/permissions
   - Dispatched after tenant creation

8. Create Notification: php artisan make:notification TenantWelcomeNotification
   - Email: welcome email with login URL, credentials
   - Via: mail

9. Views in resources/views/central/tenants/:
   - index.blade.php (DataTable + Create button)
   - create.blade.php (form)
   - edit.blade.php (form)
   - show.blade.php (detail with stats, subscription info, action buttons)

10. Add routes to routes/web.php under central middleware group
11. Run `vendor/bin/pint --dirty`
```

---

## AGENT-03: Plans & Subscriptions (Central Panel) [PARALLEL OK with AGENT-02]

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build Plans & Subscriptions management for the Central Admin Panel (Phase 1.2 from tasks.md).

1. Create models: Plan, Subscription, PlanFeature
   - php artisan make:model Plan -mf
   - php artisan make:model Subscription -mf
   - php artisan make:model PlanFeature -mf
   - Relationships: Plan hasMany Subscriptions, Plan hasMany PlanFeatures, Tenant hasOne Subscription

2. php artisan make:controller Central/PlanController --resource
3. php artisan make:controller Central/SubscriptionController --resource

4. php artisan make:service Central/PlanService
   - Methods: create(array), update(Plan, array), toggleStatus(Plan), applyToTenant(Tenant, Plan)

5. Feature flag system:
   - Create Enum: app/Enums/PlanFeature.php (MaxBookings, MaxResources, MaxStaff, HasApi, HasChannelManager, HasErp, HasCustomDomain, HasWhatsApp, etc.)
   - Service method: hasFeature(Tenant $tenant, PlanFeature $feature): bool

6. Views in resources/views/central/plans/:
   - index.blade.php (cards showing plans side by side with feature comparison)
   - create.blade.php / edit.blade.php
   - features.blade.php (feature matrix editor per plan)

7. Views in resources/views/central/subscriptions/index.blade.php (DataTable)

8. Run `vendor/bin/pint --dirty`
```

---

## AGENT-04: Central Dashboard

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the Central Admin Dashboard (Phase 1.3 from tasks.md).

1. php artisan make:controller Central/DashboardController
   - index(): aggregate stats via DashboardService, return view

2. php artisan make:service Central/DashboardService
   - Methods:
     - getStats(): array (total_tenants, active_tenants, suspended_tenants, trial_tenants, mrr, total_bookings_all_tenants)
     - getTenantGrowthChart(int $months = 6): array (labels, data)
     - getRevenueChart(int $months = 6): array (labels, data)
     - getRecentTenants(int $limit = 10): Collection
     - getPlanDistribution(): array

3. View: resources/views/central/dashboard/index.blade.php
   - Row 1: 4 stat-card components (Total Tenants, Active, MRR, Total Bookings)
   - Row 2: Tenant Growth line chart (ApexCharts) + Revenue chart
   - Row 3: Plan distribution pie chart + Recent tenants table

4. ApexCharts setup — load via CDN in layout, init charts via Alpine.js x-init with data from controller

5. Run `vendor/bin/pint --dirty`
```

---

## AGENT-05: Tenant Dashboard & Booking Core Models

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Create all core tenant database models, migrations, factories, and the Tenant Dashboard (Phases 3.1, beginning of 3.2 from tasks.md).

1. Create tenant core models + migrations:
   - php artisan make:model Booking -mf (id, reference_number, customer_id, resource_id, booking_type BookingType enum, status BookingStatus enum, check_in, check_out, guests_count, total_amount, paid_amount, source, notes, staff_id nullable, confirmed_at, cancelled_at, cancellation_reason)
   - php artisan make:model BookingItem -mf (booking_id, item_type, item_id, description, quantity, unit_price, total_price)
   - php artisan make:model BookingStatusHistory -mf (booking_id, status, previous_status, note, changed_by_id)
   - php artisan make:model Resource -mf (name, slug, resource_type ResourceType enum, booking_type BookingType enum, capacity, description, base_price, price_unit: per_night/per_hour/per_person, status ResourceStatus enum, meta JSON)
   - php artisan make:model TimeSlot -mf (resource_id, day_of_week, start_time, end_time, capacity, duration_minutes, is_active)
   - php artisan make:model BlackoutDate -mf (resource_id nullable, date, reason)
   - php artisan make:model Customer -mf (name, email, phone, date_of_birth, gender, address, notes, loyalty_points, is_blacklisted, blacklist_reason, tags JSON)

2. Define Eloquent relationships in all models

3. php artisan make:service Tenant/Dashboard/DashboardService
   - Methods: getStats(), getUpcomingBookings(int $limit), getRecentActivity(), getRevenueChart(), getBookingStatusBreakdown()

4. php artisan make:controller Tenant/DashboardController
   - index(): return tenant dashboard view with stats

5. View: resources/views/tenant/dashboard/index.blade.php
   - Row 1: 4 stat cards (Today's Bookings, Monthly Revenue, Occupancy Rate %, Pending Approvals)
   - Row 2: Upcoming bookings widget (next 24h, card list)
   - Row 3: Revenue chart (ApexCharts) + Booking status donut chart
   - Row 4: Recent activity feed

6. Run `vendor/bin/pint --dirty`
```

---

## AGENT-06: Booking Management Module

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.
Core models (Booking, Customer, Resource) already exist from AGENT-05.

Task: Build the complete Booking Management section (Phase 3.2 from tasks.md).

1. php artisan make:service Tenant/Booking/BookingCreationService
   - create(array $data): Booking
   - Validates availability, calculates pricing, creates booking + items, fires BookingCreated event

2. php artisan make:service Tenant/Booking/BookingManagementService
   - confirm(Booking), cancel(Booking, string $reason), markNoShow(Booking), processCheckin(Booking), processCheckout(Booking)

3. php artisan make:repository Tenant/BookingRepository
   - paginate(array $filters), findByReference(string $ref), pendingApprovals(), upcomingToday()

4. php artisan make:controller Tenant/BookingController --resource
   - All 7 CRUD methods + additional: confirm(), cancel(), checkin(), checkout(), noshow()

5. php artisan make:request Tenant/Booking/StoreBookingRequest
   - Validate all booking fields, run availability check in withValidator()

6. php artisan make:datatable Tenant/BookingDataTable
   - Columns: Reference, Customer, Resource, Type (badge), Status (badge), Check-in, Check-out, Amount, Actions
   - Server-side filtering by: status, booking_type, date_range, customer, resource

7. Create Event + Listener:
   - php artisan make:event BookingCreated (contains Booking model)
   - php artisan make:event BookingCancelled
   - php artisan make:event BookingConfirmed
   - php artisan make:listener SendBookingConfirmationNotification --event=BookingCreated
   - php artisan make:listener UpdateResourceAvailability --event=BookingCreated
   - php artisan make:listener GenerateInvoiceOnBooking --event=BookingConfirmed

8. Views in resources/views/tenant/bookings/:
   - index.blade.php (DataTable + filter sidebar)
   - create.blade.php (multi-step form: resource → dates → customer → extras → review)
   - show.blade.php (full detail + status timeline + action buttons)
   - edit.blade.php
   - pending.blade.php (approval queue with confirm/reject actions)

9. Run `vendor/bin/pint --dirty`
```

---

## AGENT-07: Resource Management Module [PARALLEL OK with AGENT-06]

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the complete Resource Management section (Phase 4 from tasks.md).

1. Create additional resource models:
   - php artisan make:model ResourceMedia -mf (resource_id, file_path, file_type, sort_order, is_cover, caption)
   - php artisan make:model ResourceAvailability -mf (resource_id, date, available_capacity, is_closed, note)
   - php artisan make:model PricingRule -mf (resource_id, name, rule_type: weekday/weekend/seasonal/dynamic, applies_from, applies_to, modifier_type: fixed/percent, modifier_value, priority)
   - php artisan make:model AddOn -mf (resource_id nullable, name, description, price, price_type: per_person/flat, is_required, booking_types JSON, is_active)

2. php artisan make:service Tenant/Resource/ResourceService
   - create(array), update(Resource, array), updateAvailability(Resource, array), calculatePrice(Resource, Carbon $from, Carbon $to, int $guests, array $addons): array

3. php artisan make:service Tenant/Resource/AvailabilityService
   - isAvailable(Resource, Carbon $from, Carbon $to, int $guests): bool
   - getAvailableSlots(Resource, Carbon $date): Collection
   - blockDates(Resource, array $dates, string $reason): void

4. php artisan make:controller Tenant/ResourceController --resource
5. php artisan make:controller Tenant/Resource/AvailabilityController
6. php artisan make:controller Tenant/Resource/MediaController (upload, reorder, delete)
7. php artisan make:controller Tenant/Resource/PricingController

8. php artisan make:datatable Tenant/ResourceDataTable

9. Views in resources/views/tenant/resources/:
   - index.blade.php
   - create.blade.php / edit.blade.php (tabbed: General | Availability | Pricing | Media | Add-ons)
   - show.blade.php
   - calendar.blade.php (FullCalendar.js view with availability overlay)

10. Run `vendor/bin/pint --dirty`
```

---

## AGENT-08: Customers (CRM) Module [PARALLEL OK with AGENT-06, 07]

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the Customers CRM module (Phase 6 from tasks.md).

1. Additional customer models:
   - php artisan make:model CustomerNote -mf (customer_id, staff_id, note, is_pinned)
   - php artisan make:model LoyaltyTransaction -mf (customer_id, booking_id nullable, type: earn/redeem/expire, points, balance_after, note)
   - php artisan make:model Lead -mf (customer_id nullable, name, email, phone, booking_type, source, status: new/contacted/qualified/converted/lost, notes, assigned_to)

2. php artisan make:service Tenant/Customer/CustomerService
   - create(array), update(Customer, array), blacklist(Customer, string $reason), removeFromBlacklist(Customer), addLoyaltyPoints(Customer, int $points, ?Booking $booking), redeemLoyaltyPoints(Customer, int $points): bool

3. php artisan make:repository Tenant/CustomerRepository

4. php artisan make:controller Tenant/CustomerController --resource
5. php artisan make:controller Tenant/Customer/LeadController --resource

6. php artisan make:datatable Tenant/CustomerDataTable
   - Columns: Name, Email, Phone, Total Bookings, Total Spent, Loyalty Points, Status (badge), Actions

7. Views in resources/views/tenant/customers/:
   - index.blade.php (DataTable)
   - show.blade.php (profile + booking history table + notes + loyalty ledger)
   - create.blade.php / edit.blade.php
   - blacklist.blade.php (list of blacklisted customers)
   - leads/index.blade.php (Kanban-style pipeline or DataTable)

8. Run `vendor/bin/pint --dirty`
```

---

## AGENT-09: Staff & Team Management [PARALLEL OK with AGENT-06, 07, 08]

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the Staff & Team Management module (Phase 7 from tasks.md).

1. Create models:
   - php artisan make:model Staff -mf (user_id, role, department, phone, avatar, hire_date, employment_type: full_time/part_time/contract, status: active/inactive, bio)
   - php artisan make:model StaffSchedule -mf (staff_id, day_of_week, start_time, end_time, is_available)
   - php artisan make:model StaffAssignment -mf (staff_id, booking_id, assigned_at, notes)
   - php artisan make:model LeaveRequest -mf (staff_id, leave_type, start_date, end_date, reason, status: pending/approved/rejected, approved_by nullable, response_note)
   - php artisan make:model AttendanceLog -mf (staff_id, date, clock_in, clock_out, hours_worked, notes)

2. php artisan make:service Tenant/Staff/StaffService
   - create(array), assignToBooking(Staff, Booking), approveLeave(LeaveRequest), rejectLeave(LeaveRequest, string $reason), clockIn(Staff), clockOut(Staff)

3. php artisan make:controller Tenant/StaffController --resource
4. php artisan make:controller Tenant/Staff/ScheduleController
5. php artisan make:controller Tenant/Staff/LeaveController
6. php artisan make:controller Tenant/Staff/AttendanceController

7. Views in resources/views/tenant/staff/:
   - index.blade.php (DataTable)
   - show.blade.php (profile + schedule + assignments + leave history)
   - create.blade.php / edit.blade.php
   - schedule.blade.php (weekly grid shift builder)
   - leave/index.blade.php (pending approvals + history)

8. Run `vendor/bin/pint --dirty`
```

---

## AGENT-10: Payments & Finance Module

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the Payments & Finance module (Phase 8 from tasks.md).

1. Create models:
   - php artisan make:model Transaction -mf (booking_id nullable, customer_id, amount, currency, type TransactionType enum, status PaymentStatus enum, gateway, gateway_transaction_id, gateway_response JSON, notes)
   - php artisan make:model Invoice -mf (booking_id, customer_id, invoice_number, status InvoiceStatus enum, subtotal, tax_amount, discount_amount, total, due_date, paid_at, notes)
   - php artisan make:model InvoiceItem -mf (invoice_id, description, quantity, unit_price, total_price, tax_rate)
   - php artisan make:model Refund -mf (transaction_id, booking_id, amount, reason, status: pending/processed/failed, processed_at, gateway_refund_id)
   - php artisan make:model Deposit -mf (booking_id, amount, paid_at, status, notes)

2. php artisan make:service Tenant/Finance/InvoiceService
   - generateForBooking(Booking): Invoice, sendByEmail(Invoice), generatePdf(Invoice): string, markAsPaid(Invoice)

3. php artisan make:service Tenant/Finance/PaymentService
   - record(array $data): Transaction, processRefund(Transaction, float $amount, string $reason): Refund

4. Gateway integration services:
   - php artisan make:service Tenant/Finance/Gateways/StripeService
   - php artisan make:service Tenant/Finance/Gateways/PaypalService
   - php artisan make:service Tenant/Finance/Gateways/PaymobService

5. php artisan make:controller Tenant/TransactionController
6. php artisan make:controller Tenant/InvoiceController (index, show, send, download)
7. php artisan make:controller Tenant/RefundController

8. Invoice PDF: use Laravel DomPDF, create Blade template at resources/views/pdf/invoice.blade.php

9. Views in resources/views/tenant/finance/:
   - transactions/index.blade.php (DataTable)
   - invoices/index.blade.php (DataTable)
   - invoices/show.blade.php (invoice preview with download/send buttons)
   - refunds/index.blade.php

10. Stripe Webhook: php artisan make:controller Api/StripeWebhookController
    - Handle: payment_intent.succeeded, payment_intent.payment_failed, charge.refunded

11. Run `vendor/bin/pint --dirty`
```

---

## AGENT-11: Notifications System

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the Notifications System (Phase 9 from tasks.md).

1. Create models:
   - php artisan make:model NotificationTemplate -mf (name, event_trigger, channel NotificationChannel enum, subject, body_html, body_text, variables JSON, is_active)
   - php artisan make:model NotificationLog -mf (notifiable_type, notifiable_id, template_id, channel, recipient, subject, status: sent/failed/pending, sent_at, error_message)

2. Create base Notification with template support:
   - php artisan make:notification Tenant/TemplatedNotification
   - Loads template from DB, replaces {{variables}}, sends via configured channel

3. Create specific notifications that use TemplatedNotification:
   - php artisan make:notification Tenant/BookingConfirmedNotification
   - php artisan make:notification Tenant/BookingCancelledNotification
   - php artisan make:notification Tenant/BookingReminderNotification
   - php artisan make:notification Tenant/PaymentReceivedNotification
   - php artisan make:notification Tenant/ReviewRequestNotification

4. php artisan make:command SendBookingReminders
   - Queries bookings with check-in in next 24h, dispatches reminder notifications
   - Registered in console schedule (daily)

5. php artisan make:job SendNotificationJob
   - Handles async notification sending with retry logic

6. php artisan make:service Tenant/Notification/NotificationService
   - send(Notifiable $notifiable, string $eventTrigger, array $variables): void
   - broadcast(Collection $recipients, NotificationTemplate $template): void
   - getLog(array $filters): LengthAwarePaginator

7. php artisan make:controller Tenant/NotificationController
   - templates: index, create, edit, update
   - logs: index
   - test: send test notification to self

8. Views in resources/views/tenant/notifications/:
   - templates/index.blade.php
   - templates/edit.blade.php (template editor with variable reference sidebar)
   - logs/index.blade.php (DataTable with status filter)
   - settings.blade.php (channel config: SMTP, SMS gateway, Firebase)

9. Run `vendor/bin/pint --dirty`
```

---

## AGENT-12: Reports & Analytics Module

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the Reports & Analytics module (Phase 11 from tasks.md).

1. php artisan make:service Tenant/Reports/BookingReportService
   - generate(array $filters): Collection (by date, type, status, resource, customer)

2. php artisan make:service Tenant/Reports/RevenueReportService
   - generate(array $filters): array (totals, by_period, by_resource, by_type, by_staff)

3. php artisan make:service Tenant/Reports/OccupancyReportService
   - generate(array $filters): array (utilization_rate, by_resource, heatmap_data)

4. php artisan make:service Tenant/Reports/ExportService
   - toExcel(string $reportType, array $filters): BinaryFileResponse (use maatwebsite/excel)
   - toPdf(string $reportType, array $filters): BinaryFileResponse
   - toCsv(string $reportType, array $filters): StreamedResponse

5. php artisan make:command DeliverScheduledReports
   - Queries scheduled report configs, generates, emails to configured recipients

6. php artisan make:controller Tenant/ReportController
   - bookings(), revenue(), occupancy(), customers(), cancellations(), staff(), sources()
   - export() — handles all report types

7. Views in resources/views/tenant/reports/:
   - bookings.blade.php (filter form + DataTable + chart)
   - revenue.blade.php (summary cards + bar chart + breakdown table)
   - occupancy.blade.php (heatmap calendar + utilization bar chart)
   - Each view has export buttons: CSV / Excel / PDF

8. Install maatwebsite/excel: `composer require maatwebsite/excel`
   - Create Export classes: BookingExport, RevenueExport, CustomerExport

9. Run `vendor/bin/pint --dirty`
```

---

## AGENT-13: ERP Accounting & Finance Module

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build ERP Accounting & Finance (Phase 15 from tasks.md).

1. Create accounting models:
   - php artisan make:model Account -mf (code, name, type: asset/liability/equity/revenue/expense, parent_id nullable, is_system, balance, description)
   - php artisan make:model JournalEntry -mf (reference, date, description, created_by_id, source_type, source_id)
   - php artisan make:model JournalLine -mf (journal_entry_id, account_id, debit, credit, description)
   - php artisan make:model TaxRate -mf (name, rate, applies_to JSON, is_inclusive, is_active)
   - php artisan make:model BankAccount -mf (name, bank_name, account_number, currency, opening_balance, current_balance, is_active)
   - php artisan make:model BankReconciliation -mf (bank_account_id, statement_date, statement_balance, reconciled_balance, difference, status, notes)

2. php artisan make:service Tenant/Accounting/JournalService
   - post(array $lines, string $description, string $reference, Model $source): JournalEntry
   - void(JournalEntry): void
   - getTrialBalance(Carbon $asOf): array
   - getBalanceSheet(Carbon $asOf): array
   - getProfitLoss(Carbon $from, Carbon $to): array

3. php artisan make:service Tenant/Accounting/AccountingBootstrapService
   - seedDefaultChartOfAccounts(Tenant): void (creates standard double-entry COA on tenant setup)

4. Event Listeners that auto-post journal entries:
   - php artisan make:listener PostBookingRevenueJournal --event=BookingConfirmed
   - php artisan make:listener PostPaymentJournal --event=PaymentReceived
   - php artisan make:listener PostRefundJournal --event=RefundProcessed

5. php artisan make:controller Tenant/Accounting/AccountController --resource
6. php artisan make:controller Tenant/Accounting/JournalController
7. php artisan make:controller Tenant/Accounting/ReportController
   - trialBalance(), balanceSheet(), profitLoss(), cashFlow()

8. Views in resources/views/tenant/accounting/:
   - accounts/index.blade.php (tree view of chart of accounts)
   - journal/index.blade.php (DataTable of entries)
   - journal/create.blade.php (debit/credit lines form)
   - reports/trial-balance.blade.php
   - reports/balance-sheet.blade.php
   - reports/profit-loss.blade.php

9. Run `vendor/bin/pint --dirty`
```

---

## AGENT-14: ERP HR & Payroll Module

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.
Staff model already exists from AGENT-09.

Task: Build ERP HR & Payroll (Phase 19 from tasks.md).

1. Additional HR models:
   - php artisan make:model SalaryStructure -mf (staff_id, base_salary, currency, effective_from)
   - php artisan make:model SalaryComponent -mf (salary_structure_id, name, type: allowance/deduction, amount, is_percentage, percentage_of)
   - php artisan make:model PayrollRun -mf (period_month, period_year, status: draft/processed/paid, processed_at, total_gross, total_deductions, total_net, notes)
   - php artisan make:model Payslip -mf (payroll_run_id, staff_id, gross_salary, total_deductions, net_salary, components JSON, status, paid_at)
   - php artisan make:model PerformanceReview -mf (staff_id, reviewer_id, period, rating, strengths, improvements, goals, status)

2. php artisan make:service Tenant/HR/PayrollService
   - processPayroll(int $month, int $year): PayrollRun
   - calculatePayslip(Staff $staff, int $month, int $year): array
   - generatePayslipPdf(Payslip): string

3. php artisan make:controller Tenant/HR/PayrollController
4. php artisan make:controller Tenant/HR/PerformanceController
5. php artisan make:controller Tenant/HR/SalaryController

6. Views in resources/views/tenant/hr/:
   - payroll/index.blade.php (list of payroll runs)
   - payroll/run.blade.php (run payroll form + preview table)
   - payroll/show.blade.php (payroll run detail + payslips list)
   - payslip/show.blade.php (printable payslip)
   - performance/index.blade.php

7. Run `vendor/bin/pint --dirty`
```

---

## AGENT-15: REST API for Flutter App

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the complete REST API for the Flutter customer app (Phase 25 from tasks.md).

All routes are under /api/v1/ prefix with Sanctum auth middleware.

1. Create API versioned route group in routes/api.php

2. Auth endpoints:
   - php artisan make:controller Api/V1/AuthController
   - POST /register, POST /login, POST /logout, POST /forgot-password, POST /reset-password
   - POST /refresh-token

3. Resources endpoints:
   - php artisan make:controller Api/V1/ResourceController
   - GET /resources (list + search + filter), GET /resources/{id}
   - GET /resources/{id}/availability?date=
   - GET /resources/{id}/slots?date=
   - GET /resources/{id}/reviews

4. Booking endpoints:
   - php artisan make:controller Api/V1/BookingController
   - GET /bookings (customer's own), POST /bookings, GET /bookings/{id}
   - POST /bookings/{id}/cancel, POST /bookings/{id}/request-change

5. Customer profile endpoints:
   - php artisan make:controller Api/V1/CustomerController
   - GET /customer/profile, PUT /customer/profile
   - GET /customer/loyalty-points

6. Payment endpoints:
   - php artisan make:controller Api/V1/PaymentController
   - POST /payments/intent (create Stripe PaymentIntent), POST /payments/confirm
   - POST /webhooks/stripe (public, no auth)

7. Notifications endpoints:
   - php artisan make:controller Api/V1/NotificationController
   - GET /notifications, POST /notifications/{id}/read, POST /notifications/read-all

8. Create API Resources (Eloquent) for all responses:
   - php artisan make:resource Api/V1/BookingResource
   - php artisan make:resource Api/V1/ResourceResource
   - php artisan make:resource Api/V1/CustomerResource
   - php artisan make:resource Api/V1/TransactionResource

9. Create API Form Requests:
   - php artisan make:request Api/V1/StoreBookingRequest
   - php artisan make:request Api/V1/AuthLoginRequest
   - php artisan make:request Api/V1/AuthRegisterRequest

10. Configure rate limiting per plan in RouteServiceProvider

11. Write feature tests for all API endpoints:
    - php artisan make:test Api/BookingApiTest
    - php artisan make:test Api/AuthApiTest
    - php artisan make:test Api/ResourceApiTest

12. Run `vendor/bin/pint --dirty`
```

---

## AGENT-16: Tenant Public Website (Customer Booking Flow)

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build the customer-facing tenant website (Phase 13 from tasks.md).

These routes are served under the tenant domain but do NOT require login for browsing. Booking requires customer auth.

1. php artisan make:controller Tenant/Website/HomeController
2. php artisan make:controller Tenant/Website/SearchController
3. php artisan make:controller Tenant/Website/ListingController (resource detail)
4. php artisan make:controller Tenant/Website/BookingFlowController
   - step1(), step2(), step3(), step4(), step5PaymentPage(), step6Confirmation()
   - Uses session to maintain state between steps

5. php artisan make:controller Tenant/Website/CustomerAccountController
   - myBookings(), bookingDetail(), profile(), wishlist(), reviews()

6. php artisan make:service Tenant/Website/SearchService
   - search(array $filters): LengthAwarePaginator
   - getFilters(BookingType $type): array

7. Views in resources/views/tenant/website/:
   - home.blade.php (uses layout without sidebar — marketing layout)
   - search.blade.php (results grid + filter sidebar + map toggle)
   - listing.blade.php (resource detail — gallery, description, calendar, pricing, reviews)
   - booking/step1.blade.php through step6.blade.php
   - account/my-bookings.blade.php
   - account/profile.blade.php

8. Create separate layout: resources/views/layouts/website.blade.php
   - Header with tenant logo, search bar, customer auth links
   - Footer with tenant info, links, social
   - No sidebar

9. Customer auth for website (separate from staff login):
   - php artisan make:controller Tenant/Website/Auth/CustomerAuthController
   - Routes: /login, /register, /logout (customer guard)

10. Run `vendor/bin/pint --dirty`
```

---

## AGENT-17: Reviews, Settings & Integrations

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build Reviews module, Tenant Settings, and key Integrations (Phases 10, 27, 26 from tasks.md).

1. Reviews module:
   - php artisan make:model Review -mf (booking_id, customer_id, resource_id, rating 1-5, title, body, status: pending/published/rejected, reply, replied_at, replied_by_id)
   - php artisan make:controller Tenant/ReviewController (index, show, approve, reject, reply, flag)
   - php artisan make:controller Tenant/Website/ReviewController (submit from customer)
   - Views: resources/views/tenant/reviews/index.blade.php

2. Tenant Settings module:
   - php artisan make:model TenantSetting -mf (key, value, group)
   - php artisan make:service Tenant/Settings/SettingsService — get(string $key, $default), set(string $key, $value), setMany(array), getGroup(string $group): array
   - php artisan make:controller Tenant/Settings/GeneralSettingsController
   - php artisan make:controller Tenant/Settings/BookingSettingsController
   - php artisan make:controller Tenant/Settings/NotificationSettingsController
   - php artisan make:controller Tenant/Settings/PaymentSettingsController
   - php artisan make:controller Tenant/Settings/BrandingController
   - Views: resources/views/tenant/settings/ (tabbed settings pages per group)

3. Google Maps integration:
   - php artisan make:service Shared/Maps/GoogleMapsService
   - geocode(string $address): array, reverseGeocode(float $lat, float $lng): string
   - Map component blade: renders Mapbox/Google Maps with resource pin

4. Webhook manager:
   - php artisan make:model Webhook -mf (url, events JSON, secret, is_active, last_triggered_at)
   - php artisan make:service Tenant/Integration/WebhookDispatchService — dispatch(string $event, array $payload): void
   - php artisan make:controller Tenant/Integration/WebhookController

5. Run `vendor/bin/pint --dirty`
```

---

## AGENT-18: Database Seeders & Test Data

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Create comprehensive database seeders for both central and tenant databases (Phase 29.3 from tasks.md).

1. Central DB seeders:
   - PlanSeeder: 3 plans (Starter/Growth/Enterprise) with feature flags
   - AdminUserSeeder: creates a super admin account

2. Tenant DB seeders — create a TenantDatabaseSeeder that seeds one sample tenant of each category:
   - For each: 5-10 Resources with realistic names + pricing
   - 20 Customers with realistic names, emails, history
   - 50 Bookings across various statuses and date ranges
   - Sample reviews, transactions, invoices, staff

3. Factory definitions for all models — ensure realistic faker data:
   - BookingFactory: generates valid reference numbers (BK-YYYY-XXXXXX), realistic amounts
   - ResourceFactory: context-aware names based on BookingType
   - CustomerFactory: consistent name/email/phone combinations

4. Create an artisan command: php artisan demo:seed
   - Creates 1 sample tenant per booking category with full test data
   - Useful for demos and development

5. Create seeders for:
   - Default notification templates (booking_confirmed, booking_cancelled, payment_received, booking_reminder_24h)
   - Default chart of accounts (standard double-entry COA)
   - Default roles and permissions per booking type

6. Run `vendor/bin/pint --dirty`
```

---

## AGENT-19: Feature Tests

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Write comprehensive feature tests for the core booking flow and API (Phase 29 from tasks.md).

Follow the testing-best-practices skill guidelines.

1. Central panel tests:
   - php artisan make:test --phpunit CentralTenantManagementTest
     - test_can_create_tenant, test_tenant_db_is_provisioned, test_can_suspend_tenant
   - php artisan make:test --phpunit CentralPlanTest
     - test_can_create_plan, test_feature_flags_enforced_per_plan

2. Tenant booking tests:
   - php artisan make:test --phpunit TenantBookingCreationTest
     - test_staff_can_create_booking, test_cannot_double_book_resource, test_booking_fires_events
     - test_booking_generates_invoice, test_booking_sends_confirmation_notification
   - php artisan make:test --phpunit TenantBookingManagementTest
     - test_can_confirm_pending_booking, test_can_cancel_booking, test_refund_processed_on_cancel

3. Availability tests:
   - php artisan make:test --phpunit ResourceAvailabilityTest
     - test_slot_unavailable_when_booked, test_blackout_date_blocks_booking, test_capacity_respected

4. Multi-tenancy isolation tests:
   - php artisan make:test --phpunit TenantIsolationTest
     - test_tenant_a_cannot_read_tenant_b_bookings
     - test_tenant_a_cannot_access_tenant_b_customers

5. API tests:
   - php artisan make:test --phpunit Api/BookingApiTest
     - test_customer_can_create_booking_via_api
     - test_unauthenticated_request_returns_401
     - test_rate_limiting_enforced
   - php artisan make:test --phpunit Api/AuthApiTest
     - test_customer_can_login, test_invalid_credentials_rejected

6. Finance tests:
   - php artisan make:test --phpunit InvoiceGenerationTest
     - test_invoice_auto_generated_on_booking_confirm
     - test_invoice_pdf_generated_correctly

7. Run all tests: `php artisan test --compact`
8. Run `vendor/bin/pint --dirty`
```

---

## AGENT-20: ERP Operations, Procurement & Document Management

```
You are working on a Laravel 11 SaaS booking platform.
Read instructions.md in the project root for architecture context.

Task: Build ERP Operations Scheduling (Phase 20), Procurement (Phase 18), and Document Management (Phase 24).

1. Operations Scheduling:
   - php artisan make:model OperationalChecklist -mf (booking_type BookingType enum, trigger: pre_booking/post_booking, items JSON array of task names)
   - php artisan make:model ChecklistCompletion -mf (booking_id, checklist_id, completed_items JSON, completed_by_id, completed_at)
   - php artisan make:model MaintenanceSchedule -mf (resource_id, scheduled_at, duration_hours, description, status, assigned_to_id)
   - php artisan make:controller Tenant/Operations/ChecklistController
   - php artisan make:controller Tenant/Operations/MaintenanceController
   - View: resources/views/tenant/operations/master-schedule.blade.php (FullCalendar with all resources + staff)

2. Procurement:
   - php artisan make:model Supplier -mf (name, email, phone, address, tax_id, payment_terms, notes, is_active)
   - php artisan make:model PurchaseRequest -mf (requested_by_id, title, items JSON, total_estimated, status, approved_by_id)
   - php artisan make:model PurchaseOrder -mf (supplier_id, purchase_request_id nullable, po_number, items JSON, subtotal, tax, total, status, delivery_date, notes)
   - php artisan make:controller Tenant/Procurement/SupplierController --resource
   - php artisan make:controller Tenant/Procurement/PurchaseOrderController --resource
   - Views: resources/views/tenant/procurement/

3. Document Management:
   - php artisan make:model Document -mf (documentable_type, documentable_id, name, file_path, file_type, file_size, category, expires_at, uploaded_by_id)
   - php artisan make:controller Tenant/DocumentController (upload, download, delete, list by entity)
   - php artisan make:command AlertExpiringDocuments (sends notification for docs expiring in 30 days)
   - Polymorphic relationship: Customer, Staff, Supplier, Booking all morphMany Documents

4. Run `vendor/bin/pint --dirty`
```

---

## Execution Order Guide

| Order | Agent | Can Parallelize With |
|-------|-------|---------------------|
| 1 | AGENT-00 (Foundation) | — |
| 2 | AGENT-01 (UI + Auth) | AGENT-02, 03 after AGENT-00 |
| 3 | AGENT-02 (Tenant Mgmt) | AGENT-03, 04 |
| 3 | AGENT-03 (Plans) | AGENT-02, 04 |
| 3 | AGENT-04 (Central Dashboard) | AGENT-02, 03 |
| 4 | AGENT-05 (Models + Dashboard) | — |
| 5 | AGENT-06 (Bookings) | AGENT-07, 08, 09 |
| 5 | AGENT-07 (Resources) | AGENT-06, 08, 09 |
| 5 | AGENT-08 (CRM) | AGENT-06, 07, 09 |
| 5 | AGENT-09 (Staff) | AGENT-06, 07, 08 |
| 6 | AGENT-10 (Payments) | AGENT-11, 12 |
| 6 | AGENT-11 (Notifications) | AGENT-10, 12 |
| 6 | AGENT-12 (Reports) | AGENT-10, 11 |
| 7 | AGENT-13 (ERP Accounting) | AGENT-14, 20 |
| 7 | AGENT-14 (HR + Payroll) | AGENT-13, 20 |
| 7 | AGENT-20 (Ops + Procurement + Docs) | AGENT-13, 14 |
| 8 | AGENT-15 (REST API) | AGENT-16, 17 |
| 8 | AGENT-16 (Website) | AGENT-15, 17 |
| 8 | AGENT-17 (Reviews + Settings) | AGENT-15, 16 |
| 9 | AGENT-18 (Seeders) | AGENT-19 |
| 9 | AGENT-19 (Tests) | AGENT-18 |
