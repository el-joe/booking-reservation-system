# Booking Reservation System — Development Phases & Tasks

> Reference: `instructions.md` for architecture, conventions, and stack details.
> Status legend: ⬜ Not started | 🔄 In progress | ✅ Done

---

## Gap-fill completed — 2026-10-07

The following missing pieces were added during a gap-analysis pass:

- `PaypalService` — app/Services/Tenant/Finance/Gateways/PaypalService.php
- `FawryService` — app/Services/Tenant/Finance/Gateways/FawryService.php
- `RefundProcessed` event — app/Events/RefundProcessed.php
- `PostRefundJournal` listener — app/Listeners/PostRefundJournal.php
- `RefundProcessed → PostRefundJournal` registered in AppServiceProvider
- `ReviewRequestNotification` — app/Notifications/Tenant/ReviewRequestNotification.php
- `NotificationSettingsController` — app/Http/Controllers/Tenant/Settings/NotificationSettingsController.php
- Notification settings Blade view — resources/views/tenant/settings/notifications.blade.php
- Tenant settings routes for notification settings (GET/POST `settings/notifications`)

---

---

## Phase 0 — Foundation & Infrastructure
**Goal:** Working Laravel app with multi-tenancy, auth, and base UI scaffolding.

- [ ] 0.1 Install and configure `stancl/tenancy` for domain-based multi-tenancy
- [ ] 0.2 Create central database migrations (tenants, plans, subscriptions, plan_features)
- [ ] 0.3 Create tenant database baseline migrations (shared schema applied to each tenant DB)
- [ ] 0.4 Install `spatie/laravel-permission` for RBAC
- [ ] 0.5 Configure Redis for cache, sessions, and queues
- [ ] 0.6 Configure mail (SMTP), queue workers, and scheduler
- [ ] 0.7 Scaffold TailwindCSS + Alpine.js + Vite build pipeline
- [ ] 0.8 Create base Blade layout: `layouts/central.blade.php`, `layouts/tenant.blade.php`
- [ ] 0.9 Implement Central Admin authentication (login/logout/2FA)
- [ ] 0.10 Implement Tenant Admin authentication (login/logout/2FA/forgot password)
- [ ] 0.11 Create shared Blade components: sidebar, topbar, breadcrumb, toast, modal, table, card
- [ ] 0.12 Define all Enum classes: BookingStatus, BookingType, PaymentStatus, ResourceStatus, etc.
- [ ] 0.13 Configure Yajra DataTables with AJAX base setup
- [ ] 0.14 Set up route groups: `central.*`, `tenant.*`, `api.v1.*`
- [ ] 0.15 Create base Service + Repository interfaces and abstract classes
- [ ] 0.16 Set up Horizon for queue monitoring

---

## Phase 1 — Central Admin Panel
**Goal:** Platform owner can manage tenants, plans, and global settings.

### 1.1 Tenant Management
- [ ] Tenant list (DataTable: name, domain, plan, status, created_at)
- [ ] Tenant detail / profile view
- [ ] Create tenant (form + validation + auto-create DB + send welcome email)
- [ ] Edit tenant (general info, domain, status)
- [ ] Suspend / reactivate tenant
- [ ] Delete tenant (soft delete + cleanup job)
- [ ] Tenant impersonation (log in as tenant admin)
- [ ] Tenant usage stats (bookings count, storage used, API calls)

### 1.2 Plans & Subscriptions
- [ ] Plan list (DataTable)
- [ ] Create / edit plan (name, price, billing cycle, limits)
- [ ] Plan feature flags (modules enabled per plan)
- [ ] Subscription list (tenant → plan mapping)
- [ ] Change tenant plan
- [ ] Subscription billing history

### 1.3 Central Dashboard
- [ ] Stats cards: total tenants, active tenants, MRR, total bookings
- [ ] Tenant growth chart (ApexCharts line)
- [ ] Revenue chart (monthly MRR)
- [ ] Recently registered tenants table
- [ ] Plan distribution pie chart

### 1.4 Central Settings
- [ ] Global email/SMS gateway configuration
- [ ] Default booking type feature matrix
- [ ] Platform branding (logo, name, colors)
- [ ] Maintenance mode toggle per tenant or global
- [ ] Audit log viewer

---

## Phase 2 — Tenant Onboarding Flow
**Goal:** Tenant can self-register, choose plan, set up their business profile.

- [ ] 2.1 Tenant self-registration form (name, email, business type, subdomain)
- [ ] 2.2 Plan selection page with feature comparison table
- [ ] 2.3 Payment for subscription (Stripe integration)
- [ ] 2.4 Onboarding wizard: business type → profile → first resource → availability
- [ ] 2.5 Welcome email with login credentials
- [ ] 2.6 Tenant DB provisioning job (runs after registration)

---

## Phase 3 — Tenant Dashboard & Core Booking Management
**Goal:** Tenant can see overview stats and manage all bookings.

### 3.1 Tenant Dashboard
- [ ] Stats cards: today's bookings, total revenue, occupancy rate, pending approvals
- [ ] Upcoming bookings widget (next 24h)
- [ ] Recent activity feed (booking events)
- [ ] Quick actions: New Booking, Check Availability
- [ ] Revenue chart (7d / 30d / 90d toggle)
- [ ] Booking status breakdown donut chart

### 3.2 Booking Management
- [ ] All bookings list (DataTable + filters: type, status, date range, customer, resource)
- [ ] New booking form (manual entry by staff — full multi-step form)
- [ ] Booking detail page (view all fields + status history timeline)
- [ ] Edit booking (change dates, resource, extras, notes)
- [ ] Cancel booking (with reason + refund option)
- [ ] Process refund from booking detail
- [ ] Pending approvals queue (confirm / reject with note)
- [ ] Waitlist management (view queue, notify next customer)
- [ ] Group bookings (parent + children view)
- [ ] Recurring bookings (series management)
- [ ] No-show management (mark + optional fee)
- [ ] Booking source tracking (walk-in, website, OTA, phone)

---

## Phase 4 — Resource Management
**Goal:** Tenant can define, configure, and manage their bookable resources (adapts per booking type).

### 4.1 Core Resource CRUD
- [ ] Resource list (DataTable + filter by type/status)
- [ ] Add resource (name, type, capacity, description, status)
- [ ] Edit resource
- [ ] Resource media manager (upload photos/videos, reorder, set cover)
- [ ] Resource status toggle (active / maintenance / blocked)
- [ ] Duplicate resource

### 4.2 Availability & Calendar
- [ ] Availability calendar view (FullCalendar — day/week/month)
- [ ] Time slot builder (define opening hours + slot duration + capacity per slot)
- [ ] Blackout dates manager (select dates to close)
- [ ] Seasonal availability rules (date range + hours override)
- [ ] Capacity override per specific date
- [ ] External calendar sync (iCal import/export)

### 4.3 Resource Pricing
- [ ] Base pricing setup (per night / per hour / per person / per unit)
- [ ] Pricing rules (weekday vs weekend, seasonal ranges)
- [ ] Dynamic pricing toggle
- [ ] Add-ons & extras (optional items with price: breakfast, parking, insurance)
- [ ] Deposit & prepayment rules per resource
- [ ] Tax settings per resource

---

## Phase 5 — Pricing & Offers
- [ ] 5.1 Discount codes (CRUD + usage limits + expiry + type: fixed/percent)
- [ ] 5.2 Early bird / last-minute offer rules
- [ ] 5.3 Group discount tiers
- [ ] 5.4 Promo code validation (check on booking form)
- [ ] 5.5 Offer performance report (usage, revenue impact)

---

## Phase 6 — Customers (CRM)
- [ ] 6.1 Customer list (DataTable + search + filter by tag/segment)
- [ ] 6.2 Customer profile page (details + booking history + payment history + notes)
- [ ] 6.3 Add / edit customer
- [ ] 6.4 Customer tags & segments management
- [ ] 6.5 Blacklist management (add, remove, view reason)
- [ ] 6.6 Guest check-in / check-out (for accommodation types)
- [ ] 6.7 Loyalty points ledger (earn + redeem + history)
- [ ] 6.8 Customer export (CSV)
- [ ] 6.9 Lead management (inquiry → booking pipeline)
- [ ] 6.10 Customer lifetime value report

---

## Phase 7 — Staff & Team Management
- [ ] 7.1 Staff list (DataTable)
- [ ] 7.2 Add / edit staff (profile, role, contact, documents)
- [ ] 7.3 Role & permissions management (Spatie RBAC UI)
- [ ] 7.4 Staff schedule (weekly shift builder)
- [ ] 7.5 Staff availability calendar
- [ ] 7.6 Staff assignment to bookings
- [ ] 7.7 Leave requests (submit, approve, reject)
- [ ] 7.8 Attendance log (clock in/out)
- [ ] 7.9 Staff performance report

---

## Phase 8 — Payments & Finance
- [ ] 8.1 Transactions list (DataTable + filters)
- [ ] 8.2 Transaction detail page
- [ ] 8.3 Refunds management (list, process, status tracking)
- [ ] 8.4 Invoice generation (auto on booking confirm + manual)
- [ ] 8.5 Invoice PDF download
- [ ] 8.6 Invoice email sending
- [ ] 8.7 Payment methods settings (enable/disable gateways)
- [ ] 8.8 Revenue report (by period, by resource, by booking type)
- [ ] 8.9 Pending payments tracker
- [ ] 8.10 Deposit tracking report
- [ ] 8.11 Stripe integration (card payments)
- [ ] 8.12 PayPal integration
- [ ] 8.13 Paymob integration (local Egyptian gateway)
- [ ] 8.14 Fawry integration

---

## Phase 9 — Notifications System
- [ ] 9.1 Notification template editor (email/SMS/push per trigger event)
- [ ] 9.2 Template variables system ({{customer_name}}, {{booking_date}}, etc.)
- [ ] 9.3 Trigger rule configuration (event → channel → template mapping)
- [ ] 9.4 Notification log viewer (delivery status per notification)
- [ ] 9.5 Channel settings (SMTP config, SMS gateway, Firebase)
- [ ] 9.6 Test notification send from admin
- [ ] 9.7 WhatsApp template message support
- [ ] 9.8 Bulk broadcast to customer segment

---

## Phase 10 — Reviews & Ratings
- [ ] 10.1 Reviews list (DataTable with pending/published filter)
- [ ] 10.2 Review moderation (approve / reject / flag)
- [ ] 10.3 Reply to review
- [ ] 10.4 Review stats widget (avg rating, distribution)
- [ ] 10.5 Review notification to tenant on new submission

---

## Phase 11 — Reports & Analytics
- [ ] 11.1 Bookings report (filterable by date, type, status, resource)
- [ ] 11.2 Revenue report (by period, by resource, by booking type, by staff)
- [ ] 11.3 Occupancy / utilization report (with heatmap calendar view)
- [ ] 11.4 Customer report (new vs returning, top spenders)
- [ ] 11.5 Cancellation report (reasons breakdown, rate trend)
- [ ] 11.6 Staff performance report
- [ ] 11.7 Source report (where bookings originated)
- [ ] 11.8 Export all reports to CSV / Excel / PDF
- [ ] 11.9 Scheduled report delivery (email on cron)

---

## Phase 12 — Tenant Website & Listing Builder
- [ ] 12.1 Business profile editor (name, description, logo, cover, location, hours)
- [ ] 12.2 Gallery manager (upload, reorder, categorize media)
- [ ] 12.3 Service / offering pages per resource or package
- [ ] 12.4 SEO settings (meta title, description, slug per page)
- [ ] 12.5 Custom domain mapping + SSL provisioning
- [ ] 12.6 Cancellation / refund policy editor
- [ ] 12.7 FAQ manager
- [ ] 12.8 Embeddable booking widget (iframe / JS snippet)

---

## Phase 13 — Customer-Facing Tenant Website
**Goal:** Public website for each tenant where customers can browse and book.

- [ ] 13.1 Tenant homepage (hero, featured resources, how-it-works, testimonials)
- [ ] 13.2 Resource listing / search page (with filters: price, availability, rating, type)
- [ ] 13.3 Resource detail page (gallery, description, availability calendar, pricing, reviews)
- [ ] 13.4 Booking flow step 1 — select date/time/slot/guests
- [ ] 13.5 Booking flow step 2 — add-ons & extras
- [ ] 13.6 Booking flow step 3 — guest details form
- [ ] 13.7 Booking flow step 4 — booking summary review
- [ ] 13.8 Booking flow step 5 — payment page (gateway integration)
- [ ] 13.9 Booking flow step 6 — confirmation page + email trigger
- [ ] 13.10 Customer account panel: My Bookings (upcoming/past/cancelled)
- [ ] 13.11 Customer booking detail (view, cancel, request change)
- [ ] 13.12 Customer profile & preferences
- [ ] 13.13 Saved / wishlist
- [ ] 13.14 Reviews written by customer
- [ ] 13.15 Loyalty points / wallet view

---

## Phase 14 — Central Public Website
**Goal:** Platform's own marketing/discovery website.

- [ ] 14.1 Homepage (hero + search + featured listings + categories)
- [ ] 14.2 Search & discovery page (multi-tenant search across all tenants)
- [ ] 14.3 Category browse pages (per booking type)
- [ ] 14.4 Tenant / listing detail page (aggregated from tenant data)
- [ ] 14.5 About, Contact, Help Center, Terms, Privacy pages
- [ ] 14.6 Tenant registration / onboarding landing page

---

## Phase 15 — ERP: Accounting & Finance Module
- [ ] 15.1 Chart of accounts (CRUD with account type hierarchy)
- [ ] 15.2 Journal entries (manual + auto from booking/payment events)
- [ ] 15.3 General ledger view (by account + date filter)
- [ ] 15.4 Trial balance report
- [ ] 15.5 Balance sheet report
- [ ] 15.6 Profit & loss statement
- [ ] 15.7 Deferred revenue tracking (booked but not served)
- [ ] 15.8 Tax rates configuration
- [ ] 15.9 Tax rules per service type
- [ ] 15.10 VAT report generation
- [ ] 15.11 Bank accounts management
- [ ] 15.12 Bank reconciliation tool
- [ ] 15.13 Cash flow statement
- [ ] 15.14 Petty cash tracker

---

## Phase 16 — ERP: Invoicing & Billing
- [ ] 16.1 Invoice generation engine (auto + manual triggers)
- [ ] 16.2 Credit notes & debit notes
- [ ] 16.3 Proforma invoice
- [ ] 16.4 Recurring invoice scheduler
- [ ] 16.5 Payment reminder automation
- [ ] 16.6 Overdue invoice tracker + escalation
- [ ] 16.7 Multi-currency invoicing
- [ ] 16.8 Invoice template builder (Blade + PDF)

---

## Phase 17 — ERP: Inventory & Assets (Physical Booking Types)
- [ ] 17.1 Asset register (CRUD per physical resource)
- [ ] 17.2 Asset categories & tags
- [ ] 17.3 Asset assignment tracking per booking
- [ ] 17.4 Asset condition report (before/after booking checklist)
- [ ] 17.5 Maintenance schedule + job trigger
- [ ] 17.6 Depreciation tracking (straight-line / reducing balance)
- [ ] 17.7 Consumables stock management
- [ ] 17.8 Low stock alerts + notifications
- [ ] 17.9 Purchase orders for restocking

---

## Phase 18 — ERP: Procurement
- [ ] 18.1 Supplier / vendor management (CRUD)
- [ ] 18.2 Purchase requests (staff submits, manager approves)
- [ ] 18.3 Purchase orders (PO generation + PDF)
- [ ] 18.4 Goods receiving & inspection log
- [ ] 18.5 Supplier invoices & bills
- [ ] 18.6 Purchase reports

---

## Phase 19 — ERP: HR & Payroll
- [ ] 19.1 Employee profiles (extends staff module)
- [ ] 19.2 Departments & job titles
- [ ] 19.3 Employment contracts storage + e-signature
- [ ] 19.4 Document management (IDs, certificates, expiry alerts)
- [ ] 19.5 Leave management (types, balances, requests, approvals)
- [ ] 19.6 Attendance tracking (clock in/out + reports)
- [ ] 19.7 Performance reviews
- [ ] 19.8 Salary structure setup (base + allowances + deductions)
- [ ] 19.9 Monthly payroll run (calculation engine)
- [ ] 19.10 Payslip generation (PDF)
- [ ] 19.11 Overtime calculation
- [ ] 19.12 Social insurance & tax deduction tables
- [ ] 19.13 Payroll report

---

## Phase 20 — ERP: Operations & Scheduling
- [ ] 20.1 Master operations schedule (all resources + staff + bookings on one view)
- [ ] 20.2 Shift planner (drag & drop staff to shifts)
- [ ] 20.3 Resource utilization dashboard
- [ ] 20.4 Maintenance scheduling (block resource + notify staff)
- [ ] 20.5 Operational checklists (pre/post booking tasks — per booking type)
- [ ] 20.6 Task assignment to staff (with due time + priority)
- [ ] 20.7 SLA tracking (response time, service time per booking type)

---

## Phase 21 — ERP: Marketing & Campaigns
- [ ] 21.1 Campaign manager (email/SMS/push — define audience + schedule + content)
- [ ] 21.2 Audience builder (filter by booking history, tags, location, type)
- [ ] 21.3 Promo code engine (extends Phase 5)
- [ ] 21.4 Referral program (unique referral links + tracking)
- [ ] 21.5 Affiliate tracking
- [ ] 21.6 Campaign performance reports (open rate, click rate, conversion)
- [ ] 21.7 Seasonal promotions calendar

---

## Phase 22 — ERP: Channel Management (OTA Connections)
*Applicable to: Accommodation, Transport*

- [ ] 22.1 OTA connection framework (Booking.com, Airbnb, Expedia API)
- [ ] 22.2 Channel inventory sync (push availability to OTAs)
- [ ] 22.3 Rate parity management
- [ ] 22.4 Double-booking prevention (real-time lock mechanism)
- [ ] 22.5 Reservation import from OTA channels
- [ ] 22.6 Channel revenue reports

---

## Phase 23 — ERP: Business Intelligence
- [ ] 23.1 Custom report builder (drag & drop fields + filters)
- [ ] 23.2 KPI dashboard builder (add widgets from report data)
- [ ] 23.3 Booking trends analysis (time series + forecast)
- [ ] 23.4 Revenue forecasting model
- [ ] 23.5 Occupancy / utilization forecasting
- [ ] 23.6 Customer behavior analytics
- [ ] 23.7 Cost vs revenue per booking type
- [ ] 23.8 Churn analysis
- [ ] 23.9 Scheduled report email delivery

---

## Phase 24 — Document Management
- [ ] 24.1 Document storage per entity (booking / customer / supplier / staff)
- [ ] 24.2 Contract template builder (Blade templating)
- [ ] 24.3 E-signature integration (DocuSign or HelloSign API)
- [ ] 24.4 Document expiry alerts (licenses, insurance, certificates)
- [ ] 24.5 Folder structure management

---

## Phase 25 — API (Mobile / Headless)
**Goal:** Full REST API for Flutter app and third-party integrations.

- [ ] 25.1 Authentication endpoints (register, login, logout, refresh, forgot/reset password)
- [ ] 25.2 Tenant branding / config endpoint (app fetches theme, name, features)
- [ ] 25.3 Resource listing & search endpoints
- [ ] 25.4 Resource detail endpoint (with availability + pricing)
- [ ] 25.5 Booking creation endpoint (full flow)
- [ ] 25.6 Booking management endpoints (list, detail, cancel, request change)
- [ ] 25.7 Customer profile endpoints (view, update)
- [ ] 25.8 Payment endpoints (create intent, confirm, webhook handler)
- [ ] 25.9 Reviews endpoints (submit, list per resource)
- [ ] 25.10 Notifications endpoints (list, mark read)
- [ ] 25.11 Loyalty points endpoints (balance, history)
- [ ] 25.12 API rate limiting per plan
- [ ] 25.13 API key management UI (tenant generates keys)
- [ ] 25.14 Webhook manager (tenant registers endpoints + delivery log)

---

## Phase 26 — Integrations Hub
- [ ] 26.1 Google Maps / Mapbox integration (geocoding + map display)
- [ ] 26.2 Google Calendar OAuth sync
- [ ] 26.3 Outlook Calendar sync
- [ ] 26.4 iCal export/import (universal)
- [ ] 26.5 Zapier webhook integration
- [ ] 26.6 QuickBooks / Xero accounting export
- [ ] 26.7 Power BI data connector (export endpoint)
- [ ] 26.8 Twilio SMS integration
- [ ] 26.9 SendGrid / Mailgun email integration
- [ ] 26.10 Firebase FCM push notifications

---

## Phase 27 — Tenant Settings Module
- [ ] 27.1 General settings (business name, timezone, currency, language, date format)
- [ ] 27.2 Booking settings (auto-confirm toggle, buffer time, min/max advance booking)
- [ ] 27.3 Cancellation policy settings (free period, penalty rules)
- [ ] 27.4 Invoice / receipt settings (footer, payment terms, tax display)
- [ ] 27.5 User account & security (password policy, 2FA, session management)
- [ ] 27.6 Subscription / plan view + upgrade CTA
- [ ] 27.7 Branding (logo, colors, fonts for tenant website)
- [ ] 27.8 Module enable/disable per plan feature flag

---

## Phase 28 — Flutter Customer App
**Goal:** Native mobile app for customers to browse, book, and manage reservations.

- [ ] 28.1 Project setup (Flutter, Riverpod/BLoC, Dio, routing)
- [ ] 28.2 Tenant selection / onboarding screen (scan QR or enter subdomain)
- [ ] 28.3 Auth screens (login, register, forgot password, 2FA)
- [ ] 28.4 Home screen (tenant branding, featured resources, search bar)
- [ ] 28.5 Resource listing screen (search, filters, sort)
- [ ] 28.6 Resource detail screen (gallery, description, availability, pricing, reviews)
- [ ] 28.7 Booking flow (date/time → add-ons → guest details → summary → payment → confirmation)
- [ ] 28.8 Payment screen (Stripe / local gateway)
- [ ] 28.9 My Bookings screen (upcoming / past / cancelled + detail)
- [ ] 28.10 Customer profile screen (edit profile, preferences, notification settings)
- [ ] 28.11 Loyalty points screen
- [ ] 28.12 Reviews screen (write + view history)
- [ ] 28.13 Push notifications setup (FCM)
- [ ] 28.14 Wishlist / saved resources
- [ ] 28.15 Help & support screen (FAQ, contact)

---

## Phase 29 — Testing & QA
- [ ] 29.1 Unit tests for all Service classes
- [ ] 29.2 Feature tests for all Controller endpoints (web + API)
- [ ] 29.3 Database seeder with realistic multi-tenant test data
- [ ] 29.4 Payment flow integration tests (Stripe test mode)
- [ ] 29.5 Notification delivery tests
- [ ] 29.6 Multi-tenancy isolation tests (ensure tenant A can't access tenant B data)
- [ ] 29.7 Performance testing: booking creation under load
- [ ] 29.8 Security audit: OWASP top 10 checklist
- [ ] 29.9 Cross-browser / responsive UI testing

---

## Phase 30 — DevOps & Deployment
- [ ] 30.1 Docker Compose setup for local development
- [ ] 30.2 GitHub Actions CI pipeline (lint → test → build)
- [ ] 30.3 Laravel Cloud deployment configuration
- [ ] 30.4 Queue worker process configuration (Horizon + Supervisor)
- [ ] 30.5 Scheduled task registration
- [ ] 30.6 SSL + custom domain pipeline for tenant domains
- [ ] 30.7 Backup strategy (DB + storage daily backups)
- [ ] 30.8 Monitoring setup (error tracking: Sentry, uptime: Betterstack)
- [ ] 30.9 Log aggregation (Laravel Telescope for dev, structured logs for prod)
- [ ] 30.10 CDN setup for static assets and media storage

---

## Milestone Summary

| Milestone | Phases | Target |
|-----------|--------|--------|
| M1: Working Platform Shell | 0, 1, 2 | Foundation + Central Admin + Onboarding |
| M2: Core Booking Engine | 3, 4, 5 | Booking + Resources + Pricing |
| M3: CRM + Team + Finance | 6, 7, 8, 9, 10 | Customers + Staff + Payments + Notifications + Reviews |
| M4: Analytics + Website | 11, 12, 13, 14 | Reports + Tenant Website + Public Website |
| M5: ERP Core | 15, 16, 17, 18, 19 | Accounting + Invoicing + Assets + Procurement + HR |
| M6: ERP Advanced | 20, 21, 22, 23, 24 | Operations + Marketing + OTA + BI + Docs |
| M7: API + Mobile | 25, 26, 27, 28 | REST API + Integrations + Settings + Flutter App |
| M8: Production Ready | 29, 30 | Testing + DevOps + Deployment |
