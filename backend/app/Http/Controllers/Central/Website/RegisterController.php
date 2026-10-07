<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Plan;
use App\Services\Central\SeoService;
use App\Services\Central\TenantManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stancl\Tenancy\Database\Models\Domain;

class RegisterController extends Controller
{
    public function __construct(private readonly TenantManagementService $tenantService) {}

    public function index(Request $request, SeoService $seo): View
    {
        $seo->setTitle('List Your Business — '.config('app.name', 'BookEase'))
            ->setDescription('Join thousands of businesses on BookEase. Set up your booking page in minutes.')
            ->setCanonical(url('/register'));

        $bookingTypes = BookingType::cases();
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $countries = Country::orderBy('name')->get(['id', 'name', 'iso2', 'currency_code', 'currency_symbol']);
        $selectedPlanId = (int) $request->query('plan_id', 0);

        return view('central.website.register', compact('bookingTypes', 'plans', 'countries', 'selectedPlanId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $bookingTypeValues = implode(',', array_column(BookingType::cases(), 'value'));

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'business_type' => ['required', 'string', 'in:'.$bookingTypeValues],
            'country_iso2' => ['required', 'string', 'size:2', 'exists:countries,iso2'],
            'currency_code' => ['required', 'string', 'size:3'],
            'subdomain' => ['required', 'string', 'regex:/^[a-z0-9][a-z0-9\-]{1,30}[a-z0-9]$/', 'max:63'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agreed_terms' => ['required', 'accepted'],
        ], [
            'subdomain.regex' => 'The subdomain may only contain lowercase letters, numbers, and hyphens.',
            'agreed_terms.accepted' => 'You must agree to the Terms of Service.',
        ]);

        // Check subdomain availability
        $centralDomain = config('tenancy.central_domains.0', 'localhost');
        $domain = $validated['subdomain'].'.'.$centralDomain;

        if (Domain::where('domain', $domain)->exists()) {
            return back()->withErrors(['subdomain' => 'This subdomain is already taken.'])->withInput();
        }

        $this->tenantService->create([
            'name' => $validated['business_name'],
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['email'],
            'phone' => $validated['phone'],
            'business_type' => $validated['business_type'],
            'domain' => $domain,
            'plan_id' => $validated['plan_id'],
        ]);

        return redirect()->route('central.website.home')
            ->with('success', 'Welcome! Your business account is being set up. Check your email for next steps.');
    }

    public function checkSubdomain(Request $request): JsonResponse
    {
        $subdomain = strtolower(trim($request->query('subdomain', '')));

        if (! preg_match('/^[a-z0-9][a-z0-9\-]{1,30}[a-z0-9]$/', $subdomain)) {
            return response()->json(['available' => false, 'message' => 'Invalid subdomain format']);
        }

        $centralDomain = config('tenancy.central_domains.0', 'localhost');
        $domain = $subdomain.'.'.$centralDomain;
        $available = ! Domain::where('domain', $domain)->exists();

        return response()->json(['available' => $available]);
    }
}
