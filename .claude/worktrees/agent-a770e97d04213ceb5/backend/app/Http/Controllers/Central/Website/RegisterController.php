<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Jobs\ProvisionTenantDatabase;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        $bookingTypes = BookingType::cases();

        return view('central.website.register', compact('bookingTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'business_type' => ['required', 'string', 'in:'.implode(',', array_column(BookingType::cases(), 'value'))],
            'subdomain' => ['required', 'string', 'alpha_dash', 'min:3', 'max:63', 'unique:tenants,id'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $tenant = Tenant::create([
            'id' => $validated['subdomain'],
            'name' => $validated['name'],
            'contact_email' => $validated['email'],
            'business_type' => $validated['business_type'],
            'status' => 'pending',
        ]);

        $tenant->domains()->create(['domain' => $validated['subdomain'].'.'.config('app.central_domain', 'localhost')]);

        ProvisionTenantDatabase::dispatch($tenant);

        return redirect()->route('central.website.home')
            ->with('success', 'Your business has been registered! We are setting up your account and will notify you shortly.');
    }
}
