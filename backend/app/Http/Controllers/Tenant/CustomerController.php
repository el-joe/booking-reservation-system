<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Customer\StoreCustomerRequest;
use App\Models\Customer;
use App\Services\Tenant\Customer\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(private readonly CustomerService $customerService) {}

    public function index(CustomerDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.customers.index');
    }

    public function create(): View
    {
        return view('tenant.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = $this->customerService->create($request->validated());

        return redirect()->route('tenant.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $customer->load([
            'bookings' => fn ($q) => $q->with('resource')->latest()->limit(10),
            'customerNotes' => fn ($q) => $q->orderByDesc('is_pinned')->latest(),
            'loyaltyTransactions' => fn ($q) => $q->latest()->limit(10),
        ]);

        return view('tenant.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('tenant.customers.edit', compact('customer'));
    }

    public function update(StoreCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->customerService->update($customer, $request->validated());

        return redirect()->route('tenant.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('tenant.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function blacklist(Customer $customer, Request $request): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $this->customerService->blacklist($customer, $request->string('reason')->toString());

        return back()->with('success', 'Customer has been blacklisted.');
    }

    public function removeBlacklist(Customer $customer): RedirectResponse
    {
        $this->customerService->removeFromBlacklist($customer);

        return back()->with('success', 'Customer has been removed from blacklist.');
    }

    public function blacklistView(): View
    {
        return view('tenant.customers.blacklist');
    }

    public function addNote(Customer $customer, Request $request): RedirectResponse
    {
        $request->validate([
            'note' => 'required|string|max:2000',
            'is_pinned' => 'boolean',
        ]);

        $this->customerService->addNote(
            $customer,
            $request->string('note')->toString(),
            (bool) $request->input('is_pinned', false),
        );

        return back()->with('success', 'Note added successfully.');
    }
}
