<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\BookingDataTable;
use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Booking\StoreBookingRequest;
use App\Http\Requests\Tenant\Booking\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Resource;
use App\Repositories\Tenant\BookingRepository;
use App\Services\Tenant\Booking\BookingCreationService;
use App\Services\Tenant\Booking\BookingManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingCreationService $bookingCreationService,
        private readonly BookingManagementService $bookingManagementService,
        private readonly BookingRepository $bookingRepository,
    ) {}

    public function index(BookingDataTable $dataTable): mixed
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        $statuses = BookingStatus::cases();
        $bookingTypes = BookingType::cases();

        return $dataTable->render('tenant.bookings.index', compact('statuses', 'bookingTypes'));
    }

    public function create(): View
    {
        $resources = Resource::orderBy('name')->get();
        $customers = Customer::orderBy('first_name')->get();

        return view('tenant.bookings.create', compact('resources', 'customers'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingCreationService->create($request->validated());

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', "Booking {$booking->reference_number} created successfully.");
    }

    public function show(Booking $booking): View
    {
        $booking->load(['customer', 'resource', 'items', 'statusHistories']);

        return view('tenant.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        return view('tenant.bookings.edit', compact('booking'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('tenant.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function confirm(Booking $booking): RedirectResponse
    {
        $this->bookingManagementService->confirm($booking);

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Booking confirmed successfully.');
    }

    public function cancel(Booking $booking, Request $request): RedirectResponse
    {
        $request->validate(['reason' => 'required|string']);

        $this->bookingManagementService->cancel($booking, $request->input('reason'));

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Booking cancelled.');
    }

    public function checkin(Booking $booking): RedirectResponse
    {
        $this->bookingManagementService->processCheckin($booking);

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Customer checked in.');
    }

    public function checkout(Booking $booking): RedirectResponse
    {
        $this->bookingManagementService->processCheckout($booking);

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Booking completed.');
    }

    public function noshow(Booking $booking): RedirectResponse
    {
        $this->bookingManagementService->markNoShow($booking);

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Booking marked as no-show.');
    }

    public function pending(): View
    {
        $pendingBookings = $this->bookingRepository->pendingApprovals();

        return view('tenant.bookings.pending', compact('pendingBookings'));
    }
}
