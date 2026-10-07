# Booking Reservation System — Project Instructions

## Project Overview

A **SaaS multi-tenant booking & reservation platform** supporting all major business types across Accommodation, Travel, Dining, Services, Entertainment, Business Resources, and Online/Virtual categories.

**Goal:** One unified platform where any business type can onboard as a tenant and manage their bookings end-to-end, while the platform owner manages tenants, subscriptions, and global settings.

---

## Project Structure

```
booking-reservation-system/
├── backend/                    # Laravel 11 application (PHP 8.3)
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Central/    # Central admin panel controllers
│   │   │   │   ├── Tenant/     # Tenant panel controllers
│   │   │   │   └── Api/        # API controllers (v1/)
│   │   │   ├── Requests/       # Form Request validation classes
│   │   │   └── Resources/      # Eloquent API Resources
│   │   ├── Models/             # Eloquent models
│   │   ├── Enums/              # PHP 8.1+ Enum classes
│   │   ├── Services/           # Business logic service classes
│   │   ├── Repositories/       # Repository pattern (DB abstraction)
│   │   ├── Jobs/               # Queue jobs
│   │   ├── Events/             # Domain events
│   │   ├── Listeners/          # Event listeners
│   │   ├── Notifications/      # Database + email notifications
│   │   ├── Console/Commands/   # Artisan commands
│   │   └── Providers/
│   ├── resources/
│   │   └── views/
│   │       ├── central/        # Central admin Blade views
│   │       ├── tenant/         # Tenant panel Blade views
│   │       └── components/     # Shared Blade components
│   └── routes/
│       ├── web.php             # Central routes
│       ├── tenant.php          # Tenant routes
│       └── api.php             # API routes
└── tenant_flutter_app/         # Flutter customer-facing mobile app
```

---

## Technology Stack

### Backend
| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 (PHP 8.3) |
| Database | MySQL 8 |
| Cache / Queue | Redis |
| Search | Laravel Scout + Meilisearch |
| Storage | S3-compatible (Laravel Filesystem) |
| Auth | Laravel Sanctum (API) + Session (Web) |
| Multi-tenancy | `stancl/tenancy` package |
| Table UI | Yajra DataTables (AJAX) |
| Frontend | TailwindCSS v3, Alpine.js, Vite |
| Charts | ApexCharts |
| PDF | Laravel DomPDF |
| Mail | Laravel Mail + queue driver |
| Payments | Stripe, PayPal, Paymob, Fawry |
| SMS | Twilio / Vonage |
| Push | Firebase FCM |
| Calendar Sync | iCal / Google Calendar API |

### Mobile (Flutter App)
| Layer | Technology |
|-------|-----------|
| Framework | Flutter (latest stable) |
| State | Riverpod or BLoC |
| API Client | Dio |
| Auth | JWT via Laravel Sanctum |
| Maps | Google Maps Flutter |
| Payments | Stripe Flutter SDK |

---

## Multi-Tenancy Architecture

### Strategy: Domain / Subdomain per Tenant
- **Central domain:** `bookings.app` (platform website + admin)
- **Tenant domain:** `{slug}.bookings.app` or custom domain
- **Tenancy package:** `stancl/tenancy` for database isolation

### Tenant Isolation
- Each tenant gets a **dedicated database** (full isolation)
- Central DB stores: tenants, subscriptions, plans, global settings
- Tenant DB stores: all tenant-specific data (bookings, resources, customers, etc.)

### Route Groups
```
web.php      → Central website + Central Admin Panel (/central/*)
tenant.php   → Tenant Panel (/dashboard/*) + Tenant Website (/)
api.php      → REST API (versioned /api/v1/*)
```

---

## Booking Types Supported

| Category | Types |
|----------|-------|
| Accommodation | Hotel, Apartment, Hostel, Villa/Chalet, Resort, Camping |
| Travel & Transport | Flight, Train/Bus, Car Rental, Yacht/Boat, Cruise, Airport Transfer |
| Dining & Events | Restaurant, Private Dining/Event Hall, Catering, Food Pre-order |
| Services & Appointments | Medical/Clinic, Salon/Spa, Home Service, Fitness, Tutoring, Photography |
| Entertainment & Activities | Event Ticket, Tour/Excursion, Escape Room, Co-working, Sports Court |
| Business & Resources | Meeting Room, Equipment Rental, Parking, Storage Unit |
| Online / Virtual | Online Consultation, Webinar, Live Streaming |

Each type has a `BookingType` enum and adapts its resource management, pricing model, and booking flow accordingly.

---

## Core Domain Models

### Central Database
```
tenants                 - Tenant accounts
plans                   - Subscription plan definitions
subscriptions           - Tenant → Plan mapping
plan_features           - Feature flags per plan
payments (central)      - Subscription billing records
```

### Tenant Database (per tenant)

**Booking Core**
```
bookings                - Master booking record
booking_items           - Line items per booking
booking_statuses        - Status history log
booking_sources         - Where booking came from (web, OTA, walk-in)
waitlists               - Waitlist queue per resource/slot
group_bookings          - Group booking parent
recurring_bookings      - Recurring booking config
```

**Resources (polymorphic)**
```
resources               - Generic resource entity (polymorphic)
resource_types          - Room, Table, Vehicle, Slot, Court, etc.
resource_availability   - Per-date/slot availability overrides
resource_pricing        - Pricing rules per resource
resource_media          - Photos/videos per resource
time_slots              - Slot definitions (start, end, capacity, duration)
blackout_dates          - Closed dates
```

**Customers & CRM**
```
customers               - Customer profiles
customer_notes          - Staff notes on customer
customer_tags           - Tagging/segments
loyalty_points          - Loyalty rewards ledger
blacklist               - Blocked customers
leads                   - Pre-booking inquiries
```

**Staff & HR**
```
staff                   - Staff members
roles / permissions     - RBAC (Spatie)
staff_schedules         - Shift patterns
staff_assignments       - Staff → Booking assignments
leave_requests          - Leave management
attendance_logs         - Clock in/out
```

**Pricing & Finance**
```
pricing_rules           - Time-based pricing rules
discount_codes          - Coupon/promo codes
add_ons                 - Extra services per booking type
transactions            - Payment records
invoices                - Invoice headers
invoice_items           - Invoice line items
refunds                 - Refund records
deposits                - Deposit tracking
```

**ERP Modules**
```
accounts                - Chart of accounts
journal_entries         - Double-entry bookkeeping
expenses                - Expense records
tax_rates               - Tax configuration
bank_accounts           - Bank account registry
assets (physical)       - Asset register
purchase_orders         - Procurement POs
suppliers               - Vendor management
payroll_runs            - Monthly payroll
payslips                - Per-employee payslips
```

**Notifications & Communication**
```
notification_templates  - Email/SMS/push templates
notification_logs       - Delivery audit log
messages                - Internal messaging
broadcasts              - Bulk message campaigns
```

**Reviews & Content**
```
reviews                 - Customer reviews
review_replies          - Tenant replies
faq_items               - FAQ entries
gallery_items           - Business gallery
```

---

## Key Design Patterns

### Controllers
- Thin controllers — delegate to Services
- Return views (web) or Resources (API)
- Route Model Binding for all show/edit/update/delete

### Services
```php
// app/Services/{Domain}/{ClassName}Service.php
// Example: app/Services/Booking/BookingCreationService.php
class BookingCreationService
{
    public function __construct(
        private BookingRepository $bookings,
        private AvailabilityService $availability,
        private PricingService $pricing,
    ) {}

    public function create(array $data): Booking { ... }
}
```

### Repositories
```php
// app/Repositories/{ModelName}Repository.php
// Implements interface for swap/testing
interface BookingRepositoryInterface
{
    public function findByStatus(BookingStatus $status): Collection;
}
```

### Enums
```php
// app/Enums/BookingStatus.php
enum BookingStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case CheckedIn = 'checked_in';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function label(): string { ... }
    public function color(): string { ... }
}
```

### Form Requests (Pre-submit Validation)
```php
// app/Http/Requests/Tenant/Booking/StoreBookingRequest.php
// Validation runs before controller via prepareForValidation()
// Uses authorize() for policy checks
```

### Jobs & Queues
```
bookings queue   → Booking confirmations, payment processing
notifications    → Email/SMS/push sending
reports          → Heavy report generation
imports          → CSV/Excel data imports
```

### Events & Listeners
```
BookingCreated       → SendBookingConfirmation, UpdateAvailability, CreateInvoice
BookingCancelled     → ProcessRefund, NotifyCustomer, ReleaseResource
PaymentReceived      → UpdateBookingStatus, GenerateInvoice
ReviewSubmitted      → NotifyTenant, ModerateReview
```

---

## Dashboard UI Stack

- **Layout:** Fixed sidebar + topbar using TailwindCSS
- **Tables:** Yajra DataTables with AJAX server-side processing
- **Modals:** Alpine.js + Blade components
- **Forms:** Standard HTML5 + Alpine.js for dynamic sections
- **Charts:** ApexCharts rendered via CDN / Vite
- **Toasts:** Custom Alpine.js toast component
- **Datepicker:** Flatpickr
- **Calendar:** FullCalendar.js (for availability/resource calendar)
- **Icons:** Heroicons or Phosphor Icons

---

## API Design

- **Base URL:** `/api/v1/`
- **Auth:** Bearer token (Sanctum)
- **Format:** JSON (Content-Type: application/json)
- **Pagination:** Cursor-based for feeds, page-based for tables
- **Versioning:** URL prefix (`/v1/`, `/v2/`)
- **Resources:** All responses wrapped in Eloquent API Resources
- **Rate Limiting:** Per-tenant, configurable via plan

---

## Notification Channels

| Channel | Driver |
|---------|--------|
| Email | SMTP / Mailgun / SES |
| SMS | Twilio / Vonage |
| Push (Mobile) | Firebase FCM |
| In-app | Database notifications |
| WhatsApp | WhatsApp Business API |

All notifications extend Laravel `Notification` and support database + at least email. Templates are stored in DB and rendered via Blade engine at send time.

---

## ERP Integration Points

- Booking → Finance: Every confirmed booking auto-creates an invoice + journal entry
- Payment → Ledger: Every payment posts to the accounts journal
- Staff → Payroll: Staff schedules feed attendance for payroll calculation
- Resource → Assets: Physical resources link to the asset register for depreciation
- Expense → Accounts: Expenses post journal entries to relevant cost accounts

---

## Security Considerations

- Row-level security via tenant DB isolation (`stancl/tenancy`)
- RBAC using `spatie/laravel-permission` per tenant
- All user inputs validated via Form Request classes
- SQL injection protection via Eloquent/Query Builder only
- CSRF protection on all web forms
- API rate limiting per tenant plan
- Audit log for sensitive actions (bookings, payments, user management)
- 2FA option for tenant admin accounts

---

## Environment Variables (Key)

```env
APP_URL=
CENTRAL_DOMAIN=
TENANT_DOMAIN_SUFFIX=

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=booking_central

REDIS_HOST=
QUEUE_CONNECTION=redis

STRIPE_KEY=
STRIPE_SECRET=
PAYMOB_API_KEY=

MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=

TWILIO_SID=
TWILIO_TOKEN=

FIREBASE_PROJECT_ID=
FIREBASE_PRIVATE_KEY=
```

---

## Deployment

- **Platform:** Laravel Cloud (recommended) or VPS (Nginx + PHP-FPM)
- **Queue Worker:** Supervisor-managed `php artisan queue:work`
- **Scheduler:** Single `php artisan schedule:run` cron entry
- **SSL:** Let's Encrypt or Cloudflare for tenant custom domains

---

## Development Conventions

1. Run `php artisan make:` commands for all new files
2. Run `vendor/bin/pint --dirty` after any PHP changes
3. Write feature tests for all new controllers/services
4. Use `php artisan route:list` to verify route registration
5. Every migration must have a matching model + factory
6. Commit message format: `feat(module): description` / `fix(module): description`
