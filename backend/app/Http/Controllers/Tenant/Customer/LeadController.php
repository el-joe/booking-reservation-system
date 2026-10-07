<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Customer;

use App\DataTables\Tenant\LeadDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(LeadDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.customers.leads.index');
    }

    public function create(): View
    {
        return view('tenant.customers.leads.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'booking_type' => 'nullable|string',
            'source' => 'required|in:website,referral,walk_in,phone,social,other',
            'status' => 'nullable|in:new,contacted,qualified,converted,lost',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        Lead::create($validated);

        return redirect()->route('tenant.leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead): View
    {
        return view('tenant.customers.leads.show', compact('lead'));
    }

    public function edit(Lead $lead): View
    {
        return view('tenant.customers.leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'booking_type' => 'nullable|string',
            'source' => 'required|in:website,referral,walk_in,phone,social,other',
            'status' => 'nullable|in:new,contacted,qualified,converted,lost',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $lead->update($validated);

        return redirect()->route('tenant.leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('tenant.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function convert(Lead $lead): RedirectResponse
    {
        // Create a new customer from the lead
        $customer = Customer::create([
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
        ]);

        $lead->update([
            'status' => 'converted',
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('tenant.customers.create', [
            'customer_id' => $customer->id,
            'lead_id' => $lead->id,
        ])->with('success', 'Lead converted to customer. Please complete the booking.');
    }
}
